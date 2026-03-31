<?php

namespace App\Jobs;

use App\Models\Visit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecordVisit implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $userId,
        public string $ip,
        public ?string $userAgent = null,
        public ?string $referer = null,
    ) {}

    public function handle(): void
    {
        $ipHash = hash('sha256', $this->ip.config('app.key').now()->format('Y-m-d'));

        $exists = Visit::where('user_id', $this->userId)
            ->where('ip_hash', $ipHash)
            ->where('visited_at', '>=', now()->subDay())
            ->exists();

        if ($exists) {
            return;
        }

        Visit::create([
            'user_id' => $this->userId,
            'ip_hash' => $ipHash,
            'user_agent' => $this->userAgent ? substr($this->userAgent, 0, 500) : null,
            'referer' => $this->referer ? substr($this->referer, 0, 2048) : null,
            'visited_at' => now(),
        ]);
    }
}
