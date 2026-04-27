<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérifier si l'utilisateur est connecté sur la garde 'admin'
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Veuillez vous connecter pour accéder à votre espace.');
        }

        // 2. Vérifier si l'utilisateur a bien le rôle 'agent'
        if (Auth::guard('admin')->user()->role !== 'agent') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé. Cet espace est réservé aux agents.');
        }

        return $next($request);
    }
}
