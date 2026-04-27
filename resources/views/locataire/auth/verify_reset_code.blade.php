<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du code — MAELYS-IMO</title>
    
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
        .code-input {
            letter-spacing: 1.5rem;
            text-indent: 1.5rem;
        }
    </style>
</head>
<body class="bg-login min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="absolute inset-0 bg-primary/40 backdrop-blur-[3px]"></div>

    <div class="max-w-md w-full glass rounded-[3rem] shadow-2xl relative z-10 overflow-hidden p-8 md:p-12">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-secondary/10 rounded-3xl flex items-center justify-center mx-auto mb-6 text-secondary text-3xl shadow-inner">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 class="text-3xl font-black text-primary mb-3">Vérification</h1>
            <p class="text-gray-500 font-medium text-sm">Entrez le code à 4 chiffres envoyé sur votre mobile ou email.</p>
        </div>

        <form action="{{ route('locataire.password.verify_process') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="user_id" value="{{ $userId }}">
            
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-[11px] font-black border border-red-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 text-green-600 p-4 rounded-2xl text-[11px] font-black border border-green-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                <div class="relative">
                    <input type="text" name="code" required maxlength="4" autofocus autocomplete="off"
                           placeholder="0000"
                           class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-20 text-center rounded-3xl outline-none transition-all font-black text-3xl shadow-sm text-primary tracking-[0.5em] md:tracking-[1rem]">
                </div>
            </div>

            <button type="submit" class="w-full h-16 bg-primary hover:bg-[#01163a] text-white rounded-[1.8rem] font-black text-xs uppercase tracking-[4px] shadow-xl shadow-blue-900/20 transition-all flex items-center justify-center gap-3">
                Valider le code
                <i class="fa-solid fa-check-double"></i>
            </button>

            <div class="text-center space-y-4">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Vous n'avez rien reçu ?</p>
                <a href="{{ route('locataire.password.request') }}" class="text-xs font-black text-secondary uppercase tracking-widest hover:underline transition-colors">
                    Renvoyer un nouveau code
                </a>
            </div>
        </form>
    </div>
</body>
</html>
