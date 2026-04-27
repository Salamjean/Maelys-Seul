<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffOnboardingMail;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Admin::whereIn('role', ['comptable', 'recouvrement'])->latest()->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'role' => 'required|in:comptable,recouvrement',
            'residence' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
        ]);

        $token = Str::random(32);
        $code = rand(1000, 9999);

        $member = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(16)),
            'role' => $request->role,
            'residence' => $request->residence,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact' => $request->emergency_contact,
            'emergency_contact_relation' => $request->emergency_contact_relation,
            'onboarding_token' => $token,
            'onboarding_code' => $code,
        ]);

        // Envoi du mail d'invitation (On va créer StaffOnboardingMail)
        Mail::to($member->email)->send(new StaffOnboardingMail($member));

        return redirect()->route('admin.staff.index')->with('success', 'Collaborateur invité avec succès. Un mail d\'activation lui a été envoyé.');
    }

    public function destroy(Admin $member)
    {
        if (!in_array($member->role, ['comptable', 'recouvrement'])) {
            abort(403);
        }
        $member->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Collaborateur supprimé avec succès.');
    }
}
