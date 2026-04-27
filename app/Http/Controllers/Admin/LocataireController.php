<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Bien;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class LocataireController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $locataires = User::where('role', 'locataire')
            ->whereNull('moved_out_at')
            ->with('bien')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('prenoms', 'LIKE', "%{$search}%")
                      ->orWhere('contact', 'LIKE', "%{$search}%")
                      ->orWhere('profession', 'LIKE', "%{$search}%")
                      ->orWhereHas('bien', function($qb) use ($search) {
                          $qb->where('reference', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.locataires.index', compact('locataires'));
    }

    public function create(Request $request)
    {
        $selectedBien = null;
        if ($request->has('bien_id')) {
            $selectedBien = Bien::find($request->bien_id);
        }

        $biens = Bien::where('statut', 'actif')->get();
        return view('admin.locataires.create', compact('biens', 'selectedBien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|string|unique:users,contact',
            'profession' => 'required|string',
            'adresse' => 'required|string',
            'bien_id' => 'required|exists:biens,id',
            'piece_identite' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'contrat_bail' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ], [
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre utilisateur.',
            'contact.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre utilisateur.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'contact.required' => 'Le numéro de contact est obligatoire.',
        ]);

        $data = $request->except(['password', 'password_confirmation']);
        
        $data['password'] = Hash::make(\Illuminate\Support\Str::random(16));
        $data['role'] = 'locataire';
        $data['added_by'] = auth()->guard('admin')->id();

        // Récupérer le loyer du bien
        $bien = Bien::find($request->bien_id);
        if ($bien) {
            $data['loyer_mensuel'] = $bien->loyer_mensuel;
        }

        // Handle File Uploads
        $files = [
            'piece_identite', 'attestation_travail', 'bulletin_salaire',
            'doc_extra_1', 'doc_extra_2', 'doc_extra_3', 'contrat_bail'
        ];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $request->file($file)->store('locataires/documents', 'public');
            }
        }

        // Onboarding security
        $data['configuration_code'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $data['configuration_token'] = \Illuminate\Support\Str::random(40);

        $user = User::create($data);

        // Mettre à jour le statut du bien
        if ($bien) {
            $bien->update(['statut' => 'loue']);
        }

        // Notify by SMS
        try {
            $yellika = new \App\Services\YellikaService();
            $link = route('locataire.onboarding', ['token' => $user->configuration_token]);
            $smsMsg = "Bienvenue chez Maelys-imo ! Utilisez le code {$user->configuration_code} pour activer votre espace ici : {$link}";
            $yellika->send($user->contact, $smsMsg);
        } catch (\Exception $e) {}

        // Notify by Email
        if ($user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LocataireOnboardingMail($user));
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.locataires.index')->with('success', 'Locataire ajouté avec succès. Code d\'onboarding : ' . $user->configuration_code);
    }

    public function edit(User $locataire)
    {
        if ($locataire->role !== 'locataire') abort(404);
        
        $biens = Bien::where('statut', 'actif')
            ->orWhere('id', $locataire->bien_id)
            ->get();

        return view('admin.locataires.edit', compact('locataire', 'biens'));
    }

    public function update(Request $request, User $locataire)
    {
        if ($locataire->role !== 'locataire') abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $locataire->id,
            'contact' => 'required|string|unique:users,contact,' . $locataire->id,
            'profession' => 'required|string',
            'adresse' => 'required|string',
            'bien_id' => 'required|exists:biens,id',
            'piece_identite' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'contrat_bail' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $data = $request->all();

        // Gérer le changement de bien
        if ($request->bien_id != $locataire->bien_id) {
            if ($locataire->bien_id) {
                Bien::where('id', $locataire->bien_id)->update(['statut' => 'actif']);
            }
            Bien::where('id', $request->bien_id)->update(['statut' => 'loue']);
            
            $newBien = Bien::find($request->bien_id);
            $data['loyer_mensuel'] = $newBien->loyer_mensuel;
        }

        $files = [
            'piece_identite', 'attestation_travail', 'bulletin_salaire',
            'doc_extra_1', 'doc_extra_2', 'doc_extra_3', 'contrat_bail'
        ];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                if ($locataire->$file) {
                    Storage::disk('public')->delete($locataire->$file);
                }
                $data[$file] = $request->file($file)->store('locataires/documents', 'public');
            }
        }

        $locataire->update($data);

        return redirect()->route('admin.locataires.index')->with('success', 'Dossier locataire mis à jour avec succès.');
    }

    public function movedOut(Request $request)
    {
        $search = $request->get('search');

        $locataires = User::where('role', 'locataire')
            ->whereNotNull('moved_out_at')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('prenoms', 'LIKE', "%{$search}%")
                      ->orWhere('contact', 'LIKE', "%{$search}%");
                });
            })
            ->latest('moved_out_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.locataires.moved_out', compact('locataires'));
    }

    public function moveOut(User $locataire)
    {
        if ($locataire->role !== 'locataire') abort(404);

        if ($locataire->bien_id) {
            Bien::where('id', $locataire->bien_id)->update(['statut' => 'actif']);
        }

        $locataire->update([
            'moved_out_at' => now(),
            'bien_id' => null
        ]);

        return redirect()->route('admin.locataires.index')->with('success', 'Le déménagement a été enregistré.');
    }

    public function reassign(User $locataire)
    {
        if ($locataire->role !== 'locataire') abort(404);
        $biens = Bien::where('statut', 'actif')->get();
        return view('admin.locataires.reassign', compact('locataire', 'biens'));
    }

    public function processReassign(Request $request, User $locataire)
    {
        if ($locataire->role !== 'locataire') abort(404);

        $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'contrat_bail' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $bien = Bien::find($request->bien_id);
        $bien->update(['statut' => 'loue']);

        $data = [
            'bien_id' => $bien->id,
            'loyer_mensuel' => $bien->loyer_mensuel,
            'moved_out_at' => null,
            'contract_start_date' => now(),
        ];

        if ($request->hasFile('contrat_bail')) {
            if ($locataire->contrat_bail) {
                Storage::disk('public')->delete($locataire->contrat_bail);
            }
            $data['contrat_bail'] = $request->file('contrat_bail')->store('locataires/documents', 'public');
        }

        $locataire->update($data);

        return redirect()->route('admin.locataires.index')->with('success', 'Le locataire a été ré-assigné avec succès.');
    }

    public function destroy(User $locataire)
    {
        // Libérer le bien avant de supprimer le locataire
        if ($locataire->bien_id) {
            $bien = Bien::find($locataire->bien_id);
            if ($bien) {
                $bien->update(['statut' => 'actif']);
            }
        }

        $locataire->delete();
        return back()->with('success', 'Locataire supprimé et bien remis en location.');
    }
}
