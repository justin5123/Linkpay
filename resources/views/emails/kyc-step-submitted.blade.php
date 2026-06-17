<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle demande KYC</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .btn { background: #10b981; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <h1>📄 Nouvelle soumission KYC</h1>
    <p><strong>{{ $user->prenom }} {{ $user->nom }}</strong> ({{ $user->email }}) a soumis l'étape <strong>{{ $step }}</strong> de la vérification KYC.</p>
    <p>Connectez-vous à l'administration pour traiter cette demande :</p>
    <p>
        <a href="{{ url('/admin/users/'.$user->id.'/kyc') }}" class="btn">Voir le dossier KYC</a>
    </p>
    <hr>
    <small>Cet email est automatique – merci de ne pas y répondre.</small>
</body>
</html>