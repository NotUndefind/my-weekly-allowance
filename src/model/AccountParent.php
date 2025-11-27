<?php

namespace App\Model;

use InvalidArgumentException;

/**
 * Modèle AccountParent
 *
 * Représente un parent qui gère les comptes de ses adolescents
 */
class AccountParent
{
    private string $id;
    private string $name;
    private string $email;

    /**
     * Constructeur
     *
     * @param string $name Nom du parent (minimum 3 caractères)
     * @param string $email Email valide du parent
     * @throws InvalidArgumentException Si le nom ou l'email est invalide
     */
    public function __construct(string $name, string $email)
    {
        // Validation du nom
        if (strlen($name) < 3) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 3 caractères");
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("L'email est invalide");
        }

        $this->id = uniqid('parent_', true);
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Récupère l'ID du parent
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Récupère le nom du parent
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Récupère l'email du parent
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
