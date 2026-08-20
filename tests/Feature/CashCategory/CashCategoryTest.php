<?php

use App\Enums\CategoryType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Models\User;

test('category list page can be rendered for authenticated users', function () {
    $user = User::factory()->create();
    CashCategory::factory()->count(3)->create();

    $response = $this->actingAs($user)->get(route('modules.cash.categories.index'));

    $response->assertStatus(200);
});

test('create category page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('modules.cash.categories.create'));

    $response->assertStatus(200);
});

test('category can be created with valid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('modules.cash.categories.store'), [
        'name' => 'Pendapatan Proyek',
        'code' => 'CAT-IN-999',
        'type' => CategoryType::INCOME->value,
        'description' => 'Pendapatan dari proyek klien',
        'is_active' => '1',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('modules.cash.categories.index'));

    $this->assertDatabaseHas('cash_categories', [
        'code' => 'CAT-IN-999',
        'name' => 'Pendapatan Proyek',
        'type' => CategoryType::INCOME->value,
        'is_active' => true,
    ]);
});

test('category code must be unique', function () {
    $user = User::factory()->create();
    CashCategory::factory()->create(['code' => 'CAT-DUP']);

    $response = $this->actingAs($user)->post(route('modules.cash.categories.store'), [
        'name' => 'Kategori Duplikat',
        'code' => 'CAT-DUP',
        'type' => CategoryType::EXPENSE->value,
    ]);

    $response->assertSessionHasErrors(['code']);
});

test('category can be updated', function () {
    $user = User::factory()->create();
    $category = CashCategory::factory()->create([
        'name' => 'Nama Lama',
        'code' => 'CAT-OLD',
    ]);

    $response = $this->actingAs($user)->put(route('modules.cash.categories.update', $category), [
        'name' => 'Nama Baru',
        'code' => 'CAT-OLD',
        'type' => CategoryType::EXPENSE->value,
        'description' => 'Deskripsi baru',
        'is_active' => '1',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('modules.cash.categories.index'));

    $category->refresh();
    expect($category->name)->toBe('Nama Baru');
});

test('category without transactions can be deleted', function () {
    $user = User::factory()->create();
    $category = CashCategory::factory()->create();

    $response = $this->actingAs($user)->delete(route('modules.cash.categories.destroy', $category));

    $response->assertRedirect(route('modules.cash.categories.index'));
    $this->assertSoftDeleted('cash_categories', ['id' => $category->id]);
});

test('category with associated transactions cannot be deleted', function () {
    $user = User::factory()->create();
    $category = CashCategory::factory()->create();
    CashTransaction::factory()->create([
        'cash_category_id' => $category->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete(route('modules.cash.categories.destroy', $category));

    $response->assertRedirect(route('modules.cash.categories.index'));
    $this->assertDatabaseHas('cash_categories', [
        'id' => $category->id,
        'deleted_at' => null,
    ]);
});

test('categories can be filtered by type and search keyword', function () {
    $user = User::factory()->create();
    CashCategory::factory()->create([
        'name' => 'Iuran Khusus',
        'code' => 'CAT-IN-SP',
        'type' => CategoryType::INCOME,
    ]);
    CashCategory::factory()->create([
        'name' => 'Belanja ATK',
        'code' => 'CAT-OUT-ATK',
        'type' => CategoryType::EXPENSE,
    ]);

    $response = $this->actingAs($user)->get(route('modules.cash.categories.index', [
        'search' => 'Khusus',
        'type' => CategoryType::INCOME->value,
    ]));

    $response->assertStatus(200);
    $response->assertSee('Iuran Khusus');
    $response->assertDontSee('Belanja ATK');
});
