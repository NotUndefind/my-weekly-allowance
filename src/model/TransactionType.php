<?php

namespace App\Model;

/**
 * Enum pour les types de transactions
 *
 * - DEPOSIT: Dépôt d'argent par un parent
 * - EXPENSE: Dépense effectuée par l'ado
 * - ALLOWANCE: Allocation hebdomadaire automatique
 */
enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case EXPENSE = 'expense';
    case ALLOWANCE = 'allowance';
}