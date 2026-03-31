<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Links</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4">
            <!-- Add buttons -->
            <div class="flex gap-2 mb-4">
                <x-builder.button wire:click="addLink" size="sm">
                    + Link
                </x-builder.button>
                <x-builder.button wire:click="addHeading" variant="secondary" size="sm">
                    + Título
                </x-builder.button>
                <x-builder.button wire:click="addDivider" variant="secondary" size="sm">
                    + Divisor
                </x-builder.button>
            </div>

            <!-- Sortable Link List -->
            <div wire:sort="reorderLinks" class="space-y-2">
                @forelse($links as $link)
                <div wire:sort:item="{{ $link->id }}" wire:key="link-{{ $link->id }}"
                     class="flex items-center gap-3 p-3 rounded-xl bg-builder-surface-hover border border-builder-border group transition-colors hover:border-builder-border-focus">

                    <!-- Drag Handle -->
                    <div wire:sort:handle class="cursor-grab active:cursor-grabbing text-builder-text-dim hover:text-builder-text-muted">
                        <x-builder.drag-handle />
                    </div>

                    <!-- Link Info -->
                    <div class="flex-1 min-w-0">
                        @if($link->type === \App\Enums\LinkType::Divider)
                            <div class="h-px bg-builder-border my-2"></div>
                        @else
                            <p class="text-sm font-medium text-builder-text truncate">{{ $link->title }}</p>
                            @if($link->type === \App\Enums\LinkType::Link && $link->url)
                            <p class="text-xs text-builder-text-dim truncate">{{ $link->url }}</p>
                            @endif
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <!-- Toggle active -->
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
                        <!-- Edit -->
                        <button wire:click="editLink({{ $link->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                            <svg class="w-4 h-4 text-builder-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        @endif

                        <!-- Delete -->
                        <button wire:click="deleteLink({{ $link->id }})" wire:confirm="Tem certeza que deseja excluir?" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                            <svg class="w-4 h-4 text-builder-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-center text-builder-text-dim text-sm py-4">Nenhum link adicionado ainda</p>
                @endforelse
            </div>

            <!-- Link Editor Modal -->
            @if($showEditor)
            <div class="mt-4 p-4 rounded-xl bg-builder-surface border border-builder-border space-y-4">
                <h3 class="text-sm font-semibold text-builder-text">
                    {{ $editingLinkId ? 'Editar' : 'Novo' }} {{ $type === 'heading' ? 'Título' : 'Link' }}
                </h3>

                <x-builder.input label="Título" name="title" wire:model="title" placeholder="{{ $type === 'heading' ? 'Título da seção' : 'Meu link incrível' }}" />

                @if($type === 'link')
                <x-builder.input label="URL" name="url" wire:model="url" placeholder="https://example.com" />

                {{-- Icon Picker --}}
                <div x-data="{ showIcons: false, search: '' }">
                    <label class="block text-sm font-medium text-builder-text mb-1.5">Ícone</label>
                    <button type="button" @click="showIcons = !showIcons"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg bg-builder-surface-hover border border-builder-border text-sm text-builder-text hover:border-builder-border-focus transition-colors">
                        @if($icon)
                            <x-link-icon :icon="$icon" class="w-5 h-5 text-builder-text" />
                            <span>{{ $icon }}</span>
                        @else
                            <span class="text-builder-text-dim">Escolher ícone (opcional)</span>
                        @endif
                    </button>

                    <div x-show="showIcons" x-collapse class="mt-2 p-3 rounded-xl bg-builder-surface border border-builder-border">
                        <input type="text" x-model="search" placeholder="Buscar ícone..."
                            class="w-full px-3 py-1.5 rounded-lg bg-builder-surface-hover border border-builder-border text-sm text-builder-text placeholder-builder-text-dim mb-3 focus:border-builder-border-focus focus:outline-none">

                        @php
                            $icons = [
                                'link' => 'Link',
                                'globe' => 'Globe',
                                'code' => 'Código',
                                'shopping-bag' => 'Loja',
                                'document' => 'Documento',
                                'play' => 'Play',
                                'music' => 'Música',
                                'camera' => 'Câmera',
                                'heart' => 'Coração',
                                'star' => 'Estrela',
                                'bolt' => 'Raio',
                                'gift' => 'Presente',
                                'megaphone' => 'Megafone',
                                'bookmark' => 'Marcador',
                                'briefcase' => 'Trabalho',
                                'academic-cap' => 'Educação',
                                'chat' => 'Chat',
                                'map-pin' => 'Localização',
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
                    <x-builder.button variant="ghost" wire:click="$set('showEditor', false)">Cancelar</x-builder.button>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-builder.card>
