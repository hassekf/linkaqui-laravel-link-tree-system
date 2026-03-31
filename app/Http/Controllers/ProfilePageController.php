<?php

namespace App\Http\Controllers;

use App\Jobs\RecordVisit;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilePageController extends Controller
{
    public function __invoke(Request $request, string $username): View
    {
        $user = User::where('username', $username)
            ->where('is_active', true)
            ->firstOrFail();

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

        RecordVisit::dispatch(
            userId: $user->id,
            ip: $request->ip(),
            userAgent: $request->userAgent(),
            referer: $request->header('referer'),
        );

        $fontFamily = $user->font_family ?? $themeConfig['fontFamily'] ?? 'Inter';

        return view('profile.show', [
            'profileUser' => $user,
            'themeConfig' => $themeConfig,
            'contentItems' => $contentItems,
            'socialPosition' => $user->social_position,
            'fontFamily' => $fontFamily,
            'productSearchEnabled' => $user->settings['product_search_enabled'] ?? false,
        ]);
    }
}
