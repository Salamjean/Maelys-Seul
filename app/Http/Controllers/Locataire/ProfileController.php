<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('locataire.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact' => 'required|string|unique:users,contact,' . $user->id,
            'current_password' => 'required',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Vérification du mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        // Mise à jour des infos de base
        $user->name = $request->name;
        $user->prenoms = $request->prenoms;
        $user->email = $request->email;
        $user->contact = $request->contact;

        // Mise à jour du mot de passe si renseigné
        if ($request->new_password) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
