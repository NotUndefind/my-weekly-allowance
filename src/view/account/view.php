<?php
/**
 * @var \App\Model\Account $account
 * @var \App\Model\AccountTeenager $teenager
 * @var \App\Model\AccountParent|null $parent
 * @var array $accountTransactions
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte de <?= htmlspecialchars($teenager->getName()) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
            margin-bottom: 10px;
            display: inline-block;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .info-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .balance {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }

        .forms-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-card h3 {
            margin-top: 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
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

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .transactions-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .transaction-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-deposit {
            border-left: 4px solid #28a745;
        }

        .transaction-expense {
            border-left: 4px solid #dc3545;
        }

        .transaction-allowance {
            border-left: 4px solid #007bff;
        }

        .transaction-amount {
            font-weight: bold;
            font-size: 18px;
        }

        .amount-positive {
            color: #28a745;
        }

        .amount-negative {
            color: #dc3545;
        }

        .no-transactions {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <?php if ($parent): ?>
        <a href="/parent/dashboard?id=<?= urlencode($parent->getId()) ?>" class="back-link">← Retour au dashboard</a>
    <?php endif; ?>

    <div class="header">
        <h1>Compte de <?= htmlspecialchars($teenager->getName()) ?></h1>
        <?php if ($teenager->getAge()): ?>
            <p><?= $teenager->getAge() ?> ans</p>
        <?php endif; ?>
    </div>

    <div class="info-section">
        <h2>Solde actuel</h2>
        <div class="balance"><?= number_format($account->getBalance(), 2) ?> €</div>
        <?php if ($account->getWeeklyAllowance()): ?>
            <p><strong>Allocation hebdomadaire:</strong> <?= number_format($account->getWeeklyAllowance(), 2) ?> €</p>
        <?php else: ?>
            <p><em>Aucune allocation hebdomadaire configurée</em></p>
        <?php endif; ?>
    </div>

    <div class="forms-container">
        <!-- Formulaire de dépôt -->
        <div class="form-card">
            <h3>Déposer de l'argent</h3>
            <form action="/account/deposit" method="post">
                <input type="hidden" name="accountId" value="<?= htmlspecialchars($account->getId()) ?>">

                <div class="form-group">
                    <label for="deposit-amount">Montant (€)</label>
                    <input type="number" id="deposit-amount" name="amount" step="0.01" min="0.01" required>
                </div>

                <button type="submit" class="btn btn-success">Déposer</button>
            </form>
        </div>

        <!-- Formulaire de dépense -->
        <div class="form-card">
            <h3>Enregistrer une dépense</h3>
            <form action="/account/expense" method="post">
                <input type="hidden" name="accountId" value="<?= htmlspecialchars($account->getId()) ?>">

                <div class="form-group">
                    <label for="expense-amount">Montant (€)</label>
                    <input type="number" id="expense-amount" name="amount" step="0.01" min="0.01" required>
                </div>

                <div class="form-group">
                    <label for="expense-description">Description</label>
                    <input type="text" id="expense-description" name="description" placeholder="Ex: Cinéma, McDo...">
                </div>

                <button type="submit" class="btn btn-danger">Dépenser</button>
            </form>
        </div>

        <!-- Formulaire d'allocation -->
        <div class="form-card">
            <h3>Configurer l'allocation</h3>
            <form action="/account/set-allowance" method="post">
                <input type="hidden" name="accountId" value="<?= htmlspecialchars($account->getId()) ?>">

                <div class="form-group">
                    <label for="allowance-amount">Montant hebdomadaire (€)</label>
                    <input type="number" id="allowance-amount" name="weeklyAllowance" step="0.01" min="0"
                           value="<?= $account->getWeeklyAllowance() ? number_format($account->getWeeklyAllowance(), 2, '.', '') : '' ?>" required>
                </div>

                <button type="submit" class="btn">Configurer</button>
            </form>
        </div>
    </div>

    <!-- Historique des transactions -->
    <div class="transactions-section">
        <h2>Historique des transactions</h2>

        <?php if (empty($accountTransactions)): ?>
            <div class="no-transactions">
                <p>Aucune transaction pour le moment</p>
            </div>
        <?php else: ?>
            <?php foreach ($accountTransactions as $transaction): ?>
                <?php
                $typeClass = match($transaction->getType()) {
                    \App\Model\TransactionType::DEPOSIT => 'transaction-deposit',
                    \App\Model\TransactionType::EXPENSE => 'transaction-expense',
                    \App\Model\TransactionType::ALLOWANCE => 'transaction-allowance',
                };

                $amountClass = ($transaction->getType() === \App\Model\TransactionType::EXPENSE)
                    ? 'amount-negative'
                    : 'amount-positive';

                $amountPrefix = ($transaction->getType() === \App\Model\TransactionType::EXPENSE) ? '-' : '+';

                $typeLabel = match($transaction->getType()) {
                    \App\Model\TransactionType::DEPOSIT => 'Dépôt',
                    \App\Model\TransactionType::EXPENSE => 'Dépense',
                    \App\Model\TransactionType::ALLOWANCE => 'Allocation',
                };
                ?>
                <div class="transaction-item <?= $typeClass ?>">
                    <div>
                        <strong><?= $typeLabel ?></strong>
                        <?php if ($transaction->getDescription()): ?>
                            <br>
                            <small><?= htmlspecialchars($transaction->getDescription()) ?></small>
                        <?php endif; ?>
                        <br>
                        <small><?= $transaction->getCreatedAt()->format('d/m/Y H:i') ?></small>
                    </div>
                    <div class="transaction-amount <?= $amountClass ?>">
                        <?= $amountPrefix ?><?= number_format($transaction->getAmount(), 2) ?> €
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>