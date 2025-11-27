<?php

namespace Tests\Model;

use App\Model\Transaction;
use App\Model\TransactionType;
use App\Model\Account;
use App\Model\AccountTeenager;
use App\Model\AccountParent;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * Tests unitaires pour le modèle Transaction
 *
 * Ce fichier teste :
 * - Validation du montant (doit être > 0)
 * - Validation de l'accountId
 * - Types de transaction (DEPOSIT, EXPENSE, ALLOWANCE)
 * - Description optionnelle
 * - CreatedBy optionnel
 * - Génération automatique de createdAt
 */
class TransactionTest extends TestCase
{
    private string $validAccountId;
    private string $validParentId;

    protected function setUp(): void
    {
        $parent = new AccountParent("John Doe", "john@example.com");
        $teenager = new AccountTeenager("Jules", 15, $parent->getId());
        $account = new Account($teenager->getId(), 100);

        $this->validAccountId = $account->getId();
        $this->validParentId = $parent->getId();
    }

    // ========================================
    // Tests pour la création de transaction DEPOSIT
    // ========================================

    /**
     * Test : Une transaction DEPOSIT peut être créée avec un montant valide
     */
    public function testCreateDepositTransactionWithValidAmount(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            50.0,
            "Argent de poche",
            $this->validParentId
        );

