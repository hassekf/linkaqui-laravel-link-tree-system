<?php

namespace App\Livewire\Builder;

use App\Models\Theme;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class LivePreview extends Component
{
    #[On('preview-refresh')]
    public function refreshPreview(): void
    {
        // Re-render triggers automatically by Livewire
    }

    public function render()
    {
        $user = Auth::user();
        $user->load([
            'links' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
            'socialLinks' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
            'embeds' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
        ]);

        $contentItems = collect()
            ->merge($user->links->map(fn ($link) => (object) ['type' => 'link', 'item' => $link, 'position' => $link->position]))
            ->merge($user->embeds->map(fn ($embed) => (object) ['type' => 'embed', 'item' => $embed, 'position' => $embed->position]))
            ->sortBy('position')
            ->values();

        $theme = Theme::where('slug', $user->theme_slug)->first()
            ?? Theme::where('is_default', true)->first();

        $themeConfig = $theme?->config ?? [];
        $fontFamily = $user->font_family ?? $themeConfig['fontFamily'] ?? 'Inter';

        return view('livewire.builder.live-preview', [
            'profileUser' => $user,
            'themeConfig' => $themeConfig,
            'contentItems' => $contentItems,
            'socialPosition' => $user->social_position,
            'fontFamily' => $fontFamily,
            'productSearchEnabled' => $user->settings['product_search_enabled'] ?? false,
        ]);
    }
}
