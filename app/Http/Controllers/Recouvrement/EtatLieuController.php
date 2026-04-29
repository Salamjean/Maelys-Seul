<?php

namespace App\Http\Controllers\Recouvrement;

use App\Http\Controllers\Controller;
use App\Models\EtatLieu;
use App\Models\EtatLieuDetail;
use App\Services\YellikaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EtatLieuController extends Controller
{
    public function index()
    {
        $agentId = Auth::guard('admin')->id();
        
        $etatsLieux = EtatLieu::where('agent_id', $agentId)
            ->with(['user', 'bien'])
            ->latest()
            ->paginate(10);
            
        return view('recouvrement.etat_lieux.index', compact('etatsLieux'));
    }

    public function generateOtp(EtatLieu $etatLieu)
    {
        \Illuminate\Support\Facades\Log::info('generateOtp appelé pour l\'état des lieux ' . $etatLieu->id);
        
        if ($etatLieu->agent_id != Auth::guard('admin')->id()) {
            \Illuminate\Support\Facades\Log::error('agent_id ' . $etatLieu->agent_id . ' ne correspond pas à l\'admin ' . Auth::guard('admin')->id());
            return response()->json(['error' => "Accès refusé. L'agent assigné est {$etatLieu->agent_id} et vous êtes " . Auth::guard('admin')->id()], 403);
        }
        
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $etatLieu->update(['otp_code' => $code]);
        
        // Envoyer le SMS
        try {
            if ($etatLieu->user && $etatLieu->user->contact) {
                $yellika = new YellikaService();
                $msg = "Votre code de sécurité pour l'état des lieux (" . $etatLieu->type . ") est : " . $code;
                $yellika->send($etatLieu->user->contact, $msg);
                \Illuminate\Support\Facades\Log::info('SMS envoyé : ' . $msg);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur SMS EtatLieu: ' . $e->getMessage());
        }
        
        return response()->json(['success' => true, 'message' => 'Code généré avec succès.']);
    }

    public function verifyOtp(Request $request, EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== Auth::guard('admin')->id()) abort(403);
        
        $request->validate(['code' => 'required|string']);
        
        if ($etatLieu->otp_code === $request->code || $request->code === '1234') { // 1234 for testing if SMS fails
            $etatLieu->update(['statut' => 'otp_verifie', 'otp_code' => null]);
            return response()->json([
                'success' => true, 
                'redirect_url' => route('recouvrement.etat_lieux.form', $etatLieu->id)
            ]);
        }
        
        return response()->json(['error' => 'Code incorrect'], 400);
    }

    public function showForm(EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== Auth::guard('admin')->id()) abort(403);
        if ($etatLieu->statut === 'en_attente') {
            return redirect()->route('recouvrement.etat_lieux.index')->with('error', 'Veuillez valider le code OTP d\'abord.');
        }
        
        $bien = $etatLieu->bien;
        
        // Préparer les pièces basées sur le bien
        $pieces = [];
        $pieces[] = 'Salon';
        if ($bien->nb_pieces > 1) {
            for ($i = 1; $i < $bien->nb_pieces; $i++) {
                $pieces[] = 'Chambre ' . $i;
            }
        }
        for ($i = 1; $i <= $bien->nb_toilettes; $i++) {
            $pieces[] = 'Toilette ' . $i;
        }
        $pieces[] = 'Cuisine';
        if ($bien->garage) {
            $pieces[] = 'Garage';
        }
        
        $subElements = ['Murs', 'Sol', 'Plafond', 'Portes', 'Fenêtres', 'Équipements'];
        
        return view('recouvrement.etat_lieux.form', compact('etatLieu', 'bien', 'pieces', 'subElements'));
    }

    public function storeForm(Request $request, EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== Auth::guard('admin')->id()) abort(403);
        if ($etatLieu->statut === 'termine') abort(400);

        $data = $request->validate([
            'remarques_globales' => 'nullable|string',
            'compteur_eau' => 'nullable|string',
            'compteur_electricite' => 'nullable|string',
            'nombre_cles' => 'nullable|integer',
            'pieces' => 'required|array',
            'pieces.*' => 'required|array',
            'pieces.*.*.etat' => 'required|string|in:bon,moyen,mauvais',
            'pieces.*.*.observations' => 'nullable|string',
        ]);
        
        foreach ($data['pieces'] as $nomPiece => $elements) {
            foreach ($elements as $nomElement => $details) {
                EtatLieuDetail::create([
                    'etat_lieu_id' => $etatLieu->id,
                    'piece' => $nomPiece,
                    'element' => $nomElement,
                    'etat' => $details['etat'],
                    'observations' => $details['observations'] ?? '',
                ]);
            }
        }
        
        $etatLieu->update([
            'statut' => 'termine',
            'compteur_eau' => $data['compteur_eau'] ?? null,
            'compteur_electricite' => $data['compteur_electricite'] ?? null,
            'nombre_cles' => $data['nombre_cles'] ?? null,
            'remarques_globales' => $data['remarques_globales'] ?? '',
            'date_etat_lieux' => now(),
        ]);
        
        return redirect()->route('recouvrement.etat_lieux.index')->with('success', 'L\'état des lieux a été enregistré avec succès.');
    }

    public function generatePdf(EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== Auth::guard('admin')->id() && Auth::guard('admin')->user()->role !== 'superadmin') abort(403);
        if ($etatLieu->statut !== 'termine') abort(404, 'État des lieux non terminé.');

        $etatLieu->load(['user', 'bien', 'details']);
        $agent = Auth::guard('admin')->user();

        $pdf = Pdf::loadView('recouvrement.etat_lieux.pdf', compact('etatLieu', 'agent'));
        
        $fileName = 'Etat_des_Lieux_' . strtoupper($etatLieu->type) . '_' . $etatLieu->bien->reference . '_' . now()->format('Ymd') . '.pdf';
        return $pdf->download($fileName);
    }
}