        $this->assertEquals($this->validAccountId, $transaction->getAccountId());
        $this->assertEquals(TransactionType::DEPOSIT, $transaction->getType());
        $this->assertEquals(50.0, $transaction->getAmount());
        $this->assertEquals("Argent de poche", $transaction->getDescription());
        $this->assertEquals($this->validParentId, $transaction->getCreatedBy());
    }

    /**
     * Test : Une transaction DEPOSIT avec un grand montant
     */
    public function testCreateDepositTransactionWithLargeAmount(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            1000.0,
            "Cadeau d'anniversaire"
        );

        $this->assertEquals(1000.0, $transaction->getAmount());
    }

    /**
     * Test : Une transaction DEPOSIT avec un montant décimal
     */
    public function testCreateDepositTransactionWithDecimalAmount(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            25.50,
            "Monnaie"
        );

        $this->assertEquals(25.50, $transaction->getAmount());
        $this->assertIsFloat($transaction->getAmount());
    }

    // ========================================
    // Tests pour la création de transaction EXPENSE
    // ========================================

    /**
     * Test : Une transaction EXPENSE peut être créée
     */
    public function testCreateExpenseTransactionWithValidAmount(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            30.0,
            "Cinéma",
            null
        );

        $this->assertEquals(TransactionType::EXPENSE, $transaction->getType());
        $this->assertEquals(30.0, $transaction->getAmount());
        $this->assertEquals("Cinéma", $transaction->getDescription());
        $this->assertNull($transaction->getCreatedBy());
    }

    /**
     * Test : Une transaction EXPENSE sans description
     */
    public function testCreateExpenseTransactionWithoutDescription(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            15.0,
            null,
            null
        );

        $this->assertEquals(TransactionType::EXPENSE, $transaction->getType());
        $this->assertNull($transaction->getDescription());
    }

    /**
     * Test : Une transaction EXPENSE avec description vide est acceptée
     */
    public function testCreateExpenseTransactionWithEmptyDescription(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            20.0,
            "",
            null
        );

        $this->assertEquals("", $transaction->getDescription());
    }

    // ========================================
    // Tests pour la création de transaction ALLOWANCE
    // ========================================

    /**
     * Test : Une transaction ALLOWANCE peut être créée
     */
    public function testCreateAllowanceTransactionWithValidAmount(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::ALLOWANCE,
            20.0,
            "Allocation hebdomadaire",
            null
        );

        $this->assertEquals(TransactionType::ALLOWANCE, $transaction->getType());
        $this->assertEquals(20.0, $transaction->getAmount());
        $this->assertEquals("Allocation hebdomadaire", $transaction->getDescription());
    }

    /**
     * Test : Une transaction ALLOWANCE sans createdBy
     */
    public function testCreateAllowanceTransactionWithoutCreatedBy(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::ALLOWANCE,
            25.0,
            "Allocation automatique"
        );

        $this->assertNull($transaction->getCreatedBy());
    }

    // ========================================
    // Tests pour la validation du MONTANT
    // ========================================

    /**
     * Test : Une transaction avec un montant de 0 est refusée
     */
    public function testCreateTransactionWithZeroAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant doit être positif");

        new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            0,
            "Test"
        );
    }

    /**
     * Test : Une transaction avec un montant négatif est refusée
     */
    public function testCreateTransactionWithNegativeAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant doit être positif");

        new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            -50.0,
            "Test"
        );
    }

    /**
     * Test : Une transaction avec un petit montant négatif est refusée
     */
    public function testCreateTransactionWithSmallNegativeAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant doit être positif");

        new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            -0.01,
            "Test"
        );
    }

    // ========================================
    // Tests pour la validation de l'ACCOUNT ID
    // ========================================

    /**
     * Test : Une transaction stocke correctement l'accountId
     */
    public function testTransactionStoresAccountId(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            100.0
        );

        $this->assertEquals($this->validAccountId, $transaction->getAccountId());
        $this->assertIsString($transaction->getAccountId());
    }

    /**
     * Test : Une transaction ne peut pas être créée avec un accountId vide
     */
    public function testCreateTransactionWithEmptyAccountIdThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le compte ID ne peut pas être vide");

        new Transaction(
            "",
            TransactionType::DEPOSIT,
            100.0
        );
    }

    // ========================================
    // Tests pour les métadonnées
    // ========================================

    /**
     * Test : Une transaction a un ID unique
     */
    public function testTransactionHasUniqueId(): void
    {
        $transaction1 = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            50.0
        );

        $transaction2 = new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            30.0
        );

        $this->assertNotEquals($transaction1->getId(), $transaction2->getId());
        $this->assertIsString($transaction1->getId());
        $this->assertIsString($transaction2->getId());
    }

    /**
     * Test : L'ID de la transaction commence par 'transaction_'
     */
    public function testTransactionIdStartsWithPrefix(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            100.0
        );

        $this->assertStringStartsWith('transaction_', $transaction->getId());
    }

    /**
     * Test : Une transaction a une date de création automatique
     */
    public function testTransactionHasCreatedAtDate(): void
    {
        $beforeCreation = new \DateTime();

        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            50.0
        );

        $afterCreation = new \DateTime();

        $this->assertInstanceOf(\DateTime::class, $transaction->getCreatedAt());
        $this->assertGreaterThanOrEqual($beforeCreation, $transaction->getCreatedAt());
        $this->assertLessThanOrEqual($afterCreation, $transaction->getCreatedAt());
    }

    // ========================================
    // Tests pour les types de transaction (enum)
    // ========================================

    /**
     * Test : Le type DEPOSIT est correctement stocké
     */
    public function testTransactionTypeDeposit(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            50.0
        );

        $this->assertEquals(TransactionType::DEPOSIT, $transaction->getType());
        $this->assertEquals('deposit', $transaction->getType()->value);
    }

    /**
     * Test : Le type EXPENSE est correctement stocké
     */
    public function testTransactionTypeExpense(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            30.0
        );

        $this->assertEquals(TransactionType::EXPENSE, $transaction->getType());
        $this->assertEquals('expense', $transaction->getType()->value);
    }

    /**
     * Test : Le type ALLOWANCE est correctement stocké
     */
    public function testTransactionTypeAllowance(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::ALLOWANCE,
            20.0
        );

        $this->assertEquals(TransactionType::ALLOWANCE, $transaction->getType());
        $this->assertEquals('allowance', $transaction->getType()->value);
    }

    // ========================================
    // Tests combinés
    // ========================================

    /**
     * Test : Toutes les propriétés sont correctement stockées
     */
    public function testTransactionStoresAllProperties(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            100.0,
            "Cadeau",
            $this->validParentId
        );

        $this->assertIsString($transaction->getId());
        $this->assertEquals($this->validAccountId, $transaction->getAccountId());
        $this->assertEquals(TransactionType::DEPOSIT, $transaction->getType());
        $this->assertEquals(100.0, $transaction->getAmount());
        $this->assertEquals("Cadeau", $transaction->getDescription());
        $this->assertEquals($this->validParentId, $transaction->getCreatedBy());
        $this->assertInstanceOf(\DateTime::class, $transaction->getCreatedAt());
    }

    /**
     * Test : Transaction minimale (sans description ni createdBy)
     */
    public function testTransactionWithMinimalData(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::EXPENSE,
            10.0
        );

        $this->assertEquals($this->validAccountId, $transaction->getAccountId());
        $this->assertEquals(TransactionType::EXPENSE, $transaction->getType());
        $this->assertEquals(10.0, $transaction->getAmount());
        $this->assertNull($transaction->getDescription());
        $this->assertNull($transaction->getCreatedBy());
    }

    /**
     * Test : Transaction complète avec tous les champs
     */
    public function testTransactionWithAllFields(): void
    {
        $transaction = new Transaction(
            $this->validAccountId,
            TransactionType::DEPOSIT,
            250.50,
            "Dépôt mensuel",
            $this->validParentId
        );

        $this->assertIsString($transaction->getId());
        $this->assertNotEmpty($transaction->getId());
        $this->assertEquals($this->validAccountId, $transaction->getAccountId());
        $this->assertEquals(TransactionType::DEPOSIT, $transaction->getType());
        $this->assertEquals(250.50, $transaction->getAmount());
        $this->assertEquals("Dépôt mensuel", $transaction->getDescription());
        $this->assertEquals($this->validParentId, $transaction->getCreatedBy());
        $this->assertInstanceOf(\DateTime::class, $transaction->getCreatedAt());
    }
}
