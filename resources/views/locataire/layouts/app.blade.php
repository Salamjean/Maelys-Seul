<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — Espace Locataire Maelys-imo</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Scripts & Icons --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#02245b',
                        secondary: '#ff5e14',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #02245b; border-radius: 10px; }
        
        .sidebar-link-active {
            background: linear-gradient(90deg, rgba(255,94,20,0.1) 0%, rgba(255,94,20,0) 100%);
            border-left: 4px solid #ff5e14;
            color: #ff5e14 !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-gray-800" x-data="{ mobileMenuOpen: false }">

    {{-- Overlay for mobile sidebar --}}
    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-40 lg:hidden"></div>

    <div class="min-h-screen flex relative">
        {{-- Sidebar --}}
        @include('locataire.layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Navbar --}}
            @include('locataire.layouts.navbar')

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto px-6 py-8 md:px-12">
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-primary">@yield('page-title')</h1>
                    <p class="text-gray-400 text-sm font-bold mt-1 uppercase tracking-widest">@yield('page-subtitle')</p>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    title: 'Succès !',
                    text: "{!! session('success') !!}",
                    icon: 'success',
                    confirmButtonColor: '#02245b'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Erreur !',
                    text: "{!! session('error') !!}",
                    icon: 'error',
                    confirmButtonColor: '#ff5e14'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    title: 'Erreur de saisie',
                    html: "{!! implode('<br>', $errors->all()) !!}",
                    icon: 'error',
                    confirmButtonColor: '#ff5e14'
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
