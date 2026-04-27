@extends('locataire.layouts.app')

@section('title', 'Demandes SAV')
@section('page-title', 'Service Après-Vente')
@section('page-subtitle', 'Signalez un problème ou posez une question à l\'agence')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-black text-primary italic lowercase"><span class="uppercase">M</span>es demandes</h2>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Historique de vos échanges avec le support</p>
    </div>
    <a href="{{ route('locataire.support.create') }}" class="bg-primary text-white px-6 py-3 rounded-2xl font-black italic text-sm hover:bg-primary-dark transition-all shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> NOUVELLE DEMANDE
    </a>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-8 py-5 text-center">Sujet & Catégorie</th>
                    <th class="px-8 py-5 text-center">Priorité</th>
                    <th class="px-8 py-5 text-center">Statut</th>
                    <th class="px-8 py-5 text-center">Date</th>
                    <th class="px-8 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50/30 transition-colors group">
                    <td class="px-8 py-6 text-center">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-800">{{ $req->subject }}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-0.5">
                                <i class="fa-solid fa-tag text-secondary mr-1"></i> {{ ucfirst($req->category) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        @php
                            $priorityColors = [
                                'normal' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'urgent' => 'bg-orange-50 text-orange-600 border-orange-100',
                                'critique' => 'bg-red-50 text-red-600 border-red-100',
                            ];
                            $color = $priorityColors[$req->priority] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                        @endphp
                        <span class="px-3 py-1 {{ $color }} rounded-full text-[9px] font-black uppercase tracking-widest border">
                            {{ $req->priority }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center">
                            @if($req->status !== 'pending')
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-100 flex items-center gap-1">
                                    <i class="fa-solid fa-check text-[8px]"></i> Répondu
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded-full text-[9px] font-black uppercase tracking-widest border border-gray-100 flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[8px]"></i> En attente
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $req->created_at->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center">
                            <a href="{{ route('locataire.support.show', $req->id) }}" class="p-3 bg-gray-50 text-gray-400 rounded-xl hover:bg-primary hover:text-white transition shadow-sm group-hover:scale-105">
                                <i class="fa-solid fa-eye text-lg"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-headset text-gray-200 text-3xl"></i>
                        </div>
                        <p class="text-sm font-black text-gray-400 italic">Vous n'avez pas encore envoyé de demande SAV.</p>
                        <a href="{{ route('locataire.support.create') }}" class="text-primary font-black text-xs uppercase mt-4 block underline">Envoyer ma première demande</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
        <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
