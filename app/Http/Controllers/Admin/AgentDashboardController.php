<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Visite;

class AgentDashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('admin')->user();
        
        // Statistiques simples pour l'agent
        $stats = [
            'paiements_attente' => Payment::where('status', 'pending')->count(),
            'sav_attente' => MaintenanceRequest::whereNull('admin_response')->count(),
            'visites_jour' => Visite::whereDate('date_visite', now())->count(),
        ];

        // On peut imaginer une table "AgentFile" plus tard, 
        // pour l'instant on prépare la vue avec des données simulées ou réelles si elles existent
        $files = []; 

        return view('admin.agent_dashboard', compact('agent', 'stats', 'files'));
    }
}
