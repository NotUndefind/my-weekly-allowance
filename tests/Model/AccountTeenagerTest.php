<?php

namespace Tests\Model;

use App\Model\AccountTeenager;
use App\Model\AccountParent;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * Tests unitaires pour le modèle AccountTeenager
 *
 * Ce fichier teste :
 * - Validation du nom (minimum 3 caractères)
 * - Validation de l'âge (optionnel, entre 10 et 19 ans)
 * - Validation du parentId
 * - Stockage des propriétés
 */
class AccountTeenagerTest extends TestCase {
    private string $validParentId;

    protected function setUp(): void {
        $parent = new AccountParent("John Doe", "john@example.com");
        $this->validParentId = $parent->getId();
    }

    // ========================================
    // Tests pour la validation du NOM
    // ========================================

    /**
     * Test : Un teenager peut être créé avec un nom de plus de 3 caractères
     */
    public function testCreateTeenagerWithNameGreaterThan3Characters(): void {
        $teenager = new AccountTeenager("Jules", 15, $this->validParentId);

        $this->assertEquals("Jules", $teenager->getName());
        $this->assertIsString($teenager->getName());
        $this->assertGreaterThan(3, strlen($teenager->getName()));
    }

    /**
     * Test : Un teenager peut être créé avec un nom de 4 caractères
     */
    public function testCreateTeenagerWithName4Characters(): void {
        $teenager = new AccountTeenager("Anna", 14, $this->validParentId);

        $this->assertEquals("Anna", $teenager->getName());
        $this->assertEquals(4, strlen($teenager->getName()));
    }

    /**
     * Test : Un teenager peut être créé avec exactement 3 caractères
     */
    public function testCreateTeenagerWithNameExactly3Characters(): void {
        $teenager = new AccountTeenager("Leo", 13, $this->validParentId);

        $this->assertEquals("Leo", $teenager->getName());
        $this->assertEquals(3, strlen($teenager->getName()));
    }

    /**
     * Test : Un teenager avec un nom de 2 caractères est refusé
     */
    public function testCreateTeenagerWithName2CharactersThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom doit contenir au moins 3 caractères");

