<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Bien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $yellika;

    public function __construct(\App\Services\YellikaService $yellika)
    {
        $this->yellika = $yellika;
    }
    /**
     * Calcule la période (Mois Année) couverte par le paiement
     */
    private function calculatePeriod(User $locataire, $monthsToPay)
    {
        Carbon::setLocale('fr');

        // On part de la date de début de contrat (ou création du compte)
        $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
        
        // On récupère le nombre de mois déjà validés (pour savoir d'où on repart)
        $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
            ->where('status', 'completed')
            ->sum('months_count');

        // Calcul du début et de la fin
        $periodStart = $startDate->copy()->addMonths($monthsAlreadyPaid)->startOfMonth();
        $periodEnd = $periodStart->copy()->addMonths((int)$monthsToPay - 1)->endOfMonth();

        // Formatage textuel
        if ((int)$monthsToPay === 1) {
            return ucfirst($periodStart->translatedFormat('F Y'));
        }

        return "De " . ucfirst($periodStart->translatedFormat('F Y')) . " à " . ucfirst($periodEnd->translatedFormat('F Y'));
    }

    public function initiateCashPayment(Request $request, User $locataire)
    {
        $request->validate([
            'months' => 'required|integer|min:1'
        ]);

        if (!$locataire->bien) {
            return response()->json(['error' => 'Ce locataire n\'a aucun bien assigné.'], 422);
        }

        $bien = $locataire->bien;
        $amount = $bien->loyer_mensuel * (int)$request->months;
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $reference = 'PAY-' . strtoupper(Str::random(8));
        
        $periode = $this->calculatePeriod($locataire, $request->months);

        $qrData = "MAELYS-IMO - RECU DE PAIEMENT\n" .
                  "--------------------------\n" .
                  "Ref: {$reference}\n" .
                  "Locataire: {$locataire->name} {$locataire->prenoms}\n" .
                  "Bien: {$bien->reference}\n" .
                  "Montant: {$amount} FCFA\n" .
                  "Période: {$periode}\n" .
                  "Agent: " . auth()->guard('admin')->user()->name . "\n" .
                  "Date: " . now()->format('d/m/Y H:i');
        
        $qrFileName = 'qrcodes/' . $reference . '.svg';
        
        try {
            if (!Storage::disk('public')->exists('qrcodes')) {
                Storage::disk('public')->makeDirectory('qrcodes');
            }
            $qrContent = QrCode::size(300)->margin(2)->generate($qrData);
            Storage::disk('public')->put($qrFileName, $qrContent);
        } catch (\Exception $e) {
            Log::error("Erreur QR : " . $e->getMessage());
        }

        $payment = Payment::create([
            'user_id' => $locataire->id,
            'months_count' => (int)$request->months,
            'periode_couverte' => $periode,
            'amount' => $amount,
            'payment_method' => 'especes',
            'reference' => $reference,
            'verification_code' => $code,
            'qr_code' => $qrFileName,
            'agent_id' => auth()->guard('admin')->id(),
            'status' => 'pending'
        ]);

        // Notifications (Email)
        Log::info("Tentative d'envoi Email OTP paiement à {$locataire->email}");
        try {
            Mail::send([], [], function ($message) use ($locataire, $code, $amount, $periode) {
                $message->to($locataire->email)
                    ->subject('Code de validation paiement loyer')
                    ->html("Bonjour {$locataire->name},<br><br>Un paiement de **{$amount} FCFA** pour la période de **{$periode}** est en cours.<br>Votre code est : <h2 style='color:#ff5e14;'>{$code}</h2>");
            });
            Log::info("Email OTP envoyé avec succès à {$locataire->email}");
        } catch (\Exception $e) {
            Log::error("Erreur mail : " . $e->getMessage());
        }

        // Notifications (SMS)
        if ($locataire->contact) {
            Log::info("Tentative d'envoi SMS OTP au locataire : {$locataire->contact}");
            try {
                $smsMessage = "MAELYS-IMO: Votre code de validation pour le paiement de {$amount} FCFA ({$periode}) est : {$code}";
                $this->yellika->send($locataire->contact, $smsMessage);
                Log::info("SMS OTP envoyé au locataire {$locataire->contact}");
            } catch (\Exception $e) {
                Log::error("Erreur SMS paiement espèces : " . $e->getMessage());
            }
        } else {
            Log::warning("Impossible d'envoyer le SMS : Contact locataire manquant.");
        }


        return response()->json([
            'payment_id' => $payment->id,
            'message' => "Le code pour {$periode} a été envoyé au locataire."
        ]);
    }

    public function confirmCashPayment(Request $request, Payment $payment)
    {
        $enteredCode = trim((string)$request->code);

        if ((string)$payment->verification_code !== $enteredCode) {
            return response()->json(['error' => 'Code incorrect.'], 422);
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'verification_code' => null
        ]);

        return response()->json([
            'success' => true,
            'reference' => $payment->reference,
            'qr_url' => $payment->qr_code ? Storage::url($payment->qr_code) : null
        ]);
    }

    public function getNextPaymentInfo(User $locataire, Request $request)
    {
        $months = (int) $request->get('months', 1);
        $periodInfo = $this->calculatePeriod($locataire, $months);
        
        return response()->json([
            'periode' => $periodInfo
        ]);
    }

    public function confirmByCode(Request $request)
    {
        $code = trim((string)$request->code);
        if (empty($code)) return response()->json(['error' => 'Code obligatoire.'], 422);

        $payment = Payment::where('verification_code', $code)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();

        if (!$payment) {
            return response()->json(['error' => "Le code '{$code}' est invalide ou expiré."], 422);
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'verification_code' => null
        ]);

        return response()->json([
            'success' => true,
            'reference' => $payment->reference,
            'qr_url' => $payment->qr_code ? Storage::url($payment->qr_code) : null
        ]);
    }

    public function pending(Request $request)
    {
        $search = $request->get('search');

        $pendingPayments = Payment::with('user.bien')
            ->where('status', 'pending')
            ->whereIn('payment_method', ['bank', 'bank_transfer'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('reference', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($qu) use ($search) {
                          $qu->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('prenoms', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('agent.payments.pending', compact('pendingPayments'));
    }

    public function validatePayment(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'agent_id' => auth()->guard('admin')->id(), // Enregistre l'agent qui valide
            'paid_at' => now()
        ]);
        return back()->with('success', 'Paiement validé avec succès.');
    }

    public function cancelPayment(Payment $payment)
    {
        $payment->update([
            'status' => 'cancelled',
            'agent_id' => auth()->guard('admin')->id() // Enregistre l'agent qui rejette
        ]);
        return back()->with('success', 'Paiement rejeté avec succès.');
    }

    public function history(Request $request)
    {
        $search = $request->get('search');
        \Carbon\Carbon::setLocale('fr');
        $agentId = auth()->guard('admin')->id();

        $queryBase = Payment::with(['user.bien', 'agent'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('reference', 'LIKE', "%{$search}%")
                      ->orWhere('amount', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($qu) use ($search) {
                          $qu->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('prenoms', 'LIKE', "%{$search}%");
                      });
                });
            });

        // 1. Mobiles Money : Voit tout
        $mobilePayments = (clone $queryBase)->whereIn('payment_method', ['mobile', 'wave', 'orange_money', 'mtn_money'])
            ->latest()
            ->paginate(6, ['*'], 'mobile_p')
            ->withQueryString();

        // 2. Virements : Uniquement ceux qu'il a validés
        $bankPayments = (clone $queryBase)->whereIn('payment_method', ['bank', 'bank_transfer'])
            ->where('agent_id', $agentId)
            ->latest()
            ->paginate(6, ['*'], 'bank_p')
            ->withQueryString();

        // 3. Espèces : Uniquement ceux qu'il a faits
        $cashPayments = (clone $queryBase)->whereIn('payment_method', ['especes', 'cash'])
            ->where('agent_id', $agentId)
            ->latest()
            ->paginate(6, ['*'], 'cash_p')
            ->withQueryString();

        return view('agent.payments.history', compact('mobilePayments', 'bankPayments', 'cashPayments'));
    }
}
