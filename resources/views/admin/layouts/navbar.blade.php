{{-- Top Navbar Admin --}}
<header
    style="background:white; border-bottom:1px solid #e5e7eb; padding:0 32px; height:64px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:30;">

    <h1 style="font-size:18px; font-weight:700; color:#02245b;">@yield('page-title', 'Tableau de bord')</h1>

    <div style="display:flex; align-items:center; gap:24px;">
        {{-- Quick Payment Shortcut --}}
        <button onclick="quickPaymentShortcut()" class="p-2 text-gray-400 hover:text-green-500 transition-all flex flex-col items-center group" title="Nouveau Paiement">
            <i class="fa-solid fa-money-bill-transfer text-xl"></i>
            <span class="text-[8px] font-black uppercase tracking-tighter mt-0.5 group-hover:block transition-all">Loyer</span>
        </button>

        {{-- Resume Validation Shortcut --}}
        <button onclick="resumePaymentValidation()" class="p-2 text-gray-400 hover:text-orange-500 transition-all flex flex-col items-center group relative" title="Saisir un Code">
            <i class="fa-solid fa-key text-xl"></i>
            <span class="text-[8px] font-black uppercase tracking-tighter mt-0.5 group-hover:block transition-all">Code OTP</span>
        </button>

        {{-- Notifications Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-secondary transition-colors focus:outline-none">
                <i class="fa-solid fa-bell text-xl"></i>
                @if($count_visites_en_attente > 0)
                    <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-secondary text-[10px] font-bold text-white shadow-sm ring-2 ring-white">
                        {{ $count_visites_en_attente }}
                    </span>
                @endif
            </button>

            <div x-show="open" @click.away="open = false" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden" style="display: none;">
                
                <div class="p-4 border-bottom bg-gray-50/50 flex justify-between items-center">
                    <span class="text-sm font-bold text-primary italic">Demandes de visite</span>
                    <span class="px-2 py-0.5 bg-secondary/10 text-secondary text-[10px] font-black uppercase rounded-lg">News</span>
                </div>

                <div class="max-h-[300px] overflow-y-auto">
                    @forelse($nouvelles_visites as $v)
                        <a href="{{ route('admin.biens.index') }}" class="block p-4 border-b border-gray-50 hover:bg-orange-50/30 transition">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-calendar-check text-primary text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">{{ $v->nom }} {{ $v->prenom }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5 mt-1">{{ Str::limit($v->message, 45) }}</p>
                                    <p class="text-[9px] text-secondary font-semibold mt-1">Le {{ \Carbon\Carbon::parse($v->date_visite)->format('d/m/Y') }} à {{ $v->heure_visite }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <i class="fa-solid fa-face-smile text-gray-200 text-3xl mb-2"></i>
                            <p class="text-xs text-gray-400">Aucune nouvelle demande</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('admin.biens.index') }}" class="block p-3 text-center text-[11px] font-bold text-secondary bg-gray-50 hover:underline">
                    Voir toutes les visites
                </a>
            </div>
        </div>

        {{-- User Profile Dropdown --}}
        <div x-data="{ open: false }" class="relative" style="display:flex; align-items:center; gap:12px; border-left:1px solid #f3f4f6; padding-left:24px;">
            <button @click="open = !open" class="flex items-center gap-3 hover:opacity-80 transition-all focus:outline-none">
                <div style="text-align:right;">
                    <p style="font-size:13px; font-weight:600; color:#02245b;">{{ Auth::guard('admin')->user()->name }}</p>
                    <p style="font-size:11px; color:#9ca3af;">Administrateur</p>
                </div>
                <div style="width:38px; height:38px; background:#02245b; border-radius:50%; display:flex; align-items:center; justify-content:center; position:relative;">
                    <i class="fa-solid fa-user-tie" style="color:white; font-size:16px;"></i>
                    <div style="position:absolute; bottom:0; right:0; width:10px; height:10px; background:#10b981; border:2px solid white; border-radius:50%;"></div>
                </div>
                <i class="fa-solid fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" @click.away="open = false" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden py-2" 
                style="display: none;">
                
                <div class="px-4 py-3 border-b border-gray-50 mb-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Connecté en tant que</p>
                    <p class="text-xs font-bold text-primary truncate">{{ Auth::guard('admin')->user()->email }}</p>
                </div>


                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-black text-red-500 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-power-off w-4"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
