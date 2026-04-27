<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BienController extends Controller
{
    public function index(Request $request)
    {
        $query = Bien::query();

        // Recherche par référence
        if ($request->filled('search')) {
            $query->where('reference', 'LIKE', '%' . $request->search . '%');
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type_bien', $request->type);
        }

        // Filtre par commune
        if ($request->filled('commune')) {
            $query->where('commune', 'LIKE', '%' . $request->commune . '%');
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $biens = $query->latest()->paginate(15)->withQueryString();
        
        return view('agent.biens.index', compact('biens'));
    }

    public function create()
    {
        return view('agent.biens.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_bien' => ['required', 'in:appartement,maison,bureau'],
            'typologie' => ['required', 'in:studio,f1,f2,f3,f4,f5_plus,duplex,triplex,villa,chambre_salon,autre'],
            'superficie' => ['required', 'integer', 'min:1'],
            'commune' => ['required', 'string', 'max:100'],
            'nb_pieces' => ['required', 'integer', 'min:0'],
            'nb_toilettes' => ['required', 'integer', 'min:0'],
            'garage' => ['boolean'],
            'type_utilisation' => ['required', 'string', 'max:50'],
            'loyer_mensuel' => ['required', 'numeric', 'min:0'],
            'avance' => ['nullable', 'integer', 'min:1'],
            'caution' => ['nullable', 'integer', 'min:1'],
            'frais_agence' => ['nullable', 'numeric', 'min:0'],
            'montant_total' => ['nullable', 'numeric', 'min:0'],
            'date_paiement' => ['nullable', 'integer', 'min:1', 'max:31'],
            'photo_principale' => ['required', 'image', 'max:4096'],
            'photos_supplementaires' => ['nullable', 'array', 'max:10'],
            'photos_supplementaires.*' => ['image', 'max:4096'],
            'video_3d' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:20480'],
            'google_maps_url' => ['nullable', 'url', 'max:2000'],
            'description' => ['nullable', 'string'],
        ]);

        // Photo principale
        $validated['photo_principale'] = $request->file('photo_principale')
            ->store('biens/photos', 'public');

        // Photos supplémentaires
        if ($request->hasFile('photos_supplementaires')) {
            $paths = [];
            foreach ($request->file('photos_supplementaires') as $photo) {
                $paths[] = $photo->store('biens/photos', 'public');
            }
            $validated['photos_supplementaires'] = $paths;
        }

        // Vidéo 3D
        if ($request->hasFile('video_3d')) {
            $validated['video_3d'] = $request->file('video_3d')->store('biens/videos', 'public');
        }

        // Génération de la référence
        $prefix = match ($validated['type_bien']) {
            'appartement' => 'A',
            'maison' => 'M',
            'bureau' => 'B',
            default => 'X',
        };
        $validated['reference'] = $prefix . strtoupper(Str::random(6)) . date('Y');

        $validated['statut'] = 'actif';
        $validated['added_by'] = auth()->guard('admin')->id();

        $validated['avance'] = (float) ($validated['avance'] ?? 0);
        $validated['caution'] = (float) ($validated['caution'] ?? 0);
        $validated['frais_agence'] = (float) ($request->input('frais_agence') ?? 1);

        $validated['montant_total'] = (float) $validated['loyer_mensuel']
            * ($validated['avance'] + $validated['caution'] + $validated['frais_agence']);
        $validated['garage'] = $request->boolean('garage');

        Bien::create($validated);

        return redirect()->route('agent.biens.index')
            ->with('success', 'Bien ajouté avec succès.');
    }

    public function edit(Bien $bien)
    {
        return view('agent.biens.edit', compact('bien'));
    }

    public function update(Request $request, Bien $bien)
    {
        $validated = $request->validate([
            'type_bien' => ['required', 'in:appartement,maison,bureau'],
            'typologie' => ['required', 'in:studio,f1,f2,f3,f4,f5_plus,duplex,triplex,villa,chambre_salon,autre'],
            'superficie' => ['required', 'integer', 'min:1'],
            'commune' => ['required', 'string', 'max:100'],
            'nb_pieces' => ['required', 'integer', 'min:0'],
            'nb_toilettes' => ['required', 'integer', 'min:0'],
            'garage' => ['boolean'],
            'type_utilisation' => ['required', 'string', 'max:50'],
            'loyer_mensuel' => ['required', 'numeric', 'min:0'],
            'avance' => ['nullable', 'integer', 'min:1'],
            'caution' => ['nullable', 'integer', 'min:1'],
            'frais_agence' => ['nullable', 'numeric', 'min:0'],
            'date_paiement' => ['nullable', 'integer', 'min:1', 'max:31'],
            'photo_principale' => ['nullable', 'image', 'max:4096'],
            'photos_supplementaires' => ['nullable', 'array', 'max:10'],
            'photos_supplementaires.*' => ['image', 'max:4096'],
            'video_3d' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:20480'],
            'google_maps_url' => ['nullable', 'url', 'max:2000'],
            'description' => ['nullable', 'string'],
            'statut' => ['required', 'in:actif,inactif,loué'],
        ]);

        // Photo principale
        if ($request->hasFile('photo_principale')) {
            if ($bien->photo_principale) Storage::disk('public')->delete($bien->photo_principale);
            $validated['photo_principale'] = $request->file('photo_principale')->store('biens/photos', 'public');
        }

        // Photos supplémentaires
        if ($request->hasFile('photos_supplementaires')) {
            $paths = [];
            foreach ($request->file('photos_supplementaires') as $photo) {
                $paths[] = $photo->store('biens/photos', 'public');
            }
            $validated['photos_supplementaires'] = $paths;
        }

        // Vidéo 3D
        if ($request->hasFile('video_3d')) {
            if ($bien->video_3d) Storage::disk('public')->delete($bien->video_3d);
            $validated['video_3d'] = $request->file('video_3d')->store('biens/videos', 'public');
        }

        $validated['avance'] = (float) ($validated['avance'] ?? 0);
        $validated['caution'] = (float) ($validated['caution'] ?? 0);
        $validated['frais_agence'] = (float) ($request->input('frais_agence') ?? 1);

        $validated['montant_total'] = (float) $validated['loyer_mensuel']
            * ($validated['avance'] + $validated['caution'] + $validated['frais_agence']);
        $validated['garage'] = $request->boolean('garage');

        $bien->update($validated);

        return redirect()->route('agent.biens.index')->with('success', 'Bien mis à jour avec succès.');
    }

    public function show(Bien $bien)
    {
        return view('agent.biens.show', compact('bien'));
    }

    public function destroy(Bien $bien)
    {
        $bien->delete();
        return redirect()->route('agent.biens.index')->with('success', 'Bien archivé avec succès.');
    }
}
