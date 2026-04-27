<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    style="background-color: rgba(2,36,91,0.95); backdrop-filter: blur(10px);">
    <div class="w-full" style="padding-left:5%;padding-right:5%;">
        <div style="display:flex; align-items:center; justify-content:space-between; height:64px;">

            {{-- Logo — extrémité gauche --}}
            <a href="{{ route('home') }}"
                style="flex-shrink:0; text-decoration:none; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('assets/images/maelys.jpg') }}" alt="MAELYS-IMO Logo"
                    style="height:42px; width:42px; object-fit:cover; border-radius:10px; box-shadow:0 4px 12px rgba(255,94,20,0.35); transition:transform 0.2s;"
                    onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                <div style="display:flex; flex-direction:column; line-height:1.1;">
                    <span style="font-size:20px; font-weight:800; color:white; letter-spacing:-0.5px; text-transform: uppercase;">Maelys-<span
                            style="color:#ff5e14;">Imo</span></span>
                    <span
                        style="font-size:9px; color:rgba(255,255,255,0.5); letter-spacing:2px; text-transform:uppercase; font-weight:500;">Immobilier
                        locatif</span>
                </div>
            </a>

            {{-- Navigation — centre --}}
            <div class="hidden md:flex items-center gap-1" style="flex:1; justify-content:center;">
                <a href="{{ route('home') }}"
                    class="px-4 py-2 text-sm font-medium text-white hover:text-orange-400 rounded-lg hover:bg-white/10 transition-all">
                    Accueil
                </a>
                <a href="#"
                    class="px-4 py-2 text-sm font-medium text-blue-200 hover:text-orange-400 rounded-lg hover:bg-white/10 transition-all">
                    Locations
                </a>
                <a href="#"
                    class="px-4 py-2 text-sm font-medium text-blue-200 hover:text-orange-400 rounded-lg hover:bg-white/10 transition-all">
                    Wilayas
                </a>
                <a href="#"
                    class="px-4 py-2 text-sm font-medium text-blue-200 hover:text-orange-400 rounded-lg hover:bg-white/10 transition-all">
                    Contact
                </a>
            </div>

            {{-- Auth Buttons — extrémité droite --}}
            <div class="hidden md:flex items-center gap-3" style="flex-shrink:0;">
                @auth
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div style="text-align:right;">
                            <p style="color:white; font-size:12px; font-weight:800; margin:0;">{{ Auth::user()->name }}</p>
                            <p style="color:rgba(255,255,255,0.5); font-size:9px; font-weight:700; margin:0; text-transform:uppercase;">Espace Locataire</p>
                        </div>
                        <form action="{{ route('locataire.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-10 h-10 rounded-xl bg-white/10 text-white hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-lg border border-white/10" title="Déconnexion">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl hover:opacity-90 transition-all shadow-lg flex items-center gap-2 whitespace-nowrap"
                        style="background-color:#ff5e14; box-shadow: 0 4px 15px rgba(255,94,20,0.4);">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        Connexion
                    </a>
                @endauth
            </div>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-white hover:bg-white/10 transition">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-blue-800" style="background-color:#02245b;">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}"
                class="block px-4 py-2.5 text-sm font-medium text-white rounded-lg hover:bg-white/10 transition">
                <i class="fa-solid fa-house mr-2 text-orange-400"></i> Accueil
            </a>
            <a href="#"
                class="block px-4 py-2.5 text-sm font-medium text-blue-200 rounded-lg hover:bg-white/10 transition">
                <i class="fa-solid fa-file-contract mr-2 text-orange-400"></i> Locations
            </a>
            <a href="#"
                class="block px-4 py-2.5 text-sm font-medium text-blue-200 rounded-lg hover:bg-white/10 transition">
                <i class="fa-solid fa-map-location-dot mr-2 text-orange-400"></i> Wilayas
            </a>
            <a href="#"
                class="block px-4 py-2.5 text-sm font-medium text-blue-200 rounded-lg hover:bg-white/10 transition">
                <i class="fa-solid fa-envelope mr-2 text-orange-400"></i> Contact
            </a>
            <div class="pt-3 pb-1 flex flex-col gap-2">
                @auth
                    <div class="p-4 bg-white/5 rounded-xl border border-white/10 mb-2">
                        <p class="text-white font-bold text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Locataire</p>
                        <form action="{{ route('locataire.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-red-500/20 text-red-500 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition">
                                <i class="fa-solid fa-power-off mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="block text-center px-4 py-2.5 text-sm font-medium text-white border border-white/30 rounded-lg hover:bg-white/10 transition">
                        Connexion
                    </a>
                @endauth
                <a href="#" style="background-color:#ff5e14;"
                    class="block text-center px-4 py-2.5 text-sm font-semibold text-white rounded-lg hover:opacity-90 transition">
                    <i class="fa-solid fa-plus mr-1"></i> Publier un bien
                </a>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    </script>
@endpush
