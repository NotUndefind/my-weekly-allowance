<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte Parent</title>
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
        input[type="email"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .info {
            background-color: #e7f3ff;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h1>Créer un compte Parent</h1>

    <div class="info">
        <p><strong>Note :</strong> Le nom doit contenir au moins 3 caractères et l'email doit être valide.</p>
    </div>

    <form action="/parent/create" method="post">
        <div>
            <label for="name">Nom complet *</label>
            <input type="text" name="name" id="name" required placeholder="Ex: Jean Dupont" minlength="3">
        </div>

        <div>
            <label for="username">username </label>
            <input type="text" name="username" id="username">
        </div>

        <input type="submit" value="Créer le compte">
    </form>

    <p><a href="/">← Retour à l'accueil</a></p>

</body>

</html>