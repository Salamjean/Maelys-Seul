<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — MAELYS-IMO</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center mx-auto mb-6 text-primary text-3xl">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="text-3xl font-black text-primary mb-3">Mot de passe oublié ?</h1>
            <p class="text-gray-500 font-medium text-sm">Entrez votre identifiant pour recevoir un code de réinitialisation à 4 chiffres.</p>
        </div>

        <form action="{{ route('locataire.password.email') }}" method="POST" class="space-y-6">
            @csrf
            
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-[11px] font-black border border-red-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-[10px] uppercase tracking-[3px] font-black text-gray-400 ml-1">Email ou Téléphone</label>
                <div class="relative group">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 w-8 flex justify-center text-gray-400 group-focus-within:text-secondary transition-colors">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                    <input type="text" name="login" required placeholder="Ex: 0102030405 ou email@site.com" 
                           class="w-full bg-white border-2 border-gray-50 focus:border-secondary h-16 pl-16 pr-8 rounded-2xl outline-none transition-all font-bold text-sm shadow-sm">
                </div>
            </div>

            <button type="submit" class="w-full h-16 bg-primary hover:bg-[#01163a] text-white rounded-[1.8rem] font-black text-xs uppercase tracking-[4px] shadow-xl shadow-blue-900/20 transition-all flex items-center justify-center gap-3">
                Envoyer le code
                <i class="fa-solid fa-paper-plane"></i>
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Retour à la connexion
                </a>
            </div>
        </form>
    </div>
</body>
</html>
