<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Veuillez vous connecter pour accéder à l\'espace admin.');
        }

        // Vérifier si l'utilisateur est un admin
        $user = Auth::guard('admin')->user();
        if ($user->role !== 'admin') {
            if ($user->role === 'agent') {
                return redirect()->route('agent.dashboard')->with('error', 'Accès réservé aux administrateurs.');
            } elseif ($user->role === 'comptable') {
                return redirect()->route('comptable.dashboard')->with('error', 'Accès réservé aux administrateurs.');
            } elseif ($user->role === 'recouvrement') {
                return redirect()->route('recouvrement.dashboard')->with('error', 'Accès réservé aux administrateurs.');
            }
            
            return redirect()->route('admin.login')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
