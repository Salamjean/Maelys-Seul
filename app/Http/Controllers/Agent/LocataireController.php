<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use App\Models\Bien;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\LocataireOnboardingMail;

class LocataireController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $locataires = User::where('role', 'locataire')
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

        return view('agent.locataires.index', compact('locataires'));
    }

    public function create(Request $request)
    {
        $selectedBien = null;
        if ($request->has('bien_id')) {
            $selectedBien = Bien::find($request->bien_id);
        }

        $biens = Bien::where('statut', 'actif')->get();
        return view('agent.locataires.create', compact('biens', 'selectedBien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'contact' => 'required|string',
            'profession' => 'required|string',
            'email' => 'nullable|email|unique:users',
            'adresse' => 'nullable|string',
            'bien_id' => 'required|exists:biens,id',
            'piece_identite' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'contrat_bail' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $data = $request->all();
        
        $data['password'] = Hash::make(Str::random(16));
        $data['role'] = 'locataire';
        $data['added_by'] = auth()->guard('admin')->id();

        // Récupérer le loyer du bien pour la cohérence
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
        $data['configuration_token'] = Str::random(40);

        $user = User::create($data);

        // Mettre à jour le statut du bien
        $bien = Bien::find($request->bien_id);
        if ($bien) {
            $bien->update(['statut' => 'loue']);
        }

        // Notify by SMS
        try {
            $yellika = new \App\Services\YellikaService();
            $link = route('locataire.onboarding', ['token' => $user->configuration_token]);
            $smsMsg = "Bienvenue chez ImmoSeul ! Utilisez le code {$user->configuration_code} pour activer votre espace ici : {$link}";
            $yellika->send($user->contact, $smsMsg);
        } catch (\Exception $e) {}

        // Notify by Email
        if ($user->email) {
            try {
                Mail::to($user->email)->send(new LocataireOnboardingMail($user));
            } catch (\Exception $e) {}
        }

        return redirect()->route('agent.locataires.index')->with('success', 'Locataire ajouté avec succès. Code d\'onboarding : ' . $user->configuration_code);
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
