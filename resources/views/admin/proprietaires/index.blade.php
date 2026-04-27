@extends('admin.layouts.app')

@section('title', 'Liste des propriétaires')
@section('page-title', 'Gestion des Partenaires')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Propriétaires Partenaires</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Gérez vos relations avec les bailleurs</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            {{-- Barre de Recherche --}}
            <form action="{{ route('admin.proprietaires.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, contact, email..." 
                           class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-12 pl-12 pr-10 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm group-hover:shadow-md">
                    
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    @if(request('search'))
                        <a href="{{ route('admin.proprietaires.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="h-12 px-6 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 hover:shadow-secondary/20 whitespace-nowrap">
                    Rechercher
                </button>
            </form>

            <a href="{{ route('admin.proprietaires.create') }}" class="px-6 py-3 bg-secondary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition shadow-lg shadow-secondary/10 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Nouveau Propriétaire
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-5 text-center">Propriétaire</th>
                    <th class="px-8 py-5 text-center">Contact & Job</th>
                    <th class="px-8 py-5 text-center">Résidence</th>
                    <th class="px-8 py-5 text-center">Pièce d'Identité</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($proprietaires as $p)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center">
                                <div class="flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary font-black shadow-sm">
                                        {{ substr($p->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800">{{ $p->nom }} {{ $p->prenoms }}</p>
                                        <p class="text-[11px] text-gray-400 font-bold">{{ $p->email ?? 'Sans email' }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-sm font-black text-gray-700">{{ $p->contact }}</p>
                            <p class="text-[11px] text-primary font-black uppercase tracking-wider mt-1">{{ $p->profession ?? 'Non renseigné' }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-xs text-gray-500 font-bold italic">{{ $p->lieu_residence ?? 'N/A' }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col gap-2 items-center">
                                @if($p->piece_identite_recto)
                                    <a href="{{ Storage::url($p->piece_identite_recto) }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-secondary border border-gray-100 rounded-lg text-[9px] font-black uppercase tracking-wider hover:bg-secondary hover:text-white transition w-24 justify-center">
                                        <i class="fa-solid fa-address-card"></i> Recto
                                    </a>
                                @endif
                                @if($p->piece_identite_verso)
                                    <a href="{{ Storage::url($p->piece_identite_verso) }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-primary border border-gray-100 rounded-lg text-[9px] font-black uppercase tracking-wider hover:bg-primary hover:text-white transition w-24 justify-center">
                                        <i class="fa-solid fa-id-card"></i> Verso
                                    </a>
                                @endif
                                @if(!$p->piece_identite_recto && !$p->piece_identite_verso)
                                    <span class="text-[10px] text-gray-300 italic">Aucun document</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.proprietaires.edit', $p->id) }}" class="p-2 bg-gray-100 text-gray-400 rounded-xl hover:bg-primary hover:text-white transition shadow-sm" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.proprietaires.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Supprimer ce propriétaire ?')">
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
                                <i class="fa-solid fa-handshake-slash text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 italic">Aucun propriétaire enregistré pour le moment.</p>
                            <a href="{{ route('admin.proprietaires.create') }}" class="inline-block mt-4 text-xs font-black text-secondary uppercase hover:underline">Ajouter le premier propriétaire</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proprietaires->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30 font-bold italic text-xs">
            {{ $proprietaires->links() }}
        </div>
    @endif
</div>
@endsection
