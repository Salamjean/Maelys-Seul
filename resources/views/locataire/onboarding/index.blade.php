<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Compte — Maelys-imo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 border border-gray-100 overflow-hidden">
        <div class="p-10 text-center">
            <div class="w-20 h-20 bg-[#02245b] rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-blue-900/20">
                <i class="fa-solid fa-user-shield text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-[#02245b] mb-2">Bienvenue !</h1>
            <p class="text-gray-400 text-sm font-medium">Configurez vos accès en quelques secondes</p>
        </div>

        <form action="{{ route('locataire.onboarding.process', $token) }}" method="POST" class="px-10 pb-12 space-y-6">
            @csrf
            
            @if(session('error'))
                <div class="bg-red-50 text-red-500 p-4 rounded-2xl text-xs font-bold border border-red-100 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-[10px] uppercase tracking-widest font-black text-gray-400 ml-1">Code à 4 chiffres (reçu par SMS/Mail)</label>
                <input type="text" name="code" required maxlength="4" placeholder="0000" class="w-full bg-gray-50 border-2 border-transparent focus:border-[#ff5e14] focus:bg-white px-6 py-4 rounded-2xl outline-none transition-all text-center tracking-[1rem] text-2xl font-black text-[#ff5e14]">
                @error('code') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4 pt-4 border-t border-gray-50">
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest font-black text-gray-400 ml-1">Définir un mot de passe</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-gray-50 border-2 border-transparent focus:border-[#02245b] focus:bg-white pl-12 pr-6 py-4 rounded-2xl outline-none transition-all font-bold text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest font-black text-gray-400 ml-1">Confirmer le mot de passe</label>
                    <div class="relative">
                        <i class="fa-solid fa-circle-check absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full bg-gray-50 border-2 border-transparent focus:border-[#02245b] focus:bg-white pl-12 pr-6 py-4 rounded-2xl outline-none transition-all font-bold text-sm">
                    </div>
                </div>
                @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-[#ff5e14] hover:bg-[#e04a00] text-white py-5 rounded-[1.5rem] font-extrabold text-sm uppercase tracking-widest shadow-xl shadow-orange-500/20 transition-all active:scale-[0.98] mt-6">
                Activer mon compte
            </button>
            
            <p class="text-center text-[10px] text-gray-300 font-medium px-4">
                En activant votre compte, vous acceptez nos conditions générales d'utilisation.
            </p>
        </form>
    </div>
</body>
</html>
