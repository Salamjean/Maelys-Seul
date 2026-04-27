@extends('admin.layouts.app')

@section('title', 'Visites effectuées')
@section('page-title', 'Historique des visites effectuées')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div>
            <h2 class="text-xl font-black text-primary italic">Visites confirmées</h2>
            <p class="text-xs text-gray-400 font-semibold mt-1">Historique des visites terminées</p>
        </div>
        <span class="px-4 py-2 bg-green-500/10 text-green-600 text-xs font-black rounded-xl">
            {{ $visites->total() }} Visite(s) effectuée(s)
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-6 py-4 text-center">Client</th>
                    <th class="px-6 py-4 text-center">Bien</th>
                    <th class="px-6 py-4 text-center">Date & Heure</th>
                    <th class="px-6 py-4 text-center">Statut</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($visites as $v)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-600 font-black shrink-0">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-gray-800">{{ $v->nom }} {{ $v->prenom }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium">{{ $v->telephone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="inline-block">
                                <p class="text-sm font-bold text-primary">{{ $v->bien->reference }}</p>
                                <p class="text-[11px] text-gray-400 font-medium italic">{{ $v->bien->commune }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="inline-block">
                                <p class="text-xs font-bold text-gray-700">
                                    Le {{ \Carbon\Carbon::parse($v->date_visite)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">
                                    à {{ $v->heure_visite }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-lg">
                                <i class="fa-solid fa-circle-check text-[8px]"></i>
                                Effectuée
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.biens.show', $v->bien->id) }}" class="p-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-primary hover:text-white transition shadow-sm" title="Voir le bien">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-history text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-400 italic">Aucun historique de visite disponible.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($visites->hasPages())
        <div class="p-6 border-t border-gray-50 bg-gray-50/30">
            {{ $visites->links() }}
        </div>
    @endif
</div>
@endsection
