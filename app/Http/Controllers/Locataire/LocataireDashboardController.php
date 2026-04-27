<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocataireDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('bien');
        
        // Statistiques
        $stats = [
            'total_paye' => \App\Models\Payment::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
            'nb_quittances' => \App\Models\Payment::where('user_id', $user->id)->where('status', 'completed')->count(),
            'nb_messages_sav' => \App\Models\MaintenanceRequest::where('user_id', $user->id)->count(),
            'notifications_sav' => \App\Models\MaintenanceRequest::where('user_id', $user->id)
                ->whereNotNull('admin_response')
                ->where('is_read_by_user', false)
                ->count(),
            'is_current_month_paid' => \App\Models\Payment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->exists(),
            'is_current_month_pending' => \App\Models\Payment::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'initiated'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->exists(),
            'current_month' => now()->locale('fr')->translatedFormat('F Y'),
        ];

        // Dernières activités
        $recentPayments = \App\Models\Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $recentSupport = \App\Models\MaintenanceRequest::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();
        
        return view('locataire.dashboard', compact('user', 'stats', 'recentPayments', 'recentSupport'));
    }
}
