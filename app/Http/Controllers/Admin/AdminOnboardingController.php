<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminOnboardingController extends Controller
{
    public function show($token)
    {
        $agent = Admin::where('onboarding_token', $token)->firstOrFail();
        return view('admin.auth.onboarding', compact('agent', 'token'));
    }

    public function complete(Request $request, $token)
    {
        $agent = Admin::where('onboarding_token', $token)->firstOrFail();

        $request->validate([
            'onboarding_code' => 'required|string|size:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->onboarding_code != $agent->onboarding_code) {
            return back()->withErrors(['onboarding_code' => 'Le code d\'activation est incorrect.'])->withInput();
        }

        $agent->update([
            'password' => Hash::make($request->password),
            'onboarding_token' => null,
            'onboarding_code' => null, // On invalide les deux
        ]);

        // Optionnel : Connecter automatiquement l'agent ou rediriger vers le login
        return redirect()->route('admin.login')->with('success', 'Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.');
    }
}
