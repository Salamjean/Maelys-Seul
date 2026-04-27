<?php

namespace App\Http\Controllers\Admin;

use App\Models\Proprietaire;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProprietaireController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $proprietaires = Proprietaire::when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenoms', 'LIKE', "%{$search}%")
                  ->orWhere('contact', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('admin.proprietaires.index', compact('proprietaires'));
    }

    public function create()
    {
        return view('admin.proprietaires.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'contact' => 'required|string',
            'email' => 'nullable|email|max:255',
            'piece_identite_recto' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'piece_identite_verso' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $data = $request->except(['piece_identite_recto', 'piece_identite_verso']);

        if ($request->hasFile('piece_identite_recto')) {
            $data['piece_identite_recto'] = $request->file('piece_identite_recto')->store('proprietaires/pieces', 'public');
        }
        if ($request->hasFile('piece_identite_verso')) {
            $data['piece_identite_verso'] = $request->file('piece_identite_verso')->store('proprietaires/pieces', 'public');
        }

        Proprietaire::create($data);

        return redirect()->route('admin.proprietaires.index')->with('success', 'Propriétaire ajouté avec succès.');
    }

    public function edit(Proprietaire $proprietaire)
    {
        return view('admin.proprietaires.edit', compact('proprietaire'));
    }

    public function update(Request $request, Proprietaire $proprietaire)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'contact' => 'required|string',
            'email' => 'nullable|email|max:255',
            'piece_identite_recto' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'piece_identite_verso' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $data = $request->except(['piece_identite_recto', 'piece_identite_verso']);

        if ($request->hasFile('piece_identite_recto')) {
            if ($proprietaire->piece_identite_recto) {
                Storage::disk('public')->delete($proprietaire->piece_identite_recto);
            }
            $data['piece_identite_recto'] = $request->file('piece_identite_recto')->store('proprietaires/pieces', 'public');
        }

        if ($request->hasFile('piece_identite_verso')) {
            if ($proprietaire->piece_identite_verso) {
                Storage::disk('public')->delete($proprietaire->piece_identite_verso);
            }
            $data['piece_identite_verso'] = $request->file('piece_identite_verso')->store('proprietaires/pieces', 'public');
        }

        $proprietaire->update($data);

        return redirect()->route('admin.proprietaires.index')->with('success', 'Propriétaire mis à jour avec succès.');
    }

    public function destroy(Proprietaire $proprietaire)
    {
        if ($proprietaire->piece_identite_recto) {
            Storage::disk('public')->delete($proprietaire->piece_identite_recto);
        }
        if ($proprietaire->piece_identite_verso) {
            Storage::disk('public')->delete($proprietaire->piece_identite_verso);
        }
        $proprietaire->delete();
        return back()->with('success', 'Propriétaire supprimé.');
    }
}
