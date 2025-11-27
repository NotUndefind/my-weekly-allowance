<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès - Parent créé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>✓ Parent créé avec succès !</h1>

    <div class="success">
        <?= htmlspecialchars($message) ?>
    </div>

    <div class="info-box">
        <p><strong>ID généré :</strong> <?= htmlspecialchars($parent->getId()) ?></p>
        <p><strong>Nom :</strong> <?= htmlspecialchars($parent->getName()) ?></p>
    </div>

    <p><a href="/">← Retour à l'accueil</a></p>
    <p><a href="/parent/create">Créer un autre parent</a></p>
</body>

</html>
