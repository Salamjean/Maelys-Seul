<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Locataire — MAELYS-IMO</title>
    
    {{-- Scripts & Styles --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
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
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .bg-login {
            background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-login min-h-screen flex items-center justify-center p-4 md:p-8 relative overflow-hidden">
    
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-primary/40 backdrop-blur-[3px]"></div>

    {{-- Decor elements --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-secondary/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/30 rounded-full blur-3xl"></div>

    <div class="max-w-4xl w-full flex flex-col md:flex-row glass rounded-[3rem] shadow-2xl relative z-10 overflow-hidden">
        
        {{-- Left Side: Branding / Info --}}
        <div class="hidden md:flex md:w-5/12 bg-primary/10 p-12 flex-col justify-between border-r border-white/20">
            <div>
                <a href="{{ route('home') }}"><img src="{{ asset('assets/images/maelys.jpg') }}" alt="Logo" class="w-20 h-20 rounded-2xl shadow-xl border-4 border-white mb-8"></a>
                <h2 class="text-3xl font-black text-primary italic leading-tight uppercase tracking-tighter">MAELYS-<span class="text-secondary">IMO</span></h2>
                <p class="text-gray-500 text-sm font-bold mt-2 uppercase tracking-widest">Votre espace privilège</p>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg"><i class="fa-solid fa-file-invoice"></i></div>
                    <p class="text-xs font-bold text-gray-600">Consultez vos quittances en un clic</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-secondary text-white flex items-center justify-center shadow-lg"><i class="fa-solid fa-wrench"></i></div>
                    <p class="text-xs font-bold text-gray-600">Envoyez vos demandes de SAV</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white text-primary flex items-center justify-center shadow-lg border border-gray-100"><i class="fa-solid fa-shield-halved"></i></div>
                    <p class="text-xs font-bold text-gray-600">Sécurisez vos paiements</p>
                </div>
            </div>
        </div>

        {{-- Right Side: Form --}}
        <div class="flex-1 p-8 md:p-16">
            <div class="text-center md:text-left mb-10">
                <h1 class="text-4xl font-black text-primary mb-3">Ravi de vous revoir !</h1>
                <p class="text-gray-400 font-medium">Connectez-vous pour gérer votre location sereinement.</p>
            </div>

            <form action="{{ route('locataire.login.process') }}" method="POST" class="space-y-6" x-data="{ loginType: 'phone', showPassword: false }">
                @csrf
                <input type="hidden" name="login_type" :value="loginType">

                {{-- Toggle Login Type --}}
                <div class="flex p-1.5 bg-gray-100/50 backdrop-blur rounded-2xl mb-8 border border-gray-100">
                    <button type="button" @click="loginType = 'phone'" 
                            :class="loginType === 'phone' ? 'bg-white text-primary shadow-sm border border-gray-100' : 'text-gray-400'"
                            class="flex-1 py-3 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all duration-300">
                        <i class="fa-solid fa-phone mr-2"></i> Téléphone
                    </button>
                    <button type="button" @click="loginType = 'email'" 
                            :class="loginType === 'email' ? 'bg-white text-primary shadow-sm border border-gray-100' : 'text-gray-400'"
                            class="flex-1 py-3 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all duration-300">
                        <i class="fa-solid fa-envelope mr-2"></i> Email
                    </button>
                </div>

                @if(session('error'))
                    <div class="bg-red-50 text-red-600 p-5 rounded-2xl text-[11px] font-black border border-red-100 flex items-center gap-4 animate-shake">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 text-green-600 p-5 rounded-2xl text-[11px] font-black border border-green-100 flex items-center gap-4 animate-fade-in">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-[3px] font-black text-gray-400 ml-1" x-text="loginType === 'phone' ? 'Identifiant Téléphone' : 'Identifiant Email'"></label>
                        <div class="relative group">
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 w-8 flex justify-center text-gray-400 group-focus-within:text-secondary transition-colors duration-300">
                                <i :class="loginType === 'phone' ? 'fa-solid fa-phone-flip' : 'fa-solid fa-at'"></i>
                            </div>
                            <input type="text" name="login" required 
                                   :placeholder="loginType === 'phone' ? 'Ex: 0102030405' : 'votre@email.com'" 
                                   class="w-full bg-white border-2 border-gray-50 focus:border-secondary focus:ring-4 focus:ring-secondary/5 h-16 pl-16 pr-8 rounded-2xl outline-none transition-all font-bold text-sm shadow-sm group-hover:shadow-md">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-[3px] font-black text-gray-400 ml-1">Mot de passe sécurisé</label>
                        <div class="relative group">
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 w-8 flex justify-center text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" 
                                   class="w-full bg-white border-2 border-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 h-16 pl-16 pr-14 rounded-2xl outline-none transition-all font-bold text-sm shadow-sm group-hover:shadow-md">
                            <button type="button" @click="showPassword = !showPassword" 
                                    class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                                <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center px-1">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" class="hidden peer">
                        <div class="w-6 h-6 border-2 border-gray-200 rounded-lg peer-checked:bg-secondary peer-checked:border-secondary flex items-center justify-center transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-400 group-hover:text-gray-600 transition-colors">Rester connecté</span>
                    </label>
                    <a href="{{ route('locataire.password.request') }}" class="text-xs font-black text-secondary uppercase tracking-widest hover:underline">Oublié ?</a>
                </div>

                <button type="submit" class="w-full h-16 bg-primary hover:bg-[#01163a] text-white rounded-[1.8rem] font-black text-xs uppercase tracking-[4px] shadow-2xl shadow-blue-900/40 transition-all active:scale-[0.97] flex items-center justify-center gap-3 mt-4">
                    Connexion Immédiate
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </button>
                
                <p class="text-center text-[10px] text-gray-300 font-bold uppercase tracking-widest mt-8">
                    &copy; {{ date('Y') }} MAELYS-IMO. Sécurisé à 100%.
                </p>
            </form>
        </div>
    </div>

</body>
</html>
