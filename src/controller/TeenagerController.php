<?php

namespace App\Controllers;

use App\Model\AccountTeenager;
use App\Model\Account;
use InvalidArgumentException;

class TeenagerController
{
    /**
     * Affiche le formulaire de création d'un adolescent
     */
    public function createView()
    {
        // Récupérer le parentId depuis l'URL
        $parentId = $_GET['parentId'] ?? null;

        if (!$parentId) {
            echo "Erreur : Parent ID manquant";
            return;
        }

        // Vérifier que le parent existe
        session_start();
        $parents = $_SESSION['parents'] ?? [];

        if (!isset($parents[$parentId])) {
            echo "Erreur : Parent non trouvé";
            return;
        }

        $parent = $parents[$parentId];

        // Afficher le formulaire
        require_once __DIR__ . '/../view/teenagers/create.php';
    }

    /**
     * Traite la soumission du formulaire de création
     */
    public function createForm()
    {
        session_start();

        // Récupérer les données du formulaire
        $name = $_POST['name'] ?? '';
        $age = $_POST['age'] ?? null;
        $parentId = $_POST['parentId'] ?? '';

        // Convertir l'âge en int ou null
        $age = ($age !== '' && $age !== null) ? (int)$age : null;

        try {
            // Créer l'adolescent avec le modèle AccountTeenager
            $teenager = new AccountTeenager($name, $age, $parentId);

            // Créer automatiquement un compte pour cet adolescent
            $account = new Account($teenager->getId(), 0);

            // Sauvegarder en session
            if (!isset($_SESSION['teenagers'])) {
                $_SESSION['teenagers'] = [];
            }
            $_SESSION['teenagers'][$teenager->getId()] = $teenager;

            if (!isset($_SESSION['accounts'])) {
                $_SESSION['accounts'] = [];
            }
            $_SESSION['accounts'][$account->getId()] = $account;

            // Rediriger vers le dashboard du parent
            header("Location: /parent/dashboard?id=" . urlencode($parentId));
            exit;

        } catch (InvalidArgumentException $e) {
            // Afficher l'erreur
            $error = $e->getMessage();
            $parent = $_SESSION['parents'][$parentId] ?? null;
            require_once __DIR__ . '/../view/teenagers/error.php';
        }
    }

    /**
     * Connexion d'un adolescent (login)
     */
    public function login()
    {
        session_start();

        // Récupérer les données du formulaire
        $teenagerId = $_POST['teenagerId'] ?? null;

        if (!$teenagerId) {
            echo "Erreur : Veuillez sélectionner un compte";
            return;
        }

        // Vérifier que l'adolescent existe
        $teenagers = $_SESSION['teenagers'] ?? [];
        if (!isset($teenagers[$teenagerId])) {
            echo "Erreur : Compte adolescent non trouvé";
            return;
        }

        // Rediriger vers la vue de l'adolescent
        header("Location: /teenager/view?id=" . urlencode($teenagerId));
        exit;
    }

    /**
     * Affiche le dashboard de l'adolescent (son solde et ses transactions)
     */
    public function view()
    {
        session_start();

        // Récupérer l'ID de l'adolescent depuis l'URL
        $teenagerId = $_GET['id'] ?? null;

        if (!$teenagerId) {
            echo "Erreur : ID de l'adolescent manquant";
            return;
        }

        // Récupérer l'adolescent
        $teenagers = $_SESSION['teenagers'] ?? [];
        if (!isset($teenagers[$teenagerId])) {
            echo "Erreur : Adolescent non trouvé";
            return;
        }
        $teenager = $teenagers[$teenagerId];

        // Récupérer le compte de l'adolescent
        $accounts = $_SESSION['accounts'] ?? [];
        $account = null;
        foreach ($accounts as $acc) {
            if ($acc->getTeenagerId() === $teenagerId) {
                $account = $acc;
                break;
            }
        }

        if (!$account) {
            echo "Erreur : Compte non trouvé pour cet adolescent";
            return;
        }

        // Récupérer les transactions du compte
        $allTransactions = $_SESSION['transactions'] ?? [];
        $transactions = array_filter($allTransactions, function($t) use ($account) {
            return $t->getAccountId() === $account->getId();
        });

        // Trier par date décroissante
        usort($transactions, function($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        // Afficher la vue
        require_once __DIR__ . '/../view/teenagers/view.php';
    }
}
