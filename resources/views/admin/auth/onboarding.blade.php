<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation de compte Agent - MAELYS-IMO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; background: #f4f7f6; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-cover bg-center" style="background-image: url('{{ asset('assets/images/bg-auth.jpg') }}'); background-blend-mode: overlay; background-color: rgba(2, 36, 91, 0.8);">
    
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-10">
            <div class="inline-block p-4 bg-white rounded-3xl shadow-2xl mb-4">
                <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Logo" class="w-20 h-20 object-cover rounded-2xl">
            </div>
            <h1 class="text-3xl font-black text-white italic tracking-tighter">MAELYS-<span class="text-[#ff5e14]">IMO</span></h1>
            <p class="text-white/60 text-xs font-bold uppercase tracking-[4px] mt-2">Activation Agent</p>
        </div>

        {{-- Card --}}
        <div class="glass-card rounded-[2.5rem] shadow-2xl overflow-hidden p-10 border border-white/10">
            <div class="mb-8">
                <h2 class="text-xl font-black text-[#02245b]">Bonjour {{ $agent->name }},</h2>
                <p class="text-gray-500 text-sm mt-2 font-medium leading-relaxed">
                    Veuillez définir votre mot de passe pour activer votre accès collaborateur.
                </p>
            </div>

            <form action="{{ route('admin.onboarding.complete', $token) }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Code d'activation --}}
                <div>
                    <label class="text-[10px] font-black text-[#ff5e14] uppercase tracking-[2px] ml-1 block mb-3 text-center md:text-left italic">Saisir le Code d'activation à 4 chiffres</label>
                    <div class="relative group">
                        <input type="text" name="onboarding_code" required maxlength="4"
                               class="w-full bg-orange-50 border-2 border-orange-100 focus:border-[#ff5e14] h-16 text-center text-2xl tracking-[15px] rounded-2xl outline-none transition-all font-black text-[#ff5e14] shadow-inner"
                               placeholder="0000">
                        <i class="fa-solid fa-key absolute right-6 top-1/2 -translate-y-1/2 text-orange-200 group-focus-within:text-[#ff5e14] transition-colors"></i>
                    </div>
                    @error('onboarding_code') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Mot de passe --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3 text-center md:text-left">Nouveau Mot de passe</label>
                    <div class="relative group">
                        <input type="password" name="password" required
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-[#02245b] h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                               placeholder="••••••••">
                        <i class="fa-solid fa-lock absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#02245b] transition-colors"></i>
                    </div>
                    @error('password') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Confirmation --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3 text-center md:text-left">Confirmer le mot de passe</label>
                    <div class="relative group">
                        <input type="password" name="password_confirmation" required
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-[#02245b] h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                               placeholder="••••••••">
                        <i class="fa-solid fa-shield-check absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#02245b] transition-colors"></i>
                    </div>
                </div>

                <button type="submit" class="w-full h-14 bg-[#ff5e14] text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-[#e04d0c] transition shadow-xl shadow-orange-900/20 flex items-center justify-center gap-3 mt-8">
                    ACTIVER MON COMPTE AGENT <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>
        </div>

        <p class="text-center mt-8 text-white/40 text-[10px] font-bold uppercase tracking-widest">
            &copy; {{ date('Y') }} MAELYS-IMO • Système de gestion sécurisé
        </p>
    </div>

</body>
</html>
