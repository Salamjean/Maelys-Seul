<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — Maelys-imo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; font-family:'Inter',sans-serif;">
    <img src="{{ asset('assets/images/kk.jpg') }}" alt="" style="position:fixed; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;">
    <div style="position:fixed; inset:0; background:linear-gradient(135deg, rgba(2,36,91,0.88) 0%, rgba(2,36,91,0.75) 50%, rgba(10,31,68,0.90) 100%); z-index:1;"></div>

    <div style="width:100%; max-width:420px; padding:16px; position:relative; z-index:10;">
        <div style="text-align:center; margin-bottom:32px;">
            <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Maelys-imo" style="width:72px; height:72px; object-fit:cover; border-radius:18px; box-shadow:0 6px 20px rgba(255,94,20,0.45); display:inline-block; margin-bottom:16px;">
            <h1 style="font-size:26px; font-weight:800; color:white;">Maelys-<span style="color:#ff5e14;">imo</span></h1>
        </div>

        <div style="background:white; border-radius:20px; padding:36px; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
            <h2 style="font-size:20px; font-weight:700; color:#02245b; margin-bottom:6px;">Récupération</h2>
            <p style="font-size:13px; color:#9ca3af; margin-bottom:28px;">Entrez votre email ou numéro de téléphone pour recevoir un code.</p>

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;"></i>
                    <span style="font-size:13px; color:#dc2626;">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.email') }}">
                @csrf
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:11px; font-weight:800; color:#374151; margin-bottom:8px; text-transform:uppercase; letter-spacing:1px;">Email ou Téléphone</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-user-shield" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px;"></i>
                        <input type="text" name="login" required autofocus placeholder="votre@email.com ou 01020304..." 
                            style="width:100%; padding:11px 14px 11px 40px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; color:#111827; outline:none; box-sizing:border-box;">
                    </div>
                </div>

                <button type="submit" style="width:100%; padding:13px; background-color:#ff5e14; color:white; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(255,94,20,0.35); text-transform:uppercase; letter-spacing:1px;">
                    Envoyer le code
                </button>
            </form>

            <div style="text-align:center; margin-top:20px;">
                <a href="{{ route('admin.login') }}" style="font-size:13px; color:#02245b; font-weight:600; text-decoration:none;">
                    <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </div>
</body>
</html>
