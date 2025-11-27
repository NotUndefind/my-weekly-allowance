<?php

namespace App\Model;

use DateTime;
use InvalidArgumentException;

/**
 * Modèle Account
 *
 * Représente le compte (porte-monnaie) d'un adolescent
 * avec sa balance, allocation hebdomadaire et dates
 */
class Account
{
    private string $id;
    private string $teenagerId;
    private float $balance;
    private ?float $weeklyAllowance;
    private ?DateTime $lastAllowanceDate;
    private DateTime $createdAt;

    /**
     * Constructeur
     *
     * @param string $teenagerId ID de l'adolescent
     * @param float $initialBalance Balance initiale (doit être >= 0)
     * @param float|null $weeklyAllowance Allocation hebdomadaire (optionnel)
     * @throws InvalidArgumentException Si la balance initiale est négative
     */
    public function __construct(
        string $teenagerId,
        float $initialBalance = 0,
        ?float $weeklyAllowance = null
    ) {
        // Validation de la balance initiale
        if ($initialBalance < 0) {
            throw new InvalidArgumentException("Le dépôt initial ne peut pas être négatif");
        }

        // Validation du teenagerId
        if (empty($teenagerId)) {
            throw new InvalidArgumentException("Le teenager ID ne peut pas être vide");
        }

        // Validation de l'allocation hebdomadaire (si fournie)
        if ($weeklyAllowance !== null && $weeklyAllowance <= 0) {
            throw new InvalidArgumentException("L'allocation hebdomadaire doit être positive");
        }

        $this->id = uniqid('account_', true);
        $this->teenagerId = $teenagerId;
        $this->balance = $initialBalance;
        $this->weeklyAllowance = $weeklyAllowance;
        $this->lastAllowanceDate = null;
        $this->createdAt = new DateTime();
    }

    /**
     * Récupère l'ID du compte
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Récupère l'ID de l'adolescent
     */
    public function getTeenagerId(): string
    {
        return $this->teenagerId;
    }

    /**
     * Récupère la balance actuelle
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Récupère l'allocation hebdomadaire
     */
    public function getWeeklyAllowance(): ?float
    {
        return $this->weeklyAllowance;
    }

    /**
     * Récupère la date de la dernière allocation
     */
    public function getLastAllowanceDate(): ?DateTime
    {
        return $this->lastAllowanceDate;
    }

    /**
     * Récupère la date de création du compte
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Définit l'allocation hebdomadaire
     *
     * @param float $amount Montant de l'allocation (doit être > 0)
     * @throws InvalidArgumentException Si le montant est <= 0
     */
    public function setWeeklyAllowance(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("L'allocation hebdomadaire doit être positive");
        }

        $this->weeklyAllowance = $amount;
    }

    /**
     * Définit la date de la dernière allocation (pour les tests)
     *
     * @param DateTime $date Date à définir
     */
    public function setLastAllowanceDate(DateTime $date): void
    {
        $this->lastAllowanceDate = $date;
    }

    /**
     * Dépose de l'argent sur le compte
     *
     * @param float $amount Montant à déposer (doit être > 0)
     * @return bool True si le dépôt est réussi
     * @throws InvalidArgumentException Si le montant est <= 0
     */
    public function deposit(float $amount): bool
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant du dépôt doit être positif");
        }

        $this->balance += $amount;
        return true;
    }

    /**
     * Enregistre une dépense
     *
     * @param float $amount Montant de la dépense (doit être > 0)
     * @param string|null $description Description de la dépense (optionnel)
     * @return bool True si la dépense est acceptée, False si le solde est insuffisant
     * @throws InvalidArgumentException Si le montant est <= 0
     */
    public function recordExpense(float $amount, ?string $description = null): bool
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant de la dépense doit être positif");
        }

        // Vérifier si le solde est suffisant
        if ($amount > $this->balance) {
            return false;
        }

        $this->balance -= $amount;
        return true;
    }

    /**
     * Vérifie si l'allocation hebdomadaire doit être appliquée
     *
     * @return bool True si 7 jours ou plus se sont écoulés depuis la dernière allocation
     */
    public function shouldApplyAllowance(): bool
    {
        // Pas d'allocation configurée
        if ($this->weeklyAllowance === null) {
            return false;
        }

        // Première allocation
        if ($this->lastAllowanceDate === null) {
            return true;
        }

        // Vérifier si 7 jours se sont écoulés
        $now = new DateTime();
        $interval = $this->lastAllowanceDate->diff($now);
        $daysPassed = $interval->days;

        return $daysPassed >= 7;
    }

    /**
     * Applique l'allocation hebdomadaire
     *
     * @return bool True si l'allocation a été appliquée
     * @throws InvalidArgumentException Si aucune allocation n'est configurée
     */
    public function applyWeeklyAllowance(): bool
    {
        if ($this->weeklyAllowance === null) {
            throw new InvalidArgumentException("Aucune allocation hebdomadaire n'est configurée");
        }

        $this->balance += $this->weeklyAllowance;
        $this->lastAllowanceDate = new DateTime();

        return true;
    }
}