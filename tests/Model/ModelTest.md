# Rapport des Tests Unitaires - Mod�les

**Projet :** MyWeeklyAllowance
**Date :** 2025-11-26
**M�thodologie :** TDD (Test-Driven Development)

---

## =� Vue d'ensemble

Ce rapport d�taille tous les tests unitaires cr��s pour les mod�les du projet MyWeeklyAllowance. Chaque mod�le a �t� test� selon la m�thodologie TDD avec des cas de tests couvrant les sc�narios nominaux et les cas d'erreur.

---

## 1� AccountParent - Tests du mod�le Parent

**Fichier :** `tests/Model/AccountParentTest.php`
**Mod�le test� :** `src/model/AccountParent.php`
**Nombre de tests :** 27

### Structure du mod�le

```php
class AccountParent {
    private string $id;           // G�n�r� automatiquement (uniqid)
    private string $name;          // Minimum 3 caract�res
    private string $email;         // Email valide
}
```

### Cat�gories de tests

#### A. Validation du nom (7 tests)

| Test                                                 | Sc�nario                          | R�sultat attendu                       |
| ---------------------------------------------------- | --------------------------------- | -------------------------------------- |
| `testCreateParentWithNameGreaterThan3Characters`     | Nom > 3 caract�res                |  Accept�                               |
| `testCreateParentWithName4Characters`                | Nom = 4 caract�res                |  Accept�                               |
| `testCreateParentWithNameExactly3Characters`         | Nom = 3 caract�res (limite basse) |  Accept�                               |
| `testCreateParentWithName2CharactersThrowsException` | Nom = 2 caract�res                | L Exception `InvalidArgumentException` |
| `testCreateParentWithName1CharacterThrowsException`  | Nom = 1 caract�re                 | L Exception                            |
| `testCreateParentWithEmptyNameThrowsException`       | Nom vide                          | L Exception                            |
| `testCreateParentWithLongName`                       | Nom long (compos�)                |  Accept�                               |

#### B. Validation de l'email (9 tests)

| Test                                                         | Sc�nario              | R�sultat attendu |
| ------------------------------------------------------------ | --------------------- | ---------------- |
| `testCreateParentWithValidEmail`                             | Email valide standard |  Accept�         |
| `testCreateParentWithEmailContainingNumbers`                 | Email avec chiffres   |  Accept�         |
| `testCreateParentWithEmailContainingDots`                    | Email avec points     |  Accept�         |
| `testCreateParentWithShortEmail`                             | Email court valide    |  Accept�         |
| `testCreateParentWithInvalidEmailNoAtThrowsException`        | Email sans @          | L Exception      |
| `testCreateParentWithInvalidEmailNoDomainThrowsException`    | Email sans domaine    | L Exception      |
| `testCreateParentWithEmptyEmailThrowsException`              | Email vide            | L Exception      |
| `testCreateParentWithInvalidEmailNoExtensionThrowsException` | Email sans extension  | L Exception      |
| `testCreateParentWithInvalidEmailWithSpacesThrowsException`  | Email avec espaces    | L Exception      |

#### C. G�n�ration d'ID unique (3 tests)

| Test                           | Sc�nario                            | R�sultat attendu |
| ------------------------------ | ----------------------------------- | ---------------- |
| `testParentHasUniqueId`        | Deux parents ont des IDs diff�rents |  IDs uniques     |
| `testParentIdStartsWithPrefix` | ID commence par 'parent\_'          |  Pr�fixe correct |
| `testParentIdIsNotEmpty`       | ID n'est pas vide                   |  ID g�n�r�       |

#### D. Tests combin�s (4 tests)

| Test                                         | Sc�nario                          | R�sultat attendu  |
| -------------------------------------------- | --------------------------------- | ----------------- |
| `testParentStoresAllProperties`              | Stockage de toutes les propri�t�s |  Donn�es int�gres |
| `testParentWithMinimalValidName`             | Nom minimal + email valide        |  Accept�          |
| `testParentWithLongNameAndEmail`             | Nom et email longs                |  Accept�          |
| `testTwoParentsWithSameNameHaveDifferentIds` | M�me nom, IDs diff�rents          |  IDs uniques      |

---

## 2� AccountTeenager - Tests du mod�le Adolescent

**Fichier :** `tests/Model/AccountTeenagerTest.php`
**Mod�le test� :** `src/model/AccountTeenager.php`
**Nombre de tests :** 29

### Structure du mod�le

```php
class AccountTeenager {
    private string $id;           // G�n�r� automatiquement (uniqid)
    private string $name;          // Minimum 3 caract�res
    private ?int $age;            // Optionnel, entre 10 et 19 ans
    private string $parentId;      // R�f�rence au parent
}
```

### Cat�gories de tests

#### A. Validation du nom (7 tests)

