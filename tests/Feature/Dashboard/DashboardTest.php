<?php

use App\Enums\CategoryType;
use App\Enums\TransactionType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Models\User;

test('dashboard page can be rendered for authenticated users', function () {
    $user = User::factory()->create();
    $category = CashCategory::factory()->create(['type' => CategoryType::INCOME]);
    
    CashTransaction::factory()->create([
        'type' => TransactionType::INCOME,
        'amount' => 500000,
        'cash_category_id' => $category->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('modules.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Total Saldo Kas');
    $response->assertSee('Pemasukan Bulan Ini');
    $response->assertSee('Pengeluaran Bulan Ini');
});
