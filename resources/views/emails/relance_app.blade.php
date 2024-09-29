<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos informations de connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h2 {
            color: #2c3e50;
        }
        p {
            font-size: 14px;
            margin: 10px 0;
        }
        a {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #2980b9;
        }
        img {
            margin-top: 20px;
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>Bonjour,</h2>

    <p>Vous trouverez ci-dessous vos informations de connexion à notre système :</p>

    <p><strong>Matricule :</strong> {{ $matricule ?? 'Non spécifié' }}</p>
    <p><strong>Email :</strong> {{ $email ?? 'Non spécifié' }}</p>
    <p><strong>Mot de passe par défaut :</strong> {{ $password ?? 'Non spécifié' }}</p>

    <p>Vous pouvez vous connecter en cliquant sur le lien suivant :</p>
    <p>
        <a href="{{ $loginLink ?? '#' }}">Se connecter</a>
    </p>

    @if (!empty($qrcode))
        <p>Veuillez utiliser le QR code ci-dessous pour accéder à certaines fonctionnalités :</p>
        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code">
    @else
        <p>Le QR code n'est pas disponible pour le moment.</p>
    @endif

    <p>Merci,</p>
    <p>L'équipe de gestion pédagogique</p>
</body>
</html>
