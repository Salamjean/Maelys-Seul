@extends('recouvrement.layouts.app')

@section('title', 'Tableau de Bord - Recouvrement')
@section('page-title', 'Tableau de Bord')

@section('content')
<div class="space-y-8">
    {{-- Welcome Header --}}
    <div class="bg-gradient-to-br from-primary to-blue-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-blue-900/20">
        <div class="relative z-10">
            <h2 class="text-3xl font-black italic uppercase tracking-tighter mb-2">Bon retour, {{ $admin->name }} !</h2>
            <p class="text-white/60 font-medium max-w-lg">Voici un aperçu de vos performances de recouvrement et des actions en attente pour aujourd'hui.</p>
            
            <div class="flex gap-4 mt-8">
                <a href="{{ route('recouvrement.tenants.late') }}" class="bg-secondary text-white px-8 py-3.5 rounded-2xl font-black uppercase text-[11px] tracking-widest hover:bg-white hover:text-secondary transition-all shadow-lg shadow-orange-500/20">
                    Traiter les retards
                </a>
                <a href="{{ route('recouvrement.versements.index') }}" class="bg-white/10 text-white border border-white/20 px-8 py-3.5 rounded-2xl font-black uppercase text-[11px] tracking-widest hover:bg-white/20 transition-all backdrop-blur-md">
                    Historique versements
                </a>
            </div>
        </div>
        <i class="fa-solid fa-gauge-high absolute -right-8 -bottom-8 text-white/5 text-[15rem] -rotate-12"></i>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Collecté --}}
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Collecté Terrain</span>
            </div>
            <p class="text-xl font-black text-primary italic leading-none">{{ number_format($totalCollected, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></p>
            <div class="h-1 bg-gray-50 rounded-full mt-4 overflow-hidden">
                <div class="h-full bg-primary w-2/3"></div>
            </div>
        </div>

        {{-- Versé --}}
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm group hover:border-green-500/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Versé Caisse</span>
            </div>
            <p class="text-xl font-black text-primary italic leading-none">{{ number_format($totalVersed, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></p>
            <div class="h-1 bg-gray-50 rounded-full mt-4 overflow-hidden">
                <div class="h-full bg-green-500 w-full"></div>
            </div>
        </div>

        {{-- Reste à Verser --}}
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm group hover:border-secondary/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 {{ $remainingToVerse > 0 ? 'bg-orange-50 text-secondary' : 'bg-blue-50 text-primary' }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Reste à verser</span>
            </div>
            <p class="text-xl font-black {{ $remainingToVerse > 0 ? 'text-secondary' : 'text-primary' }} italic leading-none">{{ number_format($remainingToVerse, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></p>
            <div class="h-1 bg-gray-50 rounded-full mt-4 overflow-hidden">
                <div class="h-full {{ $remainingToVerse > 0 ? 'bg-secondary animate-pulse' : 'bg-primary' }} w-1/3"></div>
            </div>
        </div>

        {{-- Retards --}}
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm group hover:border-red-500/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Locataires en retard</span>
            </div>
            <p class="text-xl font-black text-primary italic leading-none">{{ $lateCount }} <span class="text-[10px]">Dossiers</span></p>
            <div class="h-1 bg-gray-50 rounded-full mt-4 overflow-hidden">
                <div class="h-full bg-red-500 w-1/2"></div>
            </div>
        </div>
    </div>

    {{-- Bottom Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12">
        {{-- Recent Encaissements --}}
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-3">
                    <i class="fa-solid fa-money-bill-wave text-secondary"></i>
                    Derniers Encaissements
                </h3>
                <a href="{{ route('recouvrement.my_payments') }}" class="text-[10px] font-black text-secondary hover:underline uppercase tracking-widest">Voir tout</a>
            </div>
            <div class="flex-1">
                @forelse($recentEncaissements as $e)
                    <div class="p-6 border-b border-gray-50 hover:bg-gray-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary font-black text-xs">
                                {{ substr($e->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-black text-primary uppercase italic">{{ $e->user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $e->periode_couverte }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-secondary">{{ number_format($e->amount, 0, ',', ' ') }} <span class="text-[9px]">FCFA</span></p>
                            <p class="text-[9px] text-gray-300 font-black">{{ $e->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400">
                        <i class="fa-solid fa-receipt text-3xl mb-3 opacity-20"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">Aucun encaissement</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Versements --}}
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-3">
                    <i class="fa-solid fa-money-bill-transfer text-green-500"></i>
                    Derniers Versements
                </h3>
                <a href="{{ route('recouvrement.versements.index') }}" class="text-[10px] font-black text-secondary hover:underline uppercase tracking-widest">Voir tout</a>
            </div>
            <div class="flex-1">
                @forelse($recentVersements as $v)
                    <div class="p-6 border-b border-gray-50 hover:bg-gray-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-500 font-black text-xs">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-primary uppercase italic">{{ $v->reference }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Versé à {{ $v->comptable->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-primary">{{ number_format($v->amount, 0, ',', ' ') }} <span class="text-[9px]">FCFA</span></p>
                            <p class="text-[9px] text-gray-300 font-black">{{ $v->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400">
                        <i class="fa-solid fa-money-bill-transfer text-3xl mb-3 opacity-20"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">Aucun versement effectué</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
