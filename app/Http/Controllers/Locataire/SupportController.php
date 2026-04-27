<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $requests = MaintenanceRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('locataire.support.index', compact('requests'));
    }

    public function create()
    {
        return view('locataire.support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'message' => 'required|string',
        ]);

        MaintenanceRequest::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('locataire.support.index')->with('success', 'Votre demande a été envoyée avec succès à l\'agence.');
    }

    public function show(MaintenanceRequest $request)
    {
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->admin_response && !$request->is_read_by_user) {
            $request->update(['is_read_by_user' => true]);
        }

        return view('locataire.support.show', compact('request'));
    }
}
