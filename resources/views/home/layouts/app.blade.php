<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MAELYS-IMO — Trouvez votre bien idéal')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#02245b',
                        secondary: '#ff5e14',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.7s ease forwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        .property-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .property-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(2, 36, 91, 0.15);
        }

        .search-input:focus {
            outline: none;
            border-color: #ff5e14;
            box-shadow: 0 0 0 3px rgba(255, 94, 20, 0.2);
        }

        .navbar-scrolled {
            background-color: #02245b !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-white text-gray-800">

    {{-- Navbar --}}
    @include('home.layouts.navbar')

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer style="background-color:#02245b;" class="text-white pt-14 pb-6">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">

                {{-- Brand --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div style="background-color:#ff5e14;"
                            class="w-9 h-9 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-building text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-white uppercase tracking-tighter italic">Maelys-<span style="color:#ff5e14;">Imo</span></span>
                    </div>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        La plateforme de référence pour trouver et publier des biens immobiliers à louer en toute
                        confiance.
                    </p>
                    <div class="flex gap-3 mt-5">
                        <a href="#" style="background-color:#ff5e14;"
                            class="w-9 h-9 rounded-full flex items-center justify-center hover:opacity-80 transition">
                            <i class="fab fa-facebook-f text-white text-sm"></i>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-full bg-blue-700 flex items-center justify-center hover:bg-blue-600 transition">
                            <i class="fab fa-instagram text-white text-sm"></i>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-full bg-blue-700 flex items-center justify-center hover:bg-blue-600 transition">
                            <i class="fab fa-linkedin-in text-white text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Navigation --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Navigation</h4>
                    <ul class="space-y-2 text-blue-200 text-sm">
                        <li><a href="#" class="hover:text-orange-400 transition">Accueil</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Locations</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Wilayas</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Publier un bien</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Contact</a></li>
                    </ul>
                </div>

                {{-- Types de biens --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Types de biens</h4>
                    <ul class="space-y-2 text-blue-200 text-sm">
                        <li><a href="#" class="hover:text-orange-400 transition">Appartements</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Maisons & Villas</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Studios</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Bureaux</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Locaux commerciaux</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Contact</h4>
                    <ul class="space-y-3 text-blue-200 text-sm">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-location-dot mt-0.5" style="color:#ff5e14;"></i>
                            <span>123 Avenue Principale, Alger, Algérie</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-phone" style="color:#ff5e14;"></i>
                            <span>+213 555 000 000</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope" style="color:#ff5e14;"></i>
                            <span>contact@Maelys-imo.dz</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-blue-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-blue-300 text-xs">
                <p>&copy; {{ date('Y') }} MAELYS-IMO. Tous droits réservés.</p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-orange-400 transition">Politique de confidentialité</a>
                    <a href="#" class="hover:text-orange-400 transition">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Demande envoyée !',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Super !',
                    confirmButtonColor: '#ff5e14',
                    borderRadius: '20px',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl',
                        confirmButton: 'rounded-xl px-10 py-4 font-black uppercase text-sm tracking-widest'
                    }
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
