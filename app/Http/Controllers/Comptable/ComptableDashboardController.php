<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class ComptableDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $currentMonth = \Carbon\Carbon::now()->startOfMonth();

        // 1. Total Encaissé ce mois (Tous types confondus, validés)
        $totalEncaisseMois = \App\Models\Payment::where('status', 'completed')
            ->where('updated_at', '>=', $currentMonth)
            ->sum('amount');

        // 2. Paiements en attente de validation (Montant total)
        $totalAttenteValidation = \App\Models\Payment::where('status', 'pending')
            ->whereIn('payment_method', ['bank', 'bank_transfer', 'mobile', 'wave', 'orange_money', 'mtn_money'])
            ->sum('amount');

        $countAttenteValidation = \App\Models\Payment::where('status', 'pending')
            ->whereIn('payment_method', ['bank', 'bank_transfer', 'mobile', 'wave', 'orange_money', 'mtn_money'])
            ->count();

        // 3. Total des versements reçus (des agents)
        $totalVersementsRecus = \App\Models\Versement::where('created_at', '>=', $currentMonth)
            ->sum('amount');

        // 4. Locataires en retard (Count) - Réutilisation de la logique de rappels
        $allLocataires = User::where('role', 'locataire')->with('bien')->get();
        $countLateTenants = 0;
        foreach ($allLocataires as $locataire) {
            if (!$locataire->bien)
                continue;
            $startDate = $locataire->contract_start_date ? \Carbon\Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
            $monthsAlreadyPaid = (int) \App\Models\Payment::where('user_id', $locataire->id)->where('status', 'completed')->sum('months_count');
            $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);
            if ($nextPaymentDate->lessThanOrEqualTo($currentMonth)) {
                $dayOfPayment = $locataire->bien->date_paiement ?? 5;
                if ($nextPaymentDate->equalTo($currentMonth)) {
                    if (\Carbon\Carbon::now()->day >= $dayOfPayment)
                        $countLateTenants++;
                } else {
                    $countLateTenants++;
                }
            }
        }

        // 5. Dernières transactions
        $recentTransactions = \App\Models\Payment::with(['user', 'agent'])
            ->where('status', 'completed')
            ->latest()
            ->take(8)
            ->get();

        return view('comptable.dashboard', compact(
            'admin',
            'totalEncaisseMois',
            'totalAttenteValidation',
            'countAttenteValidation',
            'totalVersementsRecus',
            'countLateTenants',
            'recentTransactions'
        ));
    }

    public function locataires(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $search = $request->get('search');

        $locataires = User::where('role', 'locataire')
            ->with('bien')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('prenoms', 'LIKE', "%{$search}%")
                        ->orWhere('contact', 'LIKE', "%{$search}%")
                        ->orWhereHas('bien', function ($qb) use ($search) {
                            $qb->where('reference', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('comptable.locataires.index', compact('admin', 'locataires'));
    }

    public function pendingPayments(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $search = $request->get('search');

        $pendingPayments = \App\Models\Payment::with('user.bien')
            ->where('status', 'pending')
            ->where('payment_method', 'bank')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($qu) use ($search) {
                            $qu->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('prenoms', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('comptable.payments.pending', compact('admin', 'pendingPayments'));
    }

    public function historyPayments(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $search = $request->get('search');
        \Carbon\Carbon::setLocale('fr');

        $queryBase = \App\Models\Payment::with(['user.bien', 'agent'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($qu) use ($search) {
                            $qu->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('prenoms', 'LIKE', "%{$search}%");
                        });
                });
            });

        $mobilePayments = (clone $queryBase)->where('payment_method', 'mobile')->latest()->paginate(6, ['*'], 'mobile_p')->withQueryString();
        $bankPayments = (clone $queryBase)->where('payment_method', 'bank')->latest()->paginate(6, ['*'], 'bank_p')->withQueryString();
        $cashPayments = (clone $queryBase)->where('payment_method', 'especes')->latest()->paginate(6, ['*'], 'cash_p')->withQueryString();

        return view('comptable.payments.history', compact('admin', 'mobilePayments', 'bankPayments', 'cashPayments'));
    }

    public function versements()
    {
        $admin = Auth::guard('admin')->user();
        $agents = \App\Models\Admin::where('role', 'recouvrement')->get();
        $versements = \App\Models\Versement::with(['agent', 'comptable'])->latest()->paginate(10);

        return view('comptable.versements.index', compact('admin', 'agents', 'versements'));
    }

    public function getAgentStats($agentId)
    {
        $totalCollected = \App\Models\Payment::where('agent_id', $agentId)
            ->where('status', 'completed')
            ->sum('amount');

        $totalDeposited = \App\Models\Versement::where('agent_id', $agentId)
            ->sum('amount');

        $remaining = $totalCollected - $totalDeposited;

        return response()->json([
            'total_collected' => $totalCollected,
            'total_deposited' => $totalDeposited,
            'remaining' => $remaining
        ]);
    }

    public function storeVersement(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:admins,id',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string'
        ]);

        $agentId = $request->agent_id;
        $amountToDeposit = $request->amount;

        // Vérifier le solde de l'agent
        $totalCollected = \App\Models\Payment::where('agent_id', $agentId)
            ->where('status', 'completed')
            ->sum('amount');

        $totalDeposited = \App\Models\Versement::where('agent_id', $agentId)
            ->sum('amount');

        $remaining = $totalCollected - $totalDeposited;

        if ($amountToDeposit > $remaining) {
            return back()->with('error', "Le montant saisi dépasse le solde encaissé par l'agent (" . number_format($remaining, 0, ',', ' ') . " FCFA).");
        }

        \App\Models\Versement::create([
            'agent_id' => $agentId,
            'comptable_id' => Auth::guard('admin')->id(),
            'amount' => $amountToDeposit,
            'reference' => 'VERS-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'notes' => $request->notes
        ]);

        return back()->with('success', "Versement enregistré avec succès.");
    }

    public function rappels()
    {
        $allLocataires = \App\Models\User::where('role', 'locataire')->with('bien')->get();
        $lateTenants = [];
        $currentMonth = \Carbon\Carbon::now()->startOfMonth();
        $today = \Carbon\Carbon::now();

        foreach ($allLocataires as $locataire) {
            if (!$locataire->bien)
                continue;

            $startDate = $locataire->contract_start_date ? \Carbon\Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
            $monthsAlreadyPaid = (int) \App\Models\Payment::where('user_id', $locataire->id)
                ->where('status', 'completed')
                ->sum('months_count');

            $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);

            if ($nextPaymentDate->lessThanOrEqualTo($currentMonth)) {
                $dayOfPayment = $locataire->bien->date_paiement ?? 5;
                if ($nextPaymentDate->equalTo($currentMonth)) {
                    if ($today->day >= $dayOfPayment) {
                        $locataire->is_really_late = true;
                    } else {
                        continue;
                    }
                } else {
                    $locataire->is_really_late = true;
                }

                $monthsLate = $nextPaymentDate->diffInMonths($currentMonth) + 1;
                $locataire->months_late = $monthsLate;
                $locataire->next_period = ucfirst($nextPaymentDate->translatedFormat('F Y'));
                $lateTenants[] = $locataire;
            }
        }

        return view('comptable.rappels.index', compact('lateTenants'));
    }

    public function sendRappel(\App\Models\User $locataire)
    {
        $locataire->load('bien');
        if (!$locataire->bien)
            return back()->with('error', "Ce locataire n'a pas de bien assigné.");

        $startDate = $locataire->contract_start_date ? \Carbon\Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
        $monthsAlreadyPaid = (int) \App\Models\Payment::where('user_id', $locataire->id)
            ->where('status', 'completed')
            ->sum('months_count');
        $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);
        $period = ucfirst($nextPaymentDate->translatedFormat('F Y'));
        $amount = number_format($locataire->bien->loyer_mensuel, 0, ',', ' ');

        // Email
        $emailSent = false;
        if ($locataire->email) {
            try {
                \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($locataire, $period, $amount) {
                    $message->to($locataire->email)
                        ->subject('Rappel de paiement de loyer - Maelys Immobilier')
                        ->html("
                            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
                                <div style='background-color: #02245b; padding: 20px; text-align: center;'>
                                    <h1 style='color: white; margin: 0;'>MAELYS-<span style='color:#ff5e14;'>IMO</span></h1>
                                </div>
                                <div style='padding: 30px;'>
                                    <p>Bonjour <strong>{$locataire->name} {$locataire->prenoms}</strong>,</p>
                                    <p>Nous vous informons que votre loyer pour la période de <strong>{$period}</strong> est désormais dû.</p>
                                    <p>Montant à régler : <strong style='color: #ff5e14; font-size: 1.2em;'>{$amount} FCFA</strong></p>
                                    <p>Nous vous prions de bien vouloir effectuer votre règlement dans les plus brefs délais via votre espace locataire ou en agence.</p>
                                    <br>
                                    <p>Cordialement,<br>L'équipe Maelys Immobilier</p>
                                </div>
                            </div>
                        ");
                });
                $emailSent = true;
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        // SMS
        $smsSent = false;
        if ($locataire->contact) {
            $smsMessage = "RAPPEL: Bonjour {$locataire->name}, votre loyer de {$amount} FCFA ({$period}) est arrive a echeance. Merci de regulariser votre situation. Maelys Immobilier.";
            try {
                $yellika = app(\App\Services\YellikaService::class);
                $smsSent = $yellika->send($locataire->contact, $smsMessage);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        return back()->with('success', "Rappel envoyé avec succès.");
    }
}
