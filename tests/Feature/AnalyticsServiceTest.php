<?php

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use App\Models\Visit;
use App\Services\AnalyticsService;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->service = new AnalyticsService;
    $this->user = User::factory()->create();
});

test('getUniqueVisits returns total visits for user', function () {
    Visit::factory()->count(5)->for($this->user)->create();
    Visit::factory()->count(3)->create(); // other user

    expect($this->service->getUniqueVisits($this->user))->toBe(5);
});

test('getUniqueVisits filters by date range', function () {
    Visit::factory()->for($this->user)->create(['visited_at' => now()->subDays(2)]);
    Visit::factory()->for($this->user)->create(['visited_at' => now()->subDays(10)]);

    $count = $this->service->getUniqueVisits($this->user, Carbon::now()->subDays(7));

    expect($count)->toBe(1);
});

test('getTotalClicks returns clicks for user links', function () {
    $link = Link::factory()->for($this->user)->create();
    Click::factory()->count(3)->for($link)->create();

    $otherLink = Link::factory()->create();
    Click::factory()->count(2)->for($otherLink)->create();

    expect($this->service->getTotalClicks($this->user))->toBe(3);
});

test('getTotalClicks filters by date range', function () {
    $link = Link::factory()->for($this->user)->create();
    Click::factory()->for($link)->create(['clicked_at' => now()->subDays(2)]);
    Click::factory()->for($link)->create(['clicked_at' => now()->subDays(10)]);

    $count = $this->service->getTotalClicks($this->user, Carbon::now()->subDays(7));

    expect($count)->toBe(1);
});

test('getClicksByLink returns links ordered by clicks_count', function () {
    $linkA = Link::factory()->for($this->user)->create(['clicks_count' => 10, 'title' => 'Link A']);
    $linkB = Link::factory()->for($this->user)->create(['clicks_count' => 25, 'title' => 'Link B']);

    $result = $this->service->getClicksByLink($this->user);

    expect($result->first()->title)->toBe('Link B')
        ->and($result->first()->clicks_count)->toBe(25)
        ->and($result->last()->title)->toBe('Link A')
        ->and($result->last()->clicks_count)->toBe(10);
});

test('getVisitsOverTime returns daily counts', function () {
    Visit::factory()->for($this->user)->create(['visited_at' => now()->subDays(1)]);
    Visit::factory()->count(2)->for($this->user)->create(['visited_at' => now()]);

    $result = $this->service->getVisitsOverTime($this->user, 7);

    expect($result)->toHaveCount(2);
});

test('getTopReferrers returns referrers ordered by count', function () {
    Visit::factory()->count(3)->for($this->user)->create(['referer' => 'https://google.com']);
    Visit::factory()->count(1)->for($this->user)->create(['referer' => 'https://twitter.com']);
    Visit::factory()->for($this->user)->create(['referer' => null]);

    $result = $this->service->getTopReferrers($this->user);

    expect($result)->toHaveCount(2)
        ->and($result->first()->referer)->toBe('https://google.com')
        ->and($result->first()->count)->toBe(3);
});
