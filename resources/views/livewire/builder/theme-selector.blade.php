<x-builder.card>
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between">
            <h2 class="text-lg font-semibold text-builder-text">Tema</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-builder-text-muted transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-4">
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                @foreach($themes as $theme)
                <button wire:click="selectTheme('{{ $theme->slug }}')"
                    class="relative rounded-xl p-2 border-2 transition-all duration-200 {{ $selectedTheme === $theme->slug ? 'border-builder-primary' : 'border-builder-border hover:border-builder-border-focus' }}">

                    <!-- Theme preview mini -->
                    <div class="w-full h-20 rounded-lg overflow-hidden mb-1.5 flex items-center justify-center gap-1" style="background: {{ $theme->config['background'] ?? '#0a0a0f' }};">
                        @foreach(($theme->config['meshColors'] ?? $theme->config['gradientColors'] ?? []) as $color)
                        <div class="w-2.5 h-2.5 rounded-full opacity-60" style="background: {{ $color }};"></div>
                        @endforeach
                    </div>

                    <p class="text-xs font-medium text-center text-builder-text truncate">{{ $theme->name }}</p>

                    @if($selectedTheme === $theme->slug)
                    <div class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-builder-primary flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    @endif
                </button>
                @endforeach
            </div>
        </div>
    </div>
</x-builder.card>
