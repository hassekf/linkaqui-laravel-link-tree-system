<?php

use App\Livewire\Admin\ThemeManager;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

test('guests cannot access theme manager', function () {
    $this->get(route('admin.themes'))
        ->assertRedirect(route('login'));
});

test('non-admin users cannot access theme manager', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.themes'))
        ->assertForbidden();
});

test('admin users can access theme manager', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.themes'))
        ->assertOk();
});

test('admin can view themes list', function () {
    $admin = User::factory()->admin()->create();
    $theme = Theme::factory()->create(['name' => 'Test Theme']);

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->assertSee('Test Theme');
});

test('admin can set default theme', function () {
    $admin = User::factory()->admin()->create();
    $currentDefault = Theme::factory()->default()->create();
    $newDefault = Theme::factory()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('setDefault', $newDefault->id);

    expect($currentDefault->fresh()->is_default)->toBeFalse();
    expect($newDefault->fresh()->is_default)->toBeTrue();
});

test('admin can open the create theme editor', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->assertSet('showEditor', false)
        ->call('createTheme')
        ->assertSet('showEditor', true)
        ->assertSet('editingThemeId', null)
        ->assertSet('themeName', '');
});

test('admin can create a new theme', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('createTheme')
        ->set('themeName', 'My Custom Theme')
        ->set('themeSlug', 'my-custom-theme')
        ->set('themeDescription', 'A test theme')
        ->set('background', '#111111')
        ->set('backgroundType', 'solid')
        ->set('textPrimary', '#eeeeee')
        ->set('textSecondary', '#888888')
        ->set('fontFamily', 'Inter')
        ->set('animations', 'fade')
        ->call('saveTheme')
        ->assertSet('showEditor', false);

    $theme = Theme::where('slug', 'my-custom-theme')->first();

    expect($theme)->not->toBeNull();
    expect($theme->name)->toBe('My Custom Theme');
    expect($theme->description)->toBe('A test theme');
    expect($theme->config['background'])->toBe('#111111');
    expect($theme->config['backgroundType'])->toBe('solid');
    expect($theme->config['animations'])->toBe('fade');
});

test('admin can edit an existing theme', function () {
    $admin = User::factory()->admin()->create();
    $theme = Theme::factory()->create([
        'name' => 'Original Name',
        'slug' => 'original-name',
        'config' => [
            'background' => '#000000',
            'backgroundType' => 'solid',
            'meshColors' => ['#aaa', '#bbb', '#ccc'],
            'gradientColors' => ['#111', '#222'],
            'grain' => false,
            'cardBg' => 'rgba(0,0,0,0.1)',
            'cardBorder' => 'rgba(0,0,0,0.2)',
            'cardBlur' => false,
            'textPrimary' => '#ffffff',
            'textSecondary' => '#cccccc',
            'nameGradient' => ['#fff', '#ccc'],
            'avatarRingGradient' => ['#aaa', '#bbb', '#ccc'],
            'fontFamily' => 'Inter',
            'animations' => 'none',
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('editTheme', $theme->id)
        ->assertSet('showEditor', true)
        ->assertSet('editingThemeId', $theme->id)
        ->assertSet('themeName', 'Original Name')
        ->assertSet('background', '#000000')
        ->assertSet('meshColor1', '#aaa')
        ->set('themeName', 'Updated Name')
        ->set('background', '#222222')
        ->call('saveTheme')
        ->assertSet('showEditor', false);

    $theme->refresh();
    expect($theme->name)->toBe('Updated Name');
    expect($theme->config['background'])->toBe('#222222');
});

test('admin can delete a non-default theme', function () {
    $admin = User::factory()->admin()->create();
    $theme = Theme::factory()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('deleteTheme', $theme->id);

    expect(Theme::find($theme->id))->toBeNull();
});

test('admin cannot delete the default theme', function () {
    $admin = User::factory()->admin()->create();
    $theme = Theme::factory()->default()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('deleteTheme', $theme->id);

    expect(Theme::find($theme->id))->not->toBeNull();
});

test('admin can close the editor', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('createTheme')
        ->assertSet('showEditor', true)
        ->call('closeEditor')
        ->assertSet('showEditor', false)
        ->assertSet('editingThemeId', null);
});

test('slug is auto-generated from name when creating', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('createTheme')
        ->set('themeName', 'My New Theme')
        ->assertSet('themeSlug', 'my-new-theme');
});

test('saving a theme validates required fields', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('createTheme')
        ->set('themeName', '')
        ->set('themeSlug', '')
        ->call('saveTheme')
        ->assertHasErrors(['themeName', 'themeSlug']);
});

