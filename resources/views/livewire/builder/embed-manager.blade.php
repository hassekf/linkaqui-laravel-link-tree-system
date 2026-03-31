<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Embeds</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4">
            <x-builder.button wire:click="addEmbed" size="sm" class="mb-4">
                + Embed
            </x-builder.button>

            <!-- Existing embeds -->
            <div class="space-y-2">
                @forelse($embeds as $embed)
                <div class="p-3 rounded-xl bg-builder-surface-hover border border-builder-border group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $embed->type === \App\Enums\EmbedType::YouTube ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                            @if($embed->type === \App\Enums\EmbedType::YouTube)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-builder-text">{{ $embed->title ?? ($embed->type === \App\Enums\EmbedType::YouTube ? 'YouTube' : 'Spotify') }}</p>
                            <p class="text-xs text-builder-text-dim truncate">{{ $embed->embed_url }}</p>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="toggleEmbed({{ $embed->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active">
                                @if($embed->is_active)
                                <svg class="w-4 h-4 text-builder-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                @else
                                <svg class="w-4 h-4 text-builder-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                @endif
                            </button>
                            <button wire:click="editEmbed({{ $embed->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active">
                                <svg class="w-4 h-4 text-builder-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button wire:click="deleteEmbed({{ $embed->id }})" wire:confirm="Remover este embed?" class="p-1.5 rounded-lg hover:bg-builder-surface-active">
                                <svg class="w-4 h-4 text-builder-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-builder-text-dim text-sm py-4">Nenhum embed adicionado ainda</p>
                @endforelse
            </div>

            <!-- Editor -->
            @if($showEditor)
            <div class="mt-4 p-4 rounded-xl bg-builder-surface border border-builder-border space-y-4">
                <h3 class="text-sm font-semibold text-builder-text">{{ $editingId ? 'Editar' : 'Novo' }} Embed</h3>

                <x-builder.input label="URL do YouTube ou Spotify" name="rawUrl" wire:model.live.debounce.500ms="rawUrl" placeholder="Cole a URL aqui" />

                @if(!$detectedType && !$rawUrl)
                <div class="text-xs text-builder-text-dim space-y-1">
                    <p class="font-medium text-builder-text-muted">Formatos aceitos:</p>
                    <p>YouTube: youtube.com/watch?v=..., youtu.be/...</p>
                    <p>Spotify: open.spotify.com/track/..., /album/..., /playlist/...</p>
                </div>
                @endif

                @if($detectedType)
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-2 py-1 rounded-full {{ $detectedType === 'youtube' ? 'bg-red-500/10 text-red-400' : 'bg-green-500/10 text-green-400' }}">
                        {{ $detectedType === 'youtube' ? 'YouTube' : 'Spotify' }} detectado
                    </span>
                </div>
                @endif

                <x-builder.input label="Título (opcional)" name="title" wire:model="title" placeholder="Nome do vídeo ou música" />

                <!-- Preview -->
                @if($embedUrl)
                <div class="rounded-xl overflow-hidden border border-builder-border">
                    @if($detectedType === 'youtube')
                    <div class="relative w-full" style="padding-bottom: 56.25%;">
                        <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full" frameborder="0" sandbox="allow-scripts allow-same-origin allow-presentation" loading="lazy"></iframe>
                    </div>
                    @elseif($detectedType === 'spotify')
                    <iframe src="{{ $embedUrl }}" class="w-full" height="152" frameborder="0" sandbox="allow-scripts allow-same-origin" loading="lazy"></iframe>
                    @endif
                </div>
                @endif

                <div class="flex gap-2">
                    <x-builder.button wire:click="saveEmbed">Salvar</x-builder.button>
                    <x-builder.button variant="ghost" wire:click="$set('showEditor', false)">Cancelar</x-builder.button>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-builder.card>
