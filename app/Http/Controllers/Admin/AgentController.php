<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Admin::where('role', 'agent')->latest()->get();
        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'residence' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
        ]);

        $token = \Illuminate\Support\Str::random(32);
        $code = rand(1000, 9999);

        $agent = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(\Illuminate\Support\Str::random(16)), // Mot de passe temporaire aléatoire
            'role' => 'agent',
            'residence' => $request->residence,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact' => $request->emergency_contact,
            'emergency_contact_relation' => $request->emergency_contact_relation,
            'onboarding_token' => $token,
            'onboarding_code' => $code,
        ]);

        // Envoi du mail d'invitation
        \Illuminate\Support\Facades\Mail::to($agent->email)->send(new \App\Mail\AgentOnboardingMail($agent));

        return redirect()->route('admin.agents.index')->with('success', 'Agent invité avec succès. Un mail d\'activation lui a été envoyé.');
    }

    public function edit(Admin $agent)
    {
        // Vérifier que c'est bien un agent
        if ($agent->role !== 'agent') {
            abort(403);
        }
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Admin $agent)
    {
        if ($agent->role !== 'agent') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $agent->id,
            'password' => 'nullable|string|min:8|confirmed',
            'residence' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'residence' => $request->residence,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact' => $request->emergency_contact,
            'emergency_contact_relation' => $request->emergency_contact_relation,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $agent->update($data);

        return redirect()->route('admin.agents.index')->with('success', 'Agent mis à jour avec succès.');
    }

    public function destroy(Admin $agent)
    {
        if ($agent->role !== 'agent') {
            abort(403);
        }
        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent supprimé avec succès.');
    }
}
