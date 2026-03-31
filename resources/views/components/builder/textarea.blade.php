@props([
    'label' => null,
    'name',
])

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-builder-text">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'rows' => '3',
            'class' => 'w-full rounded-lg border bg-builder-surface text-builder-text placeholder-builder-text-dim px-3 py-2 text-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-builder-border-focus focus:border-builder-border-focus resize-y ' . ($errors->has($name) ? 'border-builder-danger' : 'border-builder-border'),
        ]) }}
    >{{ $slot }}</textarea>

    @error($name)
        <p class="text-xs text-builder-danger">{{ $message }}</p>
    @enderror
</div>
