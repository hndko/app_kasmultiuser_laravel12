<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',
            self::INACTIVE => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',
        };
    }
}
