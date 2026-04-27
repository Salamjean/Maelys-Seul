<aside class="fixed inset-y-0 left-0 w-72 bg-primary z-50 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl flex flex-col shrink-0 border-r border-white/5"
       :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
       style="background: linear-gradient(180deg, #02245b 0%, #01163a 100%);">
    <style>
        .custom-scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>

    {{-- Logo Section --}}
    <div class="h-24 flex items-center px-8 border-b border-white/5">
        <a href="{{ route('locataire.dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Logo" class="w-10 h-10 rounded-xl shadow-lg border-2 border-secondary/20">
            <div>
                <p class="text-lg font-black text-white leading-none italic uppercase tracking-tighter">Maelys-<span class="text-secondary">Imo</span></p>
                <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest mt-1">Espace Locataire</p>
            </div>
        </a>
    </div>

    {{-- User Profile Mini --}}
    <div class="p-6">
        <div class="bg-white/5 rounded-3xl p-5 border border-white/5 group hover:border-secondary transition-colors duration-300">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-secondary text-white flex items-center justify-center font-black shadow-lg shadow-orange-500/10">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-white truncate">{{ Auth::user()->name }}</p>
                    <span class="text-[10px] text-white/40 font-black uppercase tracking-wider">Locataire Actif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
        <p class="px-4 text-[10px] font-black text-secondary uppercase tracking-[2.5px] mb-4 opacity-80">Menu Principal</p>
        
        <a href="{{ route('locataire.dashboard') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl text-white/60 hover:text-white hover:bg-white/5 transition-all font-bold text-sm {{ request()->routeIs('locataire.dashboard') ? 'bg-white/10 text-white border-l-4 border-secondary' : '' }}">
            <div class="w-8 h-8 rounded-xl {{ request()->routeIs('locataire.dashboard') ? 'bg-secondary text-white' : 'bg-white/5 text-white/40' }} flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chart-pie text-xs"></i>
            </div>
            Tableau de bord
        </a>

        <a href="{{ route('locataire.pay') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl text-white/60 hover:text-white hover:bg-white/5 transition-all font-bold text-sm {{ request()->routeIs('locataire.pay') ? 'bg-white/10 text-white border-l-4 border-secondary' : '' }}">
            <div class="w-8 h-8 rounded-xl {{ request()->routeIs('locataire.pay') ? 'bg-secondary text-white' : 'bg-white/5 text-white/40' }} flex items-center justify-center transition-colors">
                <i class="fa-solid fa-credit-card text-xs"></i>
            </div>
            Payer le loyer
        </a>

        <a href="{{ route('locataire.quittances') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl text-white/60 hover:text-white hover:bg-white/5 transition-all font-bold text-sm {{ request()->routeIs('locataire.quittances') ? 'bg-white/10 text-white border-l-4 border-secondary' : '' }}">
            <div class="w-8 h-8 rounded-xl {{ request()->routeIs('locataire.quittances') ? 'bg-secondary text-white' : 'bg-white/5 text-white/40' }} flex items-center justify-center transition-colors">
                <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
            </div>
            Mes Quittances
        </a>

        <a href="{{ route('locataire.contrat') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl text-white/60 hover:text-white hover:bg-white/5 transition-all font-bold text-sm {{ request()->routeIs('locataire.contrat') ? 'bg-white/10 text-white border-l-4 border-secondary' : '' }}">
            <div class="w-8 h-8 rounded-xl {{ request()->routeIs('locataire.contrat') ? 'bg-secondary text-white' : 'bg-white/5 text-white/40' }} flex items-center justify-center transition-colors">
                <i class="fa-solid fa-file-contract text-xs"></i>
            </div>
            Mon Contrat
        </a>

        <a href="{{ route('locataire.support.index') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl text-white/60 hover:text-white hover:bg-white/5 transition-all font-bold text-sm {{ request()->routeIs('locataire.support.*') ? 'bg-white/10 text-white border-l-4 border-secondary' : '' }}">
            <div class="w-8 h-8 rounded-xl {{ request()->routeIs('locataire.support.*') ? 'bg-secondary text-white' : 'bg-white/5 text-white/40' }} flex items-center justify-center transition-colors">
                <i class="fa-solid fa-headset text-xs"></i>
            </div>
            <span class="flex-1">Demandes SAV</span>
            @if($count_notifications_sav > 0)
                <span class="bg-secondary text-white text-[9px] font-black px-2 py-0.5 rounded-full animate-pulse">
                    {{ $count_notifications_sav }}
                </span>
            @endif
        </a>

    </nav>
</aside>
