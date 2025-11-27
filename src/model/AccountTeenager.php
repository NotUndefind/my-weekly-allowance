<?php

namespace App\Model;

use InvalidArgumentException;

/**
 * Modèle AccountTeenager
 *
 * Représente un adolescent avec son nom, âge optionnel et le lien vers son parent
 */
class AccountTeenager
{
    private string $id;
    private string $name;
    private ?int $age;
    private string $parentId;

    /**
     * Constructeur
     *
     * @param string $name Nom de l'adolescent (minimum 3 caractères)
     * @param int|null $age Âge de l'adolescent (optionnel, entre 10 et 19 ans si fourni)
     * @param string $parentId ID du parent
     * @throws InvalidArgumentException Si le nom ou l'âge est invalide
     */
    public function __construct(string $name, ?int $age, string $parentId)
    {
        // Validation du nom
        if (strlen($name) < 3) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 3 caractères");
        }

        // Validation de l'âge (si fourni)
        if ($age !== null && ($age < 10 || $age > 19)) {
            throw new InvalidArgumentException("L'âge doit être entre 10 et 19 ans");
        }

        // Validation du parentId
        if (empty($parentId)) {
            throw new InvalidArgumentException("Le parent ID ne peut pas être vide");
        }

        $this->id = uniqid('teenager_', true);
        $this->name = $name;
        $this->age = $age;
        $this->parentId = $parentId;
    }

    /**
     * Récupère l'ID de l'adolescent
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Récupère le nom de l'adolescent
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Récupère l'âge de l'adolescent
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * Récupère l'ID du parent
     */
    public function getParentId(): string
    {
        return $this->parentId;
    }
}