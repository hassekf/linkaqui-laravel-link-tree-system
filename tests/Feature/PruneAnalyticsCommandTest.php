<?php

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use App\Models\Visit;

test('it prunes visits older than 90 days', function () {
    $user = User::factory()->create();
    Visit::factory()->for($user)->create(['visited_at' => now()->subDays(91)]);
    Visit::factory()->for($user)->create(['visited_at' => now()->subDays(30)]);

    $this->artisan('analytics:prune')->assertSuccessful();

    expect(Visit::count())->toBe(1);
});

test('it prunes clicks older than 90 days', function () {
    $link = Link::factory()->create();
    Click::factory()->for($link)->create(['clicked_at' => now()->subDays(91)]);
    Click::factory()->for($link)->create(['clicked_at' => now()->subDays(30)]);

    $this->artisan('analytics:prune')->assertSuccessful();

    expect(Click::count())->toBe(1);
});

test('it outputs pruned counts', function () {
    $user = User::factory()->create();
    Visit::factory()->count(2)->for($user)->create(['visited_at' => now()->subDays(100)]);

    $this->artisan('analytics:prune')
        ->expectsOutputToContain('Pruned 2 visits')
        ->assertSuccessful();
});
