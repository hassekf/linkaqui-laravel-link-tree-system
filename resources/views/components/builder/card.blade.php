@props([
    'padding' => 'p-4',
])

<div {{ $attributes->merge(['class' => "bg-builder-surface border border-builder-border rounded-xl $padding"]) }}>
    {{ $slot }}
</div>
