<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-builder-text">Produtos</h1>
        <x-builder.button wire:click="addProduct" size="sm">+ Novo produto</x-builder.button>
    </div>

    {{-- Product search toggle --}}
    <x-builder.card>
        <x-builder.toggle
            label="Ativar busca de produtos na sua pagina"
            name="productSearchEnabled"
            :checked="$productSearchEnabled"
            wire:click="toggleProductSearch"
        />
    </x-builder.card>

    {{-- Top 5 da semana --}}
    @if($topProducts->isNotEmpty())
    <x-builder.card>
        <h2 class="text-sm font-semibold text-builder-text mb-3">Top produtos da semana</h2>
        <div class="space-y-2">
            @foreach($topProducts as $i => $tp)
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold text-builder-text-dim w-6 text-center">{{ $i + 1 }}</span>
                @if($tp->image_url)
                <img src="{{ $tp->image_url }}" alt="" class="w-8 h-8 rounded-lg object-cover shrink-0">
                @else
                <div class="w-8 h-8 rounded-lg bg-builder-surface-active shrink-0"></div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-builder-text truncate">{{ $tp->name }}</p>
                    @php($maxClicks = $topProducts->max('clicks_count') ?: 1)
                    <div class="mt-1 h-1 rounded-full bg-builder-surface-active overflow-hidden">
                        <div class="h-full rounded-full bg-builder-primary" style="width: {{ ($tp->clicks_count / $maxClicks) * 100 }}%;"></div>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-mono text-builder-text">{{ $tp->clicks_count }} <span class="text-builder-text-dim">cliques</span></p>
                    <p class="text-xs font-mono text-builder-text-dim">{{ $tp->searches_count }} buscas</p>
                </div>
            </div>
            @endforeach
        </div>
    </x-builder.card>
    @endif

    {{-- Search --}}
    <div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nome ou codigo..."
               class="w-full rounded-lg border border-builder-border bg-builder-surface text-builder-text placeholder-builder-text-dim px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-builder-border-focus focus:border-builder-border-focus transition-colors">
    </div>

    {{-- Product Editor --}}
    @if($showEditor)
    <x-builder.card>
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-builder-text">
                {{ $editingProductId ? 'Editar produto' : 'Novo produto' }}
            </h3>

            {{-- Image upload --}}
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-builder-text">Imagem do produto</label>
                <input type="file" wire:model="productImage" accept="image/jpg,image/jpeg,image/png,image/webp"
                       class="w-full text-sm text-builder-text-muted file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-builder-surface-hover file:text-builder-text hover:file:bg-builder-surface-active">
                @error('productImage') <p class="text-xs text-builder-danger">{{ $message }}</p> @enderror
                <div wire:loading wire:target="productImage" class="text-xs text-builder-text-muted">Enviando...</div>
            </div>

            <x-builder.input label="Nome" name="name" wire:model="name" placeholder="Nome do produto" />

            {{-- Code with regenerate --}}
            <div class="space-y-1.5">
                <label for="code" class="block text-sm font-medium text-builder-text">Codigo</label>
                <div class="flex gap-2">
                    <input id="code" name="code" type="text" wire:model="code" readonly
                           class="flex-1 rounded-lg border bg-builder-surface text-builder-text px-3 py-2 text-sm border-builder-border font-mono tracking-wider">
                    <button type="button" wire:click="regenerateCode"
                            class="px-3 py-2 rounded-lg text-xs bg-builder-surface-hover border border-builder-border text-builder-text-muted hover:text-builder-text hover:border-builder-border-focus transition-colors">
                        Gerar novo
                    </button>
                </div>
                @error('code') <p class="text-xs text-builder-danger">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <x-builder.input label="Preco original" name="originalPrice" wire:model="originalPrice" placeholder="99.90" type="number" step="0.01" min="0" />
                <x-builder.input label="Preco promocional" name="salePrice" wire:model="salePrice" placeholder="49.90" type="number" step="0.01" min="0" />
            </div>

            <x-builder.input label="URL do produto (link de afiliado)" name="productUrl" wire:model="productUrl" placeholder="https://exemplo.com/produto" />

            <div class="flex gap-2">
                <x-builder.button wire:click="saveProduct">Salvar</x-builder.button>
                <x-builder.button variant="ghost" wire:click="closeEditor">Cancelar</x-builder.button>
            </div>
        </div>
    </x-builder.card>
    @endif

    {{-- Products List --}}
    <div class="space-y-2">
        @forelse($products as $product)
            <div wire:key="product-{{ $product->id }}"
                 class="flex items-center gap-4 p-4 rounded-xl bg-builder-surface-hover border border-builder-border group transition-colors hover:border-builder-border-focus">

                {{-- Stats (prominent, left side) --}}
                <div class="shrink-0 w-20 text-center">
                    <p class="text-2xl font-bold text-builder-text">{{ $product->clicks_count }}</p>
                    <p class="text-[10px] text-builder-text-dim uppercase tracking-wider">cliques</p>
                    <div class="mt-1 h-px bg-builder-border"></div>
                    <p class="mt-1 text-lg font-semibold text-builder-text-muted">{{ $product->searches_count }}</p>
                    <p class="text-[10px] text-builder-text-dim uppercase tracking-wider">buscas</p>
                </div>

                {{-- Image --}}
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="" class="w-14 h-14 rounded-xl object-cover shrink-0">
                @else
                <div class="w-14 h-14 rounded-xl bg-builder-surface-active flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-builder-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                    </svg>
                </div>
                @endif

                {{-- Product info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-builder-text truncate">{{ $product->name }}</p>
                        <button x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText('{{ $product->code }}'); copied = true; setTimeout(() => copied = false, 1500); $dispatch('toast', { message: 'Codigo copiado!', type: 'success' });"
                                class="text-[10px] px-1.5 py-0.5 rounded font-mono shrink-0 cursor-pointer transition-colors"
                                :class="copied ? 'bg-green-500/20 text-green-400' : 'bg-builder-primary/20 text-builder-primary hover:bg-builder-primary/30'"
                                :title="copied ? 'Copiado!' : 'Clique para copiar'">
                            <span x-show="!copied">{{ $product->code }}</span>
                            <span x-show="copied" x-cloak>Copiado!</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        @if($product->original_price && $product->sale_price && $product->original_price != $product->sale_price)
                            <span class="text-xs text-builder-text-dim line-through">R$ {{ number_format((float) $product->original_price, 2, ',', '.') }}</span>
                            <span class="text-sm text-builder-success font-semibold">R$ {{ number_format((float) $product->sale_price, 2, ',', '.') }}</span>
                            @if($product->discount_percentage)
                            <span class="text-[10px] px-1 py-0.5 rounded-full bg-green-500/20 text-green-400 font-bold">-{{ $product->discount_percentage }}%</span>
                            @endif
                        @elseif($product->sale_price)
                            <span class="text-sm text-builder-text font-medium">R$ {{ number_format((float) $product->sale_price, 2, ',', '.') }}</span>
                        @elseif($product->original_price)
                            <span class="text-sm text-builder-text font-medium">R$ {{ number_format((float) $product->original_price, 2, ',', '.') }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-builder-text-dim truncate mt-0.5">{{ $product->url }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                    <button wire:click="toggleProduct({{ $product->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors" title="{{ $product->is_active ? 'Desativar' : 'Ativar' }}">
                        @if($product->is_active)
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
                    <button wire:click="editProduct({{ $product->id }})" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                        <svg class="w-4 h-4 text-builder-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button wire:click="deleteProduct({{ $product->id }})" wire:confirm="Excluir este produto?" class="p-1.5 rounded-lg hover:bg-builder-surface-active transition-colors">
                        <svg class="w-4 h-4 text-builder-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <p class="text-center text-builder-text-dim text-sm py-8">
                {{ $search ? 'Nenhum produto encontrado para "' . $search . '"' : 'Nenhum produto adicionado ainda' }}
            </p>
        @endforelse
    </div>
</div>
