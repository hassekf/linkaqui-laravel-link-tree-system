<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Analytics</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4 space-y-4">
            {{-- Visits --}}
            <div>
                <h3 class="text-sm font-medium text-builder-text-muted mb-2">Visitas Unicas</h3>
                <div class="grid grid-cols-4 gap-2">
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($visitsToday) }}</p>
                        <p class="text-xs text-builder-text-muted">Hoje</p>
                    </div>
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($visits7Days) }}</p>
                        <p class="text-xs text-builder-text-muted">7 dias</p>
                    </div>
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($visits30Days) }}</p>
                        <p class="text-xs text-builder-text-muted">30 dias</p>
                    </div>
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($visitsAllTime) }}</p>
                        <p class="text-xs text-builder-text-muted">Total</p>
                    </div>
                </div>
            </div>

            {{-- Clicks --}}
            <div>
                <h3 class="text-sm font-medium text-builder-text-muted mb-2">Cliques</h3>
                <div class="grid grid-cols-4 gap-2">
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($clicksToday) }}</p>
                        <p class="text-xs text-builder-text-muted">Hoje</p>
                    </div>
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($clicks7Days) }}</p>
                        <p class="text-xs text-builder-text-muted">7 dias</p>
                    </div>
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($clicks30Days) }}</p>
                        <p class="text-xs text-builder-text-muted">30 dias</p>
                    </div>
                    <div class="text-center p-2 bg-builder-surface-hover rounded-lg">
                        <p class="text-lg font-bold text-builder-text">{{ number_format($clicksAllTime) }}</p>
                        <p class="text-xs text-builder-text-muted">Total</p>
                    </div>
                </div>
            </div>

            {{-- Top Links --}}
            @if($topLinks->isNotEmpty())
                <div>
                    <h3 class="text-sm font-medium text-builder-text-muted mb-2">Top 5 Links</h3>
                    <div class="space-y-1">
                        @foreach($topLinks as $link)
                            <div class="flex items-center justify-between p-2 bg-builder-surface-hover rounded-lg">
                                <span class="text-sm text-builder-text truncate mr-2">{{ $link->title }}</span>
                                <span class="text-sm font-medium text-builder-text-muted whitespace-nowrap">{{ number_format($link->clicks_count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-builder.card>
