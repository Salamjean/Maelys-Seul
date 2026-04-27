<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe — MAELYS-IMO</title>
    
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
<body class="bg-login min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="absolute inset-0 bg-primary/40 backdrop-blur-[3px]"></div>

    <div class="max-w-md w-full glass rounded-[3rem] shadow-2xl relative z-10 overflow-hidden p-8 md:p-12">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner">
                <i class="fa-solid fa-lock-open"></i>
            </div>
            <h1 class="text-3xl font-black text-primary mb-3">Nouveau Pass</h1>
            <p class="text-gray-500 font-medium text-sm">Définissez votre nouveau mot de passe de connexion.</p>
        </div>

        <form action="{{ route('locataire.password.update') }}" method="POST" class="space-y-6" x-data="{ show: false }">
            @csrf
            <input type="hidden" name="user_id" value="{{ $userId }}">
            
            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-[11px] font-black border border-red-100 space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="space-y-6">
                {{-- Nouveau Mot de passe --}}
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-[3px] font-black text-gray-400 ml-1">Nouveau mot de passe</label>
                    <div class="relative group">
                        <div class="absolute left-6 top-1/2 -translate-y-1/2 w-8 flex justify-center text-gray-400 group-focus-within:text-primary transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••" 
                               class="w-full bg-white border-2 border-gray-50 focus:border-primary h-16 pl-16 pr-14 rounded-2xl outline-none transition-all font-bold text-sm shadow-sm">
                        <button type="button" @click="show = !show" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirmation --}}
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-[3px] font-black text-gray-400 ml-1">Confirmer le mot de passe</label>
                    <div class="relative group">
                        <div class="absolute left-6 top-1/2 -translate-y-1/2 w-8 flex justify-center text-gray-400 group-focus-within:text-primary transition-colors">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••" 
                               class="w-full bg-white border-2 border-gray-50 focus:border-primary h-16 pl-16 pr-14 rounded-2xl outline-none transition-all font-bold text-sm shadow-sm">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full h-16 bg-primary hover:bg-[#01163a] text-white rounded-[1.8rem] font-black text-xs uppercase tracking-[4px] shadow-xl shadow-blue-900/20 transition-all flex items-center justify-center gap-3 mt-4">
                Mettre à jour
                <i class="fa-solid fa-rotate"></i>
            </button>
        </form>
    </div>
</body>
</html>
