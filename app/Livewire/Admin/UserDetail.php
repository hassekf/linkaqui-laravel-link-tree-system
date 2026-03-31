<?php

namespace App\Livewire\Admin;

use App\Models\Link;
use App\Models\Theme;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Detalhes do Usuario')]
class UserDetail extends Component
{
    #[Locked]
    public int $userId;

    public string $period = '30';

    public function mount(User $user): void
    {
        $this->userId = $user->id;
    }

    #[Computed]
    public function user(): User
    {
        return User::with(['links', 'socialLinks', 'embeds'])->findOrFail($this->userId);
    }

    #[Computed]
    public function uniqueVisits(): int
    {
        $from = $this->period === 'all' ? null : now()->subDays((int) $this->period);

        return app(AnalyticsService::class)->getUniqueVisits($this->user, $from);
    }

    #[Computed]
    public function totalClicks(): int
    {
        $from = $this->period === 'all' ? null : now()->subDays((int) $this->period);

        return app(AnalyticsService::class)->getTotalClicks($this->user, $from);
    }

    /** @return Collection<int, Link> */
    #[Computed]
    public function topLinks(): Collection
    {
        return $this->user->links()
            ->where('type', 'link')
            ->reorder()
            ->orderByDesc('clicks_count')
            ->take(5)
            ->get();
    }

    /** @return Collection<int, object{date: string, count: int}> */
    #[Computed]
    public function visitsOverTime(): Collection
    {
        $days = $this->period === 'all' ? 365 : (int) $this->period;

        return app(AnalyticsService::class)->getVisitsOverTime($this->user, $days);
    }

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function themeConfig(): array
    {
        $theme = Theme::where('slug', $this->user->theme_slug)->first()
            ?? Theme::where('is_default', true)->first();

        return $theme?->config ?? [];
    }

    public function render()
    {
        return view('livewire.admin.user-detail');
    }
}
