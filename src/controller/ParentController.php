<?php

namespace App\Controllers;

use App\Model\AccountParent;
use InvalidArgumentException;

class ParentController {

    /**
     * Affiche le dashboard d'un parent
     */
    public function dashboard() {
        session_start();

        // Récupérer l'ID du parent depuis l'URL
        $parentId = $_GET['id'] ?? null;

        if (!$parentId) {
            echo "Erreur : ID parent manquant";
            return;
        }

        // Récupérer le parent depuis la session
        $parents = $_SESSION['parents'] ?? [];

        if (!isset($parents[$parentId])) {
            echo "Erreur : Parent non trouvé";
            return;
        }

        $parent = $parents[$parentId];

        // Récupérer les teenagers de ce parent
        $allTeenagers = $_SESSION['teenagers'] ?? [];
        $teenagers = array_filter($allTeenagers, function($teen) use ($parentId) {
            return $teen->getParentId() === $parentId;
        });

        // Afficher la vue dashboard
        require_once __DIR__ . '/../view/parent/dashboard.php';
    }

    /**
     * Affiche le formulaire de création d'un parent
     */
    public function createView() {
        require_once __DIR__ . '/../view/parent/create.php';
    }

    /**
     * Traite la soumission du formulaire de création
     */
    public function createForm() {
        // Récupérer les données du formulaire
        $name = $_POST['name'] ?? '';

        try {
            // Créer un nouveau parent avec le modèle AccountParent
            $parent = new AccountParent($name);

            // Sauvegarder en session dans un tableau
            session_start();
            if (!isset($_SESSION['parents'])) {
                $_SESSION['parents'] = [];
            }
            $_SESSION['parents'][$parent->getId()] = $parent;

            // Passer les données à la vue de succès
            $success = true;
            $message = "Le compte parent a été créé avec succès !";
            require_once __DIR__ . '/../view/parent/success.php';
        } catch (InvalidArgumentException $e) {
            // Passer l'erreur à la vue d'erreur
            $error = $e->getMessage();
            require_once __DIR__ . '/../view/parent/error.php';
        }
    }
}
