<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue chez ImmoSeul</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #02245b; padding: 30px; border-radius: 15px 15px 0 0; text-align: center;">
        <h1 style="color: #ffffff; margin: 0;">Bienvenue chez ImmoSeul</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 15px 15px;">
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        
        <p>Votre contrat de bail a été enregistré avec succès par notre administration immobilière.</p>
        
        <p>Afin de pouvoir consulter vos documents, gérer vos loyers et suivre vos demandes, vous devez maintenant <strong>configurer votre accès personnel</strong>.</p>
        
        <div style="background-color: #f9fafb; border: 2px dashed #ff5e14; padding: 20px; text-align: center; border-radius: 12px; margin: 25px 0;">
            <p style="margin-top: 0; font-size: 14px; color: #6b7280;">Votre code de configuration unique :</p>
            <h2 style="font-size: 36px; letter-spacing: 15px; color: #ff5e14; margin: 10px 0;">{{ $user->configuration_code }}</h2>
        </div>
        
        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('locataire.onboarding', ['token' => $user->configuration_token]) }}" 
               style="background-color: #ff5e14; color: #ffffff; padding: 15px 35px; text-decoration: none; border-radius: 12px; font-weight: bold; display: inline-block;">
               Configurer mes accès
            </a>
        </p>
        
        <p style="font-size: 13px; color: #9ca3af; margin-top: 40px; border-top: 1px solid #f3f4f6; padding-top: 20px;">
            Si vous n'avez pas fait de demande de location chez ImmoSeul, veuillez ignorer cet e-mail.
        </p>
    </div>
</body>
</html>
