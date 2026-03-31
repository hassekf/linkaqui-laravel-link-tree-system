<?php

use App\Livewire\Builder\ProfileSettings;
use App\Models\User;
use Livewire\Livewire;

test('user can update name', function () {
    $user = User::factory()->create(['name' => 'Old Name']);

    Livewire::actingAs($user)
        ->test(ProfileSettings::class)
        ->assertSet('name', 'Old Name')
        ->set('name', 'New Name')
        ->call('save');

    expect($user->fresh()->name)->toBe('New Name');
});

test('user can update username', function () {
    $user = User::factory()->create(['username' => 'oldusername']);

    Livewire::actingAs($user)
        ->test(ProfileSettings::class)
        ->assertSet('username', 'oldusername')
        ->set('username', 'newusername')
        ->call('save');

    expect($user->fresh()->username)->toBe('newusername');
});

test('user can update bio', function () {
    $user = User::factory()->create(['bio' => 'Old bio']);

    Livewire::actingAs($user)
        ->test(ProfileSettings::class)
        ->assertSet('bio', 'Old bio')
        ->set('bio', 'New bio text')
        ->call('save');

    expect($user->fresh()->bio)->toBe('New bio text');
});

test('username validation rejects invalid characters', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ProfileSettings::class)
        ->set('username', 'Invalid User!')
        ->call('save')
        ->assertHasErrors('username');
});

test('username validation rejects reserved words', function (string $reserved) {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ProfileSettings::class)
        ->set('username', $reserved)
        ->call('save')
        ->assertHasErrors('username');
})->with(['admin', 'api', 'www']);
