<?php

namespace App\Console\Commands;

use App\Models\Click;
use App\Models\Visit;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('analytics:prune')]
#[Description('Delete visits and clicks older than 90 days')]
class PruneAnalyticsCommand extends Command
{
    public function handle(): int
    {
        $cutoff = now()->subDays(90);

        $deletedVisits = Visit::where('visited_at', '<', $cutoff)->delete();
        $deletedClicks = Click::where('clicked_at', '<', $cutoff)->delete();

        $this->info("Pruned {$deletedVisits} visits and {$deletedClicks} clicks older than 90 days.");

        return self::SUCCESS;
    }
}
