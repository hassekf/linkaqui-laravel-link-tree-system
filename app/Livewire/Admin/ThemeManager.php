<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Gerenciar Temas')]
class ThemeManager extends Component
{
    public bool $showEditor = false;

    #[Locked]
    public ?int $editingThemeId = null;

    // Basic info
    public string $themeName = '';

    public string $themeSlug = '';

    public string $themeDescription = '';

    // Config fields
    public string $background = '#0a0a0f';

    public string $backgroundType = 'mesh';

    public string $meshColor1 = '#7c5cfc';

    public string $meshColor2 = '#00d4ff';

    public string $meshColor3 = '#ff6bcb';

    public string $gradientColor1 = '#1e3a5f';

    public string $gradientColor2 = '#0c1222';

    public bool $grain = true;

    public string $cardBg = 'rgba(255,255,255,0.05)';

    public string $cardBorder = 'rgba(255,255,255,0.1)';

    public bool $cardBlur = true;

    public string $textPrimary = '#ffffff';

    public string $textSecondary = '#9ca3af';

    public string $nameGradient1 = '#ffffff';

    public string $nameGradient2 = '#a78bfa';

    public string $ringColor1 = '#7c5cfc';

    public string $ringColor2 = '#00d4ff';

    public string $ringColor3 = '#ff6bcb';

    public string $fontFamily = 'Inter';

    public string $animations = 'staggered';

    /** @return Collection<int, Theme> */
    #[Computed]
    public function themes(): Collection
    {
        return Theme::orderBy('name')->get();
    }

    public function setDefault(int $themeId): void
    {
        Theme::where('is_default', true)->update(['is_default' => false]);
        Theme::where('id', $themeId)->update(['is_default' => true]);
        unset($this->themes);
    }

    public function createTheme(): void
    {
        $this->resetEditorFields();
        $this->editingThemeId = null;
        $this->showEditor = true;
    }

    public function editTheme(int $id): void
    {
        $theme = Theme::findOrFail($id);

        $this->editingThemeId = $theme->id;
        $this->themeName = $theme->name;
        $this->themeSlug = $theme->slug;
        $this->themeDescription = $theme->description ?? '';

        $config = $theme->config ?? [];
        $this->background = $config['background'] ?? '#0a0a0f';
        $this->backgroundType = $config['backgroundType'] ?? 'mesh';
        $this->meshColor1 = $config['meshColors'][0] ?? '#7c5cfc';
        $this->meshColor2 = $config['meshColors'][1] ?? '#00d4ff';
        $this->meshColor3 = $config['meshColors'][2] ?? '#ff6bcb';
        $this->gradientColor1 = $config['gradientColors'][0] ?? '#1e3a5f';
        $this->gradientColor2 = $config['gradientColors'][1] ?? '#0c1222';
        $this->grain = $config['grain'] ?? true;
        $this->cardBg = $config['cardBg'] ?? 'rgba(255,255,255,0.05)';
        $this->cardBorder = $config['cardBorder'] ?? 'rgba(255,255,255,0.1)';
        $this->cardBlur = $config['cardBlur'] ?? true;
        $this->textPrimary = $config['textPrimary'] ?? '#ffffff';
        $this->textSecondary = $config['textSecondary'] ?? '#9ca3af';
        $this->nameGradient1 = $config['nameGradient'][0] ?? '#ffffff';
        $this->nameGradient2 = $config['nameGradient'][1] ?? '#a78bfa';
        $this->ringColor1 = $config['avatarRingGradient'][0] ?? '#7c5cfc';
        $this->ringColor2 = $config['avatarRingGradient'][1] ?? '#00d4ff';
        $this->ringColor3 = $config['avatarRingGradient'][2] ?? '#ff6bcb';
        $this->fontFamily = $config['fontFamily'] ?? 'Inter';
        $this->animations = $config['animations'] ?? 'staggered';

        $this->showEditor = true;
    }

    public function saveTheme(): void
    {
        $this->validate([
            'themeName' => 'required|string|max:255',
            'themeSlug' => 'required|string|max:255|unique:themes,slug,'.$this->editingThemeId,
            'themeDescription' => 'nullable|string|max:500',
            'background' => 'required|string',
            'backgroundType' => 'required|in:mesh,solid,gradient',
            'fontFamily' => 'required|string',
            'animations' => 'required|in:staggered,fade,none',
        ]);

        $config = [
            'background' => $this->background,
            'backgroundType' => $this->backgroundType,
            'meshColors' => [$this->meshColor1, $this->meshColor2, $this->meshColor3],
            'gradientColors' => [$this->gradientColor1, $this->gradientColor2],
            'grain' => $this->grain,
            'cardBg' => $this->cardBg,
            'cardBorder' => $this->cardBorder,
            'cardBlur' => $this->cardBlur,
            'textPrimary' => $this->textPrimary,
            'textSecondary' => $this->textSecondary,
            'nameGradient' => [$this->nameGradient1, $this->nameGradient2],
            'avatarRingGradient' => [$this->ringColor1, $this->ringColor2, $this->ringColor3],
            'statusIndicator' => true,
            'fontFamily' => $this->fontFamily,
            'animations' => $this->animations,
        ];

        $data = [
            'name' => $this->themeName,
            'slug' => $this->themeSlug,
            'description' => $this->themeDescription ?: null,
            'config' => $config,
        ];

        if ($this->editingThemeId) {
            Theme::where('id', $this->editingThemeId)->update($data);
        } else {
            Theme::create($data);
        }

        $this->closeEditor();
        unset($this->themes);
    }

    public function deleteTheme(int $id): void
    {
        $theme = Theme::findOrFail($id);

        if ($theme->is_default) {
            return;
        }

        $theme->delete();
        unset($this->themes);
    }

    public function closeEditor(): void
    {
        $this->showEditor = false;
        $this->editingThemeId = null;
        $this->resetEditorFields();
    }

    public function updatedThemeName(string $value): void
    {
        if (! $this->editingThemeId) {
            $this->themeSlug = Str::slug($value);
        }
    }

    private function resetEditorFields(): void
    {
        $this->themeName = '';
        $this->themeSlug = '';
        $this->themeDescription = '';
        $this->background = '#0a0a0f';
        $this->backgroundType = 'mesh';
        $this->meshColor1 = '#7c5cfc';
        $this->meshColor2 = '#00d4ff';
        $this->meshColor3 = '#ff6bcb';
        $this->gradientColor1 = '#1e3a5f';
        $this->gradientColor2 = '#0c1222';
        $this->grain = true;
        $this->cardBg = 'rgba(255,255,255,0.05)';
        $this->cardBorder = 'rgba(255,255,255,0.1)';
        $this->cardBlur = true;
        $this->textPrimary = '#ffffff';
        $this->textSecondary = '#9ca3af';
        $this->nameGradient1 = '#ffffff';
        $this->nameGradient2 = '#a78bfa';
        $this->ringColor1 = '#7c5cfc';
        $this->ringColor2 = '#00d4ff';
        $this->ringColor3 = '#ff6bcb';
        $this->fontFamily = 'Inter';
        $this->animations = 'staggered';
    }

    public function render()
    {
        return view('livewire.admin.theme-manager');
    }
}
