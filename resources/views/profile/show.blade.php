<x-layouts.profile :title="$profileUser->name . ' | LinkAqui'" :$profileUser :$themeConfig :$fontFamily>
    <x-profile.theme-background :config="$themeConfig" />

    <main class="relative z-10 mx-auto max-w-[480px] px-6 py-12">

        {{-- Avatar Section --}}
        <div class="flex flex-col items-center text-center mb-8" style="animation: stagger-in 0.5s ease-out both; animation-delay: 0.05s;">
            <div class="relative mb-4 w-[110px] h-[110px]">
                {{-- Rotating ring (behind everything) --}}
                <div class="absolute inset-0 rounded-full animate-avatar-ring"
                     style="background: conic-gradient(from 0deg, {{ $themeConfig['ringColors'][0] ?? '#7c5cfc' }}, {{ $themeConfig['ringColors'][1] ?? '#a855f7' }}, {{ $themeConfig['ringColors'][2] ?? '#ec4899' }}, {{ $themeConfig['ringColors'][0] ?? '#7c5cfc' }});"></div>
                {{-- Static avatar with bg gap --}}
                <div class="absolute inset-[3px] rounded-full p-[3px]" style="background: var(--theme-bg);">
                    <img src="{{ $profileUser->avatar_url }}" alt="{{ $profileUser->name }}"
                         class="w-full h-full rounded-full object-cover">
                </div>
            </div>

            <h1 class="text-2xl font-bold mb-1"
                style="background: linear-gradient(to right, {{ $themeConfig['nameGradient'][0] ?? '#ffffff' }}, {{ $themeConfig['nameGradient'][1] ?? '#a855f7' }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ $profileUser->name }}
            </h1>

            <p class="text-sm" style="color: var(--theme-text-muted);">{{ '@' . $profileUser->username }}</p>

            @if($profileUser->bio)
            <p class="mt-2 text-sm" style="color: var(--theme-text-muted);">{{ $profileUser->bio }}</p>
            @endif
        </div>

        {{-- Product Search --}}
        @if($productSearchEnabled)
        <div class="mb-8" style="animation: stagger-in 0.5s ease-out both; animation-delay: 0.08s;"
             x-data="{
                code: '',
                loading: false,
                searched: false,
                found: false,
                product: null,
                message: '',
                async search() {
                    if (this.code.length < 3) return;
                    this.loading = true;
                    this.searched = false;

                    try {
                        const resp = await fetch(`/product/search?code=${encodeURIComponent(this.code)}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await resp.json();

                        await new Promise(r => setTimeout(r, 300));

                        this.found = data.found;
                        this.product = data.product || null;
                        this.message = data.message || '';
                    } catch (e) {
                        this.found = false;
                        this.message = 'Erro ao buscar produto';
                    }

                    this.loading = false;
                    this.searched = true;
                },
                async trackClick(productUrl) {
                    try {
                        const code = this.product?.code;
                        if (code) {
                            fetch(`/product/${code}/click`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json',
                                },
                            });
                        }
                    } catch (e) {}
                    window.location.href = productUrl;
                }
             }">

            {{-- Search input --}}
            <div class="flex gap-2">
                <input type="text" x-model="code"
                       @keydown.enter="search()"
                       placeholder="Digite o codigo do produto"
                       maxlength="10"
                       class="flex-1 rounded-xl px-4 py-3 text-sm placeholder-opacity-50 focus:outline-none focus:ring-2 transition-all"
                       style="background: var(--theme-card-bg); border: 1px solid var(--theme-card-border); color: var(--theme-text); backdrop-filter: blur(12px); --tw-ring-color: var(--theme-card-border);"
                       x-on:input="code = code.toUpperCase()">
                <button @click="search()"
                        :disabled="loading || code.length < 3"
                        class="rounded-xl px-5 py-3 text-sm font-medium transition-all disabled:opacity-50"
                        style="background: var(--theme-card-bg); border: 1px solid var(--theme-card-border); color: var(--theme-text); backdrop-filter: blur(12px);"
                        :class="{ 'hover:-translate-y-0.5': !loading && code.length >= 3 }">
                    <span x-show="!loading">Buscar</span>
                    <span x-show="loading" x-cloak>
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>

            {{-- Skeleton loading --}}
            <div x-show="loading" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-3">
                <div class="rounded-2xl p-4 animate-pulse" style="background: var(--theme-card-bg); border: 1px solid var(--theme-card-border);">
                    <div class="flex gap-4">
                        <div class="w-20 h-20 rounded-xl" style="background: var(--theme-card-border);"></div>
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 rounded w-3/4" style="background: var(--theme-card-border);"></div>
                            <div class="h-3 rounded w-1/2" style="background: var(--theme-card-border);"></div>
                            <div class="h-3 rounded w-1/3" style="background: var(--theme-card-border);"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product result --}}
            <div x-show="searched && !loading" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <template x-if="found && product">
                    <div class="mt-3 rounded-2xl overflow-hidden" style="background: var(--theme-card-bg); border: 1px solid var(--theme-card-border); backdrop-filter: blur(12px);">
                        <div class="flex gap-4 p-4">
                            {{-- Product image --}}
                            <template x-if="product.image_url">
                                <img :src="product.image_url" :alt="product.name" class="w-20 h-20 rounded-xl object-cover shrink-0">
                            </template>

                            {{-- Product info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate" x-text="product.name" style="color: var(--theme-text);"></p>

                                <div class="flex items-center gap-2 mt-1">
                                    <template x-if="product.original_price && product.sale_price && product.original_price !== product.sale_price">
                                        <span class="text-xs line-through opacity-50" style="color: var(--theme-text-muted);" x-text="'R$ ' + product.original_price"></span>
                                    </template>
                                    <template x-if="product.sale_price">
                                        <span class="text-sm font-bold" style="color: var(--theme-text);" x-text="'R$ ' + product.sale_price"></span>
                                    </template>
                                    <template x-if="!product.sale_price && product.original_price">
                                        <span class="text-sm font-bold" style="color: var(--theme-text);" x-text="'R$ ' + product.original_price"></span>
                                    </template>
                                </div>

                                <template x-if="product.discount_percentage">
                                    <span class="inline-block mt-1 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-green-500/20 text-green-400" x-text="product.discount_percentage + '% OFF'"></span>
                                </template>
                            </div>
                        </div>

                        {{-- Buy button --}}
                        <button @click="trackClick(product.url)"
                                class="w-full py-3 text-sm font-semibold text-center transition-opacity hover:opacity-90"
                                style="background: rgba(255,255,255,0.1); color: var(--theme-text); border-top: 1px solid var(--theme-card-border);">
                            Comprar agora
                        </button>
                    </div>
                </template>

                <template x-if="!found">
                    <p class="mt-3 text-center text-sm py-3 rounded-xl" style="color: var(--theme-text-muted); background: var(--theme-card-bg); border: 1px solid var(--theme-card-border);" x-text="message"></p>
                </template>
            </div>
        </div>
        @endif

        {{-- Social at top --}}
        @if($socialPosition === 'top')
            @include('profile.partials.social-links', ['profileUser' => $profileUser, 'themeConfig' => $themeConfig])
            @if($profileUser->socialLinks->isNotEmpty())<div class="mb-8"></div>@endif
        @endif

        {{-- Content Items (links + embeds unified) --}}
        @if($contentItems->isNotEmpty())
        <div class="space-y-3 mb-8">
            @foreach($contentItems as $index => $contentItem)
                @if($contentItem->type === 'link')
                    @php($link = $contentItem->item)
                    @if($link->type === \App\Enums\LinkType::Heading)
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-center pt-4"
                            style="color: var(--theme-text-muted); animation: stagger-in 0.5s ease-out both; animation-delay: {{ 0.1 + $index * 0.05 }}s;">
                            {{ $link->title }}
                        </h3>
                    @elseif($link->type === \App\Enums\LinkType::Divider)
                        <hr class="border-0 h-px my-4"
                            style="background: var(--theme-card-border); animation: stagger-in 0.5s ease-out both; animation-delay: {{ 0.1 + $index * 0.05 }}s;">
                    @else
                        <a href="#"
                           data-link-id="{{ $link->id }}"
                           data-url="{{ $link->url }}"
                           class="link-card group flex items-center gap-4 rounded-2xl px-5 py-4 transition-all duration-300 hover:-translate-y-0.5"
                           style="background: {{ $link->bg_color ?? 'var(--theme-card-bg)' }}; border: 1px solid var(--theme-card-border); backdrop-filter: blur(12px); animation: stagger-in 0.5s ease-out both; animation-delay: {{ 0.1 + $index * 0.05 }}s; {{ $link->text_color ? 'color: ' . $link->text_color : '' }}">

                            @if($link->image_path)
                                <img src="{{ asset('storage/' . $link->image_path) }}" alt="" class="w-[22px] h-[22px] rounded object-cover">
                            @elseif($link->icon)
                                <x-link-icon :icon="$link->icon" class="w-[22px] h-[22px] shrink-0 opacity-70" />
                            @endif

                            <span class="flex-1 text-center font-medium text-sm">{{ $link->title }}</span>

                            <svg class="w-4 h-4 opacity-50 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif
                @elseif($contentItem->type === 'embed')
                    @php($embed = $contentItem->item)
                    <div class="rounded-2xl overflow-hidden"
                         style="border: 1px solid var(--theme-card-border); animation: stagger-in 0.5s ease-out both; animation-delay: {{ 0.1 + $index * 0.05 }}s;">
                        @if($embed->title)
                        <p class="text-xs font-medium px-4 py-2" style="color: var(--theme-text-muted); background: var(--theme-card-bg);">{{ $embed->title }}</p>
                        @endif

                        @if($embed->type === \App\Enums\EmbedType::YouTube || $embed->type === \App\Enums\EmbedType::Vimeo)
                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                            <iframe src="{{ $embed->embed_url }}"
                                    class="absolute inset-0 w-full h-full"
                                    frameborder="0"
                                    sandbox="allow-scripts allow-same-origin allow-presentation"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    loading="lazy"></iframe>
                        </div>
                        @elseif($embed->type === \App\Enums\EmbedType::Spotify)
                        <iframe src="{{ $embed->embed_url }}"
                                class="w-full"
                                height="152"
                                frameborder="0"
                                sandbox="allow-scripts allow-same-origin"
                                allow="encrypted-media"
                                loading="lazy"></iframe>
                        @elseif($embed->type === \App\Enums\EmbedType::SoundCloud)
                        <iframe src="{{ $embed->embed_url }}"
                                class="w-full"
                                height="166"
                                frameborder="0"
                                sandbox="allow-scripts allow-same-origin allow-popups"
                                allow="autoplay"
                                loading="lazy"></iframe>
                        @elseif($embed->type === \App\Enums\EmbedType::Twitch)
                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                            <iframe src="{{ $embed->embed_url }}&parent={{ request()->getHost() }}"
                                    class="absolute inset-0 w-full h-full"
                                    frameborder="0"
                                    sandbox="allow-scripts allow-same-origin allow-popups"
                                    allowfullscreen
                                    loading="lazy"></iframe>
                        </div>
                        @elseif($embed->type === \App\Enums\EmbedType::TikTok)
                        <iframe src="{{ $embed->embed_url }}"
                                class="w-full"
                                height="740"
                                frameborder="0"
                                sandbox="allow-scripts allow-same-origin allow-popups"
                                loading="lazy"></iframe>
                        @elseif($embed->type === \App\Enums\EmbedType::AppleMusic)
                        <iframe src="{{ $embed->embed_url }}"
                                class="w-full"
                                height="175"
                                frameborder="0"
                                sandbox="allow-scripts allow-same-origin allow-popups allow-forms"
                                allow="autoplay; encrypted-media"
                                loading="lazy"
                                style="border-radius: 12px;"></iframe>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Social at bottom (default) --}}
        @if($socialPosition === 'bottom')
            @include('profile.partials.social-links', ['profileUser' => $profileUser, 'themeConfig' => $themeConfig])
        @endif

        {{-- Footer --}}
        <footer class="mt-12 text-center" style="animation: stagger-in 0.5s ease-out both; animation-delay: 0.5s;">
            <a href="{{ url('/') }}" class="text-xs transition-opacity hover:opacity-100 opacity-50" style="color: var(--theme-text-muted);">
                Feito com LinkAqui
            </a>
        </footer>
    </main>
</x-layouts.profile>
