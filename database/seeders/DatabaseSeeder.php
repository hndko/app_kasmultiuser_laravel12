<?php

namespace Database\Seeders;

use App\Enums\TransactionType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CashCategorySeeder::class,
        ]);

        $admin = User::where('email', 'admin@example.com')->first();
        $user = User::where('email', 'user@example.com')->first();

        // Sample initial transactions for testing and demo
        $inCat1 = CashCategory::where('code', 'CAT-IN-001')->first();
        $inCat2 = CashCategory::where('code', 'CAT-IN-002')->first();
        $outCat1 = CashCategory::where('code', 'CAT-OUT-001')->first();
        $outCat2 = CashCategory::where('code', 'CAT-OUT-004')->first();

        if ($inCat1 && $admin) {
            CashTransaction::updateOrCreate(
                ['transaction_number' => 'TRX-' . date('Ymd') . '-0001'],
                [
                    'transaction_date' => now()->toDateString(),
                    'type' => TransactionType::INCOME,
                    'cash_category_id' => $inCat1->id,
                    'amount' => 5000000,
                    'description' => 'Iuran bulanan kas periode berjalan',
                    'reference' => 'KWT-001',
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]
            );
        }

        if ($inCat2 && $user) {
            CashTransaction::updateOrCreate(
                ['transaction_number' => 'TRX-' . date('Ymd') . '-0002'],
                [
                    'transaction_date' => now()->toDateString(),
                    'type' => TransactionType::INCOME,
                    'cash_category_id' => $inCat2->id,
                    'amount' => 1500000,
                    'description' => 'Donasi kas masuk',
                    'reference' => 'DON-102',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]
            );
        }

        if ($outCat1 && $admin) {
            CashTransaction::updateOrCreate(
                ['transaction_number' => 'TRX-' . date('Ymd') . '-0003'],
                [
                    'transaction_date' => now()->toDateString(),
                    'type' => TransactionType::EXPENSE,
                    'cash_category_id' => $outCat1->id,
                    'amount' => 750000,
                    'description' => 'Pembayaran tagihan listrik & internet kantor',
                    'reference' => 'INV-PLN-08',
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]
            );
        }

        if ($outCat2 && $user) {
            CashTransaction::updateOrCreate(
                ['transaction_number' => 'TRX-' . date('Ymd') . '-0004'],
                [
                    'transaction_date' => now()->toDateString(),
                    'type' => TransactionType::EXPENSE,
                    'cash_category_id' => $outCat2->id,
                    'amount' => 250000,
                    'description' => 'Konsumsi makan siang dan snack rapat evaluasi kas',
                    'reference' => 'STR-291',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]
            );
        }
    }
}
