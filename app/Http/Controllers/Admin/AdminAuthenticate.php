<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user->role === 'agent') {
                return redirect()->route('agent.dashboard');
            } elseif ($user->role === 'comptable') {
                return redirect()->route('comptable.dashboard');
            } elseif ($user->role === 'recouvrement') {
                return redirect()->route('recouvrement.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::guard('admin')->user();
            
            if ($user->role === 'agent') {
                return redirect()->route('agent.dashboard');
            } elseif ($user->role === 'comptable') {
                return redirect()->route('comptable.dashboard');
            } elseif ($user->role === 'recouvrement') {
                return redirect()->route('recouvrement.dashboard');
            }
            
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
