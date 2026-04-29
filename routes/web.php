<?php

use App\Http\Controllers\Admin\AdminAuthenticate;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BienController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | Les routes simple de l'application
    |--------------------------------------------------------------------------
*/

Route::prefix('/')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/toutes-les-annonces', [HomeController::class, 'allBiens'])->name('biens.all');
    Route::get('/biens/{bien}', [HomeController::class, 'show'])->name('biens.show');
    Route::get('/biens/{bien}/visite', [\App\Http\Controllers\Home\VisiteController::class, 'create'])->name('visite.create');
    Route::post('/biens/{bien}/visite', [\App\Http\Controllers\Home\VisiteController::class, 'store'])->name('visite.store');

    // Page Contact
    Route::get('/contact', [\App\Http\Controllers\Home\ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [\App\Http\Controllers\Home\ContactController::class, 'store'])->name('contact.store');

    // Onboarding Locataire
    Route::get('/configurer-mon-compte/{token}', [\App\Http\Controllers\Locataire\OnboardingController::class, 'show'])->name('locataire.onboarding');
    Route::post('/configurer-mon-compte/{token}', [\App\Http\Controllers\Locataire\OnboardingController::class, 'process'])->name('locataire.onboarding.process');

    // Auth Locataire
    Route::get('/connexion', [\App\Http\Controllers\Locataire\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [\App\Http\Controllers\Locataire\LoginController::class, 'login'])->name('locataire.login.process');
    Route::post('/deconnexion', [\App\Http\Controllers\Locataire\LoginController::class, 'logout'])->name('locataire.logout');

    // Mot de passe oublié (Locataire)
    Route::get('/mot-de-passe-oublie', [\App\Http\Controllers\Locataire\Auth\ForgotPasswordController::class, 'showForgotForm'])->name('locataire.password.request');
    Route::post('/mot-de-passe-oublie', [\App\Http\Controllers\Locataire\Auth\ForgotPasswordController::class, 'sendResetCode'])->name('locataire.password.email');
    Route::get('/verifier-code-reinitialisation', [\App\Http\Controllers\Locataire\Auth\ForgotPasswordController::class, 'showVerifyForm'])->name('locataire.password.verify');
    Route::post('/verifier-code-reinitialisation', [\App\Http\Controllers\Locataire\Auth\ForgotPasswordController::class, 'verifyCode'])->name('locataire.password.verify_process');
    Route::get('/reinitialiser-mot-de-passe', [\App\Http\Controllers\Locataire\Auth\ForgotPasswordController::class, 'showResetForm'])->name('locataire.password.reset_form');
    Route::post('/reinitialiser-mot-de-passe', [\App\Http\Controllers\Locataire\Auth\ForgotPasswordController::class, 'resetPassword'])->name('locataire.password.update');

    // Espace Locataire protégé
    Route::middleware('auth')->prefix('mon-espace')->group(function () {
        Route::get('/', [\App\Http\Controllers\Locataire\LocataireDashboardController::class, 'index'])->name('locataire.dashboard');
        Route::get('/quittances', [\App\Http\Controllers\Locataire\QuittanceController::class, 'index'])->name('locataire.quittances');
        Route::get('/quittances/{payment}/download', [\App\Http\Controllers\Locataire\QuittanceController::class, 'download'])->name('locataire.quittances.download');

        // Nouvelles routes pour le paiement et le contrat
        Route::get('/payer-loyer', [\App\Http\Controllers\Locataire\RentPaymentController::class, 'index'])->name('locataire.pay');
        Route::post('/payer-loyer/initier', [\App\Http\Controllers\Locataire\RentPaymentController::class, 'initiate'])->name('locataire.pay.initiate');
        Route::get('/payer-loyer/retry/{payment}', [\App\Http\Controllers\Locataire\RentPaymentController::class, 'retry'])->name('locataire.pay.retry');
        Route::get('/mon-contrat', [\App\Http\Controllers\Locataire\RentPaymentController::class, 'contrat'])->name('locataire.contrat');

        // États des Lieux
        Route::get('/etats-des-lieux', [\App\Http\Controllers\Locataire\EtatLieuController::class, 'index'])->name('locataire.etat_lieux.index');
        Route::get('/etats-des-lieux/{etatLieu}/pdf', [\App\Http\Controllers\Locataire\EtatLieuController::class, 'downloadPdf'])->name('locataire.etat_lieux.pdf');

        // SAV / Support
        Route::get('/support', [\App\Http\Controllers\Locataire\SupportController::class, 'index'])->name('locataire.support.index');
        Route::get('/support/nouveau', [\App\Http\Controllers\Locataire\SupportController::class, 'create'])->name('locataire.support.create');
        Route::post('/support/envoyer', [\App\Http\Controllers\Locataire\SupportController::class, 'store'])->name('locataire.support.store');
        Route::get('/support/{request}', [\App\Http\Controllers\Locataire\SupportController::class, 'show'])->name('locataire.support.show');

        // Mon Profil
        Route::get('/mon-profil', [\App\Http\Controllers\Locataire\ProfileController::class, 'show'])->name('locataire.profile.show');
        Route::post('/mon-profil', [\App\Http\Controllers\Locataire\ProfileController::class, 'update'])->name('locataire.profile.update');
    });

    // Webhook Wave (Public)
    Route::post('/wave/webhook', [\App\Http\Controllers\Locataire\RentPaymentController::class, 'webhook'])->name('wave.webhook');
});


