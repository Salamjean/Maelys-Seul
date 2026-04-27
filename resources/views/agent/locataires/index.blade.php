@extends('agent.layouts.app')

@section('title', 'Liste des locataires')
@section('page-title', 'Gestion des Locataires')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Locataires Actifs</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Gérez vos contrats et documents locataires</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            {{-- Barre de Recherche --}}
            <form action="{{ route('agent.locataires.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, contact, profession..." 
                           class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-12 pl-12 pr-10 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm group-hover:shadow-md">
                    
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    @if(request('search'))
                        <a href="{{ route('agent.locataires.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="h-12 px-6 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 hover:shadow-secondary/20 whitespace-nowrap">
                    Rechercher
                </button>
            </form>

            <a href="{{ route('agent.locataires.create') }}" class="px-6 py-3 bg-secondary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition shadow-lg shadow-secondary/10 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Nouveau Locataire
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-5 text-center">Locataire</th>
                    <th class="px-8 py-5 text-center">Contact & Job</th>
                    <th class="px-8 py-5 text-center">Bien loué</th>
                    <th class="px-8 py-5 text-center">Documents</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($locataires as $l)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center">
                                <div class="flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black shadow-sm">
                                        {{ substr($l->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800 uppercase italic tracking-tighter">{{ $l->name }} {{ $l->prenoms }}</p>
                                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">{{ $l->email ?? 'Sans email' }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-sm font-black text-gray-700 italic uppercase tracking-tighter">{{ $l->contact }}</p>
                            <p class="text-[11px] text-secondary font-black uppercase tracking-wider mt-1 italic">{{ $l->profession }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($l->bien)
                                <a href="{{ route('agent.biens.show', $l->bien->id) }}" class="group">
                                    <p class="text-sm font-black text-primary group-hover:text-secondary transition italic uppercase tracking-tighter">{{ $l->bien->reference }}</p>
                                    <p class="text-[11px] text-gray-400 font-bold italic uppercase tracking-widest">{{ $l->bien->commune }}</p>
                                </a>
                            @else
                                <span class="text-xs text-gray-300 italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center">
                                @php
                                    $docs = [
                                        ['label' => 'Pièce d\'identité', 'path' => $l->piece_identite, 'icon' => 'fa-id-card'],
                                        ['label' => 'Contrat de bail', 'path' => $l->contrat_bail, 'icon' => 'fa-file-contract'],
                                        ['label' => 'Attestation de travail', 'path' => $l->attestation_travail, 'icon' => 'fa-briefcase'],
                                        ['label' => 'Bulletin de salaire', 'path' => $l->bulletin_salaire, 'icon' => 'fa-file-invoice-dollar'],
                                        ['label' => 'Document Extra 1', 'path' => $l->doc_extra_1, 'icon' => 'fa-file-medical'],
                                        ['label' => 'Document Extra 2', 'path' => $l->doc_extra_2, 'icon' => 'fa-file-medical'],
                                        ['label' => 'Document Extra 3', 'path' => $l->doc_extra_3, 'icon' => 'fa-file-medical'],
                                    ];
                                    $availableDocs = array_filter($docs, fn($d) => !empty($d['path']));
                                @endphp
                                
                                <button onclick="showDocuments('{{ $l->name }} {{ $l->prenoms }}', {{ json_encode($availableDocs) }})" 
                                        class="px-4 py-2 bg-gray-50 text-primary border border-gray-100 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-primary hover:text-white transition flex items-center gap-2">
                                    <i class="fa-solid fa-folder-open"></i>
                                    Documents ({{ count($availableDocs) }})
                                </button>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('agent.locataires.edit', $l->id) }}" class="p-2 bg-gray-100 text-gray-400 rounded-xl hover:bg-primary hover:text-white transition shadow-sm" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" 
                                        onclick="initiateCashPayment({{ $l->id }}, '{{ $l->name }}')"
                                        class="p-2 bg-green-50/50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm" 
                                        title="Initier Paiement">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </button>
                                <button type="button" 
                                        onclick="resumePaymentValidation()"
                                        class="p-2 bg-orange-50/50 text-orange-500 rounded-xl hover:bg-orange-500 hover:text-white transition shadow-sm" 
                                        title="Saisir Code OTP">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                                <form action="{{ route('agent.locataires.move_out', $l->id) }}" method="POST" onsubmit="return confirm('Confirmer le déménagement de ce locataire ? Le bien sera libéré.')">
                                    @csrf
                                    <button type="submit" class="p-2 bg-indigo-50/50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Déménagement">
                                        <i class="fa-solid fa-truck-ramp-box"></i>
                                    </button>
                                </form>
                                <form action="{{ route('agent.locataires.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce locataire ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50/50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm" title="Supprimer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-users-slash text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 italic">Aucun locataire enregistré pour le moment.</p>
                            <a href="{{ route('agent.locataires.create') }}" class="inline-block mt-4 text-xs font-black text-secondary uppercase hover:underline">Ajouter le premier locataire</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($locataires->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30 font-bold">
            {{ $locataires->links() }}
        </div>
    @endif
</div>
@endsection
