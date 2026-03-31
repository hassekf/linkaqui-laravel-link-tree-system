<?php

namespace App\Livewire\Builder;

use App\Services\AnalyticsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnalyticsSummary extends Component
{
    public function render(AnalyticsService $analytics)
    {
        $user = Auth::user();

        return view('livewire.builder.analytics-summary', [
            'visitsToday' => $analytics->getUniqueVisits($user, now()->startOfDay()),
            'visits7Days' => $analytics->getUniqueVisits($user, now()->subDays(7)),
            'visits30Days' => $analytics->getUniqueVisits($user, now()->subDays(30)),
            'visitsAllTime' => $analytics->getUniqueVisits($user),
            'clicksToday' => $analytics->getTotalClicks($user, now()->startOfDay()),
            'clicks7Days' => $analytics->getTotalClicks($user, now()->subDays(7)),
            'clicks30Days' => $analytics->getTotalClicks($user, now()->subDays(30)),
            'clicksAllTime' => $analytics->getTotalClicks($user),
            'topLinks' => $analytics->getClicksByLink($user)->take(5),
        ]);
    }
}
