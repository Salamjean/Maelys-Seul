@extends('locataire.layouts.app')

@section('title', 'Mes Quittances')
@section('page-title', 'Mes Quittances de Loyer')
@section('page-subtitle', 'Consultez et téléchargez vos reçus de paiement en temps réel')

@section('content')
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-gray-50/20">
        <div>
            <h2 class="text-xl font-black text-primary italic lowercase"><span class="uppercase">H</span>istorique des paiements</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[2px] mt-1">Retrouvez tous vos loyers encaissés par l'agence</p>
        </div>
        
        <form action="{{ route('locataire.quittances') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="Rechercher (Réf, Mois...)" 
                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:border-primary focus:ring-0 transition-all outline-none">
                @if($search)
                    <a href="{{ route('locataire.quittances') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="bg-primary text-white p-2.5 rounded-xl hover:bg-primary-dark transition-all shadow-sm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-8 py-5 text-center">Référence</th>
                    <th class="px-8 py-5 text-center">Période</th>
                    <th class="px-8 py-5 text-center">Statut</th>
                    <th class="px-8 py-5 text-center">Montant</th>
                    <th class="px-8 py-5 text-center">Date</th>
                    <th class="px-8 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($quittances as $q)
                <tr class="hover:bg-gray-50/30 transition-colors group">
                    <td class="px-8 py-6 text-center">
                        <span class="text-xs font-black text-primary bg-primary/5 px-3 py-1.5 rounded-lg border border-primary/10">
                            {{ $q->reference }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center" style="display: flex; justify-content: center;">
                        <div class="flex items-center gap-2 text-center">
                            <div class="w-2 h-2 rounded-full bg-secondary"></div>
                            <p class="text-sm font-black text-gray-800">
                                {{ str_replace(
                                    ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                    ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                                    $q->periode_couverte
                                ) }}
                            </p>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center text-center">
                            @if($q->status === 'completed')
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-100 flex items-center gap-1">
                                    <i class="fa-solid fa-check-double text-[8px]"></i> Validé
                                </span>
                            @else
                                <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-orange-100 flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[8px]"></i> En attente
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex flex-col items-center gap-1">
                            <p class="text-sm font-black text-gray-700">{{ number_format($q->amount, 0, ',', ' ') }} FCFA</p>
                            @if($q->payment_method === 'mobile' && $q->mobile_network)
                                <div class="flex items-center gap-1.5 bg-primary/5 px-2.5 py-1 rounded-lg border border-primary/10 mt-1">
                                    <img src="{{ asset('assets/images/' . $q->mobile_network . '.png') }}" class="h-4 w-4 object-contain rounded-sm shadow-sm">
                                    <span class="text-[9px] font-black text-primary uppercase tracking-tighter">{{ $q->mobile_network }}</span>
                                </div>
                            @elseif($q->payment_method === 'bank')
                                <div class="flex items-center gap-1.5 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 mt-1">
                                    <i class="fa-solid fa-building-columns text-[10px] text-blue-500"></i>
                                    <span class="text-[9px] font-black text-blue-500 uppercase tracking-tighter">Virement</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100 mt-1">
                                    <i class="fa-solid fa-money-bill-1 text-[10px] text-gray-400"></i>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Espèces</span>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $q->created_at->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center">
                            @if($q->status === 'completed')
                                <a href="{{ route('locataire.quittances.download', $q->id) }}" 
                                   class="p-3 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm group-hover:scale-105" 
                                   title="Télécharger PDF">
                                    <i class="fa-solid fa-file-pdf text-lg"></i>
                                </a>
                            @else
                                <button class="p-3 bg-gray-50 text-gray-200 rounded-xl cursor-not-allowed" title="Attente de validation agence">
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-file-invoice-dollar text-gray-200 text-3xl"></i>
                        </div>
                        <p class="text-sm font-black text-gray-400 italic">Aucune quittance officielle n'est encore disponible.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($quittances->hasPages())
        <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
            {{ $quittances->appends(['search' => $search])->links() }}
        </div>
    @endif
</div>
@endsection
