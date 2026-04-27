<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VisiteController extends Controller
{
    public function create(Bien $bien)
    {
        return view('home.visite', compact('bien'));
    }

    public function store(Request $request, Bien $bien)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'date_visite' => 'required|date|after_or_equal:today',
            'heure_visite' => [
                'required',
                function ($attribute, $value, $fail) {
                    $hour = (int) substr($value, 0, 2);
                    if ($hour >= 18) {
                        $fail('Les visites ne sont pas autorisées après 18h.');
                    }
                    if ($hour < 8) {
                        $fail('Les visites ne sont pas autorisées avant 08h.');
                    }
                },
            ],
        ]);

        $visite = Visite::create([
            'bien_id' => $bien->id,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_visite' => $request->date_visite,
            'heure_visite' => $request->heure_visite,
            'message' => $request->message,
        ]);

        // Envoi du SMS de confirmation via le nouveau YellikaService
        $yellikaService = new \App\Services\YellikaService();
        $message = "Bonjour {$request->nom}, votre demande de visite pour le bien {$bien->reference} a bien ete recue. Nous vous contacterons pour confirmer le RDV du {$request->date_visite} a {$request->heure_visite}. Merci de votre confiance.";
        
        $yellikaService->send($request->telephone, $message);

        return redirect()->route('home')->with('success', 'Votre demande de visite a été envoyée avec succès. Vous recevrez un SMS de confirmation sous peu.');
    }
}
