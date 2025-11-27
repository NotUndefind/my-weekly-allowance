<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - Création adolescent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
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
    <h1>✗ Erreur lors de la création</h1>

    <div class="error">
        <strong>Erreur :</strong> <?= htmlspecialchars($error) ?>
    </div>

    <p><a href="javascript:history.back()">← Retour au formulaire</a></p>
    <?php if (isset($parent)): ?>
        <p><a href="/parent/dashboard?id=<?= urlencode($parent->getId()) ?>">Retour au dashboard</a></p>
    <?php endif; ?>
</body>

</html>