        new AccountTeenager("Ab", 15, $this->validParentId);
    }

    /**
     * Test : Un teenager avec un nom de 1 caractère est refusé
     */
    public function testCreateTeenagerWithName1CharacterThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom doit contenir au moins 3 caractères");

        new AccountTeenager("A", 15, $this->validParentId);
    }

    /**
     * Test : Un teenager avec un nom vide est refusé
     */
    public function testCreateTeenagerWithEmptyNameThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom doit contenir au moins 3 caractères");

        new AccountTeenager("", 15, $this->validParentId);
    }

    /**
     * Test : Un teenager peut avoir un nom long
     */
    public function testCreateTeenagerWithLongName(): void {
        $teenager = new AccountTeenager("Jean-Baptiste", 16, $this->validParentId);

        $this->assertEquals("Jean-Baptiste", $teenager->getName());
        $this->assertGreaterThan(3, strlen($teenager->getName()));
    }

    // ========================================
    // Tests pour la validation de l'ÂGE
    // ========================================

    /**
     * Test : Un teenager peut être créé avec un âge valide (10 ans)
     */
    public function testCreateTeenagerWithAge10(): void {
        $teenager = new AccountTeenager("Marie", 10, $this->validParentId);

        $this->assertEquals(10, $teenager->getAge());
        $this->assertIsInt($teenager->getAge());
    }

    /**
     * Test : Un teenager peut être créé avec un âge valide (19 ans)
     */
    public function testCreateTeenagerWithAge19(): void {
        $teenager = new AccountTeenager("Pierre", 19, $this->validParentId);

        $this->assertEquals(19, $teenager->getAge());
    }

    /**
     * Test : Un teenager peut être créé avec un âge moyen (15 ans)
     */
    public function testCreateTeenagerWithAge15(): void {
        $teenager = new AccountTeenager("Sophie", 15, $this->validParentId);

        $this->assertEquals(15, $teenager->getAge());
        $this->assertGreaterThanOrEqual(10, $teenager->getAge());
        $this->assertLessThanOrEqual(19, $teenager->getAge());
    }

    /**
     * Test : Un teenager peut être créé sans âge (null)
     */
    public function testCreateTeenagerWithNullAge(): void {
        $teenager = new AccountTeenager("Lucas", null, $this->validParentId);

        $this->assertNull($teenager->getAge());
    }

    /**
     * Test : Un teenager avec un âge < 10 est refusé
     */
    public function testCreateTeenagerWithAge9ThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'âge doit être entre 10 et 19 ans");

        new AccountTeenager("Emma", 9, $this->validParentId);
    }

    /**
     * Test : Un teenager avec un âge > 19 est refusé
     */
    public function testCreateTeenagerWithAge20ThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'âge doit être entre 10 et 19 ans");

        new AccountTeenager("Thomas", 20, $this->validParentId);
    }

    /**
     * Test : Un teenager avec un âge de 0 est refusé
     */
    public function testCreateTeenagerWithAge0ThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'âge doit être entre 10 et 19 ans");

        new AccountTeenager("Chloe", 0, $this->validParentId);
    }

    /**
     * Test : Un teenager avec un âge négatif est refusé
     */
    public function testCreateTeenagerWithNegativeAgeThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'âge doit être entre 10 et 19 ans");

        new AccountTeenager("Noah", -5, $this->validParentId);
    }

    // ========================================
    // Tests pour la validation du PARENT ID
    // ========================================

    /**
     * Test : Un teenager stocke correctement le parentId
     */
    public function testTeenagerStoresParentId(): void {
        $teenager = new AccountTeenager("Alice", 14, $this->validParentId);

        $this->assertEquals($this->validParentId, $teenager->getParentId());
        $this->assertIsString($teenager->getParentId());
    }

    /**
     * Test : Un teenager ne peut pas être créé avec un parentId vide
     */
    public function testCreateTeenagerWithEmptyParentIdThrowsException(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le parent ID ne peut pas être vide");

        new AccountTeenager("Bob", 15, "");
    }

    // ========================================
    // Tests pour les métadonnées
    // ========================================

    /**
     * Test : Un teenager a un ID unique
     */
    public function testTeenagerHasUniqueId(): void {
        $teenager1 = new AccountTeenager("Charlie", 15, $this->validParentId);
        $teenager2 = new AccountTeenager("Diana", 16, $this->validParentId);

        $this->assertNotEquals($teenager1->getId(), $teenager2->getId());
        $this->assertIsString($teenager1->getId());
        $this->assertIsString($teenager2->getId());
    }

    /**
     * Test : L'ID du teenager commence par 'teenager_'
     */
    public function testTeenagerIdStartsWithPrefix(): void {
        $teenager = new AccountTeenager("Eric", 14, $this->validParentId);

        $this->assertStringStartsWith('teenager_', $teenager->getId());
    }

    // ========================================
    // Tests combinés
    // ========================================

    /**
     * Test : Toutes les propriétés sont correctement stockées ensemble
     */
    public function testTeenagerStoresAllProperties(): void {
        $teenager = new AccountTeenager("Fiona", 17, $this->validParentId);

        $this->assertEquals("Fiona", $teenager->getName());
        $this->assertEquals(17, $teenager->getAge());
        $this->assertEquals($this->validParentId, $teenager->getParentId());
        $this->assertIsString($teenager->getId());
    }

    /**
     * Test : Teenager avec nom minimal (3 chars) et âge minimal (10)
     */
    public function testTeenagerWithMinimalValidValues(): void {
        $teenager = new AccountTeenager("Max", 10, $this->validParentId);

        $this->assertEquals("Max", $teenager->getName());
        $this->assertEquals(10, $teenager->getAge());
    }

    /**
     * Test : Teenager avec âge maximal (19)
     */
    public function testTeenagerWithMaximalAge(): void {
        $teenager = new AccountTeenager("Julia", 19, $this->validParentId);

        $this->assertEquals(19, $teenager->getAge());
    }

    /**
     * Test : Teenager sans âge mais avec nom valide
     */
    public function testTeenagerWithoutAgeButValidName(): void {
        $teenager = new AccountTeenager("Kevin", null, $this->validParentId);

        $this->assertEquals("Kevin", $teenager->getName());
        $this->assertNull($teenager->getAge());
        $this->assertEquals($this->validParentId, $teenager->getParentId());
    }
}
