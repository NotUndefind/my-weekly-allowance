<?php
/**
 * @var \App\Model\AccountTeenager $teenager
 * @var \App\Model\Account $account
 * @var array $transactions
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - <?= htmlspecialchars($teenager->getName()) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
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

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .balance-card {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .balance {
            font-size: 48px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }

        .allowance-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #2196F3;
        }

        .transactions-section {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .transactions-section h2 {
            margin-top: 0;
            color: #333;
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
            font-size: 20px;
        }

        .amount-positive {
            color: #28a745;
        }

        .amount-negative {
            color: #dc3545;
        }

        .no-transactions {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-deposit {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-expense {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-allowance {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>

<body>
    <a href="/" class="back-link">← Retour à l'accueil</a>

    <div class="header">
        <h1>Bonjour <?= htmlspecialchars($teenager->getName()) ?> !</h1>
        <?php if ($teenager->getAge()): ?>
            <p><?= $teenager->getAge() ?> ans</p>
        <?php endif; ?>
    </div>

    <div class="balance-card">
        <h2>Mon solde</h2>
        <div class="balance"><?= number_format($account->getBalance(), 2) ?> €</div>

        <?php if ($account->getWeeklyAllowance()): ?>
            <div class="allowance-info">
                <strong>Allocation hebdomadaire :</strong> <?= number_format($account->getWeeklyAllowance(), 2) ?> €
            </div>
        <?php endif; ?>
    </div>

    <div class="transactions-section">
        <h2>Mes dépenses et revenus</h2>

        <?php if (empty($transactions)): ?>
            <div class="no-transactions">
                <p>Aucune transaction pour le moment</p>
                <p>Demande à tes parents de déposer de l'argent !</p>
            </div>
        <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
                <?php
                $typeClass = match($transaction->getType()) {
                    \App\Model\TransactionType::DEPOSIT => 'transaction-deposit',
                    \App\Model\TransactionType::EXPENSE => 'transaction-expense',
                    \App\Model\TransactionType::ALLOWANCE => 'transaction-allowance',
                };

                $badgeClass = match($transaction->getType()) {
                    \App\Model\TransactionType::DEPOSIT => 'badge-deposit',
                    \App\Model\TransactionType::EXPENSE => 'badge-expense',
                    \App\Model\TransactionType::ALLOWANCE => 'badge-allowance',
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
                        <span class="badge <?= $badgeClass ?>"><?= $typeLabel ?></span>
                        <?php if ($transaction->getDescription()): ?>
                            <br>
                            <span style="font-size: 16px; margin-top: 5px; display: inline-block;">
                                <?= htmlspecialchars($transaction->getDescription()) ?>
                            </span>
                        <?php endif; ?>
                        <br>
                        <small style="color: #6c757d;">
                            <?= $transaction->getCreatedAt()->format('d/m/Y à H:i') ?>
                        </small>
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