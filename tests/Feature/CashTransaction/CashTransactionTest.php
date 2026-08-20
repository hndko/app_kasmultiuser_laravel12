<?php

use App\Enums\CategoryType;
use App\Enums\TransactionType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Models\User;

test('transaction list page can be rendered for authenticated users', function () {
    $user = User::factory()->create();
    CashTransaction::factory()->count(3)->create(['created_by' => $user->id]);

    $response = $this->actingAs($user)->get(route('modules.cash.transactions.index'));

    $response->assertStatus(200);
});

test('create transaction page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('modules.cash.transactions.create'));

    $response->assertStatus(200);
});

test('income transaction can be created with valid data', function () {
    $user = User::factory()->create();
    $category = CashCategory::factory()->create(['type' => CategoryType::INCOME]);

    $response = $this->actingAs($user)->post(route('modules.cash.transactions.store'), [
        'transaction_date' => now()->toDateString(),
        'type' => TransactionType::INCOME->value,
        'cash_category_id' => $category->id,
        'amount' => '750000',
        'description' => 'Pemasukan dari kas iuran anggota',
        'reference' => 'KWT-991',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('modules.cash.transactions.index'));

    $this->assertDatabaseHas('cash_transactions', [
        'type' => TransactionType::INCOME->value,
        'cash_category_id' => $category->id,
        'amount' => 750000,
        'reference' => 'KWT-991',
        'created_by' => $user->id,
    ]);
});

test('expense transaction cannot use income-only category', function () {
    $user = User::factory()->create();
    $incomeCategory = CashCategory::factory()->create(['type' => CategoryType::INCOME]);

    $response = $this->actingAs($user)->post(route('modules.cash.transactions.store'), [
        'transaction_date' => now()->toDateString(),
        'type' => TransactionType::EXPENSE->value,
        'cash_category_id' => $incomeCategory->id,
        'amount' => '100000',
        'description' => 'Beli bensin',
    ]);

    $response->assertSessionHasErrors(['cash_category_id']);
});

test('transaction show page displays details and audit log', function () {
    $user = User::factory()->create();
    $transaction = CashTransaction::factory()->create(['created_by' => $user->id]);

    $response = $this->actingAs($user)->get(route('modules.cash.transactions.show', $transaction));

    $response->assertStatus(200);
    $response->assertSee($transaction->transaction_number);
});

test('transaction can be updated', function () {
    $user = User::factory()->create();
    $category = CashCategory::factory()->create(['type' => CategoryType::EXPENSE]);
    $transaction = CashTransaction::factory()->create([
        'type' => TransactionType::EXPENSE,
        'cash_category_id' => $category->id,
        'amount' => 200000,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->put(route('modules.cash.transactions.update', $transaction), [
        'transaction_date' => now()->toDateString(),
        'type' => TransactionType::EXPENSE->value,
        'cash_category_id' => $category->id,
        'amount' => '350000',
        'description' => 'Keterangan revisi biaya operasional',
        'reference' => 'INV-REV',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('modules.cash.transactions.index'));

    $transaction->refresh();
    expect((float)$transaction->amount)->toBe(350000.0);
    expect($transaction->updated_by)->toBe($user->id);
});

test('transaction can be deleted', function () {
    $user = User::factory()->create();
    $transaction = CashTransaction::factory()->create(['created_by' => $user->id]);

    $response = $this->actingAs($user)->delete(route('modules.cash.transactions.destroy', $transaction));

    $response->assertRedirect(route('modules.cash.transactions.index'));
    $this->assertSoftDeleted('cash_transactions', ['id' => $transaction->id]);
});

test('transactions can be filtered by keyword, type, and date range', function () {
    $user = User::factory()->create();
    $inCat = CashCategory::factory()->create(['type' => CategoryType::INCOME]);
    $outCat = CashCategory::factory()->create(['type' => CategoryType::EXPENSE]);

    $trx1 = CashTransaction::factory()->create([
        'type' => TransactionType::INCOME,
        'cash_category_id' => $inCat->id,
        'amount' => 1000000,
        'description' => 'Target Kata Kunci Khusus',
        'created_by' => $user->id,
    ]);

    $trx2 = CashTransaction::factory()->create([
        'type' => TransactionType::EXPENSE,
        'cash_category_id' => $outCat->id,
        'amount' => 500000,
        'description' => 'Belanja ATK Kantor',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('modules.cash.transactions.index', [
        'search' => 'Khusus',
        'type' => TransactionType::INCOME->value,
    ]));

    $response->assertStatus(200);
    $response->assertSee($trx1->transaction_number);
    $response->assertDontSee($trx2->transaction_number);
});
