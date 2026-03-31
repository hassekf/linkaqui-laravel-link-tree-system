@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-builder-border-focus focus:ring-offset-2 focus:ring-offset-builder-bg disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';

    $variantClasses = match ($variant) {
        'primary' => 'bg-builder-primary hover:bg-builder-primary-hover text-builder-primary-text',
        'secondary' => 'bg-builder-surface hover:bg-builder-surface-hover text-builder-text border border-builder-border',
        'ghost' => 'bg-transparent hover:bg-builder-surface-hover text-builder-text-muted hover:text-builder-text',
        'danger' => 'bg-builder-danger hover:bg-builder-danger-hover text-white',
        default => 'bg-builder-primary hover:bg-builder-primary-hover text-builder-primary-text',
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses"]) }}>
    {{ $slot }}
</button>
