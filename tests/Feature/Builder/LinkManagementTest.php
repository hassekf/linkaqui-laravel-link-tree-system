<?php

use App\Livewire\Builder\LinkList;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

test('builder page requires authentication', function () {
    $this->get(route('builder'))
        ->assertRedirect(route('login'));
});

test('authenticated user can access builder', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('builder'));

    // Confirms authenticated users are not redirected away
    expect($response->isRedirection())->toBeFalse();
});

test('user can add a link', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('addLink')
        ->assertSet('showEditor', true)
        ->set('title', 'My Website')
        ->set('url', 'https://example.com')
        ->set('type', 'link')
        ->call('saveLink');

    expect(Link::where('user_id', $user->id)->where('title', 'My Website')->exists())->toBeTrue();
});

test('user can edit their link', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['title' => 'Old Title']);

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('editLink', $link->id)
        ->assertSet('editingLinkId', $link->id)
        ->set('title', 'New Title')
        ->set('url', 'https://updated.com')
        ->call('saveLink');

    expect($link->fresh()->title)->toBe('New Title');
    expect($link->fresh()->url)->toBe('https://updated.com');
});

test('user cannot edit another users link', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $link = Link::factory()->for($otherUser)->create();

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('editLink', $link->id);
})->throws(ModelNotFoundException::class);

test('user can delete their link', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('deleteLink', $link->id);

    expect(Link::find($link->id))->toBeNull();
});

test('user can toggle link active status', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['is_active' => true]);

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('toggleLink', $link->id);

    expect($link->fresh()->is_active)->toBeFalse();
});

test('user can reorder links', function () {
    $user = User::factory()->create();
    $link1 = Link::factory()->for($user)->create(['position' => 1]);
    $link2 = Link::factory()->for($user)->create(['position' => 2]);

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('reorderLinks', $link1->id, 2)
        ->call('reorderLinks', $link2->id, 1);

    expect($link1->fresh()->position)->toBe(2);
    expect($link2->fresh()->position)->toBe(1);
});

test('user can add heading', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('addHeading')
        ->assertSet('showEditor', true)
        ->assertSet('type', 'heading')
        ->set('title', 'My Section')
        ->call('saveLink');

    expect(Link::where('user_id', $user->id)->where('type', 'heading')->where('title', 'My Section')->exists())->toBeTrue();
});

test('user can add divider', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LinkList::class)
        ->call('addDivider');

    expect(Link::where('user_id', $user->id)->where('type', 'divider')->exists())->toBeTrue();
});