/*
    |--------------------------------------------------------------------------
    | Les routes de gestion de l'administration de l'agence immobilière
    |--------------------------------------------------------------------------
*/
Route::prefix('/admin')->group(function () {

    // Authentification (publiques)
    Route::get('/login', [AdminAuthenticate::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminAuthenticate::class, 'handleLogin'])->name('admin.handleLogin');
    Route::post('/logout', [AdminAuthenticate::class, 'logout'])->name('admin.logout');

    // Mot de passe oublié (Admin)
    Route::get('/mot-de-passe-oublie', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showForgotForm'])->name('admin.password.request');
    Route::post('/mot-de-passe-oublie', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetCode'])->name('admin.password.email');
    Route::get('/verifier-code-reinitialisation', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showVerifyForm'])->name('admin.password.verify');
    Route::post('/verifier-code-reinitialisation', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'verifyCode'])->name('admin.password.verify_process');
    Route::get('/reinitialiser-mot-de-passe', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset_form');
    Route::post('/reinitialiser-mot-de-passe', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'resetPassword'])->name('admin.password.update');

    // Onboarding Agents (Activation de compte)
    Route::get('/onboarding/{token}', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'show'])->name('admin.onboarding');
    Route::post('/onboarding/{token}', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'complete'])->name('admin.onboarding.complete');

    // ==========================================
    // ESPACE AGENT (Protégé par 'agent' middleware)
    // ==========================================
    Route::prefix('agent')->name('agent.')->middleware('agent')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Agent\AgentDashboardController::class, 'index'])->name('dashboard');
        
        // Paiements
        Route::get('/paiements-en-attente', [\App\Http\Controllers\Agent\PaymentController::class, 'pending'])->name('payments.pending');
        Route::get('/historique-paiements', [\App\Http\Controllers\Agent\PaymentController::class, 'history'])->name('payments.history');
        Route::post('/paiements/{payment}/valider', [\App\Http\Controllers\Agent\PaymentController::class, 'validatePayment'])->name('payments.validate');
        Route::post('/paiements/{payment}/rejeter', [\App\Http\Controllers\Agent\PaymentController::class, 'cancelPayment'])->name('payments.cancel');
        
        // Nouvelles routes de paiement (Espèces/OTP)
        Route::post('/locataires/{locataire}/payments/initiate', [\App\Http\Controllers\Agent\PaymentController::class, 'initiateCashPayment'])->name('payments.initiate');
        Route::post('/payments/{payment}/confirm', [\App\Http\Controllers\Agent\PaymentController::class, 'confirmCashPayment'])->name('payments.confirm');
        Route::post('/payments/confirm-direct', [\App\Http\Controllers\Agent\PaymentController::class, 'confirmByCode'])->name('payments.confirm_direct');
        Route::get('/locataires/{locataire}/next-payment-info', [\App\Http\Controllers\Agent\PaymentController::class, 'getNextPaymentInfo'])->name('payments.next_info');
        
        // SAV / Support
        Route::get('/support', [\App\Http\Controllers\Agent\SupportController::class, 'index'])->name('support.index');
        Route::get('/support/{supportRequest}', [\App\Http\Controllers\Agent\SupportController::class, 'show'])->name('support.show');
        Route::post('/support/{supportRequest}/repondre', [\App\Http\Controllers\Agent\SupportController::class, 'respond'])->name('support.respond');
        
        // API Helpers
        Route::get('/api/locataires-list', function () {
            return \App\Models\User::where('role', 'locataire')->whereNotNull('bien_id')->get(['id', 'name', 'prenoms']);
        })->name('api.locataires');

        // Visites
        Route::get('/visites', [\App\Http\Controllers\Agent\VisiteController::class, 'index'])->name('visites.index');
        Route::get('/visites-effectuees', [\App\Http\Controllers\Agent\VisiteController::class, 'effectuees'])->name('visites.effectuees');
        Route::post('/visites/{visite}/confirmer', [\App\Http\Controllers\Agent\VisiteController::class, 'confirmer'])->name('visites.confirmer');
        Route::post('/visites/{visite}/terminer', [\App\Http\Controllers\Agent\VisiteController::class, 'terminer'])->name('visites.terminer');
        Route::post('/visites/{visite}/annuler', [\App\Http\Controllers\Agent\VisiteController::class, 'annuler'])->name('visites.annuler');

        // Biens Immobilier
        Route::resource('biens', \App\Http\Controllers\Agent\BienController::class);

        // Locataires
        Route::get('locataires/{locataire}/reassign', [\App\Http\Controllers\Agent\LocataireController::class, 'reassign'])->name('locataires.reassign');
        Route::post('locataires/{locataire}/reassign', [\App\Http\Controllers\Agent\LocataireController::class, 'processReassign'])->name('locataires.reassign_process');
        Route::get('locataires/demenages', [\App\Http\Controllers\Agent\LocataireController::class, 'movedOut'])->name('locataires.moved_out');
        Route::post('locataires/{locataire}/demenagement', [\App\Http\Controllers\Agent\LocataireController::class, 'moveOut'])->name('locataires.move_out');
        Route::resource('locataires', \App\Http\Controllers\Agent\LocataireController::class);

        // Rappels
        Route::get('/rappels', [\App\Http\Controllers\Agent\ReminderController::class, 'index'])->name('rappels.index');
        Route::post('/rappels/{locataire}/envoyer', [\App\Http\Controllers\Agent\ReminderController::class, 'sendReminder'])->name('rappels.send');

        // Mes Fichiers
        Route::get('/mes-fichiers', [\App\Http\Controllers\Agent\FileController::class, 'index'])->name('files.index');
        Route::post('/mes-fichiers', [\App\Http\Controllers\Agent\FileController::class, 'store'])->name('files.store');
        Route::delete('/mes-fichiers/{file}', [\App\Http\Controllers\Agent\FileController::class, 'destroy'])->name('files.destroy');

    });

    // ==========================================
    // ESPACE COMPTABLE (Protégé par 'comptable' middleware)
    // ==========================================
    Route::prefix('comptable')->name('comptable.')->middleware('comptable')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'index'])->name('dashboard');
        Route::get('/locataires', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'locataires'])->name('locataires.index');
        
        // Gestion des paiements (À Valider / Historique)
        Route::get('/paiements-en-attente', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'pendingPayments'])->name('payments.pending');
        Route::get('/historique-paiements', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'historyPayments'])->name('payments.history');
        Route::post('/paiements/{payment}/valider', [\App\Http\Controllers\Admin\PaymentController::class, 'validateManualPayment'])->name('payments.validate');
        Route::post('/paiements/{payment}/rejeter', [\App\Http\Controllers\Admin\PaymentController::class, 'cancelManualPayment'])->name('payments.cancel');

        // Paiements (Espèces/OTP) pour le comptable
        Route::post('/locataires/{locataire}/payments/initiate', [\App\Http\Controllers\Admin\PaymentController::class, 'initiateCashPayment'])->name('payments.initiate');
        Route::post('/payments/{payment}/confirm', [\App\Http\Controllers\Admin\PaymentController::class, 'confirmCashPayment'])->name('payments.confirm');
        Route::post('/payments/confirm-direct', [\App\Http\Controllers\Admin\PaymentController::class, 'confirmByCode'])->name('payments.confirm_direct');
        Route::get('/locataires/{locataire}/next-payment-info', [\App\Http\Controllers\Admin\PaymentController::class, 'getNextPaymentInfo'])->name('payments.next_info');

        // Versements (Agents vers Comptable)
        Route::get('/versements', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'versements'])->name('versements.index');
        Route::post('/versements', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'storeVersement'])->name('versements.store');
        Route::get('/api/agent-stats/{agent}', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'getAgentStats'])->name('api.agent_stats');

        // Rappels
        Route::get('/rappels', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'rappels'])->name('rappels.index');
        Route::post('/rappels/{locataire}/send', [\App\Http\Controllers\Comptable\ComptableDashboardController::class, 'sendRappel'])->name('rappels.send');

        // Mon Profil
        Route::get('/mon-profil', [\App\Http\Controllers\Comptable\ProfileController::class, 'show'])->name('profile.show');
        Route::post('/mon-profil', [\App\Http\Controllers\Comptable\ProfileController::class, 'update'])->name('profile.update');
    });

    // ==========================================
    // ESPACE RECOUVREMENT (Protégé par 'recouvrement' middleware)
    // ==========================================
    Route::prefix('recouvrement')->name('recouvrement.')->middleware('recouvrement')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tenants/late', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'lateTenants'])->name('tenants.late');
        Route::get('/tenants/{locataire}/pay', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'pay'])->name('tenants.pay');
        Route::post('/tenants/{locataire}/initiate', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'initiatePayment'])->name('tenants.initiate_payment');
        Route::get('/payments/{payment}/confirm', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'confirmPaymentForm'])->name('tenants.confirm_payment');
        Route::post('/payments/{payment}/verify', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'verifyPayment'])->name('tenants.verify_payment');
        Route::get('/my-payments', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'myPayments'])->name('my_payments');
        Route::get('/mes-versements', [\App\Http\Controllers\Recouvrement\RecouvrementDashboardController::class, 'versements'])->name('versements.index');

        // Etats des lieux
        Route::get('/etat-lieux', [\App\Http\Controllers\Recouvrement\EtatLieuController::class, 'index'])->name('etat_lieux.index');
        Route::post('/etat-lieux/{etatLieu}/generate-otp', [\App\Http\Controllers\Recouvrement\EtatLieuController::class, 'generateOtp'])->name('etat_lieux.generate_otp');
        Route::post('/etat-lieux/{etatLieu}/verify-otp', [\App\Http\Controllers\Recouvrement\EtatLieuController::class, 'verifyOtp'])->name('etat_lieux.verify_otp');
        Route::get('/etat-lieux/{etatLieu}/form', [\App\Http\Controllers\Recouvrement\EtatLieuController::class, 'showForm'])->name('etat_lieux.form');
        Route::post('/etat-lieux/{etatLieu}/store', [\App\Http\Controllers\Recouvrement\EtatLieuController::class, 'storeForm'])->name('etat_lieux.store');
        Route::get('/etat-lieux/{etatLieu}/pdf', [\App\Http\Controllers\Recouvrement\EtatLieuController::class, 'generatePdf'])->name('etat_lieux.pdf');

        // Mon Profil
        Route::get('/mon-profil', [\App\Http\Controllers\Recouvrement\ProfileController::class, 'show'])->name('profile.show');
        Route::post('/mon-profil', [\App\Http\Controllers\Recouvrement\ProfileController::class, 'update'])->name('profile.update');
    });

    // ==========================================
    // ESPACE ADMIN (Protégé par 'admin' middleware)
    // ==========================================
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Locataires
        Route::get('/locataires', [App\Http\Controllers\Admin\LocataireController::class, 'index'])->name('admin.locataires.index');
        Route::get('/locataires/ajouter', [App\Http\Controllers\Admin\LocataireController::class, 'create'])->name('admin.locataires.create');
        Route::get('/locataires/demenages', [\App\Http\Controllers\Admin\LocataireController::class, 'movedOut'])->name('admin.locataires.moved_out');
        Route::get('/locataires/{locataire}/modifier', [App\Http\Controllers\Admin\LocataireController::class, 'edit'])->name('admin.locataires.edit');
        Route::put('/locataires/{locataire}', [App\Http\Controllers\Admin\LocataireController::class, 'update'])->name('admin.locataires.update');
        Route::post('/locataires', [App\Http\Controllers\Admin\LocataireController::class, 'store'])->name('admin.locataires.store');
        Route::post('/locataires/{locataire}/demenagement', [\App\Http\Controllers\Admin\LocataireController::class, 'moveOut'])->name('admin.locataires.move_out');
        Route::get('/locataires/{locataire}/reassign', [\App\Http\Controllers\Admin\LocataireController::class, 'reassign'])->name('admin.locataires.reassign');
        Route::post('/locataires/{locataire}/reassign', [\App\Http\Controllers\Admin\LocataireController::class, 'processReassign'])->name('admin.locataires.reassign_process');
        Route::post('/locataires/{locataire}/payments/initiate', [\App\Http\Controllers\Admin\PaymentController::class, 'initiateCashPayment'])->name('admin.payments.initiate');
        Route::post('/payments/{payment}/confirm', [\App\Http\Controllers\Admin\PaymentController::class, 'confirmCashPayment'])->name('admin.payments.confirm');
        Route::post('/payments/confirm-direct', [\App\Http\Controllers\Admin\PaymentController::class, 'confirmByCode'])->name('admin.payments.confirm_direct');
        Route::get('/locataires/{locataire}/next-payment-info', [\App\Http\Controllers\Admin\PaymentController::class, 'getNextPaymentInfo'])->name('admin.payments.next_info');
        Route::delete('/locataires/{locataire}', [App\Http\Controllers\Admin\LocataireController::class, 'destroy'])->name('admin.locataires.destroy');

        // Validations de paiements (Virements / Mobile)
        Route::get('/paiements-en-attente', [\App\Http\Controllers\Admin\PaymentController::class, 'pending'])->name('admin.payments.pending');
        Route::post('/paiements/{payment}/valider', [\App\Http\Controllers\Admin\PaymentController::class, 'validateManualPayment'])->name('admin.payments.validate');
        Route::post('/paiements/{payment}/rejeter', [\App\Http\Controllers\Admin\PaymentController::class, 'cancelManualPayment'])->name('admin.payments.cancel');
        Route::get('/historique-virements', [\App\Http\Controllers\Admin\PaymentController::class, 'history'])->name('admin.payments.history');
        Route::get('/versements', [\App\Http\Controllers\Admin\VersementController::class, 'index'])->name('admin.versements.index');
        Route::get('/rappels', [\App\Http\Controllers\Admin\ReminderController::class, 'index'])->name('admin.rappels.index');
        Route::post('/rappels/{locataire}/envoyer', [\App\Http\Controllers\Admin\ReminderController::class, 'sendReminder'])->name('admin.rappels.send');



        // API Helpers
        Route::get('/api/locataires-list', function () {
            return \App\Models\User::where('role', 'locataire')->whereNotNull('bien_id')->get(['id', 'name', 'prenoms']);
        })->name('admin.api.locataires');

        Route::get('/api/pending-payments', function () {
            return \App\Models\Payment::where('status', 'pending')->with('user:id,name,prenoms')->get();
        })->name('admin.api.pending_payments');

        // Visites
        Route::get('/visites/demandees', [\App\Http\Controllers\Admin\VisiteController::class, 'demandees'])->name('admin.visites.demandees');
        Route::get('/visites/effectuees', [\App\Http\Controllers\Admin\VisiteController::class, 'effectuees'])->name('admin.visites.effectuees');
        Route::post('/visites/{visite}/confirmer', [\App\Http\Controllers\Admin\VisiteController::class, 'confirmer'])->name('admin.visites.confirmer');
        Route::post('/visites/{visite}/terminer', [\App\Http\Controllers\Admin\VisiteController::class, 'terminer'])->name('admin.visites.terminer');
        Route::post('/visites/{visite}/annuler', [\App\Http\Controllers\Admin\VisiteController::class, 'annuler'])->name('admin.visites.annuler');

        // Biens
        Route::get('/biens', [BienController::class, 'index'])->name('admin.biens.index');
        Route::get('/biens/create', [BienController::class, 'create'])->name('admin.biens.create');
        Route::post('/biens', [BienController::class, 'store'])->name('admin.biens.store');
        Route::get('/biens/{bien}', [BienController::class, 'show'])->name('admin.biens.show');
        Route::get('/biens/{bien}/edit', [BienController::class, 'edit'])->name('admin.biens.edit');
        Route::put('/biens/{bien}', [BienController::class, 'update'])->name('admin.biens.update');
        Route::delete('/biens/{bien}', [BienController::class, 'destroy'])->name('admin.biens.destroy');

        // Agents (Gestion de l'équipe)
        Route::resource('agents', \App\Http\Controllers\Admin\AgentController::class)->names([
            'index' => 'admin.agents.index',
            'create' => 'admin.agents.create',
            'store' => 'admin.agents.store',
            'edit' => 'admin.agents.edit',
            'update' => 'admin.agents.update',
            'destroy' => 'admin.agents.destroy',
        ]);

        // SAV / Support Locataires
        Route::get('/support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('admin.support.index');
        Route::get('/support/{request}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('admin.support.show');
        Route::post('/support/{maintenanceRequest}/respond', [\App\Http\Controllers\Admin\SupportController::class, 'respond'])->name('admin.support.respond');

        // Propriétaires (Partenaires)
        Route::resource('proprietaires', \App\Http\Controllers\Admin\ProprietaireController::class)->names([
            'index' => 'admin.proprietaires.index',
            'create' => 'admin.proprietaires.create',
            'store' => 'admin.proprietaires.store',
            'edit' => 'admin.proprietaires.edit',
            'update' => 'admin.proprietaires.update',
            'destroy' => 'admin.proprietaires.destroy',
        ]);
        // Gestion du Personnel (Comptable & Recouvrement)
        Route::get('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('admin.staff.index');
        Route::get('/staff/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('admin.staff.store');
        Route::delete('/staff/{member}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('admin.staff.destroy');
    });
});
