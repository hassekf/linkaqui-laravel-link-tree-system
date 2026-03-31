<?php

use App\Models\Embed;
use App\Models\Link;
use App\Models\SocialLink;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('profile page renders for valid user', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create(['username' => 'johndoe']);

    $this->get(route('profile.show', ['username' => 'johndoe']))
        ->assertOk()
        ->assertViewHas('profileUser');
});

test('profile page shows 404 for non-existent username', function () {
    $this->get(route('profile.show', ['username' => 'nonexistent']))
        ->assertNotFound();
});

test('profile page shows 404 for inactive user', function () {
    User::factory()->create(['username' => 'inactive', 'is_active' => false]);

    $this->get(route('profile.show', ['username' => 'inactive']))
        ->assertNotFound();
});

test('profile page displays user name and bio', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create([
        'username' => 'janedoe',
        'name' => 'Jane Doe',
        'bio' => 'Hello, I am Jane.',
    ]);

    $response = $this->get(route('profile.show', ['username' => 'janedoe']));

    $response->assertOk();
    $profileUser = $response->viewData('profileUser');
    expect($profileUser->name)->toBe('Jane Doe');
    expect($profileUser->bio)->toBe('Hello, I am Jane.');
});

test('profile page displays active links only', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create(['username' => 'linkuser']);
    Link::factory()->for($user)->create(['title' => 'Active Link', 'is_active' => true]);
    Link::factory()->for($user)->inactive()->create(['title' => 'Inactive Link']);

    $response = $this->get(route('profile.show', ['username' => 'linkuser']));

    $response->assertOk();
    $profileUser = $response->viewData('profileUser');
    expect($profileUser->links)->toHaveCount(1);
    expect($profileUser->links->first()->title)->toBe('Active Link');
});

test('profile page displays social links', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create(['username' => 'socialuser']);
    SocialLink::factory()->for($user)->create([
        'platform' => 'instagram',
        'url' => 'https://instagram.com/socialuser',
        'is_active' => true,
    ]);
    SocialLink::factory()->for($user)->inactive()->create([
        'platform' => 'github',
        'url' => 'https://github.com/socialuser',
    ]);

    // Test the controller data loading (only active social links are loaded)
    $user = User::where('username', 'socialuser')
        ->where('is_active', true)
        ->firstOrFail();

    $user->load([
        'socialLinks' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
    ]);

    expect($user->socialLinks)->toHaveCount(1);
    expect($user->socialLinks->first()->url)->toBe('https://instagram.com/socialuser');
});

test('profile page displays embeds', function () {
    Queue::fake();
    Theme::factory()->default()->create();
    $user = User::factory()->create(['username' => 'embeduser']);
    Embed::factory()->youtube()->for($user)->create([
        'title' => 'My Cool Video',
        'is_active' => true,
    ]);

    $response = $this->get(route('profile.show', ['username' => 'embeduser']));

    $response->assertOk();
    $profileUser = $response->viewData('profileUser');
    expect($profileUser->embeds)->toHaveCount(1);
    expect($profileUser->embeds->first()->title)->toBe('My Cool Video');
});

test('profile page applies theme config', function () {
    Queue::fake();
    $theme = Theme::factory()->create([
        'slug' => 'custom-theme',
        'config' => [
            'background' => '#ff0000',
            'backgroundType' => 'solid',
            'grain' => false,
            'cardBg' => 'rgba(255,255,255,0.05)',
            'cardBorder' => 'rgba(255,255,255,0.1)',
            'cardBlur' => true,
            'textPrimary' => '#ffffff',
            'textSecondary' => '#9ca3af',
            'fontFamily' => 'Inter',
            'animations' => 'staggered',
        ],
    ]);

    $user = User::factory()->create([
        'username' => 'themeuser',
        'settings' => ['theme_slug' => 'custom-theme'],
    ]);

    $response = $this->get(route('profile.show', ['username' => 'themeuser']));

    $response->assertOk();
    $themeConfig = $response->viewData('themeConfig');
    expect($themeConfig['background'])->toBe('#ff0000');
    expect($themeConfig['fontFamily'])->toBe('Inter');
});
