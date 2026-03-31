<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Temas</flux:heading>
        <flux:button variant="primary" icon="plus" wire:click="createTheme">Novo tema</flux:button>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Tema</flux:table.column>
            <flux:table.column>Descrição</flux:table.column>
            <flux:table.column>Padrão</flux:table.column>
            <flux:table.column align="end">Ações</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->themes as $theme)
                <flux:table.row :key="$theme->id">
                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-8 rounded-lg overflow-hidden border border-zinc-700 shrink-0" style="background: {{ $theme->config['background'] ?? '#0a0a0f' }};">
                                @if (($theme->config['backgroundType'] ?? 'mesh') === 'mesh')
                                    @foreach ($theme->config['meshColors'] ?? [] as $color)
                                        <div class="inline-block w-2 h-2 rounded-full opacity-60 ml-0.5 mt-1" style="background: {{ $color }};"></div>
                                    @endforeach
                                @endif
                            </div>
                            <div>
                                <div class="font-medium">{{ $theme->name }}</div>
                                <flux:text size="sm">{{ $theme->slug }}</flux:text>
                            </div>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $theme->description ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        @if ($theme->is_default)
                            <flux:badge size="sm" color="green">Padrão</flux:badge>
                        @else
                            <flux:button variant="ghost" size="sm" wire:click="setDefault({{ $theme->id }})">
                                Definir como padrão
                            </flux:button>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-1">
                            <flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="editTheme({{ $theme->id }})" />
                            @unless ($theme->is_default)
                                <flux:button variant="ghost" size="sm" icon="trash" wire:click="deleteTheme({{ $theme->id }})" wire:confirm="Tem certeza que deseja excluir este tema?" />
                            @endunless
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4">
                        <flux:text class="text-center">Nenhum tema encontrado.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Theme Editor Flyout --}}
    <flux:modal wire:model.self="showEditor" flyout class="md:w-xl lg:w-2xl space-y-6" @close="$wire.closeEditor()">
        <div>
            <flux:heading size="lg">{{ $editingThemeId ? 'Editar tema' : 'Novo tema' }}</flux:heading>
            <flux:text class="mt-1">{{ $editingThemeId ? 'Altere as configurações do tema.' : 'Configure um novo tema visual.' }}</flux:text>
        </div>

        <form wire:submit="saveTheme" class="space-y-8">
            {{-- Section: Basic Info --}}
            <div class="space-y-4">
                <flux:heading size="sm">Informações Básicas</flux:heading>
                <flux:separator />

                <flux:input label="Nome" wire:model.live="themeName" placeholder="Ex: Midnight Glow" />
                <flux:input label="Slug" wire:model="themeSlug" placeholder="midnight-glow" />
                <flux:textarea label="Descrição" wire:model="themeDescription" placeholder="Uma breve descrição do tema..." rows="2" />
            </div>

            {{-- Section: Background --}}
            <div class="space-y-4">
                <flux:heading size="sm">Fundo</flux:heading>
                <flux:separator />

                <flux:select label="Tipo de fundo" wire:model.live="backgroundType">
                    <flux:select.option value="mesh">Mesh</flux:select.option>
                    <flux:select.option value="solid">Sólido</flux:select.option>
                    <flux:select.option value="gradient">Gradiente</flux:select.option>
                </flux:select>

                <div>
                    <flux:label>Cor de fundo</flux:label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="color" wire:model.live="background" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <flux:input wire:model.live="background" class="font-mono" />
                    </div>
                </div>

                @if ($backgroundType === 'mesh')
                    <div class="space-y-3">
                        <flux:label>Cores do mesh</flux:label>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="meshColor1" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                    <flux:input wire:model.live="meshColor1" size="sm" class="font-mono" />
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="meshColor2" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                    <flux:input wire:model.live="meshColor2" size="sm" class="font-mono" />
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="meshColor3" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                    <flux:input wire:model.live="meshColor3" size="sm" class="font-mono" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($backgroundType === 'gradient')
                    <div class="space-y-3">
                        <flux:label>Cores do gradiente</flux:label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="gradientColor1" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                    <flux:input wire:model.live="gradientColor1" size="sm" class="font-mono" />
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="gradientColor2" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                    <flux:input wire:model.live="gradientColor2" size="sm" class="font-mono" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <flux:switch label="Overlay de grain" wire:model.live="grain" />
            </div>

            {{-- Section: Cards --}}
            <div class="space-y-4">
                <flux:heading size="sm">Cards</flux:heading>
                <flux:separator />

                <flux:input label="Cor de fundo do card" wire:model.live="cardBg" placeholder="rgba(255,255,255,0.05)" class="font-mono" />
                <flux:input label="Cor da borda do card" wire:model.live="cardBorder" placeholder="rgba(255,255,255,0.1)" class="font-mono" />
                <flux:switch label="Blur no card" wire:model.live="cardBlur" />
            </div>

            {{-- Section: Text --}}
            <div class="space-y-4">
                <flux:heading size="sm">Texto</flux:heading>
                <flux:separator />

                <div>
                    <flux:label>Cor primária</flux:label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="color" wire:model.live="textPrimary" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <flux:input wire:model.live="textPrimary" class="font-mono" />
                    </div>
                </div>

                <div>
                    <flux:label>Cor secundária</flux:label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="color" wire:model.live="textSecondary" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <flux:input wire:model.live="textSecondary" class="font-mono" />
                    </div>
                </div>

                <div class="space-y-3">
                    <flux:label>Gradiente do nome</flux:label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="nameGradient1" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                <flux:input wire:model.live="nameGradient1" size="sm" class="font-mono" />
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="nameGradient2" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                <flux:input wire:model.live="nameGradient2" size="sm" class="font-mono" />
                            </div>
                        </div>
                    </div>
                </div>

                <flux:select label="Fonte" wire:model.live="fontFamily">
                    <flux:select.option value="Inter">Inter</flux:select.option>
                    <flux:select.option value="system-ui">System UI</flux:select.option>
                    <flux:select.option value="serif">Serif</flux:select.option>
                    <flux:select.option value="monospace">Monospace</flux:select.option>
                </flux:select>
            </div>

            {{-- Section: Avatar --}}
            <div class="space-y-4">
                <flux:heading size="sm">Avatar</flux:heading>
                <flux:separator />

                <div class="space-y-3">
                    <flux:label>Cores do anel</flux:label>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="ringColor1" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                <flux:input wire:model.live="ringColor1" size="sm" class="font-mono" />
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="ringColor2" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                <flux:input wire:model.live="ringColor2" size="sm" class="font-mono" />
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="ringColor3" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                                <flux:input wire:model.live="ringColor3" size="sm" class="font-mono" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Animations --}}
            <div class="space-y-4">
                <flux:heading size="sm">Animações</flux:heading>
                <flux:separator />

                <flux:select label="Tipo" wire:model.live="animations">
                    <flux:select.option value="staggered">Staggered</flux:select.option>
                    <flux:select.option value="fade">Fade</flux:select.option>
                    <flux:select.option value="none">Nenhuma</flux:select.option>
                </flux:select>
            </div>

            {{-- Mini Preview --}}
            <div class="space-y-2">
                <flux:heading size="sm">Pré-visualização</flux:heading>
                <flux:separator />
                <div class="rounded-xl overflow-hidden border border-zinc-700" style="height: 200px; background: {{ $background }};">
                    @if ($backgroundType === 'mesh')
                        <div class="relative w-full h-full overflow-hidden">
                            <div class="absolute w-20 h-20 rounded-full opacity-30 blur-xl" style="background: {{ $meshColor1 }}; top: 10%; left: 10%;"></div>
                            <div class="absolute w-16 h-16 rounded-full opacity-20 blur-xl" style="background: {{ $meshColor2 }}; top: 40%; right: 10%;"></div>
                            <div class="absolute w-24 h-24 rounded-full opacity-20 blur-xl" style="background: {{ $meshColor3 }}; bottom: 5%; left: 30%;"></div>
                            <div class="relative z-10 flex flex-col items-center justify-center h-full">
                                <div class="w-8 h-8 rounded-full mb-2" style="background: conic-gradient({{ $ringColor1 }}, {{ $ringColor2 }}, {{ $ringColor3 }}, {{ $ringColor1 }});"></div>
                                <div class="text-sm font-bold" style="color: {{ $textPrimary }};">Preview</div>
                                <div class="text-xs mt-1" style="color: {{ $textSecondary }};">@<!-- -->username</div>
                                <div class="mt-3 w-32 py-1.5 rounded-lg text-center text-xs" style="background: {{ $cardBg }}; border: 1px solid {{ $cardBorder }}; color: {{ $textPrimary }};">Link</div>
                            </div>
                        </div>
                    @elseif ($backgroundType === 'gradient')
                        <div class="relative w-full h-full overflow-hidden" style="background: linear-gradient(to bottom, {{ $gradientColor1 }}, {{ $gradientColor2 }});">
                            <div class="relative z-10 flex flex-col items-center justify-center h-full">
                                <div class="w-8 h-8 rounded-full mb-2" style="background: conic-gradient({{ $ringColor1 }}, {{ $ringColor2 }}, {{ $ringColor3 }}, {{ $ringColor1 }});"></div>
                                <div class="text-sm font-bold" style="color: {{ $textPrimary }};">Preview</div>
                                <div class="text-xs mt-1" style="color: {{ $textSecondary }};">@<!-- -->username</div>
                                <div class="mt-3 w-32 py-1.5 rounded-lg text-center text-xs" style="background: {{ $cardBg }}; border: 1px solid {{ $cardBorder }}; color: {{ $textPrimary }};">Link</div>
                            </div>
                        </div>
                    @else
                        <div class="relative w-full h-full overflow-hidden">
                            <div class="relative z-10 flex flex-col items-center justify-center h-full">
                                <div class="w-8 h-8 rounded-full mb-2" style="background: conic-gradient({{ $ringColor1 }}, {{ $ringColor2 }}, {{ $ringColor3 }}, {{ $ringColor1 }});"></div>
                                <div class="text-sm font-bold" style="color: {{ $textPrimary }};">Preview</div>
                                <div class="text-xs mt-1" style="color: {{ $textSecondary }};">@<!-- -->username</div>
                                <div class="mt-3 w-32 py-1.5 rounded-lg text-center text-xs" style="background: {{ $cardBg }}; border: 1px solid {{ $cardBorder }}; color: {{ $textPrimary }};">Link</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 pt-4">
                <flux:button wire:click="closeEditor">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingThemeId ? 'Salvar alterações' : 'Criar tema' }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
