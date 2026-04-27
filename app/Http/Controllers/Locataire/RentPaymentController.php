<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\WaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class RentPaymentController extends Controller
{
    protected WaveService $waveService;

    public function __construct(WaveService $waveService)
    {
        $this->waveService = $waveService;
    }
    public function index()
    {
        $user = Auth::user();
        $bien = $user->bien;

        if (!$bien) {
            return redirect()->back()->with('error', "Aucun bien n'est associé à votre compte.");
        }

        // Calcul du mois prochain dû en fonction des paiements validés uniquement
        // On utilise sum('months_count') car un paiement peut couvrir plusieurs mois
        $monthsAlreadyPaid = (int) Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('months_count');

        $startDate = $user->contract_start_date ? Carbon::parse($user->contract_start_date) : Carbon::parse($user->created_at);
        $nextMonthDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);

        // Vérification s'il y a un paiement en attente (plus de 2 minutes) pour informer le locataire
        $pendingPayment = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(2))
            ->first();

        $nextMonth = ucfirst($nextMonthDate->translatedFormat('F Y'));
        $amount = (float) $user->bien->loyer_mensuel;

        return view('locataire.pay', compact('nextMonth', 'amount', 'user', 'pendingPayment'));
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
            'provider' => 'nullable|string',
            'months_count' => 'required|integer|min:1|max:12',
            'payment_proof' => 'nullable|file|mimes:jpeg,png,pdf|max:5120',
            'period_display' => 'required'
        ]);

        $user = Auth::user();
        $bien = $user->bien;

        if (!$bien) {
            return redirect()->back()->with('error', "Aucun bien n'est associé à votre compte.");
        }

        $monthsCount = $request->months_count;
        $totalAmount = $bien->loyer_mensuel * $monthsCount;

        // On annule les anciens paiements en attente pour éviter les doublons
        Payment::where('user_id', $user->id)->where('status', 'pending')->delete();

        // Création du paiement en attente
        $payment = new \App\Models\Payment();
        $payment->user_id = $user->id;
        $payment->months_count = $monthsCount;
        $payment->amount = $totalAmount;
        $payment->reference = 'PAY-' . strtoupper(\Illuminate\Support\Str::random(10));
        $payment->status = 'pending';
        $payment->payment_method = $request->payment_method;
        $payment->mobile_network = $request->provider; // Récupération du réseau (Orange, MTN, Moov, Wave)
        $payment->periode_couverte = ($monthsCount > 1)
            ? $request->period_display . ' (sur ' . $monthsCount . ' mois)'
            : $request->period_display;
        $payment->paid_at = now();

        // Gestion de la preuve de paiement (Virement)
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('proofs', 'public');
            $payment->payment_proof = $path;
        }

        $payment->save();

        // Si c'est Wave, on initie la session de paiement via le service
        if ($request->payment_method === 'mobile' && $request->provider === 'wave') {
            $session = $this->waveService->createCheckoutSession(
                $totalAmount,
                $payment->reference,
                route('locataire.quittances'),
                route('locataire.pay')
            );

            if ($session) {
                return redirect($session['wave_launch_url']);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'initialisation du paiement Wave. Veuillez réessayer.');
        }

        return redirect()->route('locataire.quittances')->with('success', 'Félicitations ! Votre demande de règlement pour ' . $payment->periode_couverte . ' a été envoyée. Montant total : ' . number_format($totalAmount, 0, ',', ' ') . ' CFA.');
    }

    public function retry(Payment $payment)
    {
        // On ne peut relancer que si c'est un paiement mobile en attente
        if ($payment->status !== 'pending' || $payment->payment_method !== 'mobile') {
            return redirect()->route('locataire.quittances')->with('error', 'Ce paiement ne peut pas être relancé.');
        }

        $session = $this->waveService->createCheckoutSession(
            $payment->amount,
            $payment->reference,
            route('locataire.quittances'),
            route('locataire.pay')
        );

        if ($session) {
            return redirect($session['wave_launch_url']);
        }

        return redirect()->back()->with('error', 'Impossible de relancer le paiement Wave. Veuillez réessayer plus tard.');
    }
    public function contrat()
    {
        $user = Auth::user();
        $bien = $user->bien;

        return view('locataire.contrat', compact('user', 'bien'));
    }

    public function webhook(Request $request)
    {
        Log::info('Wave Webhook Received', ['payload' => $request->all()]);

        $signature = $request->header('Wave-Signature');
        $payload = $request->getContent();

        // Vérification sécurisée de la signature via le service
        if (!$this->waveService->verifyWebhookSignature($signature, $payload)) {
            Log::warning('Wave Webhook: Invalid Signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->all();

        if (isset($event['type']) && $event['type'] === 'checkout.session.completed') {
            $session = $event['data'];
            $payment = Payment::where('reference', $session['client_reference'])->first();

            if ($payment) {
                if ($payment->status === 'pending') {
                    // Génération du QR Code avant validation
                    $qrData = "MAELYS-IMO - Quittance de Loyer\n" .
                              "Référence: {$payment->reference}\n" .
                              "Locataire: {$payment->user->name} {$payment->user->prenoms}\n" .
                              "Montant: {$payment->amount} FCFA\n" .
                              "Période: {$payment->periode_couverte}\n" .
                              "Date: " . now()->format('d/m/Y H:i');
                    
                    $qrFileName = 'qrcodes/' . $payment->reference . '.svg';
                    
                    try {
                        if (!Storage::disk('public')->exists('qrcodes')) {
                            Storage::disk('public')->makeDirectory('qrcodes');
                        }
                        $qrContent = QrCode::size(300)->margin(2)->generate($qrData);
                        Storage::disk('public')->put($qrFileName, $qrContent);
                        $payment->qr_code = $qrFileName;
                    } catch (\Exception $e) {
                        Log::error("Erreur QR Webhook : " . $e->getMessage());
                    }

                    $payment->status = 'completed';
                    $payment->paid_at = now();
                    $payment->save();
                    Log::info('Wave Payment Validated Automatically with QR Code', ['reference' => $payment->reference]);
                } else {
                    Log::info('Wave Payment already processed', ['reference' => $payment->reference, 'status' => $payment->status]);
                }
            } else {
                Log::error('Wave Payment NOT FOUND in database', ['reference' => $session['client_reference']]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
