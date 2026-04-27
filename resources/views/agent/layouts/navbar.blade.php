{{-- Top Navbar Agent --}}
<header class="bg-white border-b border-gray-100 px-8 h-20 flex items-center justify-between fixed top-0 right-0 left-[280px] z-30 shadow-sm backdrop-blur-md bg-white/80">

    <div class="flex items-center gap-4 italic lowercase font-black text-primary">
        <span class="uppercase">M</span>AELYS-IMO <span class="mx-2 text-gray-200">/</span> 
        <span class="text-xs text-secondary font-black uppercase tracking-widest italic">Tableau de bord</span>
    </div>

    <div class="flex items-center gap-8">
        {{-- Quick Search --}}
        <div class="relative hidden lg:block">
            <input type="text" placeholder="Rechercher..." class="bg-gray-50 border-2 border-transparent focus:border-secondary/20 h-11 w-64 pl-12 pr-6 rounded-2xl outline-none transition-all font-bold text-xs text-gray-700">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        
        {{-- Quick Actions --}}
        <div class="flex items-center gap-4">
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
        </div>

        {{-- Notifications --}}
        <div class="relative">
            <button class="relative p-2 text-gray-400 hover:text-secondary transition-colors focus:outline-none">
                <i class="fa-solid fa-bell text-xl"></i>
                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-secondary text-[10px] font-bold text-white shadow-sm ring-2 ring-white">
                    0
                </span>
            </button>
        </div>

        {{-- User Profile Dropdown --}}
        <div x-data="{ open: false }" class="relative flex items-center gap-4 pl-8 border-l border-gray-100">
            <button @click="open = !open" class="flex items-center gap-4 hover:opacity-80 transition-all focus:outline-none">
                <div class="text-right">
                    <p class="text-xs font-black text-primary italic uppercase tracking-tighter">{{ Auth::guard('admin')->user()->name }}</p>
                    <p class="text-[9px] font-bold text-secondary uppercase tracking-[2px]">Agent Immobilier</p>
                </div>
                <div class="w-11 h-11 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-900/10 relative">
                    <i class="fa-solid fa-user-gear"></i>
                    <div style="position:absolute; bottom:-2px; right:-2px; width:10px; height:10px; background:#10b981; border:2px solid white; border-radius:50%;"></div>
                </div>
                <i class="fa-solid fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" @click.away="open = false" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 top-full mt-4 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden py-2" 
                style="display: none;">
                
                <div class="px-4 py-3 border-b border-gray-50 mb-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Agent Connecté</p>
                    <p class="text-xs font-bold text-primary truncate">{{ Auth::guard('admin')->user()->email }}</p>
                </div>

                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-id-badge text-gray-400 w-4"></i>
                    Mes Infos
                </a>
                
                <div class="h-px bg-gray-50 my-1"></div>

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
