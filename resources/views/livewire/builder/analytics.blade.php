<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-builder-text">Analytics</h1>

        <div class="flex items-center gap-1 bg-builder-surface border border-builder-border rounded-lg p-1">
            @foreach([
                '7' => '7 dias',
                '30' => '30 dias',
                '90' => '90 dias',
                'all' => 'Tudo',
            ] as $value => $label)
                <button wire:click="$set('period', '{{ $value }}')"
                        class="text-xs px-3 py-1.5 rounded-md transition-colors {{ $period === $value ? 'bg-builder-primary text-builder-primary-text' : 'text-builder-text-muted hover:text-builder-text' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-builder.card>
            <p class="text-sm text-builder-text-muted">Visitas únicas</p>
            <p class="text-3xl font-bold text-builder-text mt-1">{{ number_format($this->uniqueVisits) }}</p>
        </x-builder.card>

        <x-builder.card>
            <p class="text-sm text-builder-text-muted">Total de cliques</p>
            <p class="text-3xl font-bold text-builder-text mt-1">{{ number_format($this->totalClicks) }}</p>
        </x-builder.card>

        <x-builder.card>
            <p class="text-sm text-builder-text-muted">Taxa de cliques</p>
            <p class="text-3xl font-bold text-builder-text mt-1">{{ $this->clickRate }}%</p>
        </x-builder.card>
    </div>

    {{-- Chart: Visits Over Time --}}
    <x-builder.card>
        <h2 class="text-sm font-semibold text-builder-text mb-4">Visitas por dia</h2>

        @if($this->visitsOverTime->isNotEmpty())
            <div class="flex items-end gap-1 h-40">
                @php($maxVisits = $this->visitsOverTime->max('count') ?: 1)
                @foreach($this->visitsOverTime as $day)
                    <div class="flex-1 rounded-t transition-all hover:opacity-80"
                         style="height: {{ ($day->count / $maxVisits) * 100 }}%; background: var(--color-builder-primary); min-height: 2px;"
                         title="{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}: {{ $day->count }} visitas">
                    </div>
                @endforeach
            </div>
            <div class="flex gap-1 mt-1">
                @foreach($this->visitsOverTime as $day)
                    <div class="flex-1 text-center">
                        @if($loop->first || $loop->last || $loop->iteration % max(1, intval($this->visitsOverTime->count() / 6)) === 0)
                            <span class="text-[10px] text-builder-text-muted">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-builder-text-muted py-8 text-center">Nenhuma visita neste período.</p>
        @endif
    </x-builder.card>

    {{-- Two Columns: Top Links + Referrers --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Top Links --}}
        <x-builder.card>
            <h2 class="text-sm font-semibold text-builder-text mb-4">Top 10 Links</h2>

            @if($this->topLinks->isNotEmpty())
                @php($maxClicks = $this->topLinks->max('clicks_count') ?: 1)
                <div class="space-y-1">
                    @foreach($this->topLinks as $link)
                        <div class="flex items-center gap-3 py-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-builder-text truncate">{{ $link->title }}</p>
                                <div class="mt-1 h-1.5 rounded-full bg-builder-surface-hover overflow-hidden">
                                    <div class="h-full rounded-full" style="width: {{ ($link->clicks_count / $maxClicks) * 100 }}%; background: var(--color-builder-primary);"></div>
                                </div>
                            </div>
                            <span class="text-sm font-mono text-builder-text-muted shrink-0">{{ number_format($link->clicks_count) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-builder-text-muted py-4 text-center">Nenhum link com cliques.</p>
            @endif
        </x-builder.card>

        {{-- Top Referrers --}}
        <x-builder.card>
            <h2 class="text-sm font-semibold text-builder-text mb-4">Top Referrers</h2>

            @if($this->topReferrers->isNotEmpty())
                <div class="space-y-1">
                    @foreach($this->topReferrers as $referrer)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-builder-text truncate mr-2">{{ $referrer->referer }}</span>
                            <span class="text-sm font-mono text-builder-text-muted shrink-0">{{ number_format($referrer->count) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-builder-text-muted py-4 text-center">Nenhum referrer registrado.</p>
            @endif
        </x-builder.card>
    </div>
</div>
