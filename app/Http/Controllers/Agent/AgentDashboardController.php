<?php

namespace App\Http\Controllers\Agent;

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
        
        $stats = [
            'paiements_attente' => Payment::where('status', 'pending')->count(),
            'sav_attente' => MaintenanceRequest::whereNull('admin_response')->count(),
            'visites_jour' => Visite::whereDate('date_visite', now())->count(),
        ];

        return view('agent.dashboard', compact('agent', 'stats'));
    }
}
