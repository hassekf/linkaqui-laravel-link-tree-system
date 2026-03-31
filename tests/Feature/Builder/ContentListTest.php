<?php

use App\Livewire\Builder\ContentList;
use App\Models\Embed;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

test('content list renders with links and embeds merged', function () {
    $user = User::factory()->create();
    Link::factory()->for($user)->create(['position' => 1, 'title' => 'My Link']);
    Embed::factory()->youtube()->for($user)->create(['position' => 2, 'title' => 'My Video']);

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->assertSee('My Link')
        ->assertSee('My Video');
});

test('content items are sorted by position across types', function () {
    $user = User::factory()->create();
    Embed::factory()->youtube()->for($user)->create(['position' => 1]);
    Link::factory()->for($user)->create(['position' => 2]);

    $component = Livewire::actingAs($user)->test(ContentList::class);

    $items = $component->instance()->contentItems;

    // The embed at position 1 should come before the link at position 2
    expect($items->first()->type)->toBe('embed');
    expect($items->last()->type)->toBe('link');
});

test('user can add a link via content list', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('addLink')
        ->assertSet('showLinkEditor', true)
        ->set('title', 'My Website')
        ->set('url', 'https://example.com')
        ->set('linkType', 'link')
        ->call('saveLink');

    expect(Link::where('user_id', $user->id)->where('title', 'My Website')->exists())->toBeTrue();
});

test('user can add heading via content list', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('addHeading')
        ->assertSet('showLinkEditor', true)
        ->assertSet('linkType', 'heading')
        ->set('title', 'My Section')
        ->call('saveLink');

    expect(Link::where('user_id', $user->id)->where('type', 'heading')->where('title', 'My Section')->exists())->toBeTrue();
});

test('user can add divider via content list', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('addDivider');

    expect(Link::where('user_id', $user->id)->where('type', 'divider')->exists())->toBeTrue();
});

test('user can add youtube embed via content list', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('addEmbed')
        ->assertSet('showEmbedEditor', true)
        ->set('rawUrl', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        ->assertSet('detectedType', 'youtube')
        ->set('embedTitle', 'My Video')
        ->call('saveEmbed');

    expect(Embed::where('user_id', $user->id)->where('type', 'youtube')->exists())->toBeTrue();
});

test('user can add spotify embed via content list', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('addEmbed')
        ->set('rawUrl', 'https://open.spotify.com/track/4cOdK2wGLETKBW3PvgPWqT')
        ->assertSet('detectedType', 'spotify')
        ->set('embedTitle', 'My Song')
        ->call('saveEmbed');

    expect(Embed::where('user_id', $user->id)->where('type', 'spotify')->exists())->toBeTrue();
});

test('user can edit their link via content list', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['title' => 'Old Title']);

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('editLink', $link->id)
        ->assertSet('editingLinkId', $link->id)
        ->set('title', 'New Title')
        ->set('url', 'https://updated.com')
        ->call('saveLink');

    expect($link->fresh()->title)->toBe('New Title');
    expect($link->fresh()->url)->toBe('https://updated.com');
});

test('user cannot edit another users link via content list', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $link = Link::factory()->for($otherUser)->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('editLink', $link->id);
})->throws(ModelNotFoundException::class);

test('user can delete link via content list', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('deleteLink', $link->id);

    expect(Link::find($link->id))->toBeNull();
});

test('user can toggle link via content list', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['is_active' => true]);

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('toggleLink', $link->id);

    expect($link->fresh()->is_active)->toBeFalse();
});

test('user can delete embed via content list', function () {
    $user = User::factory()->create();
    $embed = Embed::factory()->youtube()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('deleteEmbed', $embed->id);

    expect(Embed::find($embed->id))->toBeNull();
});

test('user can toggle embed via content list', function () {
    $user = User::factory()->create();
    $embed = Embed::factory()->youtube()->for($user)->create(['is_active' => true]);

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('toggleEmbed', $embed->id);

    expect($embed->fresh()->is_active)->toBeFalse();
});

test('reorder content updates positions across links and embeds', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['position' => 1]);
    $embed = Embed::factory()->youtube()->for($user)->create(['position' => 2]);

    // Move embed to position 0 (before link) — this recalculates all positions
    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('reorderContent', 'embed-'.$embed->id, 0);

    expect($embed->fresh()->position)->toBe(0);
    expect($link->fresh()->position)->toBe(1);
});

test('user cannot reorder another users content', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $link = Link::factory()->for($otherUser)->create(['position' => 1]);

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('reorderContent', 'link-'.$link->id, 5);

    // Position should remain unchanged because query is scoped to auth user
    expect($link->fresh()->position)->toBe(1);
});

test('invalid embed url is rejected via content list', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ContentList::class)
        ->call('addEmbed')
        ->set('rawUrl', 'https://example.com/not-a-video')
        ->assertSet('detectedType', null)
        ->call('saveEmbed')
        ->assertHasErrors('rawUrl');
});
