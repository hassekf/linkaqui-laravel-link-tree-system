@props([])

<div {{ $attributes->merge(['class' => 'cursor-grab active:cursor-grabbing text-builder-text-dim hover:text-builder-text-muted transition-colors']) }}>
    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <circle cx="7" cy="4" r="1.5" />
        <circle cx="13" cy="4" r="1.5" />
        <circle cx="7" cy="10" r="1.5" />
        <circle cx="13" cy="10" r="1.5" />
        <circle cx="7" cy="16" r="1.5" />
        <circle cx="13" cy="16" r="1.5" />
    </svg>
</div>
