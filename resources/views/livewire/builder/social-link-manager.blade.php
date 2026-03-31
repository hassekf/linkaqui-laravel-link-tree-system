<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Redes Sociais</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4">
            <!-- Existing social links -->
            @if($socialLinks->isNotEmpty())
            <div class="space-y-2 mb-4">
                @foreach($socialLinks as $social)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-builder-surface-hover border border-builder-border group">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: {{ $social->platform->color() }}20;">
                        <x-social-icon :platform="$social->platform->value" class="w-4 h-4" style="color: {{ $social->platform->color() }};" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-builder-text">{{ $social->platform->label() }}</p>
                        <p class="text-xs text-builder-text-dim truncate">{{ $social->platform->usesUsername() ? '@' . $social->platform->extractUsername($social->url) : $social->url }}</p>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="editSocial({{ $social->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active">
                            <svg class="w-4 h-4 text-builder-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button wire:click="deleteSocial({{ $social->id }})" wire:confirm="Remover esta rede social?" class="p-1.5 rounded-lg hover:bg-builder-surface-active">
                            <svg class="w-4 h-4 text-builder-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Platform grid to add new -->
            <p class="text-xs text-builder-text-muted mb-2">Adicionar rede social:</p>
            <div class="grid grid-cols-7 gap-2">
                @foreach($platforms as $platform)
                <button wire:click="addSocial('{{ $platform->value }}')"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 {{ in_array($platform->value, $existingPlatforms) ? 'bg-builder-surface-active ring-2 ring-builder-primary' : 'bg-builder-surface-hover hover:bg-builder-surface-active border border-builder-border' }}"
                    title="{{ $platform->label() }}">
                    <x-social-icon :platform="$platform->value" class="w-5 h-5" style="color: {{ $platform->color() }};" />
                </button>
                @endforeach
            </div>

            <!-- Editor -->
            @if($showEditor)
            @php($platformEnum = $platform instanceof \App\Enums\SocialPlatform ? $platform : \App\Enums\SocialPlatform::from($platform))
            <div class="mt-4 p-4 rounded-xl bg-builder-surface border border-builder-border space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: {{ $platformEnum->color() }}20;">
                        <x-social-icon :platform="$platformEnum->value" class="w-4 h-4" style="color: {{ $platformEnum->color() }};" />
                    </div>
                    <h3 class="text-sm font-semibold text-builder-text">{{ $platformEnum->label() }}</h3>
                </div>

                <div>
                    <x-builder.input :label="$platformEnum->inputLabel()" name="input" wire:model="input" :placeholder="$platformEnum->inputPlaceholder()" />
                    @if($platformEnum->usesUsername() && $input)
                        <p class="text-xs text-builder-text-dim mt-1.5">{{ $platformEnum->baseUrl() }}{{ ltrim($input, '@') }}</p>
                    @endif
                </div>

                <div class="flex gap-2">
                    <x-builder.button wire:click="saveSocial">Salvar</x-builder.button>
                    <x-builder.button variant="ghost" wire:click="$set('showEditor', false)">Cancelar</x-builder.button>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-builder.card>
