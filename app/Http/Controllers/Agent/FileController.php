<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $agentId = auth()->guard('admin')->id();

        // 1. Documents des Locataires
        $query = User::where('role', 'locataire');
        try {
            $query->where('added_by', $agentId);
        } catch (\Exception $e) {}

        $locataires = $query->with('bien')->latest()->get();

        $files = [];
        foreach ($locataires as $locataire) {
            $docTypes = [
                'piece_identite' => 'Pièce d\'identité',
                'contrat_bail' => 'Contrat de bail',
                'attestation_travail' => 'Attestation de travail',
                'bulletin_salaire' => 'Bulletin de salaire',
                'doc_extra_1' => 'Document Extra 1',
                'doc_extra_2' => 'Document Extra 2',
                'doc_extra_3' => 'Document Extra 3',
            ];

            foreach ($docTypes as $field => $label) {
                if ($locataire->$field) {
                    $files[] = [
                        'id' => $locataire->id . '_' . $field,
                        'name' => $label . " - " . $locataire->name . " " . $locataire->prenoms,
                        'type' => $label,
                        'owner' => $locataire->name . " " . $locataire->prenoms,
                        'property' => $locataire->bien ? $locataire->bien->reference : 'N/A',
                        'path' => $locataire->$field,
                        'url' => Storage::url($locataire->$field),
                        'date' => $locataire->created_at->format('d/m/Y'),
                        'extension' => pathinfo($locataire->$field, PATHINFO_EXTENSION),
                        'is_general' => false,
                    ];
                }
            }
        }

        // 2. Documents Généraux de l'Agent
        $agentFiles = AgentFile::where('agent_id', $agentId)->latest()->get();
        foreach ($agentFiles as $af) {
            $files[] = [
                'id' => $af->id,
                'name' => $af->name,
                'type' => $af->type ?? 'Général',
                'owner' => 'Moi',
                'property' => 'N/A',
                'path' => $af->path,
                'url' => Storage::url($af->path),
                'date' => $af->created_at->format('d/m/Y'),
                'extension' => pathinfo($af->path, PATHINFO_EXTENSION),
                'is_general' => true,
            ];
        }

        // Trier par date décroissante (plus récents en premier)
        usort($files, function($a, $b) {
            return strtotime(str_replace('/', '-', $b['date'])) - strtotime(str_replace('/', '-', $a['date']));
        });

        return view('agent.files.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // Max 10MB
            'type' => 'nullable|string|max:100',
        ]);

        $path = $request->file('file')->store('agent/files', 'public');

        AgentFile::create([
            'name' => $request->name,
            'path' => $path,
            'type' => $request->type,
            'agent_id' => auth()->guard('admin')->id(),
        ]);

        return back()->with('success', 'Fichier ajouté avec succès.');
    }

    public function destroy(AgentFile $file)
    {
        if ($file->agent_id !== auth()->guard('admin')->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success', 'Fichier supprimé avec succès.');
    }
}
