@extends('admin.layouts.app')

@section('title', 'SAV & Support')
@section('page-title', 'Service Après-Vente')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Messages Locataires</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Gérez les demandes de maintenance et d'assistance</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            {{-- Barre de Recherche --}}
            <form action="{{ route('admin.support.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Sujet, message, locataire..." 
                           class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-12 pl-12 pr-10 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm group-hover:shadow-md">
                    
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    @if(request('search'))
                        <a href="{{ route('admin.support.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="h-12 px-6 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 hover:shadow-secondary/20 whitespace-nowrap">
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    {{-- Filtres de Statut --}}
    <div class="px-8 py-4 bg-gray-50/30 border-b border-gray-50 flex gap-4 overflow-x-auto">
        <a href="{{ route('admin.support.index') }}" 
           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request('status') ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-gray-50' }}">
            Tous les messages
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'pending' ? 'bg-orange-500 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-gray-50' }}">
            En attente
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'answered']) }}" 
           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'answered' ? 'bg-indigo-500 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-gray-50' }}">
            Réponses envoyées
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'in_progress']) }}" 
           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'in_progress' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-gray-50' }}">
            En cours
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'completed' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-gray-50' }}">
            Terminés
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-5 text-center">Locataire & Bien</th>
                    <th class="px-8 py-5 text-center">Sujet & Message</th>
                    <th class="px-8 py-5 text-center">Priorité</th>
                    <th class="px-8 py-5 text-center">Statut</th>
                    <th class="px-8 py-5 text-center">Date</th>
                    <th class="px-8 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6" style="display:flex; justify-content:center; align-items:center;">
                            <div class="flex items-center gap-4" style="text-align:center">
                                @if($req->user)
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black shadow-sm">
                                        {{ substr($req->user->name, 0, 1) }}
                                    </div>
                                    <div style="text-align:center">
                                        <p class="text-sm font-black text-gray-800 leading-tight">{{ $req->user->name }} {{ $req->user->prenoms }}</p>
                                        <p class="text-[10px] text-secondary font-black uppercase tracking-tighter">{{ $req->user->bien->reference ?? 'Logement' }}</p>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary font-black shadow-sm">
                                        {{ substr($req->guest_name, 0, 1) }}
                                    </div>
                                    <div style="text-align:center">
                                        <p class="text-sm font-black text-gray-800 leading-tight">{{ $req->guest_name }}</p>
                                        <p class="text-[10px] text-secondary font-black uppercase tracking-tighter">Visiteur (Contact)</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 max-w-md text-center">
                            <p class="text-sm font-black text-primary italic leading-tight">{{ $req->subject }}</p>
                            <p class="text-xs text-gray-400 line-clamp-1 mt-1">{{ $req->message }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $prioColors = [
                                    'low' => 'bg-gray-100 text-gray-600',
                                    'medium' => 'bg-blue-100 text-blue-600',
                                    'high' => 'bg-orange-100 text-orange-600',
                                    'critical' => 'bg-red-100 text-red-600'
                                ];
                                $prioLabels = [
                                    'low' => 'Basse',
                                    'medium' => 'Moyenne',
                                    'high' => 'Haute',
                                    'critical' => 'Urgent'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $prioColors[$req->priority] ?? 'bg-gray-100' }}">
                                {{ $prioLabels[$req->priority] ?? $req->priority }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($req->user)
                                @if($req->status === 'pending')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-[9px] font-black uppercase tracking-widest">En attente</span>
                                @elseif($req->status === 'answered')
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-[9px] font-black uppercase tracking-widest">Réponse envoyée</span>
                                @elseif($req->status === 'in_progress')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest">En cours</span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Terminé</span>
                                @endif
                            @else
                                <span class="text-gray-300 text-[10px] font-bold italic">N/A</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-xs font-black text-gray-500 uppercase italic">{{ $req->created_at->locale('fr')->translatedFormat('d F Y') }}</p>
                            <p class="text-[10px] text-gray-300">{{ $req->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($req->user)
                                <a href="{{ route('admin.support.show', $req->id) }}" class="p-3 bg-gray-50 text-primary rounded-2xl border border-gray-100 hover:bg-primary hover:text-white transition shadow-sm inline-flex items-center justify-center">
                                    <i class="fa-solid fa-reply"></i>
                                </a>
                            @else
                                <a href="{{ route('admin.support.show', $req->id) }}" class="p-3 bg-gray-50 text-gray-400 rounded-2xl border border-gray-100 hover:bg-gray-200 transition shadow-sm inline-flex items-center justify-center" title="Voir le message">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-comment-slash text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 italic">Aucun message locataire trouvé.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30 font-bold italic text-xs">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
