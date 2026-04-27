<?php

namespace App\Http\Controllers\Locataire;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function show(Request $request, $token)
    {
        $user = User::where('configuration_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Lien de configuration invalide ou expiré.');
        }

        return view('locataire.auth.setup_password', compact('user', 'token'));
    }

    public function process(Request $request, $token)
    {
        $request->validate([
            'code' => 'required|string|size:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('configuration_token', $token)->first();

        if (!$user) {
            return back()->with('error', 'Erreur de session. Veuillez recommencer.');
        }

        if ($user->configuration_code !== $request->code) {
            return back()->with('error', 'Le code de configuration est incorrect.');
        }

        // Validate and complete onboarding
        $user->update([
            'password' => Hash::make($request->password),
            'configuration_code' => null, // Code consumed
            'configuration_token' => null, // Link consumed
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Votre compte a été activé avec succès ! Connectez-vous maintenant pour accéder à votre espace.');
    }
}
