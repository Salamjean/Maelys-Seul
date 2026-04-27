<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Recouvrement Maelys-imo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#02245b',
                        secondary: '#ff5e14',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
        }

        .sidebar-link.active div {
            background-color: #ff5e14 !important;
            box-shadow: 0 4px 12px rgba(255, 94, 20, 0.3);
        }

        .sidebar-link.active div i {
            color: white !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex" x-data="{ mobileMenuOpen: false }">

    {{-- Overlay for mobile sidebar --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-40 lg:hidden"></div>

    @include('recouvrement.layouts.sidebar')

    {{-- Main --}}
    <div class="flex-1 min-h-screen flex flex-col transition-all duration-300 min-w-0" :class="mobileMenuOpen ? 'pl-0' : ''">

            {{-- Navbar Style Admin --}}
            <nav
                class="bg-white/80 backdrop-blur-md border-b border-gray-100 h-20 px-4 md:px-8 flex items-center justify-between sticky top-0 z-40 shadow-sm">
                <div class="flex items-center gap-2 md:gap-4">
                    {{-- Hamburger mobile --}}
                    <button @click="mobileMenuOpen = true"
                        class="lg:hidden w-11 h-11 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-blue-900/20">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>

                    <div class="hidden sm:flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h1 class="text-xl font-black text-primary italic uppercase tracking-tighter">
                            @yield('page-title')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 p-2 rounded-2xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                            <div
                                class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-black shadow-lg shadow-primary/20 overflow-hidden">
                                @if(Auth::guard('admin')->user()->photo)
                                    <img src="{{ Storage::url(Auth::guard('admin')->user()->photo) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="flex flex-col text-left hidden md:block">
                                <span
                                    class="text-xs font-black text-primary uppercase leading-none">{{ Auth::guard('admin')->user()->name }}</span>
                                <span
                                    class="text-[10px] font-bold text-secondary uppercase tracking-widest mt-1">Recouvrement</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1 transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-3xl shadow-2xl border border-gray-100 py-3 z-50">

                            <div class="px-6 py-3 border-b border-gray-50 mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mon Compte</p>
                            </div>

                            <a href="{{ route('recouvrement.profile.show') }}"
                                class="flex items-center gap-3 px-6 py-3 text-primary hover:bg-gray-50 transition-colors text-xs font-black uppercase tracking-widest">
                                <i class="fa-solid fa-user-circle"></i>
                                Mon Profil
                            </a>

                            <div class="h-px bg-gray-50 my-2 mx-4"></div>

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-6 py-3 text-red-500 hover:bg-red-50 transition-colors text-xs font-black uppercase tracking-widest">
                                    <i class="fa-solid fa-power-off"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Content --}}
            <main style="flex:1; padding: 40px 3%; transition: all 0.3s ease;">
                @yield('content')
            </main>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Succès !',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff5e14',
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            confirmButton: 'rounded-xl px-8 py-3 font-bold'
                        }
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Erreur',
                        text: "{{ session('error') }}",
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            confirmButton: 'rounded-xl px-8 py-3 font-bold'
                        }
                    });
                });
            </script>
        @endif

        @stack('scripts')
</body>

</html>