<?php

namespace App\Http\Controllers\Recouvrement;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Versement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\YellikaService;
use Illuminate\Support\Facades\Log;

class RecouvrementDashboardController extends Controller
{
    protected $yellika;

    public function __construct(YellikaService $yellika)
    {
        $this->yellika = $yellika;
    }
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Stats financières
        $totalCollected = Payment::where('agent_id', $admin->id)
            ->where('status', 'completed')
            ->sum('amount');

        $totalVersed = Versement::where('agent_id', $admin->id)
            ->sum('amount');

        $remainingToVerse = $totalCollected - $totalVersed;

        // Compteur de retards
        $lateCount = $this->getLateTenantsCount();

        // Dernières activités
        $recentEncaissements = Payment::where('agent_id', $admin->id)
            ->where('status', 'completed')
            ->with('user.bien')
            ->latest()
            ->take(5)
            ->get();

        $recentVersements = Versement::where('agent_id', $admin->id)
            ->with('comptable')
            ->latest()
            ->take(5)
            ->get();

        return view('recouvrement.dashboard', compact(
            'admin',
            'lateCount',
            'totalCollected',
            'totalVersed',
            'remainingToVerse',
            'recentEncaissements',
            'recentVersements'
        ));
    }

    public function lateTenants()
    {
        $admin = Auth::guard('admin')->user();
        $allLocataires = User::where('role', 'locataire')->with('bien')->get();
        $lateTenants = [];
        $currentMonth = Carbon::now()->startOfMonth();

        foreach ($allLocataires as $locataire) {
            $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
            $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
                ->where('status', 'completed')
                ->sum('months_count');
            $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);

            if ($nextPaymentDate->lessThanOrEqualTo($currentMonth)) {
                $monthsLate = $nextPaymentDate->diffInMonths($currentMonth) + 1;
                $locataire->months_late = $monthsLate;
                $locataire->next_payment_date = $nextPaymentDate;
                $lateTenants[] = $locataire;
            }
        }

        return view('recouvrement.late_tenants', compact('admin', 'lateTenants'));
    }

    public function pay(User $locataire)
    {
        $admin = Auth::guard('admin')->user();
        $locataire->load('bien');

        $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
        $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
            ->where('status', 'completed')
            ->sum('months_count');

        $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);
        $periode = ucfirst($nextPaymentDate->translatedFormat('F Y'));

        return view('recouvrement.pay', compact('admin', 'locataire', 'periode'));
    }

    public function initiatePayment(User $locataire)
    {
        $admin = Auth::guard('admin')->user();

        if (!$locataire->bien) {
            return back()->with('error', 'Ce locataire n\'a aucun bien assigné.');
        }

        $bien = $locataire->bien;
        $amount = $bien->loyer_mensuel;
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $reference = 'PAY-' . strtoupper(Str::random(8));

        // Calcul de la période (1 mois)
        $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
        $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
            ->where('status', 'completed')
            ->sum('months_count');
        $periodStart = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);
        $periode = ucfirst($periodStart->translatedFormat('F Y'));

        // Données du reçu pour le QR
        $qrData = "MAELYS-IMO - RECU DE PAIEMENT (RECOUVREMENT)\n" .
            "--------------------------\n" .
            "Ref: {$reference}\n" .
            "Locataire: {$locataire->name} {$locataire->prenoms}\n" .
            "Bien: {$bien->reference}\n" .
            "Montant: {$amount} FCFA\n" .
            "Période: {$periode}\n" .
            "Agent: {$admin->name}\n" .
            "Date: " . now()->format('d/m/Y H:i');

        $qrFileName = 'qrcodes/' . $reference . '.svg';

        // Génération du QR Code
        try {
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('qrcodes')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('qrcodes');
            }
            \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->margin(2)->generate($qrData, storage_path('app/public/' . $qrFileName));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur QR Recouvrement : " . $e->getMessage());
        }

        // Création du paiement PENDING
        $payment = Payment::create([
            'user_id' => $locataire->id,
            'months_count' => 1,
            'periode_couverte' => $periode,
            'amount' => $amount,
            'payment_method' => 'especes',
            'reference' => $reference,
            'verification_code' => $code,
            'qr_code' => $qrFileName,
            'agent_id' => $admin->id,
            'status' => 'pending'
        ]);

        // Envoi du code par Mail
        Log::info("Tentative d'envoi Email OTP Recouvrement à {$locataire->email}");
        try {
            Mail::send([], [], function ($message) use ($locataire, $code, $amount, $periode) {
                $message->to($locataire->email)
                    ->subject('Code de validation paiement recouvrement')
                    ->html("Bonjour {$locataire->name},<br><br>Un encaissement de **{$amount} FCFA** pour le mois de **{$periode}** est initié par l'agent de recouvrement.<br>Veuillez lui communiquer le code suivant pour valider votre reçu : <h2 style='color:#ff5e14;'>{$code}</h2>");
            });
            Log::info("Email OTP Recouvrement envoyé avec succès à {$locataire->email}");
        } catch (\Exception $e) {
            Log::error("Erreur mail Recouvrement : " . $e->getMessage());
        }

        // Envoi du code par SMS (Locataire)
        if ($locataire->contact) {
            Log::info("Tentative d'envoi SMS OTP Recouvrement au locataire : {$locataire->contact}");
            try {
                $smsMessage = "MAELYS-IMO: Code validation recouvrement pour {$amount} FCFA ({$periode}) : {$code}";
                $this->yellika->send($locataire->contact, $smsMessage);
                Log::info("SMS OTP Recouvrement envoyé au locataire {$locataire->contact}");
            } catch (\Exception $e) {
                Log::error("Erreur SMS Recouvrement locataire : " . $e->getMessage());
            }
        } else {
            Log::warning("SMS Recouvrement impossible : Contact locataire manquant.");
        }


        return redirect()->route('recouvrement.tenants.confirm_payment', $payment->id)
            ->with('success', "Un code de validation a été envoyé au locataire pour le mois de $periode.");
    }

    public function confirmPaymentForm(Payment $payment)
    {
        $admin = Auth::guard('admin')->user();
        $payment->load('user.bien');
        return view('recouvrement.confirm_payment', compact('admin', 'payment'));
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $request->validate(['code' => 'required|string']);

        if ($payment->verification_code !== $request->code) {
            return back()->with('error', 'Le code de validation est incorrect.');
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'verification_code' => null
        ]);

        return redirect()->route('recouvrement.tenants.late')
            ->with('success', "Le paiement de {$payment->amount} FCFA pour {$payment->periode_couverte} a été validé avec succès.");
    }

    public function myPayments(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $query = Payment::where('agent_id', $admin->id)
            ->where('status', 'completed')
            ->with('user.bien')
            ->latest();

        // Filtrage par locataire
        if ($request->filled('locataire_id')) {
            $query->where('user_id', $request->locataire_id);
        }

        // Filtrage par période (recherche dans periode_couverte)
        if ($request->filled('periode')) {
            $query->where('periode_couverte', 'like', '%' . $request->periode . '%');
        }

        $payments = $query->get();
        $totalCollected = $payments->sum('amount');

        // Récupérer la liste des locataires pour la liste déroulante
        $locataires = User::where('role', 'locataire')->orderBy('name')->get();

        return view('recouvrement.my_payments', compact('admin', 'payments', 'totalCollected', 'locataires'));
    }

    private function getLateTenantsCount()
    {
        $allLocataires = User::where('role', 'locataire')->get();
        $count = 0;
        $currentMonth = Carbon::now()->startOfMonth();

        foreach ($allLocataires as $locataire) {
            $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
            $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
                ->where('status', 'completed')
                ->sum('months_count');
            $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);

            if ($nextPaymentDate->lessThanOrEqualTo($currentMonth)) {
                $count++;
            }
        }
        return $count;
    }

    public function versements()
    {
        $admin = Auth::guard('admin')->user();

        // Total collecté sur le terrain (payements complétés par cet agent)
        $totalCollected = Payment::where('agent_id', $admin->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Total déjà versé à la comptabilité
        $versements = Versement::where('agent_id', $admin->id)
            ->with('comptable')
            ->latest()
            ->get();

        $totalVersed = $versements->sum('amount');
        $remainingToVerse = $totalCollected - $totalVersed;

        return view('recouvrement.versements', compact('admin', 'versements', 'totalCollected', 'totalVersed', 'remainingToVerse'));
    }
}
