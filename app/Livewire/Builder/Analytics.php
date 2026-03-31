<?php

namespace App\Livewire\Builder;

use App\Models\Link;
use App\Services\AnalyticsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.builder')]
#[Title('Analytics')]
class Analytics extends Component
{
    public string $period = '30';

    public function updatedPeriod(): void
    {
        // triggers re-render
    }

    private function getFrom(): ?\DateTimeInterface
    {
        return match ($this->period) {
            '7' => now()->subDays(7),
            '30' => now()->subDays(30),
            '90' => now()->subDays(90),
            default => null,
        };
    }

    #[Computed]
    public function uniqueVisits(): int
    {
        return app(AnalyticsService::class)->getUniqueVisits(Auth::user(), $this->getFrom());
    }

    #[Computed]
    public function totalClicks(): int
    {
        return app(AnalyticsService::class)->getTotalClicks(Auth::user(), $this->getFrom());
    }

    #[Computed]
    public function clickRate(): float
    {
        if ($this->uniqueVisits === 0) {
            return 0;
        }

        return round(($this->totalClicks / $this->uniqueVisits) * 100, 1);
    }

    /** @return Collection<int, Link> */
    #[Computed]
    public function topLinks(): Collection
    {
        return Auth::user()->links()
            ->where('type', 'link')
            ->reorder()
            ->orderByDesc('clicks_count')
            ->take(10)
            ->get();
    }

    /** @return Collection<int, object{date: string, count: int}> */
    #[Computed]
    public function visitsOverTime(): Collection
    {
        $days = $this->period === 'all' ? 365 : (int) $this->period;

        return app(AnalyticsService::class)->getVisitsOverTime(Auth::user(), $days);
    }

    /** @return Collection<int, object{referer: string, count: int}> */
    #[Computed]
    public function topReferrers(): Collection
    {
        return app(AnalyticsService::class)->getTopReferrers(Auth::user(), 10);
    }

    public function render()
    {
        return view('livewire.builder.analytics');
    }
}