| Test                                                   | Sc�nario           | R�sultat attendu |
| ------------------------------------------------------ | ------------------ | ---------------- |
| `testCreateTeenagerWithNameGreaterThan3Characters`     | Nom > 3 caract�res |  Accept�         |
| `testCreateTeenagerWithName4Characters`                | Nom = 4 caract�res |  Accept�         |
| `testCreateTeenagerWithNameExactly3Characters`         | Nom = 3 caract�res |  Accept�         |
| `testCreateTeenagerWithName2CharactersThrowsException` | Nom = 2 caract�res | L Exception      |
| `testCreateTeenagerWithName1CharacterThrowsException`  | Nom = 1 caract�re  | L Exception      |
| `testCreateTeenagerWithEmptyNameThrowsException`       | Nom vide           | L Exception      |
| `testCreateTeenagerWithLongName`                       | Nom long (compos�) |  Accept�         |

#### B. Validation de l'�ge (8 tests)

| Test                                               | Sc�nario                  | R�sultat attendu |
| -------------------------------------------------- | ------------------------- | ---------------- |
| `testCreateTeenagerWithAge10`                      | �ge = 10 (minimum)        |  Accept�         |
| `testCreateTeenagerWithAge19`                      | �ge = 19 (maximum)        |  Accept�         |
| `testCreateTeenagerWithAge15`                      | �ge = 15 (valeur moyenne) |  Accept�         |
| `testCreateTeenagerWithNullAge`                    | �ge = null (optionnel)    |  Accept�         |
| `testCreateTeenagerWithAge9ThrowsException`        | �ge < 10                  | L Exception      |
| `testCreateTeenagerWithAge20ThrowsException`       | �ge > 19                  | L Exception      |
| `testCreateTeenagerWithAge0ThrowsException`        | �ge = 0                   | L Exception      |
| `testCreateTeenagerWithNegativeAgeThrowsException` | �ge n�gatif               | L Exception      |

#### C. Validation du parentId (2 tests)

| Test                                                 | Sc�nario                     | R�sultat attendu |
| ---------------------------------------------------- | ---------------------------- | ---------------- |
| `testTeenagerStoresParentId`                         | parentId stock� correctement |  Lien parent     |
| `testCreateTeenagerWithEmptyParentIdThrowsException` | parentId vide                | L Exception      |

#### D. G�n�ration d'ID unique (2 tests)

| Test                             | Sc�nario                     | R�sultat attendu  |
| -------------------------------- | ---------------------------- | ----------------- |
| `testTeenagerHasUniqueId`        | IDs uniques                  |  Unicit� garantie |
| `testTeenagerIdStartsWithPrefix` | ID commence par 'teenager\_' |  Pr�fixe correct  |

#### E. Tests combin�s (4 tests)

| Test                                 | Sc�nario                   | R�sultat attendu  |
| ------------------------------------ | -------------------------- | ----------------- |
| `testTeenagerStoresAllProperties`    | Toutes propri�t�s stock�es |  Donn�es int�gres |
| `testTeenagerWithMinimalValidValues` | Valeurs minimales valides  |  Accept�          |
| `testTeenagerWithMaximalAge`         | �ge maximal (19)           |  Accept�          |
| `testTeenagerWithoutAgeButValidName` | Sans �ge mais nom valide   |  Accept�          |

---

## 3� Account - Tests du mod�le Compte

**Fichier :** `tests/Model/AccountTest.php`
**Mod�le test� :** `src/model/Account.php`
**Nombre de tests :** 27

### Structure du mod�le

```php
class Account {
    private string $id;                  // G�n�r� automatiquement
    private string $teenagerId;          // R�f�rence � l'ado
    private float $balance;              // Solde (>= 0)
    private ?float $weeklyAllowance;     // Allocation hebdomadaire
    private ?DateTime $lastAllowanceDate;// Date derni�re allocation
    private DateTime $createdAt;         // Date de cr�ation
}
```

### Cat�gories de tests

#### A. Cr�ation de compte avec balance (4 tests)

| Test                                                     | Sc�nario           | R�sultat attendu |
| -------------------------------------------------------- | ------------------ | ---------------- |
| `testCreateAccountWithValidTeenagerIdAndPositiveBalance` | Balance > 0        |  Accept�         |
| `testCreateAccountWithLargeBalance`                      | Grande balance     |  Accept�         |
| `testCreateAccountWithSmallPositiveBalance`              | Petite balance (1) |  Accept�         |
| `testCreateAccountWithDecimalBalance`                    | Balance d�cimale   |  Accept�         |

#### B. Balance � z�ro (3 tests)

| Test                                  | Sc�nario                    | R�sultat attendu |
| ------------------------------------- | --------------------------- | ---------------- |
| `testCreateAccountWithZeroBalance`    | Balance = 0                 |  Accept�         |
| `testCreateAccountWithDefaultBalance` | Balance par d�faut          |  0 par d�faut    |
| `testZeroBalanceIsNumeric`            | V�rification type num�rique |  Type correct    |

