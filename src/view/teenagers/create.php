<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un adolescent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .info {
            background-color: #e7f3ff;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <a href="/parent/dashboard?id=<?= urlencode($parentId) ?>" class="back-link">← Retour au dashboard</a>

    <h1>Ajouter un adolescent</h1>

    <div class="info">
        <p><strong>Parent :</strong> <?= htmlspecialchars($parent->getName()) ?></p>
        <p><strong>Règles :</strong></p>
        <ul>
            <li>Le nom doit contenir au moins 3 caractères</li>
            <li>L'âge doit être entre 10 et 19 ans (optionnel)</li>
            <li>Un compte sera créé automatiquement pour cet adolescent</li>
        </ul>
    </div>

    <form action="/teenager/create" method="post">
        <!-- Champ caché pour conserver le parentId -->
        <input type="hidden" name="parentId" value="<?= htmlspecialchars($parentId) ?>">

        <div>
            <label for="name">Nom de l'adolescent *</label>
            <input type="text" name="name" id="name" required placeholder="Ex: Jules" minlength="3">
        </div>

        <div>
            <label for="age">Âge (optionnel)</label>
            <input type="number" name="age" id="age" placeholder="Entre 10 et 19 ans" min="10" max="19">
            <small style="color: #6c757d;">Laissez vide si vous ne voulez pas préciser l'âge</small>
        </div>

        <input type="submit" value="Créer l'adolescent">
    </form>
</body>

</html>
