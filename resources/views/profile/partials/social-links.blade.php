@if($profileUser->socialLinks->isNotEmpty())
<div style="animation: stagger-in 0.5s ease-out both; animation-delay: 0.4s;">
    <p class="text-xs font-semibold uppercase tracking-wider text-center mb-4" style="color: var(--theme-text-muted);">Redes sociais</p>
    <div class="flex items-center justify-center gap-3">
        @foreach($profileUser->socialLinks as $social)
        <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer"
           class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:-translate-y-1"
           style="background: var(--theme-card-bg); border: 1px solid var(--theme-card-border);"
           title="{{ $social->platform->label() }}">
            <x-social-icon :platform="$social->platform->value" class="w-5 h-5" style="color: {{ $social->platform->color() }};" />
        </a>
        @endforeach
    </div>
</div>
@endif
