<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('locataire.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'login_type' => 'required|string|in:email,phone'
        ]);

        $loginValue = $request->login;
        $loginType = $request->login_type;

        // Validation stricte du format selon le type choisi
        if ($loginType === 'email') {
            if (!filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Veuillez entrer une adresse email valide.')->withInput();
            }
            $loginField = 'email';
        } else {
            if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Veuillez entrer un numéro de téléphone, pas un email.')->withInput();
            }
            $loginField = 'contact';
        }

        $attempt = Auth::guard('web')->attempt([
            $loginField => $loginValue,
            'password' => $request->password,
        ], $request->filled('remember'));

        if ($attempt) {
            $request->session()->regenerate();
            return redirect()->intended(route('locataire.dashboard'))->with('success', 'Heureux de vous revoir !');
        }

        $errorMessage = ($loginType === 'email') 
            ? 'Cette adresse email ou le mot de passe est incorrect.' 
            : 'Ce numéro de téléphone ou le mot de passe est incorrect.';

        return back()->withInput($request->only('login', 'remember'))->with('error', $errorMessage);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
