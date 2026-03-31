<?php

use App\Livewire\Builder\EmbedManager;
use App\Models\Embed;
use App\Models\User;
use Livewire\Livewire;

test('user can add youtube embed', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(EmbedManager::class)
        ->call('addEmbed')
        ->assertSet('showEditor', true)
        ->set('rawUrl', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        ->assertSet('detectedType', 'youtube')
        ->set('title', 'My Video')
        ->call('saveEmbed');

    expect(Embed::where('user_id', $user->id)->where('type', 'youtube')->exists())->toBeTrue();
});

test('user can add spotify embed', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(EmbedManager::class)
        ->call('addEmbed')
        ->set('rawUrl', 'https://open.spotify.com/track/4cOdK2wGLETKBW3PvgPWqT')
        ->assertSet('detectedType', 'spotify')
        ->set('title', 'My Song')
        ->call('saveEmbed');

    expect(Embed::where('user_id', $user->id)->where('type', 'spotify')->exists())->toBeTrue();
});

test('invalid url is rejected', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(EmbedManager::class)
        ->call('addEmbed')
        ->set('rawUrl', 'https://example.com/not-a-video')
        ->assertSet('detectedType', null)
        ->assertSet('embedUrl', null)
        ->call('saveEmbed')
        ->assertHasErrors('rawUrl');
});

test('user can delete embed', function () {
    $user = User::factory()->create();
    $embed = Embed::factory()->youtube()->for($user)->create();

    Livewire::actingAs($user)
        ->test(EmbedManager::class)
        ->call('deleteEmbed', $embed->id);

    expect(Embed::find($embed->id))->toBeNull();
});
