<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($parent->getName()) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .teenager-card {
            background-color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
        }

        .no-teenagers {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #ffc107;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .teen-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .teen-balance {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<body>
    <a href="/" class="back-link">� Retour � l'accueil</a>

    <div class="header">
        <h1>Dashboard de <?= htmlspecialchars($parent->getName()) ?></h1>
        <p>Name: <?= htmlspecialchars($parent->getName()) ?></p>
    </div>

    <div class="section">
        <h2>Mes adolescents (<?= count($teenagers) ?>)</h2>

        <?php if (empty($teenagers)): ?>
            <div class="no-teenagers">
                <strong>Aucun adolescent enregistr�.</strong>
                <p>Commencez par ajouter un adolescent pour g�rer son argent de poche.</p>
            </div>
        <?php else: ?>
            <?php foreach ($teenagers as $teenager): ?>
                <?php
                // R�cup�rer le compte de cet adolescent
                $accounts = $_SESSION['accounts'] ?? [];
                $teenagerAccount = null;
                foreach ($accounts as $account) {
                    if ($account->getTeenagerId() === $teenager->getId()) {
                        $teenagerAccount = $account;
                        break;
                    }
                }
                ?>
                <div class="teenager-card">
                    <div class="teen-info">
                        <div>
                            <strong><?= htmlspecialchars($teenager->getName()) ?></strong>
                            <?php if ($teenager->getAge()): ?>
                                - <?= $teenager->getAge() ?> ans
                            <?php endif; ?>
                            <br>
                            <small>name: <?= htmlspecialchars($teenager->getName()) ?></small>
                        </div>
                        <div>
                            <?php if ($teenagerAccount): ?>
                                <span class="teen-balance">
                                    Solde: <?= number_format($teenagerAccount->getBalance(), 2) ?> �
                                </span>
                            <?php else: ?>
                                <span style="color: #6c757d;">Pas de compte</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <?php if ($teenagerAccount): ?>
                            <a href="/account/view?id=<?= urlencode($teenagerAccount->getId()) ?>" class="btn btn-success">
                                G�rer le compte
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="/teenager/create?parentId=<?= urlencode($parent->getId()) ?>" class="btn">
            + Ajouter un adolescent
        </a>
    </div>
</body>

</html>