<?php

use App\Jobs\RecordVisit;
use App\Models\User;
use App\Models\Visit;

test('it creates a visit record', function () {
    $user = User::factory()->create();

    RecordVisit::dispatchSync(
        userId: $user->id,
        ip: '192.168.1.1',
        userAgent: 'Mozilla/5.0',
        referer: 'https://google.com',
    );

    expect(Visit::count())->toBe(1);

    $visit = Visit::first();
    expect($visit->user_id)->toBe($user->id)
        ->and($visit->ip_hash)->not->toBe('192.168.1.1')
        ->and($visit->ip_hash)->toHaveLength(64)
        ->and($visit->user_agent)->toBe('Mozilla/5.0')
        ->and($visit->referer)->toBe('https://google.com')
        ->and($visit->visited_at)->not->toBeNull();
});

test('it deduplicates visits from the same ip within 24 hours', function () {
    $user = User::factory()->create();

    RecordVisit::dispatchSync(userId: $user->id, ip: '192.168.1.1');
    RecordVisit::dispatchSync(userId: $user->id, ip: '192.168.1.1');

    expect(Visit::count())->toBe(1);
});

test('it allows visits from different ips', function () {
    $user = User::factory()->create();

    RecordVisit::dispatchSync(userId: $user->id, ip: '192.168.1.1');
    RecordVisit::dispatchSync(userId: $user->id, ip: '192.168.1.2');

    expect(Visit::count())->toBe(2);
});

test('it truncates long user agents', function () {
    $user = User::factory()->create();
    $longAgent = str_repeat('A', 600);

    RecordVisit::dispatchSync(userId: $user->id, ip: '1.1.1.1', userAgent: $longAgent);

    expect(Visit::first()->user_agent)->toHaveLength(500);
});

test('it truncates long referers', function () {
    $user = User::factory()->create();
    $longReferer = str_repeat('B', 3000);

    RecordVisit::dispatchSync(userId: $user->id, ip: '1.1.1.1', referer: $longReferer);

    expect(Visit::first()->referer)->toHaveLength(2048);
});

test('it handles null user agent and referer', function () {
    $user = User::factory()->create();

    RecordVisit::dispatchSync(userId: $user->id, ip: '1.1.1.1');

    $visit = Visit::first();
    expect($visit->user_agent)->toBeNull()
        ->and($visit->referer)->toBeNull();
});