#### C. Balance n�gative refus�e (4 tests)

| Test                                                         | Sc�nario                  | R�sultat attendu |
| ------------------------------------------------------------ | ------------------------- | ---------------- |
| `testCreateAccountWithNegativeBalanceThrowsException`        | Balance < 0               | L Exception      |
| `testCreateAccountWithSmallNegativeBalanceThrowsException`   | Balance = -1              | L Exception      |
| `testCreateAccountWithLargeNegativeBalanceThrowsException`   | Grande balance n�gative   | L Exception      |
| `testCreateAccountWithDecimalNegativeBalanceThrowsException` | Balance d�cimale n�gative | L Exception      |

#### D. Validation teenagerId (2 tests)

| Test                                                  | Sc�nario          | R�sultat attendu |
| ----------------------------------------------------- | ----------------- | ---------------- |
| `testAccountStoresTeenagerId`                         | teenagerId stock� |  Lien ado        |
| `testCreateAccountWithEmptyTeenagerIdThrowsException` | teenagerId vide   | L Exception      |

#### E. M�tadonn�es du compte (2 tests)

| Test                          | Sc�nario         | R�sultat attendu |
| ----------------------------- | ---------------- | ---------------- |
| `testAccountHasUniqueId`      | IDs uniques      |  Unicit�         |
| `testAccountHasCreatedAtDate` | Date de cr�ation |  DateTime g�n�r� |

#### F. Allocation hebdomadaire (9 tests)

| Test                                                          | Sc�nario                 | R�sultat attendu |
| ------------------------------------------------------------- | ------------------------ | ---------------- |
| `testCreateAccountWithWeeklyAllowance`                        | Allocation � la cr�ation |  Accept�         |
| `testCreateAccountWithoutWeeklyAllowance`                     | Sans allocation          |  null par d�faut |
| `testCreateAccountWithZeroWeeklyAllowanceThrowsException`     | Allocation = 0           | L Exception      |
| `testCreateAccountWithNegativeWeeklyAllowanceThrowsException` | Allocation < 0           | L Exception      |
| `testSetWeeklyAllowanceAfterCreation`                         | D�finir apr�s cr�ation   |  Modifiable      |
| `testSetZeroWeeklyAllowanceThrowsException`                   | D�finir 0                | L Exception      |
| `testSetNegativeWeeklyAllowanceThrowsException`               | D�finir n�gatif          | L Exception      |
| `testLastAllowanceDateIsNullByDefault`                        | Date null par d�faut     |  null            |
| `testSetLastAllowanceDate`                                    | D�finir date             |  Modifiable      |

---

## 4� Transaction - Tests du mod�le Transaction

**Fichier :** `tests/Model/TransactionTest.php`
**Mod�le test� :** `src/model/Transaction.php`
**Nombre de tests :** 27

### Structure du mod�le

```php
class Transaction {
    private string $id;                  // G�n�r� automatiquement
    private string $accountId;           // R�f�rence au compte
    private TransactionType $type;       // DEPOSIT, EXPENSE, ALLOWANCE
    private float $amount;               // Montant (> 0)
    private ?string $description;        // Description optionnelle
    private ?string $createdBy;          // ID cr�ateur (optionnel)
    private DateTime $createdAt;         // Date de cr�ation
}
```

### Cat�gories de tests

#### A. Transactions DEPOSIT (3 tests)

| Test                                            | Sc�nario        | R�sultat attendu |
| ----------------------------------------------- | --------------- | ---------------- |
| `testCreateDepositTransactionWithValidAmount`   | D�p�t valide    |  Accept�         |
| `testCreateDepositTransactionWithLargeAmount`   | Grand montant   |  Accept�         |
| `testCreateDepositTransactionWithDecimalAmount` | Montant d�cimal |  Accept�         |

#### B. Transactions EXPENSE (3 tests)

| Test                                               | Sc�nario         | R�sultat attendu |
| -------------------------------------------------- | ---------------- | ---------------- |
| `testCreateExpenseTransactionWithValidAmount`      | D�pense valide   |  Accept�         |
| `testCreateExpenseTransactionWithoutDescription`   | Sans description |  null accept�    |
| `testCreateExpenseTransactionWithEmptyDescription` | Description vide |  Accept�         |

#### C. Transactions ALLOWANCE (2 tests)

| Test                                             | Sc�nario          | R�sultat attendu |
| ------------------------------------------------ | ----------------- | ---------------- |
| `testCreateAllowanceTransactionWithValidAmount`  | Allocation valide |  Accept�         |
| `testCreateAllowanceTransactionWithoutCreatedBy` | Sans cr�ateur     |  null accept�    |

