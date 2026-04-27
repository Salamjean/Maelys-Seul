@extends('comptable.layouts.app')

@section('title', 'Tableau de bord - Comptabilité')

@section('content')
<div class="space-y-8 pb-12">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-primary italic uppercase tracking-tighter">Tableau de <span class="text-secondary">Bord</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[2px] mt-1">Aperçu global de l'activité financière</p>
        </div>
        <div class="flex items-center gap-4 bg-white p-2 rounded-2xl shadow-sm border border-gray-50">
            <div class="px-4 py-2 bg-primary/5 rounded-xl">
                <span class="text-[10px] font-black text-primary uppercase tracking-widest">{{ now()->locale('fr')->translatedFormat('d F Y') }}</span>
            </div>
            <div class="w-10 h-10 bg-secondary text-white rounded-xl flex items-center justify-center shadow-lg shadow-secondary/20">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1: Encaissé ce mois --}}
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-500 shadow-inner">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Ce mois</div>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] mb-1">Total Encaissé</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-black text-primary tracking-tighter">{{ number_format($totalEncaisseMois, 0, ',', ' ') }}</h3>
                <span class="text-[10px] font-black text-secondary italic uppercase">CFA</span>
            </div>
        </div>

        {{-- Card 2: En attente validation --}}
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-500 shadow-inner">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-[9px] font-black uppercase tracking-widest">{{ $countAttenteValidation }} dossiers</div>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] mb-1">À Valider</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-black text-primary tracking-tighter">{{ number_format($totalAttenteValidation, 0, ',', ' ') }}</h3>
                <span class="text-[10px] font-black text-secondary italic uppercase">CFA</span>
            </div>
        </div>

        {{-- Card 3: Versements Agents --}}
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-500 shadow-inner">
                    <i class="fa-solid fa-vault"></i>
                </div>
                <div class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest">Remises</div>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] mb-1">Versements Reçus</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-black text-primary tracking-tighter">{{ number_format($totalVersementsRecus, 0, ',', ' ') }}</h3>
                <span class="text-[10px] font-black text-secondary italic uppercase">CFA</span>
            </div>
        </div>

        {{-- Card 4: Retards --}}
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-500 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[9px] font-black uppercase tracking-widest">Alerte</div>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] mb-1">Locataires en Retard</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-black text-primary tracking-tighter">{{ $countLateTenants }}</h3>
                <span class="text-[10px] font-black text-secondary italic uppercase ml-1">Relances</span>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Transactions --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-black text-primary italic uppercase tracking-tighter">Encaissements <span class="text-secondary">Récents</span></h3>
                <a href="{{ route('comptable.payments.history') }}" class="text-[10px] font-black text-primary hover:text-secondary uppercase tracking-[2px] transition-colors border-b-2 border-primary/10 hover:border-secondary pb-1">Voir tout l'historique</a>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-8 py-6">Locataire</th>
                                <th class="px-8 py-6 text-center">Méthode</th>
                                <th class="px-8 py-6 text-center">Montant</th>
                                <th class="px-8 py-6 text-center">Date</th>
                                <th class="px-8 py-6 text-right">Justificatif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50/30 transition-all group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary font-black text-xs">
                                            {{ substr($transaction->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-primary uppercase tracking-tighter line-clamp-1">{{ $transaction->user->name }} {{ $transaction->user->prenoms }}</span>
                                            <span class="text-[9px] font-bold text-secondary uppercase">{{ $transaction->user->bien->reference ?? 'Logement' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($transaction->payment_method === 'bank')
                                        <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center mx-auto" title="Virement">
                                            <i class="fa-solid fa-building-columns text-xs"></i>
                                        </div>
                                    @elseif($transaction->payment_method === 'especes')
                                        <div class="w-8 h-8 bg-green-50 text-green-500 rounded-lg flex items-center justify-center mx-auto" title="Espèces">
                                            <i class="fa-solid fa-money-bill-1-wave text-xs"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center mx-auto" title="Mobile Money">
                                            <i class="fa-solid fa-mobile-screen-button text-xs"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="text-sm font-black text-primary tracking-tighter">{{ number_format($transaction->amount, 0, ',', ' ') }}</span>
                                    <span class="text-[8px] font-black text-secondary italic">CFA</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex flex-col text-[10px] font-bold text-gray-400 uppercase">
                                        <span>{{ $transaction->updated_at->format('d/m/y') }}</span>
                                        <span class="text-[8px] italic opacity-60">{{ $transaction->updated_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($transaction->payment_proof)
                                        <a href="{{ Storage::url($transaction->payment_proof) }}" target="_blank" class="w-8 h-8 bg-gray-50 text-gray-400 hover:bg-primary hover:text-white rounded-lg flex items-center justify-center transition-all ml-auto">
                                            <i class="fa-solid fa-file-invoice text-xs"></i>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-gray-200 font-bold italic">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-gray-300 italic text-sm">Aucune transaction récente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Actions & Reminders --}}
        <div class="space-y-6">
            <h3 class="text-xl font-black text-primary italic uppercase tracking-tighter">Actions <span class="text-secondary">Rapides</span></h3>
            
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-4">
                <a href="{{ route('comptable.versements.index') }}" class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-primary hover:text-white transition-all group">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest">Nouveau Versement</p>
                        <p class="text-[10px] opacity-60">Encaisser fonds agent</p>
                    </div>
                </a>

                <a href="{{ route('comptable.rappels.index') }}" class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-secondary hover:text-white transition-all group">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest">Relancer Retards</p>
                        <p class="text-[10px] opacity-60">Envoyer SMS/Emails</p>
                    </div>
                </a>

                <a href="{{ route('comptable.payments.pending') }}" class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-blue-600 hover:text-white transition-all group">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest">Valider Mobiles</p>
                        <p class="text-[10px] opacity-60">Vérifier justificatifs</p>
                    </div>
                </a>
            </div>

            {{-- Summary Pie/Graph Placeholder or Info Card --}}
            <div class="bg-gradient-to-br from-primary to-blue-900 p-8 rounded-[2.5rem] text-white relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[3px] opacity-60 mb-2">État des Rappels</p>
                    <h4 class="text-3xl font-black italic tracking-tighter mb-4">{{ $countLateTenants }} <span class="text-xs font-bold uppercase not-italic opacity-60 tracking-normal">Dossiers à suivre</span></h4>
                    <p class="text-xs text-white/70 font-medium leading-relaxed">Le suivi rigoureux des relances permet de maintenir un taux de recouvrement optimal.</p>
                </div>
                <i class="fa-solid fa-chart-pie absolute -right-4 -bottom-4 text-white/5 text-9xl"></i>
            </div>
        </div>
    </div>
</div>
@endsection
