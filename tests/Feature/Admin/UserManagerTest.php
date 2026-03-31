<?php

use App\Livewire\Admin\UserManager;
use App\Models\User;
use Livewire\Livewire;

test('guests cannot access user manager', function () {
    $this->get(route('admin.users'))
        ->assertRedirect(route('login'));
});

test('non-admin users cannot access user manager', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.users'))
        ->assertForbidden();
});

test('admin users can access user manager', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.users'))
        ->assertOk();
});

test('admin can search users', function () {
    $admin = User::factory()->admin()->create();
    $searchableUser = User::factory()->create(['name' => 'Unique Searchable Name']);
    User::factory()->create(['name' => 'Other Person']);

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->set('search', 'Unique Searchable')
        ->assertSee('Unique Searchable Name')
        ->assertDontSee('Other Person');
});

test('admin can toggle user active status', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['is_active' => true]);

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('toggleActive', $user->id);

    expect($user->fresh()->is_active)->toBeFalse();
});

test('admin can toggle user admin status', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['is_admin' => false]);

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('toggleAdmin', $user->id);

    expect($user->fresh()->is_admin)->toBeTrue();
});

test('admin cannot toggle own admin status', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('toggleAdmin', $admin->id);

    expect($admin->fresh()->is_admin)->toBeTrue();
});

test('admin can delete a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('confirmDelete', $user->id)
        ->assertSet('confirmingDeleteId', $user->id)
        ->call('deleteUser');

    expect(User::find($user->id))->toBeNull();
});

test('admin cannot delete themselves', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('confirmDelete', $admin->id)
        ->call('deleteUser');

    expect(User::find($admin->id))->not->toBeNull();
});

test('admin can edit a user', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('editUser', $targetUser->id)
        ->assertSet('editName', $targetUser->name)
        ->assertSet('editUsername', $targetUser->username)
        ->assertSet('editEmail', $targetUser->email)
        ->assertSet('showEditModal', true)
        ->set('editName', 'Updated Name')
        ->set('editUsername', 'updateduser')
        ->set('editEmail', 'updated@test.com')
        ->call('saveUser')
        ->assertSet('showEditModal', false);

    $targetUser->refresh();
    expect($targetUser->name)->toBe('Updated Name')
        ->and($targetUser->username)->toBe('updateduser')
        ->and($targetUser->email)->toBe('updated@test.com');
});

test('admin cannot set duplicate username when editing', function () {
    $admin = User::factory()->admin()->create();
    $existingUser = User::factory()->create(['username' => 'takenname']);
    $targetUser = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('editUser', $targetUser->id)
        ->set('editUsername', 'takenname')
        ->call('saveUser')
        ->assertHasErrors(['editUsername']);
});

test('admin cannot set duplicate email when editing', function () {
    $admin = User::factory()->admin()->create();
    $existingUser = User::factory()->create(['email' => 'taken@test.com']);
    $targetUser = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('editUser', $targetUser->id)
        ->set('editEmail', 'taken@test.com')
        ->call('saveUser')
        ->assertHasErrors(['editEmail']);
});

test('edit user validates required fields', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('editUser', $targetUser->id)
        ->set('editName', '')
        ->set('editUsername', '')
        ->set('editEmail', '')
        ->call('saveUser')
        ->assertHasErrors(['editName', 'editUsername', 'editEmail']);
});
