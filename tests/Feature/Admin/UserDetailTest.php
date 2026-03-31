<?php

use App\Models\Link;
use App\Models\User;
use App\Models\Visit;

test('guests cannot access user detail page', function () {
    $user = User::factory()->create();

    $this->get(route('admin.users.show', $user))
        ->assertRedirect(route('login'));
});

test('non-admin users cannot access user detail page', function () {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.users.show', $targetUser))
        ->assertForbidden();
});

test('admin can view user detail page', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create(['name' => 'Test Detail User']);

    $this->actingAs($admin)
        ->get(route('admin.users.show', $targetUser))
        ->assertOk()
        ->assertSee('Test Detail User')
        ->assertSee($targetUser->username)
        ->assertSee($targetUser->email);
});

test('user detail page shows analytics stats', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create();
    Visit::factory()->count(3)->create(['user_id' => $targetUser->id]);
    Link::factory()->count(2)->create(['user_id' => $targetUser->id, 'type' => 'link']);

    $this->actingAs($admin)
        ->get(route('admin.users.show', $targetUser))
        ->assertOk()
        ->assertSee('Visitas')
        ->assertSee('Cliques')
        ->assertSee('Visitas por dia')
        ->assertSee('Top links');
});

test('user detail page shows linktree preview', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.users.show', $targetUser))
        ->assertOk()
        ->assertSee('Preview da página');
});
