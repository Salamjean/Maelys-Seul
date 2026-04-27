<?php

namespace App\Http\Controllers\Admin;

use App\Models\Visite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisiteController extends Controller
{
    public function demandees()
    {
        $visites = Visite::with('bien')
            ->whereIn('statut', ['en_attente', 'confirmee'])
            ->latest()
            ->paginate(10);

        return view('admin.visites.demandees', compact('visites'));
    }

    public function effectuees()
    {
        $visites = Visite::with('bien')
            ->where('statut', 'terminee')
            ->latest()
            ->paginate(10);

        return view('admin.visites.effectuees', compact('visites'));
    }

    public function confirmer(Request $request, Visite $visite)
    {
        $oldDate = $visite->date_visite;
        $oldHeure = $visite->heure_visite;

        $visite->update([
            'statut' => 'confirmee',
            'date_visite' => $request->date_visite ?? $visite->date_visite,
            'heure_visite' => $request->heure_visite ?? $visite->heure_visite,
            'motif' => $request->motif
        ]);

        // Envoi du SMS de confirmation...
        $yellika = new \App\Services\YellikaService();
        $bien = $visite->bien;
        
        $msg = "Bonjour {$visite->nom}, votre demande de visite pour le bien {$bien->reference} est CONFIRMEE pour le {$visite->date_visite} a {$visite->heure_visite}.";
        
        if ($oldDate != $visite->date_visite || $oldHeure != $visite->heure_visite) {
            $msg = "Bonjour {$visite->nom}, concernant votre visite pour le bien {$bien->reference}, le RDV est DECALE au {$visite->date_visite} a {$visite->heure_visite}. Motif: {$visite->motif}. Merci.";
        }

        $yellika->send($visite->telephone, $msg);

        return back()->with('success', 'La visite a été confirmée/planifiée et un SMS a été envoyé.');
    }

    public function terminer(Visite $visite)
    {
        $visite->update(['statut' => 'terminee']);
        return back()->with('success', 'La visite a été marquée comme effectuée avec succès.');
    }

    public function annuler(Visite $visite)
    {
        $visite->update(['statut' => 'annulee']);
        return back()->with('success', 'La visite a été annulée.');
    }
}
