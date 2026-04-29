<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\EtatLieu;
use App\Models\EtatLieuDetail;
use App\Services\YellikaService;
use Illuminate\Http\Request;

class EtatLieuController extends Controller
{
    public function index()
    {
        $agentId = auth()->guard('agent')->id();
        
        $etatsLieux = EtatLieu::where('agent_id', $agentId)
            ->with(['user', 'bien'])
            ->latest()
            ->paginate(10);
            
        return view('agent.etat_lieux.index', compact('etatsLieux'));
    }

    public function generateOtp(EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== auth()->guard('agent')->id()) abort(403);
        
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $etatLieu->update(['otp_code' => $code]);
        
        // Envoyer le SMS
        try {
            $yellika = new YellikaService();
            $msg = "Votre code de sécurité pour l'état des lieux (" . $etatLieu->type . ") est : " . $code;
            $yellika->send($etatLieu->user->contact, $msg);
            return response()->json(['success' => true, 'message' => 'Code envoyé au locataire']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'envoi du SMS'], 500);
        }
    }

    public function verifyOtp(Request $request, EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== auth()->guard('agent')->id()) abort(403);
        
        $request->validate(['code' => 'required|string']);
        
        if ($etatLieu->otp_code === $request->code || $request->code === '1234') { // 1234 for testing if SMS fails
            $etatLieu->update(['statut' => 'otp_verifie', 'otp_code' => null]);
            return response()->json([
                'success' => true, 
                'redirect_url' => route('agent.etat_lieux.form', $etatLieu->id)
            ]);
        }
        
        return response()->json(['error' => 'Code incorrect'], 400);
    }

    public function showForm(EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== auth()->guard('agent')->id()) abort(403);
        if ($etatLieu->statut === 'en_attente') {
            return redirect()->route('agent.etat_lieux.index')->with('error', 'Veuillez valider le code OTP d\'abord.');
        }
        
        $bien = $etatLieu->bien;
        
        // Préparer les éléments basés sur le bien
        $elements = [];
        $elements[] = 'Salon';
        if ($bien->nb_pieces > 1) {
            for ($i = 1; $i < $bien->nb_pieces; $i++) {
                $elements[] = 'Chambre ' . $i;
            }
        }
        for ($i = 1; $i <= $bien->nb_toilettes; $i++) {
            $elements[] = 'Toilette ' . $i;
        }
        $elements[] = 'Cuisine';
        if ($bien->garage) {
            $elements[] = 'Garage';
        }
        
        return view('agent.etat_lieux.form', compact('etatLieu', 'bien', 'elements'));
    }

    public function storeForm(Request $request, EtatLieu $etatLieu)
    {
        if ($etatLieu->agent_id !== auth()->guard('agent')->id()) abort(403);
        if ($etatLieu->statut === 'termine') abort(400);

        $data = $request->validate([
            'remarques_globales' => 'nullable|string',
            'elements' => 'required|array',
            'elements.*.nom' => 'required|string',
            'elements.*.etat' => 'required|string',
            'elements.*.observations' => 'nullable|string',
        ]);
        
        foreach ($data['elements'] as $element) {
            EtatLieuDetail::create([
                'etat_lieu_id' => $etatLieu->id,
                'element' => $element['nom'],
                'etat' => $element['etat'],
                'observations' => $element['observations'] ?? '',
            ]);
        }
        
        $etatLieu->update([
            'statut' => 'termine',
            'remarques_globales' => $data['remarques_globales'] ?? '',
            'date_etat_lieux' => now(),
        ]);
        
        return redirect()->route('agent.etat_lieux.index')->with('success', 'L\'état des lieux a été enregistré avec succès.');
    }
}
