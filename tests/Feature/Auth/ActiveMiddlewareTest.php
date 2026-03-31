<?php

use App\Models\User;

test('inactive user is logged out and redirected', function () {
    $user = User::factory()->create(['is_active' => false]);

    $this->actingAs($user)
        ->get(route('builder'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('active user can access builder', function () {
    $user = User::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->get(route('builder'))
        ->assertOk();
});
