<?php

namespace Tests\Model;

use App\Model\AccountParent;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * Tests unitaires pour le modèle AccountParent
 *
 * Ce fichier teste :
 * - Validation du nom (minimum 3 caractères)
 * - Validation de l'email
 * - Génération d'ID unique
 * - Stockage des propriétés
 */
class AccountParentTest extends TestCase
{
    // ========================================
    // Tests pour la validation du NOM
    // ========================================

    /**
     * Test : Un parent peut être créé avec un nom de plus de 3 caractères
     */
    public function testCreateParentWithNameGreaterThan3Characters(): void
    {
        $parent = new AccountParent("John Doe", "john@example.com");

        $this->assertEquals("John Doe", $parent->getName());
        $this->assertIsString($parent->getName());
        $this->assertGreaterThan(3, strlen($parent->getName()));
    }

    /**
     * Test : Un parent peut être créé avec un nom de 4 caractères
     */
    public function testCreateParentWithName4Characters(): void
    {
        $parent = new AccountParent("Anna", "anna@test.com");

        $this->assertEquals("Anna", $parent->getName());
        $this->assertEquals(4, strlen($parent->getName()));
    }

    /**
     * Test : Un parent peut être créé avec exactement 3 caractères
     */
    public function testCreateParentWithNameExactly3Characters(): void
    {
        $parent = new AccountParent("Bob", "bob@test.com");

        $this->assertEquals("Bob", $parent->getName());
        $this->assertEquals(3, strlen($parent->getName()));
    }

    /**
     * Test : Un parent avec un nom de 2 caractères est refusé
     */
    public function testCreateParentWithName2CharactersThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom doit contenir au moins 3 caractères");

