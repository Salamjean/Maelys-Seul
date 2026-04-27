<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Espace Comptable</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Scripts --}}
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
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
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex">

    @include('comptable.layouts.sidebar')

    <main style="margin-left:280px; flex:1; display:flex; flex-direction:column; min-width:0;">
        {{-- Header / Navbar --}}
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 h-20 px-8 flex items-center justify-between sticky top-0 z-30">
            <h1 class="text-xl font-black text-primary italic uppercase tracking-tighter">@yield('page-title')</h1>
            
            <div class="flex items-center gap-6">
                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-2 rounded-2xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-black shadow-lg shadow-primary/20 overflow-hidden">
                            @if(Auth::guard('admin')->user()->photo)
                                <img src="{{ Storage::url(Auth::guard('admin')->user()->photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="flex flex-col text-left hidden md:block">
                            <span class="text-xs font-black text-primary uppercase leading-none">{{ Auth::guard('admin')->user()->name }}</span>
                            <span class="text-[10px] font-bold text-secondary uppercase tracking-widest mt-1">Comptable</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-3xl shadow-2xl border border-gray-100 py-3 z-50">
                        
                        <div class="px-6 py-3 border-b border-gray-50 mb-2">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mon Compte</p>
                        </div>

                        <a href="{{ route('comptable.profile.show') }}" class="flex items-center gap-3 px-6 py-3 text-primary hover:bg-gray-50 transition-colors text-xs font-black uppercase tracking-widest">
                            <i class="fa-solid fa-user-circle"></i>
                            Mon Profil
                        </a>

                        <div class="h-px bg-gray-50 my-2 mx-4"></div>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-6 py-3 text-red-500 hover:bg-red-50 transition-colors text-xs font-black uppercase tracking-widest">
                                <i class="fa-solid fa-power-off"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