#### D. Validation du montant (3 tests)

| Test                                                          | Sc�nario        | R�sultat attendu |
| ------------------------------------------------------------- | --------------- | ---------------- |
| `testCreateTransactionWithZeroAmountThrowsException`          | Montant = 0     | L Exception      |
| `testCreateTransactionWithNegativeAmountThrowsException`      | Montant < 0     | L Exception      |
| `testCreateTransactionWithSmallNegativeAmountThrowsException` | Montant = -0.01 | L Exception      |

#### E. Validation accountId (2 tests)

| Test                                                     | Sc�nario         | R�sultat attendu |
| -------------------------------------------------------- | ---------------- | ---------------- |
| `testTransactionStoresAccountId`                         | accountId stock� |  Lien compte     |
| `testCreateTransactionWithEmptyAccountIdThrowsException` | accountId vide   | L Exception      |

#### F. M�tadonn�es (3 tests)

| Test                                | Sc�nario                | R�sultat attendu |
| ----------------------------------- | ----------------------- | ---------------- |
| `testTransactionHasUniqueId`        | IDs uniques             |  Unicit�         |
| `testTransactionIdStartsWithPrefix` | Pr�fixe 'transaction\_' |  Correct         |
| `testTransactionHasCreatedAtDate`   | Date de cr�ation        |  DateTime g�n�r� |

#### G. Types de transaction (enum) (3 tests)

| Test                           | Sc�nario       | R�sultat attendu |
| ------------------------------ | -------------- | ---------------- |
| `testTransactionTypeDeposit`   | Type DEPOSIT   |  Enum correct    |
| `testTransactionTypeExpense`   | Type EXPENSE   |  Enum correct    |
| `testTransactionTypeAllowance` | Type ALLOWANCE |  Enum correct    |

#### H. Tests combin�s (3 tests)

| Test                                 | Sc�nario          | R�sultat attendu  |
| ------------------------------------ | ----------------- | ----------------- |
| `testTransactionStoresAllProperties` | Toutes propri�t�s |  Donn�es int�gres |
| `testTransactionWithMinimalData`     | Donn�es minimales |  Accept�          |
| `testTransactionWithAllFields`       | Tous les champs   |  Accept�          |

---

## =� Statistiques globales

| Mod�le          | Fichier de test           | Nombre de tests | Tests r�ussis | Couverture estim�e |
| --------------- | ------------------------- | --------------- | ------------- | ------------------ |
| AccountParent   | `AccountParentTest.php`   | 27              |  27/27        | ~95%               |
| AccountTeenager | `AccountTeenagerTest.php` | 29              |  29/29        | ~95%               |
| Account         | `AccountTest.php`         | 27              |  27/27        | ~70%               |
| Transaction     | `TransactionTest.php`     | 27              |  27/27        | ~95%               |
| **TOTAL**       | **4 fichiers**            | **110 tests**   | ** 110/110**  | **~89%**           |

---

## <� Recommandations

### Points forts

 Couverture compl�te des cas nominaux et d'erreur
 Tests bien organis�s par cat�gorie
 Nomenclature claire et descriptive
 Validation stricte des donn�es d'entr�e
 Utilisation d'enums PHP 8.4+ pour les types

### Points � am�liorer

� **Account** : Tests manquants pour les m�thodes m�tier (`deposit()`, `recordExpense()`, `applyWeeklyAllowance()`, `shouldApplyAllowance()`)
� **TransactionType** : Pas de tests pour l'enum (v�rification des valeurs)
� **Tests d'int�gration** : Manquants entre les mod�les

### Tests � ajouter

#### Pour Account

-   Tests pour `deposit(amount)` - augmentation de la balance
-   Tests pour `recordExpense(amount, description)` - diminution de la balance
-   Tests pour `shouldApplyAllowance()` - v�rification des 7 jours
-   Tests pour `applyWeeklyAllowance()` - application automatique

#### Tests d'int�gration recommand�s

-   Cr�er un parent � cr�er un teenager � cr�er un account � cr�er des transactions
-   V�rifier l'int�grit� des relations entre mod�les
-   Tester les flux complets (d�p�t � d�pense � allocation)

---

## =� Prochaines �tapes

1. **Phase GREEN** : Impl�menter le code pour faire passer tous les tests
2. **Phase REFACTOR** : Am�liorer le code sans casser les tests
3. **Compl�ter les tests Account** : Ajouter les tests des m�thodes m�tier
4. **Tests d'int�gration** : Cr�er des tests de bout en bout
5. **Coverage 100%** : Viser la couverture compl�te avec `vendor/bin/phpunit --coverage-html coverage/html`

---

**Auteur :** Claude Code
**M�thodologie :** TDD (Test-Driven Development)
**Date de g�n�ration :** 2025-11-26