        new AccountParent("Al", "al@test.com");
    }

    /**
     * Test : Un parent avec un nom de 1 caractère est refusé
     */
    public function testCreateParentWithName1CharacterThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom doit contenir au moins 3 caractères");

        new AccountParent("X", "x@test.com");
    }

    /**
     * Test : Un parent avec un nom vide est refusé
     */
    public function testCreateParentWithEmptyNameThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom doit contenir au moins 3 caractères");

        new AccountParent("", "empty@test.com");
    }

    /**
     * Test : Un parent peut avoir un nom long
     */
    public function testCreateParentWithLongName(): void
    {
        $parent = new AccountParent("Jean-Baptiste Durand", "jb@example.com");

        $this->assertEquals("Jean-Baptiste Durand", $parent->getName());
        $this->assertGreaterThan(3, strlen($parent->getName()));
    }

    // ========================================
    // Tests pour la validation de l'EMAIL
    // ========================================

    /**
     * Test : Un parent peut être créé avec un email valide standard
     */
    public function testCreateParentWithValidEmail(): void
    {
        $parent = new AccountParent("Marie", "marie@example.com");

        $this->assertEquals("marie@example.com", $parent->getEmail());
        $this->assertIsString($parent->getEmail());
    }

    /**
     * Test : Un parent peut être créé avec un email contenant des chiffres
     */
    public function testCreateParentWithEmailContainingNumbers(): void
    {
        $parent = new AccountParent("Pierre", "pierre123@test.com");

        $this->assertEquals("pierre123@test.com", $parent->getEmail());
    }

    /**
     * Test : Un parent peut être créé avec un email contenant des points
     */
    public function testCreateParentWithEmailContainingDots(): void
    {
        $parent = new AccountParent("Sophie", "sophie.martin@example.fr");

        $this->assertEquals("sophie.martin@example.fr", $parent->getEmail());
    }

    /**
     * Test : Un parent peut être créé avec un email court
     */
    public function testCreateParentWithShortEmail(): void
    {
        $parent = new AccountParent("Lucas", "a@b.co");

        $this->assertEquals("a@b.co", $parent->getEmail());
    }

    /**
     * Test : Un parent ne peut pas être créé avec un email invalide (sans @)
     */
    public function testCreateParentWithInvalidEmailNoAtThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'email est invalide");

        new AccountParent("Emma", "invalidemailtest.com");
    }

    /**
     * Test : Un parent ne peut pas être créé avec un email invalide (sans domaine)
     */
    public function testCreateParentWithInvalidEmailNoDomainThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'email est invalide");

        new AccountParent("Thomas", "thomas@");
    }

    /**
     * Test : Un parent ne peut pas être créé avec un email vide
     */
    public function testCreateParentWithEmptyEmailThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'email est invalide");

        new AccountParent("Chloe", "");
    }

    /**
     * Test : Un parent ne peut pas être créé avec un email sans extension
     */
    public function testCreateParentWithInvalidEmailNoExtensionThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'email est invalide");

        new AccountParent("Noah", "noah@test");
    }

    /**
     * Test : Un parent ne peut pas être créé avec un email avec espaces
     */
    public function testCreateParentWithInvalidEmailWithSpacesThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'email est invalide");

        new AccountParent("Alice", "alice test@example.com");
    }

    // ========================================
    // Tests pour les métadonnées
    // ========================================

    /**
     * Test : Un parent a un ID unique
     */
    public function testParentHasUniqueId(): void
    {
        $parent1 = new AccountParent("Bob", "bob@test.com");
        $parent2 = new AccountParent("Charlie", "charlie@test.com");

        $this->assertNotEquals($parent1->getId(), $parent2->getId());
        $this->assertIsString($parent1->getId());
        $this->assertIsString($parent2->getId());
    }

    /**
     * Test : L'ID du parent commence par 'parent_'
     */
    public function testParentIdStartsWithPrefix(): void
    {
        $parent = new AccountParent("Diana", "diana@test.com");

        $this->assertStringStartsWith('parent_', $parent->getId());
    }

    /**
     * Test : L'ID du parent n'est pas vide
     */
    public function testParentIdIsNotEmpty(): void
    {
        $parent = new AccountParent("Eric", "eric@test.com");

        $this->assertNotEmpty($parent->getId());
        $this->assertGreaterThan(7, strlen($parent->getId())); // 'parent_' = 7 chars + uniqid
    }

    // ========================================
    // Tests combinés
    // ========================================

    /**
     * Test : Toutes les propriétés sont correctement stockées ensemble
     */
    public function testParentStoresAllProperties(): void
    {
        $parent = new AccountParent("Fiona", "fiona@example.com");

        $this->assertEquals("Fiona", $parent->getName());
        $this->assertEquals("fiona@example.com", $parent->getEmail());
        $this->assertIsString($parent->getId());
        $this->assertStringStartsWith('parent_', $parent->getId());
    }

    /**
     * Test : Parent avec nom minimal (3 chars) et email valide
     */
    public function testParentWithMinimalValidName(): void
    {
        $parent = new AccountParent("Max", "max@example.com");

        $this->assertEquals("Max", $parent->getName());
        $this->assertEquals(3, strlen($parent->getName()));
        $this->assertEquals("max@example.com", $parent->getEmail());
    }

    /**
     * Test : Parent avec nom long et email long
     */
    public function testParentWithLongNameAndEmail(): void
    {
        $parent = new AccountParent(
            "Jean-Baptiste Alexandre Durand",
            "jean.baptiste.alexandre.durand@example.com"
        );

        $this->assertEquals("Jean-Baptiste Alexandre Durand", $parent->getName());
        $this->assertEquals("jean.baptiste.alexandre.durand@example.com", $parent->getEmail());
    }

    /**
     * Test : Deux parents avec le même nom ont des IDs différents
     */
    public function testTwoParentsWithSameNameHaveDifferentIds(): void
    {
        $parent1 = new AccountParent("Julia", "julia1@test.com");
        $parent2 = new AccountParent("Julia", "julia2@test.com");

        $this->assertEquals("Julia", $parent1->getName());
        $this->assertEquals("Julia", $parent2->getName());
        $this->assertNotEquals($parent1->getId(), $parent2->getId());
    }
}
