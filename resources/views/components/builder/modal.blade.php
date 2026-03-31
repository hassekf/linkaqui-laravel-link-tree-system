@props([
    'name',
    'title' => '',
    'maxWidth' => 'max-w-lg',
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    {{ $attributes }}
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
    ></div>

    {{-- Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="w-full {{ $maxWidth }} bg-builder-surface border border-builder-border rounded-xl shadow-xl overflow-hidden"
        >
            {{-- Header --}}
            @if ($title)
                <div class="flex items-center justify-between px-6 py-4 border-b border-builder-border">
                    <h3 class="text-lg font-semibold text-builder-text">{{ $title }}</h3>
                    <button
                        @click="open = false"
                        class="text-builder-text-muted hover:text-builder-text transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Content --}}
            <div class="px-6 py-4">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if (isset($footer))
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-builder-border">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
