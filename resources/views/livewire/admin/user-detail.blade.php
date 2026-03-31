<div class="p-6 space-y-6">
    <!-- Back button + heading -->
    <div class="flex items-center gap-4">
        <flux:button variant="ghost" icon="arrow-left" :href="route('admin.users')" wire:navigate />
        <div class="flex items-center gap-3">
            <img src="{{ $this->user->avatar_url }}" class="w-12 h-12 rounded-full object-cover" />
            <div>
                <flux:heading size="xl">{{ $this->user->name }}</flux:heading>
                <flux:text>{{ '@' . $this->user->username }} &middot; {{ $this->user->email }}</flux:text>
            </div>
        </div>
        <div class="ml-auto flex gap-2">
            <flux:badge :color="$this->user->is_active ? 'green' : 'red'">{{ $this->user->is_active ? 'Ativo' : 'Inativo' }}</flux:badge>
            @if($this->user->is_admin)
            <flux:badge color="blue">Admin</flux:badge>
            @endif
        </div>
    </div>

    <!-- Period filter -->
    <div class="flex items-center gap-2">
        <flux:button size="sm" :variant="$period === '7' ? 'primary' : 'ghost'" wire:click="$set('period', '7')">7 dias</flux:button>
        <flux:button size="sm" :variant="$period === '30' ? 'primary' : 'ghost'" wire:click="$set('period', '30')">30 dias</flux:button>
        <flux:button size="sm" :variant="$period === '90' ? 'primary' : 'ghost'" wire:click="$set('period', '90')">90 dias</flux:button>
        <flux:button size="sm" :variant="$period === 'all' ? 'primary' : 'ghost'" wire:click="$set('period', 'all')">Tudo</flux:button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Analytics (2 columns span) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4">
                <flux:card class="space-y-1">
                    <flux:text>Visitas</flux:text>
                    <flux:heading size="xl">{{ number_format($this->uniqueVisits) }}</flux:heading>
                </flux:card>
                <flux:card class="space-y-1">
                    <flux:text>Cliques</flux:text>
                    <flux:heading size="xl">{{ number_format($this->totalClicks) }}</flux:heading>
                </flux:card>
                <flux:card class="space-y-1">
                    <flux:text>Links</flux:text>
                    <flux:heading size="xl">{{ $this->user->links->where('type', \App\Enums\LinkType::Link)->count() }}</flux:heading>
                </flux:card>
            </div>

            <!-- Chart -->
            <flux:card class="space-y-4">
                <flux:heading size="lg">Visitas por dia</flux:heading>
                @if($this->visitsOverTime->isNotEmpty())
                <div class="flex items-end gap-1 h-32">
                    @php $maxV = $this->visitsOverTime->max('count') ?: 1; @endphp
                    @foreach($this->visitsOverTime as $day)
                    <div class="flex-1 rounded-t transition-opacity hover:opacity-80"
                         style="height: {{ ($day->count / $maxV) * 100 }}%; min-height: 2px; background: var(--color-accent, #7c5cfc);"
                         title="{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}: {{ $day->count }}"></div>
                    @endforeach
                </div>
                @else
                <flux:text class="text-center py-8">Sem dados no período</flux:text>
                @endif
            </flux:card>

            <!-- Top links -->
            <flux:card class="space-y-4">
                <flux:heading size="lg">Top links</flux:heading>
                @forelse($this->topLinks as $link)
                <div class="flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $link->title }}</p>
                        <div class="mt-1 h-1.5 rounded-full bg-zinc-700 overflow-hidden">
                            <div class="h-full rounded-full" style="width: {{ $this->topLinks->max('clicks_count') ? ($link->clicks_count / $this->topLinks->max('clicks_count')) * 100 : 0 }}%; background: var(--color-accent, #7c5cfc);"></div>
                        </div>
                    </div>
                    <span class="text-sm font-mono text-zinc-400">{{ $link->clicks_count }}</span>
                </div>
                @empty
                <flux:text class="text-center">Sem cliques registrados</flux:text>
                @endforelse
            </flux:card>

            <!-- User info -->
            <flux:card class="space-y-2">
                <flux:heading size="lg">Informações</flux:heading>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <flux:text class="font-medium">Bio</flux:text>
                        <p>{{ $this->user->bio ?? '-' }}</p>
                    </div>
                    <div>
                        <flux:text class="font-medium">Tema</flux:text>
                        <p>{{ $this->user->theme_slug }}</p>
                    </div>
                    <div>
                        <flux:text class="font-medium">Cadastro</flux:text>
                        <p>{{ $this->user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <flux:text class="font-medium">Redes sociais</flux:text>
                        <p>{{ $this->user->socialLinks->count() }} configuradas</p>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Right: Linktree preview -->
        <div class="space-y-4">
            <flux:heading size="lg">Preview da página</flux:heading>

            <!-- Phone mockup -->
            <div class="rounded-[2rem] border-2 border-zinc-700 overflow-hidden shadow-2xl mx-auto" style="max-width: 280px; aspect-ratio: 9/16;">
                <div class="w-full h-full overflow-y-auto relative" style="background: {{ $this->themeConfig['background'] ?? '#0a0a0f' }};">
                    @if(($this->themeConfig['backgroundType'] ?? 'mesh') === 'mesh')
                    <div class="absolute inset-0 overflow-hidden">
                        @foreach($this->themeConfig['meshColors'] ?? [] as $i => $color)
                        @php $orbPos = match($i) { 0 => 'top: -5%; left: -5%', 1 => 'top: 40%; right: -10%', default => 'bottom: -5%; left: 25%' }; @endphp
                        <div class="absolute rounded-full opacity-20 blur-[40px]"
                             style="background: {{ $color }}; width: 100px; height: 100px; {{ $orbPos }};"></div>
                        @endforeach
                    </div>
                    @endif

                    <div class="relative z-10 px-4 py-6 text-center">
                        <img src="{{ $this->user->avatar_url }}" class="w-14 h-14 rounded-full mx-auto mb-2 object-cover ring-2" style="--tw-ring-color: {{ $this->themeConfig['avatarRingGradient'][0] ?? '#7c5cfc' }};" />
                        <p class="text-xs font-bold" style="color: {{ $this->themeConfig['textPrimary'] ?? '#fff' }};">{{ $this->user->name }}</p>
                        <p class="text-[9px]" style="color: {{ $this->themeConfig['textSecondary'] ?? '#9ca3af' }};">{{ '@' . $this->user->username }}</p>

                        @php
                            $previewItems = collect()
                                ->merge($this->user->links->where('is_active', true)->map(fn ($l) => (object) ['type' => 'link', 'item' => $l, 'position' => $l->position]))
                                ->merge($this->user->embeds->where('is_active', true)->map(fn ($e) => (object) ['type' => 'embed', 'item' => $e, 'position' => $e->position]))
                                ->sortBy('position')->values();
                        @endphp
                        <div class="mt-3 space-y-1.5">
                            @foreach($previewItems as $ci)
                                @if($ci->type === 'link')
                                    @if($ci->item->type->value === 'link')
                                    <div class="rounded-lg px-2 py-1.5 text-[9px] truncate"
                                         style="background: {{ $ci->item->bg_color ?? ($this->themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)') }}; border: 1px solid {{ $this->themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }}; color: {{ $ci->item->text_color ?? ($this->themeConfig['textPrimary'] ?? '#fff') }};">
                                        {{ $ci->item->title }}
                                    </div>
                                    @elseif($ci->item->type->value === 'heading')
                                    <p class="text-[8px] font-semibold uppercase pt-1" style="color: {{ $this->themeConfig['textSecondary'] ?? '#9ca3af' }};">{{ $ci->item->title }}</p>
                                    @elseif($ci->item->type->value === 'divider')
                                    <hr class="border-0 h-px" style="background: {{ $this->themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};">
                                    @endif
                                @elseif($ci->type === 'embed')
                                    <div class="rounded-lg overflow-hidden text-left" style="border: 1px solid {{ $this->themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};">
                                        <div class="flex items-center gap-1 px-2 py-1.5" style="background: {{ $this->themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }};">
                                            <div class="w-4 h-4 rounded flex items-center justify-center shrink-0 {{ $ci->item->type === \App\Enums\EmbedType::YouTube ? 'bg-red-500/20' : 'bg-green-500/20' }}">
                                                @if($ci->item->type === \App\Enums\EmbedType::YouTube)
                                                <svg class="w-2.5 h-2.5 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                @else
                                                <svg class="w-2.5 h-2.5 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                                                @endif
                                            </div>
                                            <span class="text-[8px] truncate" style="color: {{ $this->themeConfig['textPrimary'] ?? '#fff' }};">
                                                {{ $ci->item->title ?? ($ci->item->type === \App\Enums\EmbedType::YouTube ? 'YouTube' : 'Spotify') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if($this->user->socialLinks->where('is_active', true)->isNotEmpty())
                        <div class="mt-3 flex justify-center gap-1 flex-wrap">
                            @foreach($this->user->socialLinks->where('is_active', true)->sortBy('position') as $social)
                            <div class="w-5 h-5 rounded-full" style="background: {{ $this->themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }}; border: 1px solid {{ $this->themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};"></div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-center">
                <flux:button variant="ghost" size="sm" icon="arrow-top-right-on-square" href="{{ route('profile.show', ['username' => $this->user->username]) }}" target="_blank">
                    Ver página completa
                </flux:button>
            </div>
        </div>
    </div>
</div>
