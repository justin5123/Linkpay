<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinPay - Transaction</title>
</head>
<body style="margin:0; padding:20px; background-color:#f4f7f6; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <!-- En-tête -->
        <div style="background:#10b981; padding:30px; text-align:center;">
            <img src="{{ url('images/logo.png') }}" alt="LinPay" style="height:80px; width:auto; margin-bottom:15px;">
            <h2 style="color:white; margin:0;">LinPay</h2>
            <p style="margin-top:10px; color:#d1fae5;">Transaction effectuée</p>
        </div>

        <!-- Contenu -->
        <div style="padding:30px; color:#374151;">
            <h2 style="margin-top:0;">Bonjour {{ $user->prenom }} {{ $user->nom }} 👋</h2>
            <p>
                @if($type == 'paye')
                    Vous avez payé <strong>{{ number_format($montant, 2) }} {{ $devise }}</strong> à <strong>{{ $autrePartie }}</strong>.
                @else
                    Vous avez reçu <strong>{{ number_format($montant, 2) }} {{ $devise }}</strong> de <strong>{{ $autrePartie }}</strong>.
                @endif
            </p>
            <p>Cette transaction a été réalisée automatiquement via la plateforme LinPay.</p>
            <div style="text-align:center; margin:35px 0;">
                <a href="{{ url('/dashboard') }}" style="background:#10b981; color:white; text-decoration:none; padding:14px 28px; border-radius:8px; font-weight:bold; display:inline-block;">Voir mon tableau de bord</a>
            </div>
            <div style="background:#ecfdf5; border-left:4px solid #10b981; padding:15px; border-radius:4px;">
                <strong>Informations :</strong><br>
                - Référence : {{ $reference }}<br>
                - Date : {{ now()->format('d/m/Y H:i') }}
            </div>
            <p style="margin-top:25px;">Merci d'utiliser LinPay.</p>
            <p>Cordialement,<br><strong>L'équipe LinPay</strong></p>
        </div>

        <!-- Pied de page -->
        <div style="background:#f9fafb; text-align:center; padding:20px; color:#6b7280; font-size:12px;">
            <p>
                © {{ date('Y') }} LinPay. Tous droits réservés.<br>
                <strong>Support :</strong> <a href="mailto:support@linpay.com" style="color:#10b981; text-decoration:none;">support@linpay.com</a>
            </p>
        </div>
    </div>
</body>
</html>