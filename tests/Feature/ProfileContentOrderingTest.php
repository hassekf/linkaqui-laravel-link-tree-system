<?php

use App\Livewire\Builder\ProfileSettings;
use App\Models\Embed;
use App\Models\Link;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('profile page returns unified content items sorted by position', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create(['username' => 'ordertest']);

    Link::factory()->for($user)->create(['title' => 'Link A', 'position' => 1, 'is_active' => true]);
    Embed::factory()->youtube()->for($user)->create(['title' => 'Embed B', 'position' => 2, 'is_active' => true]);
    Link::factory()->for($user)->create(['title' => 'Link C', 'position' => 3, 'is_active' => true]);

    $response = $this->get(route('profile.show', ['username' => 'ordertest']));

    $response->assertOk();
    $contentItems = $response->viewData('contentItems');
    expect($contentItems)->toHaveCount(3);
    expect($contentItems[0]->type)->toBe('link');
    expect($contentItems[0]->item->title)->toBe('Link A');
    expect($contentItems[1]->type)->toBe('embed');
    expect($contentItems[1]->item->title)->toBe('Embed B');
    expect($contentItems[2]->type)->toBe('link');
    expect($contentItems[2]->item->title)->toBe('Link C');
});

test('profile page defaults social position to bottom', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create(['username' => 'socialbot']);

    $response = $this->get(route('profile.show', ['username' => 'socialbot']));

    $response->assertOk();
    expect($response->viewData('socialPosition'))->toBe('bottom');
});

test('profile page respects social position setting', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create([
        'username' => 'socialtop',
        'settings' => ['social_position' => 'top'],
    ]);

    $response = $this->get(route('profile.show', ['username' => 'socialtop']));

    $response->assertOk();
    expect($response->viewData('socialPosition'))->toBe('top');
});

test('user social_position attribute defaults to bottom', function () {
    $user = User::factory()->create();
    expect($user->social_position)->toBe('bottom');
});

test('user social_position attribute reads from settings', function () {
    $user = User::factory()->create(['settings' => ['social_position' => 'top']]);
    expect($user->social_position)->toBe('top');
});

test('profile settings component can update social position', function () {
    $user = User::factory()->create();

    Livewire\Livewire::actingAs($user)
        ->test(ProfileSettings::class)
        ->assertSet('socialPosition', 'bottom')
        ->call('updateSocialPosition', 'top')
        ->assertSet('socialPosition', 'top');

    $user->refresh();
    expect($user->social_position)->toBe('top');
});
