<?php

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
use App\Model\Account;
use App\Model\AccountTeenager;
use App\Model\AccountParent;

/**
 * Tests unitaires pour le modèle Account
 *
 * Ce fichier teste UNIQUEMENT les propriétés du modèle Account :
 * - Validation du teenagerId
 * - Gestion de la balance (positive, zéro, négative)
 * - Allocation hebdomadaire
 */
class AccountTest extends TestCase {
    private string $validTeenagerId;

    protected function setUp(): void {
        // Créer un parent et un teenager pour les tests
        $parent = new AccountParent("John Doe", "john.doe@example.com");
        $teenager = new AccountTeenager("Jules", 15, $parent->getId());
        $this->validTeenagerId = $teenager->getId();
    }

    // ========================================
    // Tests pour la création de compte
    // ========================================

    /**
     * Test : Un compte peut être créé avec un teenagerId valide et une balance positive
     */
    public function testCreateAccountWithValidTeenagerIdAndPositiveBalance(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->assertEquals($this->validTeenagerId, $account->getTeenagerId());
        $this->assertEquals(100, $account->getBalance());
        $this->assertIsFloat($account->getBalance());
    }

    /**
     * Test : Un compte peut être créé avec une grande balance
     */
    public function testCreateAccountWithLargeBalance(): void {
        $account = new Account($this->validTeenagerId, 10000);

        $this->assertEquals(10000, $account->getBalance());
        $this->assertGreaterThan(0, $account->getBalance());
    }

    /**
     * Test : Un compte peut être créé avec une petite balance positive
     */
    public function testCreateAccountWithSmallPositiveBalance(): void {
        $account = new Account($this->validTeenagerId, 1);

        $this->assertEquals(1, $account->getBalance());
        $this->assertGreaterThan(0, $account->getBalance());
    }

    /**
     * Test : Un compte peut être créé avec une balance décimale
     */
    public function testCreateAccountWithDecimalBalance(): void {
        $account = new Account($this->validTeenagerId, 25.50);

        $this->assertEquals(25.50, $account->getBalance());
        $this->assertIsFloat($account->getBalance());
    }

    // ========================================
    // Tests pour la BALANCE À ZÉRO
    // ========================================

    /**
     * Test : Un compte peut être créé avec une balance de zéro
     */
    public function testCreateAccountWithZeroBalance(): void {
        $account = new Account($this->validTeenagerId, 0);

        $this->assertEquals(0, $account->getBalance());
        $this->assertSame(0.0, $account->getBalance());
    }

    /**
     * Test : Un compte peut être créé sans spécifier de balance (défaut = 0)
     */
    public function testCreateAccountWithDefaultBalance(): void {
        $account = new Account($this->validTeenagerId);

        $this->assertEquals(0, $account->getBalance());
    }

    /**
     * Test : Une balance de zéro est bien un nombre
     */
    public function testZeroBalanceIsNumeric(): void {
        $account = new Account($this->validTeenagerId, 0);

        $this->assertIsNumeric($account->getBalance());
        $this->assertEquals(0, $account->getBalance());
    }

    // ========================================
    // Tests pour la BALANCE NÉGATIVE (refusée)
    // ========================================

    /**
     * Test : Un compte ne peut pas avoir une balance négative
     */
    public function testCreateAccountWithNegativeBalanceThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le dépôt initial ne peut pas être négatif");

