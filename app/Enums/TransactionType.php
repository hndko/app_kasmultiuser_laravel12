<?php

namespace App\Enums;

enum TransactionType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'Pemasukan',
            self::EXPENSE => 'Pengeluaran',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::INCOME => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',
            self::EXPENSE => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',
        };
    }
}
