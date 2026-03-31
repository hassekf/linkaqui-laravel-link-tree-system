@props([
    'label' => null,
    'name',
    'checked' => false,
])

<div
    x-data="{ enabled: @js((bool) $checked) }"
    class="flex items-center justify-between"
>
    @if ($label)
        <label
            for="{{ $name }}"
            class="text-sm font-medium text-builder-text cursor-pointer"
            @click="enabled = !enabled"
        >
            {{ $label }}
        </label>
    @endif

    <button
        type="button"
        role="switch"
        :aria-checked="enabled.toString()"
        @click="enabled = !enabled"
        :class="enabled ? 'bg-builder-primary' : 'bg-builder-surface-active'"
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-builder-border-focus focus:ring-offset-2 focus:ring-offset-builder-bg"
        {{ $attributes }}
    >
        <span
            :class="enabled ? 'translate-x-5' : 'translate-x-0'"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
        ></span>
    </button>

    <input type="hidden" name="{{ $name }}" :value="enabled ? '1' : '0'" />
</div>
