<header class="h-24 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-6 md:px-12 sticky top-0 z-40">
    {{-- Left Side: Toggle & Search --}}
    <div class="flex items-center gap-4">
        {{-- Mobile Menu Trigger --}}
        <button @click="mobileMenuOpen = true" class="lg:hidden w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-blue-900/20">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 px-5 py-2.5 rounded-2xl gap-3 w-80">
            <i class="fa-solid fa-magnifying-glass text-gray-300 text-sm"></i>
            <input type="text" placeholder="Rechercher une quittance..." class="bg-transparent border-none outline-none text-xs font-bold text-gray-600 placeholder:text-gray-300 w-full">
        </div>
    </div>

    {{-- Right Actions --}}
    <div class="flex items-center gap-4">
        {{-- Notifications --}}
        <div class="group relative z-30">
            <a href="{{ route('locataire.support.index') }}" 
               class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-white hover:text-secondary hover:border-secondary/20 hover:shadow-lg hover:shadow-orange-500/5 transition-all duration-300 cursor-pointer relative">
                <i class="fa-solid fa-bell text-sm {{ $count_notifications_sav > 0 ? 'animate-bounce text-secondary' : '' }}"></i>
                
                @if($count_notifications_sav > 0)
                    <span class="absolute top-2 right-2 w-5 h-5 bg-secondary text-white text-[9px] font-black rounded-full border-2 border-white flex items-center justify-center shadow-sm pointer-events-none">
                        {{ $count_notifications_sav }}
                    </span>
                @endif
            </a>
        </div>

        {{-- Separator --}}
        <div class="w-px h-8 bg-gray-100 mx-2"></div>

        {{-- Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-gray-50 transition-all duration-300">
                <div class="hidden md:flex flex-col items-end mr-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Mon Espace</p>
                    <p class="text-sm font-black text-primary">{{ Auth::user()->name }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-primary text-white flex items-center justify-center font-black shadow-lg shadow-blue-900/10">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="absolute right-0 mt-3 w-56 bg-white rounded-[1.5rem] shadow-2xl border border-gray-50 p-2 z-50">
                
                <div class="px-4 py-3 border-b border-gray-50 mb-1">
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Identifié en tant que</p>
                    <p class="text-xs font-bold text-primary truncate">{{ Auth::user()->email ?? Auth::user()->contact }}</p>
                </div>

                <a href="{{ route('locataire.profile.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:text-primary hover:bg-gray-50 transition-all font-bold text-xs">
                    <i class="fa-solid fa-user-circle text-gray-300"></i>
                    Mon Profil
                </a>

                <div class="h-px bg-gray-50 my-1"></div>

                <form action="{{ route('locataire.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all font-bold text-xs">
                        <i class="fa-solid fa-power-off opacity-70"></i>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
