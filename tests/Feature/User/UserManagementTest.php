<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;

test('regular users cannot access user management', function () {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $response = $this->actingAs($user)->get(route('modules.users.index'));

    $response->assertStatus(403);
});

test('admin users can access user management', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('modules.users.index'));

    $response->assertStatus(200);
});

test('admin can create a new user', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('modules.users.store'), [
        'name' => 'Staff Baru',
        'email' => 'staff.baru@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => UserRole::USER->value,
        'status' => UserStatus::ACTIVE->value,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('modules.users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'staff.baru@example.com',
        'name' => 'Staff Baru',
        'role' => UserRole::USER->value,
        'status' => UserStatus::ACTIVE->value,
    ]);
});

test('admin can update user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($admin)->put(route('modules.users.update', $user), [
        'name' => 'Nama Staff Diperbarui',
        'email' => $user->email,
        'role' => UserRole::USER->value,
        'status' => UserStatus::INACTIVE->value,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('modules.users.index'));

    $user->refresh();
    expect($user->name)->toBe('Nama Staff Diperbarui');
    expect($user->status)->toBe(UserStatus::INACTIVE);
});

test('admin cannot demote their own account to regular user', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->put(route('modules.users.update', $admin), [
        'name' => $admin->name,
        'email' => $admin->email,
        'role' => UserRole::USER->value,
        'status' => UserStatus::ACTIVE->value,
    ]);

    $response->assertSessionHasErrors(['role']);
});

test('admin cannot delete their own account', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->delete(route('modules.users.destroy', $admin));

    $response->assertRedirect(route('modules.users.index'));
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin can delete other user', function () {
    $admin = User::factory()->admin()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($admin)->delete(route('modules.users.destroy', $otherUser));

    $response->assertRedirect(route('modules.users.index'));
    $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
});
