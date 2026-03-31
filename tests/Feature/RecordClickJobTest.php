<?php

use App\Jobs\RecordClick;
use App\Models\Click;
use App\Models\Link;
use App\Models\User;

test('it creates a click record and increments link clicks_count', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['clicks_count' => 0]);

    RecordClick::dispatchSync(linkId: $link->id, ip: '192.168.1.1');

    expect(Click::count())->toBe(1);

    $click = Click::first();
    expect($click->link_id)->toBe($link->id)
        ->and($click->ip_hash)->not->toBe('192.168.1.1')
        ->and($click->ip_hash)->toHaveLength(64)
        ->and($click->clicked_at)->not->toBeNull();

    expect($link->fresh()->clicks_count)->toBe(1);
});

test('it records multiple clicks from the same ip', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create(['clicks_count' => 0]);

    RecordClick::dispatchSync(linkId: $link->id, ip: '192.168.1.1');
    RecordClick::dispatchSync(linkId: $link->id, ip: '192.168.1.1');

    expect(Click::count())->toBe(2);
    expect($link->fresh()->clicks_count)->toBe(2);
});

test('it hashes the ip with a daily rotating salt', function () {
    $user = User::factory()->create();
    $link = Link::factory()->for($user)->create();

    RecordClick::dispatchSync(linkId: $link->id, ip: '10.0.0.1');

    $click = Click::first();
    $expectedHash = hash('sha256', '10.0.0.1'.config('app.key').now()->format('Y-m-d'));

    expect($click->ip_hash)->toBe($expectedHash);
});
