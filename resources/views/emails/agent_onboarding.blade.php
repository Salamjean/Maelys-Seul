<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #02245b 0%, #01163a 100%); padding: 40px; text-align: center; color: #ffffff; }
        .content { padding: 40px; text-align: center; color: #333333; }
        .footer { background-color: #f9fbfb; padding: 20px; text-align: center; color: #999999; font-size: 12px; }
        .btn { display: inline-block; padding: 18px 40px; background-color: #ff5e14; color: #ffffff; text-decoration: none; border-radius: 16px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-top: 30px; box-shadow: 0 8px 20px rgba(255, 94, 20, 0.2); }
        h1 { margin: 0; font-size: 28px; font-weight: 900; italic; text-transform: uppercase; }
        p { line-height: 1.6; font-size: 16px; margin-bottom: 20px; }
        .code-box { background: #f0f4f8; padding: 15px; border-radius: 12px; display: inline-block; margin: 20px 0; font-family: monospace; font-size: 20px; font-weight: bold; color: #02245b; border: 2px dashed #cbd5e0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MAELYS-<span style="color:#ff5e14;">IMO</span></h1>
            <p style="margin-top: 10px; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-size: 11px;">Accès Collaborateur</p>
        </div>
        <div class="content">
            <h2 style="font-weight: 800; color: #02245b;">Bienvenue dans l'équipe, {{ $agent->name }} !</h2>
            <p>Un compte agent a été créé pour vous sur la plateforme de gestion immobilière <strong>MAELYS-IMO</strong>.</p>
            <p>Pour des raisons de sécurité, vous devez saisir le code d'activation ci-dessous et définir votre mot de passe pour activer votre accès.</p>
            
            <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">Votre code d'activation</div>
            <div class="code-box">
                {{ $agent->onboarding_code }}
            </div>
            
            <p style="font-size: 14px; color: #666;">Cliquez sur le bouton ci-dessous pour accéder à la page d'activation.</p>
            
            <a href="{{ $url }}" class="btn">ACTIVER MON COMPTE</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MAELYS-IMO. Tous droits réservés.<br>
            Ceci est un message automatique, merci de ne pas y répondre.
        </div>
    </div>
</body>
</html>