test('saving a theme validates unique slug', function () {
    $admin = User::factory()->admin()->create();
    Theme::factory()->create(['slug' => 'existing-slug']);

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('createTheme')
        ->set('themeName', 'New Theme')
        ->set('themeSlug', 'existing-slug')
        ->call('saveTheme')
        ->assertHasErrors(['themeSlug']);
});

test('editing theme populates all fields', function () {
    $admin = User::factory()->admin()->create();
    $theme = Theme::factory()->create([
        'name' => 'Full Theme',
        'slug' => 'full-theme',
        'description' => 'A fully configured theme',
        'config' => [
            'background' => '#1a1a2e',
            'backgroundType' => 'mesh',
            'meshColors' => ['#e94560', '#0f3460', '#533483'],
            'gradientColors' => ['#16213e', '#0f3460'],
            'grain' => true,
            'cardBg' => 'rgba(255,255,255,0.08)',
            'cardBorder' => 'rgba(255,255,255,0.15)',
            'cardBlur' => true,
            'textPrimary' => '#eaeaea',
            'textSecondary' => '#b0b0b0',
            'nameGradient' => ['#e94560', '#533483'],
            'avatarRingGradient' => ['#e94560', '#0f3460', '#533483'],
            'fontFamily' => 'Poppins',
            'animations' => 'fade',
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('editTheme', $theme->id)
        ->assertSet('themeName', 'Full Theme')
        ->assertSet('themeSlug', 'full-theme')
        ->assertSet('themeDescription', 'A fully configured theme')
        ->assertSet('background', '#1a1a2e')
        ->assertSet('backgroundType', 'mesh')
        ->assertSet('meshColor1', '#e94560')
        ->assertSet('meshColor2', '#0f3460')
        ->assertSet('meshColor3', '#533483')
        ->assertSet('gradientColor1', '#16213e')
        ->assertSet('gradientColor2', '#0f3460')
        ->assertSet('grain', true)
        ->assertSet('cardBg', 'rgba(255,255,255,0.08)')
        ->assertSet('cardBorder', 'rgba(255,255,255,0.15)')
        ->assertSet('cardBlur', true)
        ->assertSet('textPrimary', '#eaeaea')
        ->assertSet('textSecondary', '#b0b0b0')
        ->assertSet('nameGradient1', '#e94560')
        ->assertSet('nameGradient2', '#533483')
        ->assertSet('ringColor1', '#e94560')
        ->assertSet('ringColor2', '#0f3460')
        ->assertSet('ringColor3', '#533483')
        ->assertSet('fontFamily', 'Poppins')
        ->assertSet('animations', 'fade');
});

test('editing theme allows keeping the same slug', function () {
    $admin = User::factory()->admin()->create();
    $theme = Theme::factory()->create([
        'slug' => 'my-slug',
        'config' => [
            'background' => '#000',
            'backgroundType' => 'solid',
            'grain' => false,
            'cardBg' => 'rgba(0,0,0,0.1)',
            'cardBorder' => 'rgba(0,0,0,0.2)',
            'cardBlur' => false,
            'textPrimary' => '#fff',
            'textSecondary' => '#ccc',
            'nameGradient' => ['#fff', '#ccc'],
            'avatarRingGradient' => ['#aaa', '#bbb', '#ccc'],
            'fontFamily' => 'Inter',
            'animations' => 'staggered',
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(ThemeManager::class)
        ->call('editTheme', $theme->id)
        ->set('themeName', 'Updated Name')
        ->call('saveTheme')
        ->assertHasNoErrors();

    expect($theme->fresh()->name)->toBe('Updated Name');
});
