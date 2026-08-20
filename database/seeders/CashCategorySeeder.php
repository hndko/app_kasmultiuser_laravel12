<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\CashCategory;
use Illuminate\Database\Seeder;

class CashCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Pemasukan
            [
                'name' => 'Iuran Bulanan',
                'code' => 'CAT-IN-001',
                'type' => CategoryType::INCOME,
                'description' => 'Iuran rutin bulanan anggota/pengguna',
                'is_active' => true,
            ],
            [
                'name' => 'Donasi & Sumbangan',
                'code' => 'CAT-IN-002',
                'type' => CategoryType::INCOME,
                'description' => 'Penerimaan dana donasi sukarela',
                'is_active' => true,
            ],
            [
                'name' => 'Penjualan / Jasa',
                'code' => 'CAT-IN-003',
                'type' => CategoryType::INCOME,
                'description' => 'Penerimaan hasil penjualan produk atau jasa',
                'is_active' => true,
            ],
            [
                'name' => 'Pendapatan Lainnya',
                'code' => 'CAT-IN-004',
                'type' => CategoryType::INCOME,
                'description' => 'Pendapatan di luar kategori utama',
                'is_active' => true,
            ],

            // Pengeluaran
            [
                'name' => 'Operasional Kantor',
                'code' => 'CAT-OUT-001',
                'type' => CategoryType::EXPENSE,
                'description' => 'Biaya operasional harian kantor, listrik, internet',
                'is_active' => true,
            ],
            [
                'name' => 'Pembelian Perlengkapan',
                'code' => 'CAT-OUT-002',
                'type' => CategoryType::EXPENSE,
                'description' => 'Belanja alat tulis, inventaris, dan supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Transportasi & Logistik',
                'code' => 'CAT-OUT-003',
                'type' => CategoryType::EXPENSE,
                'description' => 'Biaya bensin, tol, kurir, dan ekspedisi',
                'is_active' => true,
            ],
            [
                'name' => 'Konsumsi & Konsumsi Rapat',
                'code' => 'CAT-OUT-004',
                'type' => CategoryType::EXPENSE,
                'description' => 'Biaya makanan, minuman, dan snack',
                'is_active' => true,
            ],
            [
                'name' => 'Pengeluaran Lainnya',
                'code' => 'CAT-OUT-005',
                'type' => CategoryType::EXPENSE,
                'description' => 'Pengeluaran insidental lainnya',
                'is_active' => true,
            ],

            // Keduanya
            [
                'name' => 'Penyesuaian Saldo',
                'code' => 'CAT-BOTH-001',
                'type' => CategoryType::BOTH,
                'description' => 'Koreksi atau penyesuaian catatan kas',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            CashCategory::updateOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
