<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Statistiques réelles
        $total_encaissements = Payment::where('status', 'completed')->sum('amount');
        $count_biens = Bien::count();
        $count_locataires = User::where('role', 'locataire')->count();
        $count_messages = MaintenanceRequest::count();
        
        // Activité récente (5 derniers paiements complétés)
        $recent_payments = Payment::with('user')->where('status', 'completed')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'admin', 
            'total_encaissements', 
            'count_biens', 
            'count_locataires', 
            'count_messages',
            'recent_payments'
        ));
    }
}
