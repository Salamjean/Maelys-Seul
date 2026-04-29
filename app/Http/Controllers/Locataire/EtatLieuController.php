<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use App\Models\EtatLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EtatLieuController extends Controller
{
    public function index()
    {
        $locataire = Auth::user();
        
        $etatsLieux = EtatLieu::where('user_id', $locataire->id)
            ->where('statut', 'termine')
            ->with(['bien', 'agent'])
            ->latest()
            ->get();
            
        return view('locataire.etat_lieux.index', compact('etatsLieux'));
    }

    public function downloadPdf(EtatLieu $etatLieu)
    {
        if ($etatLieu->user_id !== Auth::id()) {
            abort(403);
        }

        if ($etatLieu->statut !== 'termine') {
            abort(404, 'État des lieux non terminé.');
        }

        $etatLieu->load(['user', 'bien', 'details', 'agent']);
        $agent = $etatLieu->agent;

        $pdf = Pdf::loadView('recouvrement.etat_lieux.pdf', compact('etatLieu', 'agent'));
        
        $fileName = 'Etat_des_Lieux_' . strtoupper($etatLieu->type) . '_' . $etatLieu->bien->reference . '_' . now()->format('Ymd') . '.pdf';
        return $pdf->download($fileName);
    }
}
