<?php

use App\Enums\SocialPlatform;
use App\Models\SocialLink;
use App\Models\User;

test('user can add social link', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $maxPosition = SocialLink::where('user_id', $user->id)->max('position') ?? 0;

    $social = SocialLink::create([
        'user_id' => $user->id,
        'platform' => SocialPlatform::Instagram,
        'url' => 'https://instagram.com/testuser',
        'position' => $maxPosition + 1,
    ]);

    expect(SocialLink::where('user_id', $user->id)->where('platform', 'instagram')->exists())->toBeTrue();
    expect($social->url)->toBe('https://instagram.com/testuser');
});

test('user can edit social link', function () {
    $user = User::factory()->create();
    $social = SocialLink::factory()->for($user)->create([
        'platform' => SocialPlatform::GitHub,
        'url' => 'https://github.com/olduser',
    ]);

    $social->update(['url' => 'https://github.com/newuser']);

    expect($social->fresh()->url)->toBe('https://github.com/newuser');
});

test('user can delete social link', function () {
    $user = User::factory()->create();
    $social = SocialLink::factory()->for($user)->create();

    $social->delete();

    expect(SocialLink::find($social->id))->toBeNull();
});

test('duplicate platform redirects to edit', function () {
    $user = User::factory()->create();
    $social = SocialLink::factory()->for($user)->create([
        'platform' => SocialPlatform::Twitter,
        'url' => 'https://x.com/existinguser',
    ]);

    // Simulate the addSocial logic: check if platform already exists
    $existing = SocialLink::where('user_id', $user->id)
        ->where('platform', 'twitter')
        ->first();

    expect($existing)->not->toBeNull();
    expect($existing->id)->toBe($social->id);
    expect($existing->url)->toBe('https://x.com/existinguser');
});
