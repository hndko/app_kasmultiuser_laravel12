<?php

use App\Enums\CategoryType;
use App\Enums\TransactionType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Models\User;

test('cash report index page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('modules.reports.cash.index'));

    $response->assertStatus(200);
    $response->assertSee('Laporan Arus Kas');
    $response->assertSee('Saldo Awal');
    $response->assertSee('Saldo Akhir');
});

test('cash report print view can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('modules.reports.cash.print'));

    $response->assertStatus(200);
    $response->assertSee('Laporan Rekapitulasi Mutasi Buku Kas');
});

test('cash report calculates beginning balance, movements, and ending balance correctly', function () {
    $user = User::factory()->create();
    $inCat = CashCategory::factory()->create(['type' => CategoryType::INCOME]);
    $outCat = CashCategory::factory()->create(['type' => CategoryType::EXPENSE]);

    // 1. Transaction prior to current month (creates Saldo Awal)
    CashTransaction::factory()->create([
        'transaction_date' => now()->subMonths(2)->startOfMonth()->toDateString(),
        'type' => TransactionType::INCOME,
        'amount' => 1000000,
        'cash_category_id' => $inCat->id,
        'created_by' => $user->id,
    ]);

    // 2. Transaction in current month
    CashTransaction::factory()->create([
        'transaction_date' => now()->startOfMonth()->addDays(2)->toDateString(),
        'type' => TransactionType::EXPENSE,
        'amount' => 300000,
        'cash_category_id' => $outCat->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('modules.reports.cash.index', [
        'period' => 'this_month',
    ]));

    $response->assertStatus(200);
    // Saldo Awal should be 1.000.000
    $response->assertSee('Rp 1.000.000');
    // Saldo Akhir should be 700.000
    $response->assertSee('Rp 700.000');
});
