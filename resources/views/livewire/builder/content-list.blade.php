<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Conteudo</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4">
            <!-- Add buttons -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <x-builder.button wire:click="addLink" size="sm">+ Link</x-builder.button>
                <x-builder.button wire:click="addHeading" variant="secondary" size="sm">+ Titulo</x-builder.button>
                <x-builder.button wire:click="addDivider" variant="secondary" size="sm">+ Divisor</x-builder.button>
                <x-builder.button wire:click="addEmbed" variant="secondary" size="sm">+ Embed</x-builder.button>
            </div>

            <!-- Unified sortable list -->
            <div wire:sort="reorderContent" class="space-y-2">
                @forelse($this->contentItems as $contentItem)
                    <div wire:sort:item="{{ $contentItem->sort_key }}" wire:key="{{ $contentItem->sort_key }}"
                         class="flex items-center gap-3 p-3 rounded-xl bg-builder-surface-hover border border-builder-border group transition-colors hover:border-builder-border-focus">

                        <!-- Drag handle -->
                        <div wire:sort:handle class="cursor-grab active:cursor-grabbing text-builder-text-dim hover:text-builder-text-muted">
                            <x-builder.drag-handle />
                        </div>

                        @if($contentItem->type === 'link')
                            @php $link = $contentItem->item; @endphp
                            <!-- Link item display -->
                            <div class="flex-1 min-w-0">
                                @if($link->type === \App\Enums\LinkType::Divider)
                                    <div class="h-px bg-builder-border my-2"></div>
                                @else
                                    <div class="flex items-center gap-2">
                                        @if($link->icon)
                                            <x-link-icon :icon="$link->icon" class="w-4 h-4 text-builder-text-muted shrink-0" />
                                        @endif
                                        <p class="text-sm font-medium text-builder-text truncate">{{ $link->title }}</p>
                                        @if($link->type === \App\Enums\LinkType::Heading)
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-builder-surface-active text-builder-text-dim">titulo</span>
                                        @endif
                                    </div>
                                    @if($link->type === \App\Enums\LinkType::Link && $link->url)
                                        <p class="text-xs text-builder-text-dim truncate mt-0.5">{{ $link->url }}</p>
                                    @endif
                                @endif
                            </div>

                            <!-- Link actions -->
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="toggleLink({{ $link->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors" title="{{ $link->is_active ? 'Desativar' : 'Ativar' }}">
                                    @if($link->is_active)
                                    <svg class="w-4 h-4 text-builder-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-builder-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    @endif
                                </button>

                                @if($link->type !== \App\Enums\LinkType::Divider)
                                <button wire:click="editLink({{ $link->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                                    <svg class="w-4 h-4 text-builder-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @endif

                                <button wire:click="deleteLink({{ $link->id }})" wire:confirm="Excluir este item?" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                                    <svg class="w-4 h-4 text-builder-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                        @elseif($contentItem->type === 'embed')
                            @php $embed = $contentItem->item; @endphp
                            <!-- Embed item display -->
                            <div class="flex-1 min-w-0 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                    @switch($embed->type)
                                        @case(\App\Enums\EmbedType::YouTube) bg-red-500/10 @break
                                        @case(\App\Enums\EmbedType::Spotify) bg-green-500/10 @break
                                        @case(\App\Enums\EmbedType::SoundCloud) bg-orange-500/10 @break
                                        @case(\App\Enums\EmbedType::Twitch) bg-purple-500/10 @break
                                        @case(\App\Enums\EmbedType::Vimeo) bg-blue-500/10 @break
                                        @case(\App\Enums\EmbedType::TikTok) bg-zinc-500/10 @break
                                        @case(\App\Enums\EmbedType::AppleMusic) bg-pink-500/10 @break
                                    @endswitch">
                                    @if($embed->type === \App\Enums\EmbedType::YouTube)
                                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @elseif($embed->type === \App\Enums\EmbedType::Twitch)
                                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @elseif($embed->type === \App\Enums\EmbedType::Vimeo)
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 {{ match($embed->type) { \App\Enums\EmbedType::Spotify => 'text-green-400', \App\Enums\EmbedType::SoundCloud => 'text-orange-400', \App\Enums\EmbedType::TikTok => 'text-zinc-400', \App\Enums\EmbedType::AppleMusic => 'text-pink-400', default => 'text-builder-text-muted' } }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-builder-text">{{ $embed->title ?? match($embed->type) {
                                        \App\Enums\EmbedType::YouTube => 'YouTube',
                                        \App\Enums\EmbedType::Spotify => 'Spotify',
                                        \App\Enums\EmbedType::SoundCloud => 'SoundCloud',
                                        \App\Enums\EmbedType::Twitch => 'Twitch',
                                        \App\Enums\EmbedType::Vimeo => 'Vimeo',
                                        \App\Enums\EmbedType::TikTok => 'TikTok',
                                        \App\Enums\EmbedType::AppleMusic => 'Apple Music',
                                        default => 'Embed',
                                    } }}</p>
                                    <p class="text-xs text-builder-text-dim truncate">{{ $embed->embed_url }}</p>
                                </div>
                            </div>

                            <!-- Embed actions -->
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="toggleEmbed({{ $embed->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                                    @if($embed->is_active)
                                    <svg class="w-4 h-4 text-builder-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-builder-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    @endif
                                </button>
                                <button wire:click="editEmbed({{ $embed->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                                    <svg class="w-4 h-4 text-builder-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="deleteEmbed({{ $embed->id }})" wire:confirm="Remover este embed?" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                                    <svg class="w-4 h-4 text-builder-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-builder-text-dim text-sm py-4">Nenhum conteudo adicionado ainda</p>
                @endforelse
            </div>

            <!-- Link Editor (inline, below the list) -->
            @if($showLinkEditor)
            <div class="mt-4 p-4 rounded-xl bg-builder-surface border border-builder-border space-y-4">
                <h3 class="text-sm font-semibold text-builder-text">
                    {{ $editingLinkId ? 'Editar' : 'Novo' }} {{ $linkType === 'heading' ? 'Titulo' : 'Link' }}
                </h3>

                <x-builder.input label="Titulo" name="title" wire:model="title" placeholder="{{ $linkType === 'heading' ? 'Titulo da secao' : 'Meu link incrivel' }}" />

                @if($linkType === 'link')
                <x-builder.input label="URL" name="url" wire:model="url" placeholder="https://example.com" />

                {{-- Icon Picker --}}
                <div x-data="{ showIcons: false, search: '' }">
                    <label class="block text-sm font-medium text-builder-text mb-1.5">Icone</label>
                    <button type="button" @click="showIcons = !showIcons"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg bg-builder-surface-hover border border-builder-border text-sm text-builder-text hover:border-builder-border-focus transition-colors">
                        @if($icon)
                            <x-link-icon :icon="$icon" class="w-5 h-5 text-builder-text" />
                            <span>{{ $icon }}</span>
                        @else
                            <span class="text-builder-text-dim">Escolher icone (opcional)</span>
                        @endif
                    </button>

                    <div x-show="showIcons" x-collapse class="mt-2 p-3 rounded-xl bg-builder-surface border border-builder-border">
                        <input type="text" x-model="search" placeholder="Buscar icone..."
                            class="w-full px-3 py-1.5 rounded-lg bg-builder-surface-hover border border-builder-border text-sm text-builder-text placeholder-builder-text-dim mb-3 focus:border-builder-border-focus focus:outline-none">

                        @php
                            $icons = [
                                'link' => 'Link',
                                'globe' => 'Globe',
                                'code' => 'Codigo',
                                'shopping-bag' => 'Loja',
                                'document' => 'Documento',
                                'play' => 'Play',
                                'music' => 'Musica',
                                'camera' => 'Camera',
                                'heart' => 'Coracao',
                                'star' => 'Estrela',
                                'bolt' => 'Raio',
                                'gift' => 'Presente',
                                'megaphone' => 'Megafone',
                                'bookmark' => 'Marcador',
                                'briefcase' => 'Trabalho',
                                'academic-cap' => 'Educacao',
                                'chat' => 'Chat',
                                'map-pin' => 'Localizacao',
                                'envelope' => 'E-mail',
                                'phone' => 'Telefone',
                                'currency-dollar' => 'Dinheiro',
                                'rocket' => 'Foguete',
                            ];
                        @endphp

                        <div class="grid grid-cols-6 gap-1.5">
                            {{-- Option: no icon --}}
                            <button type="button"
                                wire:click="$set('icon', '')"
                                @click="showIcons = false"
                                class="flex flex-col items-center gap-1 p-2 rounded-lg transition-colors {{ !$icon ? 'bg-builder-primary/20 border border-builder-primary' : 'hover:bg-builder-surface-hover border border-transparent' }}"
                                x-show="'nenhum sem'.includes(search.toLowerCase()) || search === ''">
                                <svg class="w-5 h-5 text-builder-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                <span class="text-[10px] text-builder-text-dim">Nenhum</span>
                            </button>

                            @foreach($icons as $key => $label)
                            <button type="button"
                                wire:click="$set('icon', '{{ $key }}')"
                                @click="showIcons = false"
                                class="flex flex-col items-center gap-1 p-2 rounded-lg transition-colors {{ $icon === $key ? 'bg-builder-primary/20 border border-builder-primary' : 'hover:bg-builder-surface-hover border border-transparent' }}"
                                x-show="'{{ strtolower($label . ' ' . $key) }}'.includes(search.toLowerCase()) || search === ''">
                                <x-link-icon :icon="$key" class="w-5 h-5 text-builder-text" />
                                <span class="text-[10px] text-builder-text-dim">{{ $label }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <x-builder.color-picker label="Cor de fundo" name="bgColor" wire:model="bgColor" :value="$bgColor" />
                    <x-builder.color-picker label="Cor do texto" name="textColor" wire:model="textColor" :value="$textColor" />
                </div>
                @endif

                <div class="flex gap-2">
                    <x-builder.button wire:click="saveLink">Salvar</x-builder.button>
                    <x-builder.button variant="ghost" wire:click="closeLinkEditor">Cancelar</x-builder.button>
                </div>
            </div>
            @endif

            <!-- Embed Editor (inline, below the list) -->
            @if($showEmbedEditor)
            <div class="mt-4 p-4 rounded-xl bg-builder-surface border border-builder-border space-y-4">
                <h3 class="text-sm font-semibold text-builder-text">{{ $editingEmbedId ? 'Editar' : 'Novo' }} Embed</h3>

                <x-builder.input label="URL da plataforma" name="rawUrl" wire:model.live.debounce.500ms="rawUrl" placeholder="Cole a URL aqui" />

                @if(!$detectedType && !$rawUrl)
                <div class="text-xs text-builder-text-dim space-y-1">
                    <p class="font-medium text-builder-text-muted">Plataformas suportadas:</p>
                    <p>YouTube, Spotify, SoundCloud, Twitch, Vimeo, TikTok, Apple Music</p>
                </div>
                @endif

                @if($detectedType)
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-2 py-1 rounded-full
                        @switch($detectedType)
                            @case('youtube') bg-red-500/10 text-red-400 @break
                            @case('spotify') bg-green-500/10 text-green-400 @break
                            @case('soundcloud') bg-orange-500/10 text-orange-400 @break
                            @case('twitch') bg-purple-500/10 text-purple-400 @break
                            @case('vimeo') bg-blue-500/10 text-blue-400 @break
                            @case('tiktok') bg-zinc-500/10 text-zinc-400 @break
                            @case('apple_music') bg-pink-500/10 text-pink-400 @break
                        @endswitch">
                        {{ match($detectedType) {
                            'youtube' => 'YouTube',
                            'spotify' => 'Spotify',
                            'soundcloud' => 'SoundCloud',
                            'twitch' => 'Twitch',
                            'vimeo' => 'Vimeo',
                            'tiktok' => 'TikTok',
                            'apple_music' => 'Apple Music',
                            default => ucfirst($detectedType),
                        } }} detectado
                    </span>
                </div>
                @endif

                <x-builder.input label="Titulo (opcional)" name="embedTitle" wire:model="embedTitle" placeholder="Nome do video ou musica" />

                <!-- Preview -->
                @if($embedUrl)
                <div class="rounded-xl overflow-hidden border border-builder-border">
                    @if($detectedType === 'youtube' || $detectedType === 'vimeo')
                    <div class="relative w-full" style="padding-bottom: 56.25%;">
                        <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full" frameborder="0" sandbox="allow-scripts allow-same-origin allow-presentation" loading="lazy"></iframe>
                    </div>
                    @elseif($detectedType === 'spotify')
                    <iframe src="{{ $embedUrl }}" class="w-full" height="152" frameborder="0" sandbox="allow-scripts allow-same-origin" loading="lazy"></iframe>
                    @elseif($detectedType === 'soundcloud')
                    <iframe src="{{ $embedUrl }}" class="w-full" height="166" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups" loading="lazy"></iframe>
                    @elseif($detectedType === 'twitch')
                    <div class="relative w-full" style="padding-bottom: 56.25%;">
                        <iframe src="{{ $embedUrl }}&parent={{ request()->getHost() }}" class="absolute inset-0 w-full h-full" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups" loading="lazy"></iframe>
                    </div>
                    @elseif($detectedType === 'tiktok')
                    <iframe src="{{ $embedUrl }}" class="w-full" height="500" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups" loading="lazy"></iframe>
                    @elseif($detectedType === 'apple_music')
                    <iframe src="{{ $embedUrl }}" class="w-full" height="175" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-forms" loading="lazy" style="border-radius: 12px;"></iframe>
                    @endif
                </div>
                @endif

                <div class="flex gap-2">
                    <x-builder.button wire:click="saveEmbed">Salvar</x-builder.button>
                    <x-builder.button variant="ghost" wire:click="closeEmbedEditor">Cancelar</x-builder.button>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-builder.card>
