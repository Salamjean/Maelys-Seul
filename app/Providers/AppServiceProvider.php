<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Configuration de la langue en français pour les dates
        \Carbon\Carbon::setLocale('fr');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fr_FR', 'fr', 'french', 'fra', 'French_France');

        // Partager les notifications de visites et paiements avec la navbar ET la sidebar (Admin, Agent et Comptable)
        \Illuminate\Support\Facades\View::composer(['admin.layouts.navbar', 'admin.layouts.sidebar', 'agent.layouts.navbar', 'agent.layouts.sidebar', 'comptable.layouts.app', 'comptable.layouts.sidebar'], function ($view) {
            $view->with('nouvelles_visites', \App\Models\Visite::where('statut', 'en_attente')->latest()->take(5)->get());
            $view->with('count_visites_en_attente', \App\Models\Visite::where('statut', 'en_attente')->count());
            
            // Compteur des paiements manuels en attente
            $view->with('count_paiements_en_attente', \App\Models\Payment::where('status', 'pending')
                ->where('payment_method', 'bank')
                ->count());

            // Compteur des messages SAV en attente
            $view->with('count_messages_en_attente', \App\Models\MaintenanceRequest::where('status', 'pending')
                ->count());

            // Compteur des rappels (locataires en retard)
            $allLocataires = \App\Models\User::where('role', 'locataire')->whereNotNull('bien_id')->with('bien')->get();
            $countRappels = 0;
            $currentMonth = \Carbon\Carbon::now()->startOfMonth();
            $todayDay = \Carbon\Carbon::now()->day;

            foreach ($allLocataires as $locataire) {
                $startDate = $locataire->contract_start_date ? \Carbon\Carbon::parse($locataire->contract_start_date) : $locataire->created_at;
                $monthsAlreadyPaid = (int) \App\Models\Payment::where('user_id', $locataire->id)
                    ->where('status', 'completed')
                    ->sum('months_count');
                $nextPaymentDate = $startDate->copy()->startOfMonth()->addMonths($monthsAlreadyPaid);

                if ($nextPaymentDate->lessThan($currentMonth)) {
                    $countRappels++;
                } elseif ($nextPaymentDate->equalTo($currentMonth)) {
                    $dayOfPayment = $locataire->bien->date_paiement ?? 5;
                    if ($todayDay >= $dayOfPayment) {
                        $countRappels++;
                    }
                }
            }
            $view->with('count_rappels', $countRappels);

        });

        // Partage des données avec les vues Locataire
        \Illuminate\Support\Facades\View::composer('locataire.*', function ($view) {
            if (auth()->check()) {
                $view->with('count_notifications_sav', \App\Models\MaintenanceRequest::where('user_id', auth()->id())
                    ->whereNotNull('admin_response')
                    ->where('is_read_by_user', false)
                    ->count());
            }
        });
    }
}
