<?php

namespace App\Controllers;

use App\Model\Transaction;
use App\Model\TransactionType;

class AccountController {
    public function view() {
        session_start();

        // 1. Récupérer l'ID du COMPTE depuis l'URL
        $accountId = $_GET['id'] ?? null;

        // 2. Vérifier que l'accountId existe
        if (!$accountId) {
            echo "ID du compte manquant";
            return;
        }

        // 3. Récupérer le compte depuis la session
        $accounts = $_SESSION['accounts'] ?? [];
        if (!isset($accounts[$accountId])) {
            echo "Compte non trouvé";
            return;
        }
        $account = $accounts[$accountId];

        // 4. Récupérer le teenager associé via teenagerId
        $teenagerId = $account->getTeenagerId();
        $teenagers = $_SESSION['teenagers'] ?? [];
        if (!isset($teenagers[$teenagerId])) {
            echo "Adolescent non trouvé";
            return;
        }
        $teenager = $teenagers[$teenagerId];

        // 5. Récupérer le parent
        $parentId = $teenager->getParentId();
        $parents = $_SESSION['parents'] ?? [];
        $parent = $parents[$parentId] ?? null;

        // 6. Récupérer les transactions pour ce compte
        $allTransactions = $_SESSION['transactions'] ?? [];
        $accountTransactions = array_filter($allTransactions, function($t) use ($accountId) {
            return $t->getAccountId() === $accountId;
        });

        // Trier par date décroissante
        usort($accountTransactions, function($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        // 7. Affichage de la vue
        require_once __DIR__ . '/../view/account/view.php';
    }

    public function deposit() {
        session_start();

        // Récupérer les données du formulaire
        $accountId = $_POST['accountId'] ?? null;
        $amount = $_POST['amount'] ?? null;

        if (!$accountId || !$amount) {
            $error = "Données manquantes";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Valider le montant
        $amount = (float)$amount;
        if ($amount <= 0) {
            $error = "Le montant doit être positif";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Récupérer le compte
        $accounts = $_SESSION['accounts'] ?? [];
        if (!isset($accounts[$accountId])) {
            $error = "Compte non trouvé";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        $account = $accounts[$accountId];

        // Ajouter le montant au solde
        $account->addToBalance($amount);

        // Créer une transaction
        $transaction = new Transaction(
            $accountId,
            TransactionType::DEPOSIT,
            $amount,
            "Dépôt",
            $_SESSION['currentParentId'] ?? 'system'
        );

        // Sauvegarder
        if (!isset($_SESSION['transactions'])) {
            $_SESSION['transactions'] = [];
        }
        $_SESSION['transactions'][$transaction->getId()] = $transaction;
        $_SESSION['accounts'][$accountId] = $account;

        // Rediriger vers la vue du compte
        header("Location: /account/view?id=" . urlencode($accountId));
        exit;
    }

    public function expense() {
        session_start();

        // Récupérer les données du formulaire
        $accountId = $_POST['accountId'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $description = $_POST['description'] ?? 'Dépense';

        if (!$accountId || !$amount) {
            $error = "Données manquantes";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Valider le montant
        $amount = (float)$amount;
        if ($amount <= 0) {
            $error = "Le montant doit être positif";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Récupérer le compte
        $accounts = $_SESSION['accounts'] ?? [];
        if (!isset($accounts[$accountId])) {
            $error = "Compte non trouvé";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        $account = $accounts[$accountId];
        $teenagerId = $account->getTeenagerId();

        // Tenter de soustraire le montant
        $success = $account->subtractFromBalance($amount);

        if (!$success) {
            $error = "Solde insuffisant (solde actuel: " . number_format($account->getBalance(), 2) . " €)";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Créer une transaction
        $transaction = new Transaction(
            $accountId,
            TransactionType::EXPENSE,
            $amount,
            $description,
            $teenagerId
        );

        // Sauvegarder
        if (!isset($_SESSION['transactions'])) {
            $_SESSION['transactions'] = [];
        }
        $_SESSION['transactions'][$transaction->getId()] = $transaction;
        $_SESSION['accounts'][$accountId] = $account;

        // Rediriger vers la vue du compte
        header("Location: /account/view?id=" . urlencode($accountId));
        exit;
    }

    public function setAllowance() {
        session_start();

        // Récupérer les données du formulaire
        $accountId = $_POST['accountId'] ?? null;
        $weeklyAllowance = $_POST['weeklyAllowance'] ?? null;

        if (!$accountId || $weeklyAllowance === null) {
            $error = "Données manquantes";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Valider le montant
        $weeklyAllowance = (float)$weeklyAllowance;
        if ($weeklyAllowance < 0) {
            $error = "Le montant de l'allocation ne peut pas être négatif";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        // Récupérer le compte
        $accounts = $_SESSION['accounts'] ?? [];
        if (!isset($accounts[$accountId])) {
            $error = "Compte non trouvé";
            require_once __DIR__ . '/../view/account/error.php';
            return;
        }

        $account = $accounts[$accountId];

        // Configurer l'allocation
        $account->setWeeklyAllowance($weeklyAllowance);

        // Sauvegarder
        $_SESSION['accounts'][$accountId] = $account;

        // Rediriger vers la vue du compte
        header("Location: /account/view?id=" . urlencode($accountId));
        exit;
    }
}
