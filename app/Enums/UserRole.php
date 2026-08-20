<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER => 'User',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400',
            self::USER => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
        };
    }
}
