<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - MyWeeklyAllowance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }

        .parent-card {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }

        .no-parents {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .count {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h1>MyWeeklyAllowance - Accueil</h1>

    <h2>Liste des parents <span class="count">(<?= count($parents) ?>)</span></h2>

    <?php if (empty($parents)): ?>
        <div class="no-parents">
            <strong>Aucun parent enregistré.</strong>
            <p>Commencez par créer un compte parent pour gérer l'argent de poche de vos adolescents.</p>
        </div>
    <?php else: ?>
        <?php foreach ($parents as $parent): ?>
            <a href="/parent/dashboard?id=<?= urlencode($parent->getId()) ?>" style="text-decoration: none; color: inherit;">
                <div class="parent-card">
                    <strong><?= htmlspecialchars($parent->getName()) ?></strong>
                    <br>
                    <small>ID: <?= htmlspecialchars($parent->getId()) ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/parent/create" class="btn">+ Créer un nouveau parent</a>

    <hr style="margin: 40px 0;">

    <h2>Connexion Adolescent</h2>
    <div style="background-color: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 4px solid #2196F3;">
        <form action="/teenager/login" method="post">
            <div style="margin-bottom: 15px;">
                <label for="parentId" style="display: block; margin-bottom: 5px; font-weight: bold;">Sélectionner ton parent :</label>
                <select name="parentId" id="parentId" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">-- Choisir un parent --</option>
                    <?php foreach ($parents as $parent): ?>
                        <option value="<?= htmlspecialchars($parent->getId()) ?>">
                            <?= htmlspecialchars($parent->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="teenagerId" style="display: block; margin-bottom: 5px; font-weight: bold;">Sélectionner ton compte :</label>
                <select name="teenagerId" id="teenagerId" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">-- Choisir ton compte --</option>
                    <?php
                    // Afficher tous les teenagers
                    $allTeenagers = $_SESSION['teenagers'] ?? [];
                    foreach ($allTeenagers as $teen):
                    ?>
                        <option value="<?= htmlspecialchars($teen->getId()) ?>" data-parent="<?= htmlspecialchars($teen->getParentId()) ?>">
                            <?= htmlspecialchars($teen->getName()) ?>
                            <?php if ($teen->getAge()): ?>
                                (<?= $teen->getAge() ?> ans)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn" style="margin-top: 10px; width: 100%; cursor: pointer; border: none;">
                Se connecter
            </button>
        </form>
    </div>

    <script>
        // Filtrer les teenagers en fonction du parent sélectionné
        const parentSelect = document.getElementById('parentId');
        const teenagerSelect = document.getElementById('teenagerId');
        const allOptions = Array.from(teenagerSelect.options);

        parentSelect.addEventListener('change', function() {
            const selectedParentId = this.value;

            // Réinitialiser
            teenagerSelect.innerHTML = '<option value="">-- Choisir ton compte --</option>';

            if (selectedParentId) {
                // Filtrer et ajouter les options correspondantes
                allOptions.forEach(option => {
                    if (option.dataset.parent === selectedParentId) {
                        teenagerSelect.appendChild(option.cloneNode(true));
                    }
                });
            }
        });
    </script>
</body>

</html>