        new Account($this->validTeenagerId, -50);
    }

    /**
     * Test : Un compte ne peut pas avoir une petite balance négative
     */
    public function testCreateAccountWithSmallNegativeBalanceThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le dépôt initial ne peut pas être négatif");

        new Account($this->validTeenagerId, -1);
    }

    /**
     * Test : Un compte ne peut pas avoir une grande balance négative
     */
    public function testCreateAccountWithLargeNegativeBalanceThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le dépôt initial ne peut pas être négatif");

        new Account($this->validTeenagerId, -10000);
    }

    /**
     * Test : Un compte ne peut pas avoir une balance décimale négative
     */
    public function testCreateAccountWithDecimalNegativeBalanceThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le dépôt initial ne peut pas être négatif");

        new Account($this->validTeenagerId, -25.50);
    }

    // ========================================
    // Tests pour le teenagerId
    // ========================================

    /**
     * Test : Un compte stocke correctement le teenagerId
     */
    public function testAccountStoresTeenagerId(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->assertEquals($this->validTeenagerId, $account->getTeenagerId());
        $this->assertIsString($account->getTeenagerId());
    }

    /**
     * Test : Un compte ne peut pas être créé avec un teenagerId vide
     */
    public function testCreateAccountWithEmptyTeenagerIdThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le teenager ID ne peut pas être vide");

        new Account("", 100);
    }

    // ========================================
    // Tests pour les métadonnées du compte
    // ========================================

    /**
     * Test : Un compte a un ID unique
     */
    public function testAccountHasUniqueId(): void {
        $account1 = new Account($this->validTeenagerId, 100);
        $account2 = new Account($this->validTeenagerId, 200);

        $this->assertNotEquals($account1->getId(), $account2->getId());
        $this->assertIsString($account1->getId());
        $this->assertIsString($account2->getId());
    }

    /**
     * Test : Un compte a une date de création
     */
    public function testAccountHasCreatedAtDate(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->assertInstanceOf(\DateTime::class, $account->getCreatedAt());
        $this->assertLessThanOrEqual(new \DateTime(), $account->getCreatedAt());
    }

    // ========================================
    // Tests pour l'allocation hebdomadaire (propriété)
    // ========================================

    /**
     * Test : Un compte peut être créé avec une allocation hebdomadaire
     */
    public function testCreateAccountWithWeeklyAllowance(): void {
        $account = new Account($this->validTeenagerId, 100, 20);

        $this->assertEquals(20, $account->getWeeklyAllowance());
        $this->assertIsFloat($account->getWeeklyAllowance());
    }

    /**
     * Test : Un compte peut être créé sans allocation hebdomadaire
     */
    public function testCreateAccountWithoutWeeklyAllowance(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->assertNull($account->getWeeklyAllowance());
    }

    /**
     * Test : Un compte ne peut pas être créé avec une allocation hebdomadaire nulle
     */
    public function testCreateAccountWithZeroWeeklyAllowanceThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("L'allocation hebdomadaire doit être positive");

        new Account($this->validTeenagerId, 100, 0);
    }

    /**
     * Test : Un compte ne peut pas être créé avec une allocation hebdomadaire négative
     */
    public function testCreateAccountWithNegativeWeeklyAllowanceThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("L'allocation hebdomadaire doit être positive");

        new Account($this->validTeenagerId, 100, -10);
    }

    /**
     * Test : L'allocation hebdomadaire peut être définie après la création
     */
    public function testSetWeeklyAllowanceAfterCreation(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(30);

        $this->assertEquals(30, $account->getWeeklyAllowance());
    }

    /**
     * Test : Définir une allocation hebdomadaire nulle lève une exception
     */
    public function testSetZeroWeeklyAllowanceThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("L'allocation hebdomadaire doit être positive");

        $account->setWeeklyAllowance(0);
    }

    /**
     * Test : Définir une allocation hebdomadaire négative lève une exception
     */
    public function testSetNegativeWeeklyAllowanceThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("L'allocation hebdomadaire doit être positive");

        $account->setWeeklyAllowance(-20);
    }

    // ========================================
    // Tests pour la date de dernière allocation
    // ========================================

    /**
     * Test : La date de dernière allocation est null par défaut
     */
    public function testLastAllowanceDateIsNullByDefault(): void {
        $account = new Account($this->validTeenagerId, 100, 20);

        $this->assertNull($account->getLastAllowanceDate());
    }

    /**
     * Test : La date de dernière allocation peut être définie
     */
    public function testSetLastAllowanceDate(): void {
        $account = new Account($this->validTeenagerId, 100, 20);
        $date = new \DateTime('-5 days');
        $account->setLastAllowanceDate($date);

        $this->assertEquals($date, $account->getLastAllowanceDate());
        $this->assertInstanceOf(\DateTime::class, $account->getLastAllowanceDate());
    }

    // ========================================
    // Tests pour shouldApplyAllowance()
    // ========================================

    /**
     * Test : shouldApplyAllowance retourne false quand aucune allocation n'est configurée
     */
    public function testShouldNotApplyAllowanceWhenNoAllowanceConfigured(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->assertFalse($account->shouldApplyAllowance());
    }

    /**
     * Test : shouldApplyAllowance retourne true pour la première allocation
     */
    public function testShouldApplyAllowanceWhenNeverAppliedBefore(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);

        $this->assertTrue($account->shouldApplyAllowance());
        $this->assertNull($account->getLastAllowanceDate());
    }

    /**
     * Test : shouldApplyAllowance retourne true après exactement 7 jours
     */
    public function testShouldApplyAllowanceAfterExactly7Days(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);
        $account->setLastAllowanceDate(new \DateTime('-7 days'));

        $this->assertTrue($account->shouldApplyAllowance());
    }

    /**
     * Test : shouldApplyAllowance retourne true après plus de 7 jours (10 jours)
     */
    public function testShouldApplyAllowanceAfterMoreThan7Days(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);
        $account->setLastAllowanceDate(new \DateTime('-10 days'));

        $this->assertTrue($account->shouldApplyAllowance());
    }

    /**
     * Test : shouldApplyAllowance retourne false avant 7 jours (5 jours)
     */
    public function testShouldNotApplyAllowanceBeforeSevenDays(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);
        $account->setLastAllowanceDate(new \DateTime('-5 days'));

        $this->assertFalse($account->shouldApplyAllowance());
    }

    /**
     * Test : shouldApplyAllowance retourne false après 6 jours (limite inférieure)
     */
    public function testShouldNotApplyAllowanceAfter6Days(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);
        $account->setLastAllowanceDate(new \DateTime('-6 days'));

        $this->assertFalse($account->shouldApplyAllowance());
    }

    /**
     * Test : shouldApplyAllowance retourne false si appliquée aujourd'hui
     */
    public function testShouldNotApplyAllowanceOnSameDay(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);
        $account->setLastAllowanceDate(new \DateTime());

        $this->assertFalse($account->shouldApplyAllowance());
    }

    /**
     * Test : shouldApplyAllowance retourne true après 8 jours (limite supérieure)
     */
    public function testShouldApplyAllowanceAfter8Days(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);
        $account->setLastAllowanceDate(new \DateTime('-8 days'));

        $this->assertTrue($account->shouldApplyAllowance());
    }

    // ========================================
    // Tests pour deposit() et recordExpense()
    // ========================================

    public function testDepositPositiveAmount(): void {
        $account = new Account($this->validTeenagerId, 100, 20);
        $account->deposit(10);

        $this->assertEquals(110, $account->getBalance());
    }

    public function testDepositNegativeAmountThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100, 20);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant du dépôt doit être positif");

        $account->deposit(-10);
    }

    /**
     * Test : Déposer un montant décimal
     */
    public function testDepositDecimalAmount(): void {
        $account = new Account($this->validTeenagerId, 100);
        $result = $account->deposit(25.50);

        $this->assertTrue($result);
        $this->assertEquals(125.50, $account->getBalance());
    }

    /**
     * Test : Déposer 0 lève une exception
     */
    public function testDepositZeroAmountThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant du dépôt doit être positif");

        $account->deposit(0);
    }

    // ========================================
    // Tests pour recordExpense()
    // ========================================

    /**
     * Test : Dépense < Solde → acceptée, solde mis à jour
     */
    public function testRecordExpenseLessThanBalance(): void {
        $account = new Account($this->validTeenagerId, 100);
        $result = $account->recordExpense(30, "Cinéma");

        $this->assertTrue($result);
        $this->assertEquals(70, $account->getBalance());
    }

    /**
     * Test : Dépense > Solde → refusée, solde inchangé
     */
    public function testRecordExpenseGreaterThanBalance(): void {
        $account = new Account($this->validTeenagerId, 50);
        $result = $account->recordExpense(100, "Console");

        $this->assertFalse($result);
        $this->assertEquals(50, $account->getBalance());
    }

    /**
     * Test : Dépense = Solde → acceptée, solde = 0
     */
    public function testRecordExpenseEqualToBalance(): void {
        $account = new Account($this->validTeenagerId, 75);
        $result = $account->recordExpense(75, "Baskets");

        $this->assertTrue($result);
        $this->assertEquals(0, $account->getBalance());
    }

    /**
     * Test : Dépense avec montant 0 → exception
     */
    public function testRecordExpenseWithZeroAmountThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant de la dépense doit être positif");

        $account->recordExpense(0, "Test");
    }

    /**
     * Test : Dépense avec montant négatif → exception
     */
    public function testRecordExpenseWithNegativeAmountThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant de la dépense doit être positif");

        $account->recordExpense(-20, "Test");
    }

    /**
     * Test : Dépense avec description null → acceptée
     */
    public function testRecordExpenseWithNullDescription(): void {
        $account = new Account($this->validTeenagerId, 100);
        $result = $account->recordExpense(25, null);

        $this->assertTrue($result);
        $this->assertEquals(75, $account->getBalance());
    }

    /**
     * Test : Dépense avec description vide → acceptée
     */
    public function testRecordExpenseWithEmptyDescription(): void {
        $account = new Account($this->validTeenagerId, 100);
        $result = $account->recordExpense(25, "");

        $this->assertTrue($result);
        $this->assertEquals(75, $account->getBalance());
    }

    /**
     * Test : Dépense avec description valide → acceptée
     */
    public function testRecordExpenseWithValidDescription(): void {
        $account = new Account($this->validTeenagerId, 100);
        $result = $account->recordExpense(25, "Livre de PHP");

        $this->assertTrue($result);
        $this->assertEquals(75, $account->getBalance());
    }

    /**
     * Test : Dépense décimale inférieure au solde
     */
    public function testRecordExpenseWithDecimalAmount(): void {
        $account = new Account($this->validTeenagerId, 100);
        $result = $account->recordExpense(15.75, "Snack");

        $this->assertTrue($result);
        $this->assertEquals(84.25, $account->getBalance());
    }

    /**
     * Test : Dépense juste supérieure au solde → refusée
     */
    public function testRecordExpenseJustAboveBalance(): void {
        $account = new Account($this->validTeenagerId, 50);
        $result = $account->recordExpense(50.01, "Test");

        $this->assertFalse($result);
        $this->assertEquals(50, $account->getBalance());
    }

    // ========================================
    // Tests pour applyWeeklyAllowance()
    // ========================================

    /**
     * Test : Appliquer l'allocation augmente le solde du montant configuré
     */
    public function testApplyWeeklyAllowanceIncreasesBalance(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);

        $result = $account->applyWeeklyAllowance();

        $this->assertTrue($result);
        $this->assertEquals(120, $account->getBalance());
    }

    /**
     * Test : Appliquer l'allocation met à jour lastAllowanceDate
     */
    public function testApplyWeeklyAllowanceUpdatesLastAllowanceDate(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(25);

        $beforeDate = new \DateTime();
        $result = $account->applyWeeklyAllowance();
        $afterDate = new \DateTime();

        $this->assertTrue($result);
        $this->assertInstanceOf(\DateTime::class, $account->getLastAllowanceDate());
        $this->assertGreaterThanOrEqual($beforeDate, $account->getLastAllowanceDate());
        $this->assertLessThanOrEqual($afterDate, $account->getLastAllowanceDate());
    }

    /**
     * Test : Appliquer l'allocation sur un solde à zéro
     */
    public function testApplyWeeklyAllowanceOnZeroBalance(): void {
        $account = new Account($this->validTeenagerId, 0);
        $account->setWeeklyAllowance(30);

        $result = $account->applyWeeklyAllowance();

        $this->assertTrue($result);
        $this->assertEquals(30, $account->getBalance());
    }

    /**
     * Test : Appliquer l'allocation sans allocation configurée → exception
     */
    public function testApplyWeeklyAllowanceWithoutConfigurationThrowsException(): void {
        $account = new Account($this->validTeenagerId, 100);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Aucune allocation hebdomadaire n'est configurée");

        $account->applyWeeklyAllowance();
    }

    /**
     * Test : Appliquer l'allocation plusieurs fois cumule les montants
     */
    public function testApplyWeeklyAllowanceMultipleTimes(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(20);

        $account->applyWeeklyAllowance();
        $this->assertEquals(120, $account->getBalance());

        $account->applyWeeklyAllowance();
        $this->assertEquals(140, $account->getBalance());
    }

    /**
     * Test : Appliquer l'allocation avec montant décimal
     */
    public function testApplyWeeklyAllowanceWithDecimalAmount(): void {
        $account = new Account($this->validTeenagerId, 100);
        $account->setWeeklyAllowance(15.50);

        $result = $account->applyWeeklyAllowance();

        $this->assertTrue($result);
        $this->assertEquals(115.50, $account->getBalance());
    }
}
