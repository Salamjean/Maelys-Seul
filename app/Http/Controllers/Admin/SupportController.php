<?php

namespace App\Http\Controllers\Admin;

use App\Models\MaintenanceRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $requests = MaintenanceRequest::with('user.bien')
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('subject', 'LIKE', "%{$search}%")
                      ->orWhere('message', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($qu) use ($search) {
                          $qu->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('prenoms', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.support.index', compact('requests'));
    }

    public function show(MaintenanceRequest $request)
    {
        return view('admin.support.show', compact('request'));
    }

    public function respond(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        // Empêcher de répondre deux fois
        if ($maintenanceRequest->admin_response) {
            return back()->with('error', 'Ce message a déjà reçu une réponse et est verrouillé.');
        }

        $request->validate([
            'admin_response' => 'required|string',
        ]);

        $maintenanceRequest->update([
            'admin_response' => $request->admin_response,
            'status' => 'completed',
            'responded_at' => now(),
            'is_read_by_user' => false,
        ]);

        return redirect()->route('admin.support.index')->with('success', 'Réponse envoyée et dossier clôturé.');
    }
}
