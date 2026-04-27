@extends('admin.layouts.app')

@section('title', 'Anciens Locataires')
@section('page-title', 'Archives Déménagements')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-gray-400 italic">Déménagements Archivés</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Historique global des départs</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <form action="{{ route('admin.locataires.moved_out') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher dans les archives..." 
                           class="w-full bg-white border-2 border-gray-100 focus:border-gray-300 h-12 pl-12 pr-10 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
                <button type="submit" class="h-12 px-6 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-200 transition-all whitespace-nowrap">
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-5 text-center">Locataire</th>
                    <th class="px-8 py-5 text-center">Contact</th>
                    <th class="px-8 py-5 text-center">Date de départ</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($locataires as $l)
                    <tr class="hover:bg-gray-50/30 transition-colors opacity-75 hover:opacity-100">
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center">
                                <div class="flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 font-black">
                                        {{ substr($l->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-600 uppercase italic tracking-tighter">{{ $l->name }} {{ $l->prenoms }}</p>
                                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">{{ $l->email ?? 'Sans email' }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-sm font-black text-gray-500 italic uppercase tracking-tighter">{{ $l->contact }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl">
                                <i class="fa-solid fa-calendar-check text-[10px]"></i>
                                <span class="text-xs font-black uppercase italic">{{ $l->moved_out_at ? $l->moved_out_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.locataires.reassign', $l->id) }}" 
                                   class="px-4 py-2 bg-secondary text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:opacity-90 transition flex items-center gap-2 shadow-lg shadow-secondary/20">
                                    <i class="fa-solid fa-house-chimney-medical"></i>
                                    Ré-attribuer
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-box-archive text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 italic">Aucun déménagement archivé.</p>
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
