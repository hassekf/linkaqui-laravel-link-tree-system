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

    <div class="space-y-2" x-data="{
        hex: '',
        alpha: 100,
        init() {
            const raw = this.$refs.text.value || '';
            if (raw.startsWith('rgba')) {
                const m = raw.match(/rgba?\((\d+),\s*(\d+),\s*(\d+),?\s*([\d.]*)\)/);
                if (m) {
                    this.hex = '#' + [m[1],m[2],m[3]].map(x => parseInt(x).toString(16).padStart(2,'0')).join('');
                    this.alpha = Math.round((parseFloat(m[4] || 1)) * 100);
                }
            } else if (raw.startsWith('#')) {
                this.hex = raw;
                this.alpha = 100;
            }
        },
        get cssValue() {
            if (!this.hex) return '';
            if (this.alpha >= 100) return this.hex;
            const r = parseInt(this.hex.slice(1,3), 16);
            const g = parseInt(this.hex.slice(3,5), 16);
            const b = parseInt(this.hex.slice(5,7), 16);
            return 'rgba(' + r + ',' + g + ',' + b + ',' + (this.alpha / 100).toFixed(2) + ')';
        },
        sync() {
            const val = this.cssValue;
            this.$refs.text.value = val;
            this.$refs.text.dispatchEvent(new Event('input', { bubbles: true }));
        },
        pickColor() {
            this.$refs.picker.click();
        }
    }">
        <div class="flex items-center gap-3">
            {{-- Swatch (clickable) --}}
            <button type="button" @click="pickColor()"
                class="w-9 h-9 rounded-lg border border-builder-border shrink-0 overflow-hidden cursor-pointer"
                title="Escolher cor">
                <div class="w-full h-full" style="background-image: linear-gradient(45deg, #333 25%, transparent 25%), linear-gradient(-45deg, #333 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #333 75%), linear-gradient(-45deg, transparent 75%, #333 75%); background-size: 8px 8px; background-position: 0 0, 0 4px, 4px -4px, -4px 0px;">
                    <div class="w-full h-full" x-bind:style="'background-color: ' + cssValue"></div>
                </div>
            </button>

            {{-- Hidden native color picker --}}
            <input type="color" x-ref="picker" class="sr-only"
                x-bind:value="hex || '#000000'"
                x-on:input="hex = $event.target.value; sync()" />

            {{-- Text input --}}
            <input type="text" id="{{ $name }}" name="{{ $name }}"
                x-ref="text"
                maxlength="30"
                placeholder="#000000"
                {{ $attributes->merge([
                    'class' => 'flex-1 rounded-lg border border-builder-border bg-builder-surface text-builder-text px-3 py-2 text-sm font-mono transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-builder-border-focus focus:border-builder-border-focus',
                ]) }}
            />
        </div>

        {{-- Alpha slider --}}
        <div class="flex items-center gap-3">
            <span class="text-[10px] text-builder-text-dim w-14 shrink-0">Alpha</span>
            <input type="range" min="0" max="100" x-model="alpha" @input="sync()"
                class="flex-1 h-1.5 rounded-full appearance-none cursor-pointer"
                style="background: linear-gradient(to right, transparent, {{ 'var(--color-builder-primary)' }});"
            />
            <span class="text-[10px] text-builder-text-muted font-mono w-8 text-right" x-text="alpha + '%'"></span>
        </div>
    </div>

    @error($name)
        <p class="text-xs text-builder-danger">{{ $message }}</p>
    @enderror
</div>
