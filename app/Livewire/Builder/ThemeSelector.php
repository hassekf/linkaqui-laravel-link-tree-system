<?php

namespace App\Livewire\Builder;

use App\Models\Theme;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ThemeSelector extends Component
{
    public string $selectedTheme = '';

    public function mount(): void
    {
        $this->selectedTheme = Auth::user()->theme_slug;
    }

    public function selectTheme(string $slug): void
    {
        $theme = Theme::where('slug', $slug)->firstOrFail();

        $user = Auth::user();
        $settings = $user->settings ?? [];
        $settings['theme_slug'] = $slug;
        $user->update(['settings' => $settings]);

        $this->selectedTheme = $slug;
        $this->dispatch('preview-refresh');
        $this->dispatch('toast', message: 'Tema alterado!');
    }

    public function render()
    {
        return view('livewire.builder.theme-selector', [
            'themes' => Theme::all(),
        ]);
    }
}
