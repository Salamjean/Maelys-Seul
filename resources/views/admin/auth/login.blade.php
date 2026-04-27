<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin — Maelys-imo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body
    style="min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">

    {{-- Background image --}}
    <img src="{{ asset('assets/images/kk.jpg') }}" alt=""
        style="position:fixed; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;">

    {{-- Overlay --}}
    <div
        style="position:fixed; inset:0; background:linear-gradient(135deg, rgba(2,36,91,0.88) 0%, rgba(2,36,91,0.75) 50%, rgba(10,31,68,0.90) 100%); z-index:1;">
    </div>

    <div style="width:100%; max-width:420px; padding:16px; position:relative; z-index:10;">

        {{-- Logo --}}
        <div style="text-align:center; margin-bottom:32px;">
            <div style="margin-bottom:16px;">
                <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Maelys-imo"
                    style="width:72px; height:72px; object-fit:cover; border-radius:18px; box-shadow:0 6px 20px rgba(255,94,20,0.45); display:inline-block;">
            </div>
            <h1 style="font-size:26px; font-weight:800; color:white;">Maelys-<span style="color:#ff5e14;">imo</span></h1>
            <p
                style="color:rgba(255,255,255,0.5); font-size:13px; margin-top:4px; letter-spacing:2px; text-transform:uppercase;">
                Espace Administration</p>
        </div>

        {{-- Card --}}
        <div style="background:white; border-radius:20px; padding:36px; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
            <h2 style="font-size:20px; font-weight:700; color:#02245b; margin-bottom:6px;">Connexion</h2>
            <p style="font-size:13px; color:#9ca3af; margin-bottom:28px;">Entrez vos identifiants pour accéder au
                tableau de bord.</p>

            {{-- Error --}}
            @if ($errors->any())
                <div
                    style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;"></i>
                    <span style="font-size:13px; color:#dc2626;">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Session message --}}
            @if (session('error'))
                <div
                    style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; margin-bottom:20px;">
                    <span style="font-size:13px; color:#dc2626;">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.handleLogin') }}">
                @csrf

                {{-- Email --}}
                <div style="margin-bottom:18px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                        Adresse email
                    </label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-envelope"
                            style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px;"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="admin@maelys-imo.com"
                            style="width:100%; padding:11px 14px 11px 40px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; color:#111827; outline:none; box-sizing:border-box;"
                            onfocus="this.style.borderColor='#ff5e14'; this.style.boxShadow='0 0 0 3px rgba(255,94,20,0.12)';"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
                    </div>
                </div>

                {{-- Password --}}
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                        Mot de passe
                    </label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-lock"
                            style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px;"></i>
                        <input type="password" name="password" id="password-input" required placeholder="••••••••"
                            style="width:100%; padding:11px 40px 11px 40px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; color:#111827; outline:none; box-sizing:border-box;"
                            onfocus="this.style.borderColor='#ff5e14'; this.style.boxShadow='0 0 0 3px rgba(255,94,20,0.12)';"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
                        <button type="button" onclick="togglePassword()"
                            style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:0;">
                            <i class="fa-solid fa-eye" id="eye-icon" style="font-size:14px;"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember & Forgot --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="remember" id="remember"
                            style="accent-color:#ff5e14; width:16px; height:16px;">
                        <label for="remember" style="font-size:13px; color:#6b7280; cursor:pointer;">Se souvenir de
                            moi</label>
                    </div>
                    <a href="{{ route('admin.password.request') }}" style="font-size:12px; color:#ff5e14; text-decoration:none; font-weight:600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        Mot de passe oublié ?
                    </a>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    style="width:100%; padding:13px; background-color:#ff5e14; color:white; border:none; border-radius:10px;
                           font-size:15px; font-weight:700; cursor:pointer; transition:opacity 0.2s; box-shadow:0 4px 14px rgba(255,94,20,0.35);"
                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    <i class="fa-solid fa-right-to-bracket" style="margin-right:8px;"></i>
                    Se connecter
                </button>
            </form>
        </div>

        <p style="text-align:center; color:rgba(255,255,255,0.35); font-size:12px; margin-top:24px;">
            &copy; {{ date('Y') }} Maelys-imo — Tous droits réservés
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>

</html>
