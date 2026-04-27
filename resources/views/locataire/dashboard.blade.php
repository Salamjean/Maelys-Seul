@extends('locataire.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Bonjour, ' . Auth::user()->name)
@section('page-subtitle', 'Bienvenue dans votre espace locataire personnel')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- Ma Situation Financière --}}
    <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-[0.03] rounded-bl-[5rem] group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary mb-6">
                <i class="fa-solid fa-wallet text-xl"></i>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Loyer de {{ ucfirst($stats['current_month']) }}</p>
            <h3 class="text-2xl font-black text-primary">
                @if($stats['is_current_month_paid'])
                    Régularisé
                @elseif($stats['is_current_month_pending'])
                    Traitement en cours
                @else
                    Non soldé
                @endif
            </h3>
            @if($stats['is_current_month_paid'])
                <p class="text-[11px] text-green-500 font-bold mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check"></i> Statut : Payé
                </p>
            @elseif($stats['is_current_month_pending'])
                <p class="text-[11px] text-orange-500 font-bold mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-spinner fa-spin"></i> Statut : En attente de validation
                </p>
            @else
                <p class="text-[11px] text-red-500 font-bold mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation"></i> Statut : Non payé
                </p>
            @endif
        </div>
    </div>

    {{-- Mon Logement --}}
    <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary opacity-[0.03] rounded-bl-[5rem] group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6">
                <i class="fa-solid fa-house-user text-xl"></i>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Référence du bien</p>
            <h3 class="text-2xl font-black text-primary">{{ $user->bien->reference ?? 'Logement Expert' }}</h3>
            <p class="text-[11px] text-gray-400 font-bold mt-2 italic flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-secondary"></i> {{ $user->bien ? $user->bien->commune : 'En cours d\'affectation' }}
            </p>
        </div>
    </div>

    {{-- SAV & Support --}}
    <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500 opacity-[0.03] rounded-bl-[5rem] group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-6">
                <i class="fa-solid fa-headset text-xl"></i>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Assistance SAV</p>
            <h3 class="text-2xl font-black text-primary">{{ $stats['nb_messages_sav'] }} Demande(s)</h3>
            <p class="text-[11px] text-primary font-bold mt-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-secondary rounded-full {{ $stats['notifications_sav'] > 0 ? 'animate-ping' : 'opacity-20' }}"></span>
                {{ $stats['notifications_sav'] }} Réponse(s) non lue(s)
            </p>
        </div>
    </div>
</div>

<div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Historique des Quittances --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-black text-primary italic">Derniers Paiements</h2>
            <a href="{{ route('locataire.quittances') }}" class="text-[10px] font-black text-secondary uppercase tracking-widest hover:underline">Voir tout</a>
        </div>
        <div class="p-4">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentPayments as $payment)
                    <tr class="group hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fa-solid fa-credit-card text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</p>
                                    <p class="text-[10px] text-secondary font-black uppercase tracking-tighter italic">Loyer de {{ $payment->periode_couverte }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-[9px] font-black uppercase text-green-500 bg-green-50 px-2 py-1 rounded-lg border border-green-100">Validé</span>
                                <a href="{{ route('locataire.quittances.download', $payment->id) }}" class="text-[10px] font-black uppercase text-primary hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-file-pdf"></i> Quittance
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-4 py-10 text-center italic text-gray-300 text-sm">Aucun historique de paiement</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Support / Contact Rapide --}}
    <div class="bg-[#0a1931] rounded-[2.5rem] shadow-2xl shadow-blue-900/20 p-10 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-blue-900/10 pointer-events-none"></div>
        <div class="relative z-10">
            <div class="w-20 h-20 bg-secondary rounded-3xl flex items-center justify-center text-white mb-6 mx-auto shadow-xl shadow-orange-500/20">
                <i class="fa-solid fa-headset text-3xl"></i>
            </div>
            <h2 class="text-2xl font-black italic mb-2">Service Client</h2>
            <p class="text-white/50 text-sm font-medium max-w-xs mb-8 mx-auto">
                Un souci ou une question ? Notre équipe vous répond rapidement via votre espace dédié.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 w-full">
                <a href="{{ route('locataire.support.create') }}" class="flex-1 bg-secondary text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white hover:text-primary transition-all">
                    Signaler un SAV
                </a>
                <a href="{{ route('locataire.support.index') }}" class="flex-1 bg-white/5 border border-white/10 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white/10 transition-all">
                    Mes Messages
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
