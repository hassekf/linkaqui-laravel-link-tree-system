<?php

use App\Livewire\Builder\ThemeSelector;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

test('user can select a theme', function () {
    $user = User::factory()->create();
    $theme = Theme::factory()->create(['slug' => 'ocean-breeze']);

    Livewire::actingAs($user)
        ->test(ThemeSelector::class)
        ->call('selectTheme', 'ocean-breeze')
        ->assertSet('selectedTheme', 'ocean-breeze');
});

test('selected theme persists in settings', function () {
    $user = User::factory()->create(['settings' => []]);
    $theme = Theme::factory()->create(['slug' => 'sunset-glow']);

    Livewire::actingAs($user)
        ->test(ThemeSelector::class)
        ->call('selectTheme', 'sunset-glow');

    expect($user->fresh()->settings['theme_slug'])->toBe('sunset-glow');
});
