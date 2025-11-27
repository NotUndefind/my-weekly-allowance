<?php

namespace App\Model;

use InvalidArgumentException;

/**
 * Modèle AccountParent
 *
 * Représente un parent qui gère les comptes de ses adolescents
 */
class AccountParent {
    private string $id;
    private string $name;

    /**
     * Constructeur
     *
     * @param string $name Nom du parent (minimum 3 caractères)
     * @param string $email Email valide du parent
     * @throws InvalidArgumentException Si le nom ou l'email est invalide
     */
    public function __construct(string $name) {
        // Validation du nom
        if (strlen($name) < 3) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 3 caractères");
        }

        $this->id = uniqid('parent_', true);
        $this->name = $name;
    }

    /**
     * Récupère l'ID du parent
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Récupère le nom du parent
     */
    public function getName(): string {
        return $this->name;
    }
}
