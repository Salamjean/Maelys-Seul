<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du code — Maelys-imo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; font-family:'Inter',sans-serif;">
    <img src="{{ asset('assets/images/kk.jpg') }}" alt="" style="position:fixed; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;">
    <div style="position:fixed; inset:0; background:linear-gradient(135deg, rgba(2,36,91,0.88) 0%, rgba(2,36,91,0.75) 50%, rgba(10,31,68,0.90) 100%); z-index:1;"></div>

    <div style="width:100%; max-width:420px; padding:16px; position:relative; z-index:10;">
        <div style="background:white; border-radius:20px; padding:36px; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
            <h2 style="font-size:20px; font-weight:700; color:#02245b; margin-bottom:6px;">Vérification</h2>
            <p style="font-size:13px; color:#9ca3af; margin-bottom:28px;">Entrez le code de sécurité à 4 chiffres envoyé sur votre email.</p>

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; margin-bottom:20px;">
                    <span style="font-size:13px; color:#dc2626;">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 16px; margin-bottom:20px;">
                    <span style="font-size:13px; color:#16a34a;">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.verify_process') }}">
                @csrf
                <input type="hidden" name="admin_id" value="{{ $adminId }}">
                
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:12px; text-align:center;">Code de sécurité</label>
                    <div style="display:flex; justify-content:center;">
                        <input type="text" name="code" maxlength="4" required autofocus placeholder="0000" 
                            style="width:120px; padding:15px; border:2px solid #ff5e14; border-radius:12px; font-size:24px; font-weight:900; color:#02245b; outline:none; text-align:center; letter-spacing:8px;">
                    </div>
                </div>

                <button type="submit" style="width:100%; padding:13px; background-color:#ff5e14; color:white; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(255,94,20,0.35);">
                    Valider le code
                </button>
            </form>

            <div style="text-align:center; margin-top:20px;">
                <p style="font-size:12px; color:#9ca3af;">
                    Vous n'avez pas reçu le code ? 
                    <a href="{{ route('admin.password.request') }}" style="color:#ff5e14; font-weight:700; text-decoration:none;">Renvoyer</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
