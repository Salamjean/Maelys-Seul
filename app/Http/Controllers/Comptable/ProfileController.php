<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        return view('comptable.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'contact' => 'required|string|unique:admins,contact,' . $admin->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'required',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Vérification du mot de passe actuel
        if (!Hash::check($request->current_password, $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        // Mise à jour des infos de base
        $admin->name = $request->name;
        $admin->prenoms = $request->prenoms;
        $admin->email = $request->email;
        $admin->contact = $request->contact;

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
            }
            $path = $request->file('photo')->store('profile_photos', 'public');
            $admin->photo = $path;
        }

        // Mise à jour du mot de passe si renseigné
        if ($request->new_password) {
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
