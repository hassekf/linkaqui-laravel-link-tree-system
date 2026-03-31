<?php

namespace App\Services;

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use App\Models\Visit;
use DateTimeInterface;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function getUniqueVisits(User $user, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): int
    {
        return Visit::where('user_id', $user->id)
            ->when($from, fn ($q) => $q->where('visited_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('visited_at', '<=', $to))
            ->count();
    }

    public function getTotalClicks(User $user, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): int
    {
        return Click::whereHas('link', fn ($q) => $q->where('user_id', $user->id))
            ->when($from, fn ($q) => $q->where('clicked_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('clicked_at', '<=', $to))
            ->count();
    }

    /**
     * @return Collection<int, Link>
     */
    public function getClicksByLink(User $user): Collection
    {
        return $user->links()
            ->reorder()
            ->orderByDesc('clicks_count')
            ->get();
    }

    /**
     * @return Collection<int, object{date: string, count: int}>
     */
    public function getVisitsOverTime(User $user, int $days = 30): Collection
    {
        return Visit::where('user_id', $user->id)
            ->where('visited_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * @return Collection<int, object{referer: string, count: int}>
     */
    public function getTopReferrers(User $user, int $limit = 10): Collection
    {
        return Visit::where('user_id', $user->id)
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->selectRaw('referer, COUNT(*) as count')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit($limit)
            ->get();
    }
}
