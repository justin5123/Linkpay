<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification - LinPay</title>
</head>
<body style="margin:0; padding:20px; background-color:#f4f7f6; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">

        <!-- En-tête -->
        <div style="background:#10b981; padding:30px; text-align:center;">
            <img src="{{ url('images/logo.png') }}" alt="LinPay" style="height:80px; width:auto; margin-bottom:15px;">
            <h2 style="color:white; margin:0;">Code de vérification</h2>
            <p style="margin-top:10px; color:#d1fae5;">Sécurisez votre inscription</p>
        </div>

        <!-- Contenu -->
        <div style="padding:30px; color:#374151;">

            <h2 style="margin-top:0;">Bonjour,</h2>

            <p>
                Vous êtes sur le point de finaliser votre inscription sur <strong>LinPay</strong>.
                Veuillez utiliser le code ci-dessous pour valider votre adresse email.
            </p>

            <!-- Code OTP -->
            <div style="text-align:center; margin:30px 0; background:#f3f4f6; padding:20px; border-radius:8px;">
                <span style="font-size:36px; font-weight:bold; letter-spacing:8px; color:#10b981;">
                    {{ $code }}
                </span>
                <p style="margin-top:8px; color:#6b7280; font-size:14px;">Code valable 2 minutes</p>
            </div>

            <div style="background:#ecfdf5; border-left:4px solid #10b981; padding:15px; border-radius:4px; margin-top:20px;">
                <strong>Conseil sécurité :</strong><br>
                Ne partagez jamais ce code avec personne. Si vous n’êtes pas à l’origine de cette demande, ignorez cet email.
            </div>

            <p style="margin-top:25px;">Cordialement,<br><strong>L'équipe LinPay</strong></p>
        </div>

        <!-- Pied de page -->
        <div style="background:#f9fafb; text-align:center; padding:20px; color:#6b7280; font-size:12px;">
            <p style="margin:0;">
                © {{ date('Y') }} LinPay. Tous droits réservés.<br>
                <strong>Support :</strong> <a href="mailto:support@linpay.com" style="color:#10b981; text-decoration:none;">support@linpay.com</a>
            </p>
        </div>
    </div>
</body>
</html>