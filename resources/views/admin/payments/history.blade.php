@extends('admin.layouts.app')

@section('title', 'Historique des Paiements')
@section('page-title', 'Historique des Transactions')

@section('content')
<div class="" x-data="{ 
    activeTab: localStorage.getItem('activePaymentTabAdmin') || 'mobile',
    switchTab(newTab) {
        this.activeTab = newTab;
        localStorage.setItem('activePaymentTabAdmin', newTab);
    }
}">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Journal des <span class="text-secondary">Paiements</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[2px] mt-1">Consultez l'historique complet des transactions traitées</p>
        </div>

        {{-- Barre de Recherche --}}
        <form action="{{ route('admin.payments.history') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-96 group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, référence, montant..." 
                       class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-14 pl-14 pr-12 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm group-hover:shadow-md">
                
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </div>

                @if(request('search'))
                    <a href="{{ route('admin.payments.history') }}" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-circle-xmark text-lg"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="h-14 px-8 bg-primary text-white rounded-2xl font-black uppercase text-[11px] tracking-[2px] hover:bg-secondary transition-all shadow-lg shadow-blue-900/20 hover:shadow-orange-500/20 whitespace-nowrap">
                Rechercher
            </button>
        </form>
    </div>

    {{-- Toggle Tabs --}}
    <div class="flex p-1 bg-white border border-gray-100 rounded-2xl mb-8 w-fit shadow-sm">
        <button @click="switchTab('mobile')" 
                :class="activeTab === 'mobile' ? 'bg-primary text-white shadow-lg' : 'text-gray-400 hover:text-primary'"
                class="px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
            <i class="fa-solid fa-mobile-screen-button"></i>
            Paiements Mobiles
        </button>
        <button @click="switchTab('bank')" 
                :class="activeTab === 'bank' ? 'bg-primary text-white shadow-lg' : 'text-gray-400 hover:text-primary'"
                class="px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
            <i class="fa-solid fa-building-columns"></i>
            Virements Bancaires
        </button>
        <button @click="switchTab('cash')" 
                :class="activeTab === 'cash' ? 'bg-primary text-white shadow-lg' : 'text-gray-400 hover:text-primary'"
                class="px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
            <i class="fa-solid fa-money-bill-wave"></i>
            Encaissements Espèces
        </button>
    </div>

    {{-- SECTION MOBILE --}}
    <div x-show="activeTab === 'mobile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-6 text-center">Réf & Locataire</th>
                            <th class="px-8 py-6 text-center">Réseau</th>
                            <th class="px-8 py-6 text-center">Période</th>
                            <th class="px-8 py-6 text-center">Montant</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                            <th class="px-8 py-6 text-center">Traité par</th>
                            <th class="px-8 py-6 text-center">Date Traitement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($mobilePayments as $payment)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-primary tracking-tight">{{ $payment->reference }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $payment->user->name }} {{ $payment->user->prenoms }}</span>
                                    <span class="text-[10px] text-secondary font-black uppercase tracking-tighter">{{ $payment->user->bien->reference ?? 'Logement' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex flex-col items-center gap-1">
                                    <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center border border-orange-100">
                                        <img src="{{ asset('assets/images/' . ($payment->mobile_network ?? 'wave') . '.png') }}" class="w-5 h-5 object-contain">
                                    </div>
                                    <span class="text-[8px] font-black uppercase text-orange-400">{{ $payment->mobile_network ?? 'Wave' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-xs font-black text-gray-600 italic uppercase">{{ $payment->periode_couverte }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-md font-black text-primary">{{ number_format($payment->amount, 0, ',', ' ') }}</span>
                                <span class="text-[9px] font-black text-secondary uppercase ml-0.5 italic">CFA</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($payment->status === 'completed')
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Validé</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[9px] font-black uppercase tracking-widest">Rejeté</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-tighter">{{ $payment->agent->name ?? 'Système' }}</span>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase italic">{{ $payment->agent ? 'Agent' : 'Automatique' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col text-xs font-bold text-gray-500 uppercase italic">
                                    <span>{{ $payment->updated_at->locale('fr')->translatedFormat('d F Y') }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium not-italic">{{ $payment->updated_at->format('H:i') }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center text-gray-400 italic text-sm font-bold">Aucun paiement mobile dans l'historique.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($mobilePayments->hasPages())
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
                    {{ $mobilePayments->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION VIREMENT --}}
    <div x-show="activeTab === 'bank'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-6 text-center">Réf & Locataire</th>
                            <th class="px-8 py-6 text-center">Période</th>
                            <th class="px-8 py-6 text-center">Montant</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                            <th class="px-8 py-6 text-center">Traité par</th>
                            <th class="px-8 py-6 text-center">Date Traitement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($bankPayments as $payment)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-primary tracking-tight">{{ $payment->reference }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $payment->user->name }} {{ $payment->user->prenoms }}</span>
                                    <span class="text-[10px] text-secondary font-black uppercase tracking-tighter">{{ $payment->user->bien->reference ?? 'Logement' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-xs font-black text-gray-600 italic uppercase">{{ $payment->periode_couverte }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-md font-black text-primary">{{ number_format($payment->amount, 0, ',', ' ') }}</span>
                                <span class="text-[9px] font-black text-secondary uppercase ml-0.5 italic">CFA</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($payment->status === 'completed')
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Validé</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[9px] font-black uppercase tracking-widest">Rejeté</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-tighter">{{ $payment->agent->name ?? 'Système' }}</span>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase italic">{{ $payment->agent ? 'Agent' : 'Automatique' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col text-xs font-bold text-gray-500 uppercase italic">
                                    <span>{{ $payment->updated_at->locale('fr')->translatedFormat('d F Y') }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium not-italic">{{ $payment->updated_at->format('H:i') }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center text-gray-400 italic text-sm font-bold">Aucun virement bancaire dans l'historique.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bankPayments->hasPages())
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
                    {{ $bankPayments->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION ESPECES --}}
    <div x-show="activeTab === 'cash'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-6 text-center">Réf & Locataire</th>
                            <th class="px-8 py-6 text-center">Période</th>
                            <th class="px-8 py-6 text-center">Montant</th>
                            <th class="px-8 py-6 text-center">Traité par</th>
                            <th class="px-8 py-6 text-center">Date Encaissement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($cashPayments as $payment)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-primary tracking-tight">{{ $payment->reference }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $payment->user->name }} {{ $payment->user->prenoms }}</span>
                                    <span class="text-[10px] text-secondary font-black uppercase tracking-tighter">{{ $payment->user->bien->reference ?? 'Logement' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-xs font-black text-gray-600 italic uppercase">{{ $payment->periode_couverte }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-md font-black text-primary">{{ number_format($payment->amount, 0, ',', ' ') }}</span>
                                <span class="text-[9px] font-black text-secondary uppercase ml-0.5 italic">CFA</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-tighter">{{ $payment->agent->name ?? 'Système' }}</span>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase italic">{{ $payment->agent ? 'Agent' : 'Automatique' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col text-xs font-bold text-gray-500 uppercase italic">
                                    <span>{{ $payment->updated_at->locale('fr')->translatedFormat('d F Y') }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium not-italic">{{ $payment->updated_at->format('H:i') }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-gray-400 italic text-sm font-bold">Aucun encaissement en espèces dans l'historique.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cashPayments->hasPages())
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
                    {{ $cashPayments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
