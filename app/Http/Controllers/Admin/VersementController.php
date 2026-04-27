<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\Versement;
use Illuminate\Http\Request;

class VersementController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $currentTab = $request->get('tab', 'agents');

        // Récupérer uniquement les agents de recouvrement (qui effectuent les versements)
        $agents = Admin::where('role', 'recouvrement')->get();

        // Calculer les statistiques pour chaque agent
        foreach ($agents as $agent) {
            $agent->total_collected = Payment::where('agent_id', $agent->id)
                ->where('status', 'completed')
                ->sum('amount');

            $agent->total_versed = Versement::where('agent_id', $agent->id)
                ->sum('amount');

            $agent->balance = $agent->total_collected - $agent->total_versed;
        }

        $agentId = $request->get('agent_id');
        $month = $request->get('month'); // Format YYYY-MM

        // Récupérer l'historique global des versements avec filtres
        $versementsQuery = Versement::with(['agent', 'comptable']);

        if ($agentId) {
            $versementsQuery->where('agent_id', $agentId);
        }

        if ($month) {
            $date = \Carbon\Carbon::parse($month);
            $versementsQuery->whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month);
        }

        $versements = $versementsQuery->latest()->paginate(15)->withQueryString();

        // Stats (basées sur les filtres pour la période si sélectionnée)
        $paymentsQuery = Payment::whereIn('agent_id', $agents->pluck('id'))->where('status', 'completed');
        $versedQuery = Versement::query();

        if ($month) {
            $date = \Carbon\Carbon::parse($month);
            $paymentsQuery->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
            $versedQuery->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
        }
        
        if ($agentId) {
            $paymentsQuery->where('agent_id', $agentId);
            $versedQuery->where('agent_id', $agentId);
        }

        $stats = [
            'total_collected' => $paymentsQuery->sum('amount'),
            'total_versed' => $versedQuery->sum('amount'),
        ];
        $stats['remaining'] = $stats['total_collected'] - $stats['total_versed'];

        return view('admin.versements.index', compact('admin', 'agents', 'versements', 'stats', 'currentTab'));
    }
}
