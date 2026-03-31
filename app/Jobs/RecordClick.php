<?php

namespace App\Jobs;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecordClick implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $linkId,
        public string $ip,
    ) {}

    public function handle(): void
    {
        $ipHash = hash('sha256', $this->ip.config('app.key').now()->format('Y-m-d'));

        Click::create([
            'link_id' => $this->linkId,
            'ip_hash' => $ipHash,
            'clicked_at' => now(),
        ]);

        Link::where('id', $this->linkId)->increment('clicks_count');
    }
}
