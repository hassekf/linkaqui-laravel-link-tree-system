<?php

use App\Models\Link;
use App\Models\User;
use App\Models\Visit;

test('guests cannot access admin dashboard', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

test('non-admin users cannot access admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admin users can access admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('admin dashboard displays system stats', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->count(3)->create();
    Link::factory()->count(5)->create();
    Visit::factory()->count(2)->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Total de Usuários')
        ->assertSee('Total de Links')
        ->assertSee('Visitas (período)')
        ->assertSee('Cliques (período)');
});

test('admin dashboard shows recent signups', function () {
    $admin = User::factory()->admin()->create();
    $recentUser = User::factory()->create(['name' => 'Recent Test User']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Cadastros Recentes')
        ->assertSee('Recent Test User');
});
