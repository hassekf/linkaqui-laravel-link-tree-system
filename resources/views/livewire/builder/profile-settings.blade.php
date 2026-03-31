<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Perfil</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4 space-y-4">
            <!-- Avatar Upload -->
            <div class="flex items-center gap-4">
                <div class="relative w-16 h-16 rounded-full overflow-hidden bg-builder-surface-hover">
                    @if($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ auth()->user()->avatar_url }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <label class="cursor-pointer text-sm text-builder-primary hover:text-builder-primary-hover">
                        Alterar foto
                        <input type="file" wire:model="avatar" accept="image/*" class="hidden">
                    </label>
                    @error('avatar') <p class="text-xs text-builder-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <x-builder.input label="Nome" name="name" wire:model.blur="name" />
            <x-builder.input label="Username" name="username" wire:model.blur="username" />
            <x-builder.textarea label="Bio" name="bio" wire:model.blur="bio" />

            {{-- Font selector --}}
            <div>
                <label class="block text-sm font-medium text-builder-text mb-1.5">Fonte</label>
                <div class="relative">
                    <select wire:model.live="fontFamily"
                        class="w-full rounded-lg border border-builder-border bg-builder-surface text-builder-text px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-builder-border-focus focus:border-builder-border-focus appearance-none cursor-pointer">
                        <option value="">Padrão do tema</option>
                        <option value="Inter" style="font-family: Inter;">Inter</option>
                        <option value="DM Sans" style="font-family: 'DM Sans';">DM Sans</option>
                        <option value="Poppins" style="font-family: Poppins;">Poppins</option>
                        <option value="Montserrat" style="font-family: Montserrat;">Montserrat</option>
                        <option value="Outfit" style="font-family: Outfit;">Outfit</option>
                        <option value="Space Grotesk" style="font-family: 'Space Grotesk';">Space Grotesk</option>
                        <option value="JetBrains Mono" style="font-family: 'JetBrains Mono';">JetBrains Mono</option>
                        <option value="Playfair Display" style="font-family: 'Playfair Display';">Playfair Display</option>
                        <option value="Lora" style="font-family: Lora;">Lora</option>
                        <option value="Merriweather" style="font-family: Merriweather;">Merriweather</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-builder-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @if($fontFamily)
                <p class="text-xs text-builder-text-dim mt-1.5" style="font-family: '{{ $fontFamily }}', system-ui, sans-serif;">Exemplo: Abc 123 — {{ $fontFamily }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-builder-text mb-1.5">Posicao das redes sociais</label>
                <div class="flex gap-2">
                    <button type="button" wire:click="updateSocialPosition('top')"
                        class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $socialPosition === 'top' ? 'bg-builder-primary text-white' : 'bg-builder-surface-hover text-builder-text-muted border border-builder-border' }}">
                        Antes do conteudo
                    </button>
                    <button type="button" wire:click="updateSocialPosition('bottom')"
                        class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $socialPosition === 'bottom' ? 'bg-builder-primary text-white' : 'bg-builder-surface-hover text-builder-text-muted border border-builder-border' }}">
                        Depois do conteudo
                    </button>
                </div>
            </div>

            <x-builder.button wire:click="save" wire:loading.attr="disabled">
                Salvar perfil
            </x-builder.button>
        </div>
    </div>
</x-builder.card>
