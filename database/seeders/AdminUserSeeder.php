<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin default
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // Staff / Regular User default
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Staff Kasir',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // Inactive User (for testing authorization and login prevention)
        User::updateOrCreate(
            ['email' => 'inactive@example.com'],
            [
                'name' => 'Inactive User',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'status' => UserStatus::INACTIVE,
                'email_verified_at' => now(),
            ]
        );
    }
}
