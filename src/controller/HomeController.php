<?php

namespace App\Controllers;

class HomeController {

    public function index() {
        // Démarrer la session
        session_start();

        // Récupérer tous les parents de la session
        $parentsData = $_SESSION['parents'] ?? [];

        // Si $parentsData est un objet (ancien format), le convertir en tableau
        if (is_object($parentsData)) {
            $parentsData = [$parentsData];
        }

        // S'assurer que c'est toujours un tableau
        $parents = is_array($parentsData) ? $parentsData : [];

        // Afficher la vue home avec la liste des parents
        require_once __DIR__ . '/../view/home.php';
    }
}
