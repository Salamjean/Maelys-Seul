@extends('agent.layouts.app')

@section('title', 'Visites effectuées')
@section('page-title', 'Historique des visites effectuées')

@section('content')
<div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div>
            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Visites <span class="text-green-600">confirmées</span></h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Historique des visites terminées</p>
        </div>
        <span class="px-6 py-2.5 bg-green-600 text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-green-500/20 italic">
            {{ $visites->total() }} Visite(s) effectuée(s)
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-6 text-center">Client</th>
                    <th class="px-8 py-6 text-center">Bien</th>
                    <th class="px-8 py-6 text-center">Date & Heure</th>
                    <th class="px-8 py-6 text-center">Statut</th>
                    <th class="px-8 py-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($visites as $v)
                    <tr class="hover:bg-green-50/30 transition-all group">
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-600 font-black shrink-0 border border-green-500/5">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tighter italic">{{ $v->nom }} {{ $v->prenom }}</p>
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $v->telephone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="inline-block">
                                <p class="text-sm font-black text-primary uppercase tracking-tighter italic">{{ $v->bien->reference }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase italic tracking-widest mt-0.5">{{ $v->bien->commune }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex flex-col gap-1">
                                <span class="text-[10px] font-black text-gray-700 bg-gray-100 px-3 py-1.5 rounded-xl uppercase tracking-tighter">
                                    Le {{ \Carbon\Carbon::parse($v->date_visite)->translatedFormat('d F Y') }}
                                </span>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">
                                    à {{ $v->heure_visite }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-full tracking-widest italic">
                                <i class="fa-solid fa-circle-check text-[8px]"></i>
                                Effectuée
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('agent.biens.show', $v->bien->id) }}" class="w-10 h-10 bg-gray-100 text-gray-400 rounded-xl hover:bg-primary hover:text-white transition-all shadow-lg shadow-blue-900/5 flex items-center justify-center" title="Voir le bien">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-gray-200">
                                <i class="fa-solid fa-history text-gray-200 text-4xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 uppercase tracking-widest italic">Aucun historique de visite disponible.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($visites->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">
            {{ $visites->links() }}
        </div>
    @endif
</div>
@endsection
