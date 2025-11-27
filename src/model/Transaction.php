<?php

namespace App\Model;

use DateTime;
use InvalidArgumentException;

/**
 * Modèle Transaction
 *
 * Représente une transaction dans l'historique unifié
 * (dépôt, dépense ou allocation)
 */
class Transaction
{
    private string $id;
    private string $accountId;
    private TransactionType $type;
    private float $amount;
    private ?string $description;
    private ?string $createdBy;
    private DateTime $createdAt;

    /**
     * Constructeur
     *
     * @param string $accountId ID du compte concerné
     * @param TransactionType $type Type de transaction
     * @param float $amount Montant de la transaction (doit être > 0)
     * @param string|null $description Description optionnelle
     * @param string|null $createdBy ID du parent (pour dépôts) ou null (pour dépenses ado)
     * @throws InvalidArgumentException Si le montant est <= 0
     */
    public function __construct(
        string $accountId,
        TransactionType $type,
        float $amount,
        ?string $description = null,
        ?string $createdBy = null
    ) {
        // Validation du montant
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif");
        }

        // Validation de l'accountId
        if (empty($accountId)) {
            throw new InvalidArgumentException("Le compte ID ne peut pas être vide");
        }

        $this->id = uniqid('transaction_', true);
        $this->accountId = $accountId;
        $this->type = $type;
        $this->amount = $amount;
        $this->description = $description;
        $this->createdBy = $createdBy;
        $this->createdAt = new DateTime();
    }

    /**
     * Récupère l'ID de la transaction
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Récupère l'ID du compte
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * Récupère le type de transaction
     */
    public function getType(): TransactionType
    {
        return $this->type;
    }

    /**
     * Récupère le montant de la transaction
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Récupère la description de la transaction
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Récupère l'ID du créateur de la transaction
     */
    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    /**
     * Récupère la date de création de la transaction
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}