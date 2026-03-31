<?php

namespace App\Livewire\Admin;

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    public string $period = '30';

    #[Computed]
    public function totalUsers(): int
    {
        return User::count();
    }

    #[Computed]
    public function totalLinks(): int
    {
        return Link::count();
    }

    #[Computed]
    public function totalVisits(): int
    {
        $query = Visit::query();

        if ($this->period !== 'all') {
            $query->where('visited_at', '>=', now()->subDays((int) $this->period));
        }

        return $query->count();
    }

    #[Computed]
    public function todayVisits(): int
    {
        return Visit::whereDate('visited_at', today())->count();
    }

    #[Computed]
    public function totalClicks(): int
    {
        $query = Click::query();

        if ($this->period !== 'all') {
            $query->where('clicked_at', '>=', now()->subDays((int) $this->period));
        }

        return $query->count();
    }

    /** @return SupportCollection<int, object{date: string, count: int}> */
    #[Computed]
    public function visitsOverTime(): SupportCollection
    {
        $days = $this->period === 'all' ? 365 : (int) $this->period;

        return Visit::where('visited_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /** @return Collection<int, User> */
    #[Computed]
    public function topUsers(): Collection
    {
        return User::withCount(['visits' => function ($q) {
            if ($this->period !== 'all') {
                $q->where('visited_at', '>=', now()->subDays((int) $this->period));
            }
        }])
            ->orderByDesc('visits_count')
            ->take(10)
            ->get();
    }

    /** @return Collection<int, User> */
    #[Computed]
    public function recentUsers(): Collection
    {
        return User::latest()->take(10)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
