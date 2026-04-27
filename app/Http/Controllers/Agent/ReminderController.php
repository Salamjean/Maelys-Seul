<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\YellikaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    protected $yellika;

    public function __construct(YellikaService $yellika)
    {
        $this->yellika = $yellika;
    }

    public function index()
    {
        $allLocataires = User::where('role', 'locataire')->with('bien')->get();
        $lateTenants = [];
        $currentMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();

        foreach ($allLocataires as $locataire) {
            // Un locataire doit avoir un bien pour avoir une date de paiement
            if (!$locataire->bien) {
                continue;
            }

            $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
            $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
                ->where('status', 'completed')
                ->sum('months_count');
            
            // Calcul du prochain mois dû
            $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);

            // Si le prochain mois dû est inférieur ou égal au mois en cours
            if ($nextPaymentDate->lessThanOrEqualTo($currentMonth)) {
                
                // Vérifier si on a atteint le jour de paiement spécifié dans le bien
                $dayOfPayment = $locataire->bien->date_paiement ?? 5; // Par défaut le 5 si non défini
                
                // Si on est le mois en cours, on vérifie le jour
                if ($nextPaymentDate->equalTo($currentMonth)) {
                    if ($today->day >= $dayOfPayment) {
                        $locataire->is_really_late = true;
                    } else {
                        // Pas encore la date de paiement pour ce mois
                        continue;
                    }
                } else {
                    // C'est un mois passé, il est forcément en retard
                    $locataire->is_really_late = true;
                }

                $monthsLate = $nextPaymentDate->diffInMonths($currentMonth) + 1;
                $locataire->months_late = $monthsLate;
                $locataire->next_period = ucfirst($nextPaymentDate->translatedFormat('F Y'));
                $lateTenants[] = $locataire;
            }
        }

        return view('agent.rappels.index', compact('lateTenants'));
    }

    public function sendReminder(User $locataire)
    {
        $locataire->load('bien');
        
        if (!$locataire->bien) {
            return back()->with('error', "Ce locataire n'a pas de bien assigné.");
        }

        // Calcul de la période due
        $startDate = $locataire->contract_start_date ? Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
        $monthsAlreadyPaid = (int) Payment::where('user_id', $locataire->id)
            ->where('status', 'completed')
            ->sum('months_count');
        $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);
        $period = ucfirst($nextPaymentDate->translatedFormat('F Y'));
        
        $amount = number_format($locataire->bien->loyer_mensuel, 0, ',', ' ');

        // 1. Envoi de l'Email
        $emailSent = false;
        if ($locataire->email) {
            try {
                Mail::send([], [], function ($message) use ($locataire, $period, $amount) {
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
                                <div style='background-color: #f9f9f9; padding: 15px; text-align: center; font-size: 0.8em; color: #999;'>
                                    Ceci est un message automatique de rappel. Merci de ne pas y répondre.
                                </div>
                            </div>
                        ");
                });
                $emailSent = true;
            } catch (\Exception $e) {
                Log::error("Erreur envoi email rappel : " . $e->getMessage());
            }
        }

        // 2. Envoi du SMS
        $smsSent = false;
        if ($locataire->contact) {
            $smsMessage = "RAPPEL: Bonjour {$locataire->name}, votre loyer de {$amount} FCFA ({$period}) est arrive a echeance. Merci de regulariser votre situation. Maelys Immobilier.";
            $smsSent = $this->yellika->send($locataire->contact, $smsMessage);
        }

        $statusMsg = "Rappel envoyé avec succès.";
        if ($emailSent && $smsSent) {
            $statusMsg = "Rappel envoyé par Email et SMS.";
        } elseif ($emailSent) {
            $statusMsg = "Rappel envoyé par Email (échec SMS).";
        } elseif ($smsSent) {
            $statusMsg = "Rappel envoyé par SMS (échec Email).";
        } else {
            return back()->with('error', "Impossible d'envoyer le rappel (Email et SMS en échec).");
        }

        return back()->with('success', $statusMsg);
    }
}
