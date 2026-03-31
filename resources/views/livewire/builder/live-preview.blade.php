<div class="w-full max-w-[320px]">
    <!-- Phone mockup frame -->
    <div class="rounded-[2rem] border-2 border-builder-border overflow-hidden shadow-2xl" style="aspect-ratio: 9/16;">
        <div class="w-full h-full overflow-y-auto relative" style="background: {{ $themeConfig['background'] ?? '#0a0a0f' }}; font-family: '{{ $fontFamily }}', system-ui, sans-serif;">

            <!-- Simplified mesh background -->
            @if(($themeConfig['backgroundType'] ?? 'mesh') === 'mesh')
            <div class="absolute inset-0 overflow-hidden">
                @foreach($themeConfig['meshColors'] ?? [] as $i => $color)
                <div class="absolute rounded-full opacity-20 blur-[60px]"
                     style="background: {{ $color }}; width: 150px; height: 150px;
                     @if($i === 0) top: -5%; left: -5%;
                     @elseif($i === 1) top: 40%; right: -10%;
                     @else bottom: -5%; left: 25%;
                     @endif"></div>
                @endforeach
            </div>
            @endif

            <!-- Content preview -->
            <div class="relative z-10 px-4 py-8 text-center">
                <!-- Avatar -->
                <div class="w-16 h-16 rounded-full mx-auto mb-3 overflow-hidden ring-2 ring-offset-2"
                     style="--tw-ring-color: {{ $themeConfig['avatarRingGradient'][0] ?? '#7c5cfc' }}; --tw-ring-offset-color: {{ $themeConfig['background'] ?? '#0a0a0f' }};">
                    <img src="{{ $profileUser->avatar_url }}" class="w-full h-full object-cover">
                </div>

                <!-- Name -->
                <h2 class="text-sm font-bold" style="color: {{ $themeConfig['textPrimary'] ?? '#fff' }};">{{ $profileUser->name }}</h2>
                <p class="text-[10px] mt-0.5" style="color: {{ $themeConfig['textSecondary'] ?? '#9ca3af' }};">{{ '@' . $profileUser->username }}</p>

                @if($profileUser->bio)
                <p class="text-[10px] mt-1" style="color: {{ $themeConfig['textSecondary'] ?? '#9ca3af' }};">{{ $profileUser->bio }}</p>
                @endif

                <!-- Social at top -->
                @if($socialPosition === 'top' && $profileUser->socialLinks->isNotEmpty())
                <div class="mt-3 mb-2 flex justify-center gap-1.5 flex-wrap">
                    @foreach($profileUser->socialLinks as $social)
                    <div class="w-7 h-7 rounded-full flex items-center justify-center"
                         style="background: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }}; border: 1px solid {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};">
                        <x-social-icon :platform="$social->platform->value" class="w-3.5 h-3.5" style="color: {{ $social->platform->color() }};" />
                    </div>
                    @endforeach
                </div>
                @endif

                @if($productSearchEnabled)
                <div class="mt-3 mb-2 flex gap-1">
                    <div class="flex-1 h-6 rounded-lg" style="background: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }}; border: 1px solid {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};"></div>
                    <div class="w-10 h-6 rounded-lg" style="background: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }}; border: 1px solid {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};"></div>
                </div>
                @endif

                <!-- Content preview (links + embeds unified) -->
                <div class="mt-4 space-y-1.5">
                    @foreach($contentItems as $contentItem)
                        @if($contentItem->type === 'link')
                            @php($link = $contentItem->item)
                            @if($link->type === \App\Enums\LinkType::Heading)
                            <p class="text-[8px] font-semibold uppercase tracking-wider pt-2"
                               style="color: {{ $themeConfig['textSecondary'] ?? '#9ca3af' }};">
                                {{ $link->title }}
                            </p>
                            @elseif($link->type === \App\Enums\LinkType::Divider)
                            <hr class="border-0 h-px my-1" style="background: {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};">
                            @else
                            <div class="rounded-lg px-3 py-2 text-[10px] font-medium truncate"
                                 style="background: {{ $link->bg_color ?? ($themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)') }}; border: 1px solid {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }}; color: {{ $link->text_color ?? ($themeConfig['textPrimary'] ?? '#fff') }};">
                                {{ $link->title }}
                            </div>
                            @endif
                        @elseif($contentItem->type === 'embed')
                            @php($embed = $contentItem->item)
                            <div class="rounded-lg overflow-hidden text-left" style="border: 1px solid {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};">
                                @if($embed->title)
                                <p class="text-[8px] px-2 py-1" style="color: {{ $themeConfig['textSecondary'] ?? '#9ca3af' }}; background: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }};">{{ $embed->title }}</p>
                                @endif
                                <div class="flex items-center gap-1.5 px-2 py-2" style="background: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }};">
                                    <div class="w-5 h-5 rounded flex items-center justify-center shrink-0
                                        @switch($embed->type)
                                            @case(\App\Enums\EmbedType::YouTube) bg-red-500/20 @break
                                            @case(\App\Enums\EmbedType::Spotify) bg-green-500/20 @break
                                            @case(\App\Enums\EmbedType::SoundCloud) bg-orange-500/20 @break
                                            @case(\App\Enums\EmbedType::Twitch) bg-purple-500/20 @break
                                            @case(\App\Enums\EmbedType::Vimeo) bg-blue-500/20 @break
                                            @case(\App\Enums\EmbedType::TikTok) bg-zinc-500/20 @break
                                            @case(\App\Enums\EmbedType::AppleMusic) bg-pink-500/20 @break
                                        @endswitch">
                                        @if($embed->type === \App\Enums\EmbedType::YouTube)
                                        <svg class="w-3 h-3 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        @elseif($embed->type === \App\Enums\EmbedType::Spotify)
                                        <svg class="w-3 h-3 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                                        @elseif($embed->type === \App\Enums\EmbedType::SoundCloud)
                                        <svg class="w-3 h-3 text-orange-400" fill="currentColor" viewBox="0 0 24 24"><path d="M1.175 12.225c-.051 0-.094.046-.101.1l-.233 2.154.233 2.105c.007.058.05.098.101.098.05 0 .09-.04.099-.098l.255-2.105-.27-2.154c-.009-.057-.049-.1-.1-.1m-.899.828c-.06 0-.091.037-.104.094L0 14.479l.172 1.282c.013.06.045.094.104.094.057 0 .09-.037.1-.094l.2-1.282-.2-1.332c-.01-.057-.043-.094-.1-.094m1.83-1.229c-.063 0-.103.045-.108.104l-.2 2.551.2 2.456c.005.061.045.108.108.108.063 0 .105-.047.108-.108l.227-2.456-.227-2.551c-.003-.059-.045-.104-.108-.104m.862-.182c-.074 0-.12.053-.125.115l-.18 2.733.18 2.625c.005.063.051.12.125.12.072 0 .117-.057.123-.12l.202-2.625-.202-2.733c-.006-.062-.051-.115-.123-.115m.87-.12c-.083 0-.135.063-.14.13l-.16 2.853.16 2.727c.005.072.057.133.14.133.08 0 .133-.061.14-.133l.18-2.727-.18-2.853c-.007-.067-.06-.13-.14-.13m.94-.226c-.092 0-.15.072-.153.145l-.14 3.079.14 2.803c.003.078.061.148.153.148.088 0 .148-.07.153-.148l.16-2.803-.16-3.079c-.005-.073-.065-.145-.153-.145m.973-.156c-.1 0-.163.08-.166.157l-.12 3.235.12 2.845c.003.083.066.16.166.16.096 0 .16-.077.165-.16l.135-2.845-.135-3.235c-.005-.077-.065-.157-.165-.157m1.016-.17c-.108 0-.176.087-.18.17l-.1 3.405.1 2.87c.004.088.072.17.18.17.104 0 .174-.082.18-.17l.114-2.87-.114-3.405c-.006-.083-.076-.17-.18-.17m1.064-.19c-.115 0-.19.096-.193.186l-.083 3.595.083 2.882c.003.094.078.19.193.19.112 0 .187-.096.193-.19l.094-2.882-.094-3.595c-.006-.09-.081-.186-.193-.186m1.11-.21c-.12 0-.2.104-.205.2l-.063 3.805.063 2.888c.005.1.085.204.205.204.118 0 .197-.104.204-.204l.072-2.888-.072-3.805c-.007-.096-.086-.2-.204-.2m1.156-.24c-.13 0-.213.113-.218.214l-.044 4.045.044 2.89c.005.107.088.218.218.218.127 0 .21-.111.218-.218l.05-2.89-.05-4.045c-.008-.101-.091-.214-.218-.214m1.198-.272c-.136 0-.224.12-.228.226l-.023 4.317.023 2.885c.004.112.092.23.228.23.133 0 .222-.118.228-.23l.026-2.885-.026-4.317c-.006-.106-.095-.226-.228-.226m1.95-1.627c-.07 0-.138.01-.204.03-.082-.863-.775-1.538-1.633-1.538-.196 0-.384.039-.56.107-.175.068-.257.14-.257.282v8.637c0 .145.105.264.247.28.015.002 2.153.003 2.407.003 1.074 0 1.943-.87 1.943-1.945 0-1.074-.87-1.945-1.943-1.945"/></svg>
                                        @elseif($embed->type === \App\Enums\EmbedType::Twitch)
                                        <svg class="w-3 h-3 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714z"/></svg>
                                        @elseif($embed->type === \App\Enums\EmbedType::Vimeo)
                                        <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.977 6.416c-.105 2.338-1.739 5.543-4.894 9.609-3.268 4.247-6.026 6.37-8.29 6.37-1.409 0-2.578-1.294-3.553-3.881L5.322 11.4C4.603 8.816 3.834 7.522 3.01 7.522c-.179 0-.806.378-1.881 1.132L0 7.197c1.185-1.044 2.351-2.084 3.501-3.128C5.08 2.701 6.266 1.984 7.055 1.91c1.867-.18 3.016 1.1 3.447 3.838.465 2.953.789 4.789.971 5.507.539 2.45 1.131 3.674 1.776 3.674.502 0 1.256-.796 2.265-2.385 1.004-1.589 1.54-2.797 1.612-3.628.144-1.371-.395-2.061-1.614-2.061-.574 0-1.167.121-1.777.391 1.186-3.868 3.434-5.757 6.762-5.637 2.473.06 3.628 1.664 3.493 4.797l-.013.01z"/></svg>
                                        @elseif($embed->type === \App\Enums\EmbedType::TikTok)
                                        <svg class="w-3 h-3 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                        @elseif($embed->type === \App\Enums\EmbedType::AppleMusic)
                                        <svg class="w-3 h-3 text-pink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.997 6.124c0-.738-.065-1.47-.24-2.19-.317-1.31-1.062-2.31-2.18-3.043C21.003.517 20.373.285 19.7.164c-.517-.093-1.038-.135-1.564-.15-.04-.003-.083-.01-.124-.013H5.988c-.152.01-.303.017-.455.026C4.786.07 4.043.15 3.34.428 2.004.96 1.04 1.882.475 3.208c-.192.448-.292.92-.353 1.4-.073.565-.1 1.132-.108 1.7v11.38c.01.37.022.74.063 1.11.083.738.24 1.454.586 2.113.548 1.04 1.36 1.79 2.438 2.26.478.21.988.333 1.51.407.567.08 1.137.112 1.71.12h11.64c.462-.01.923-.03 1.382-.083.694-.08 1.37-.24 2-.545 1.164-.564 2.002-1.416 2.508-2.602.2-.466.326-.957.394-1.46.075-.563.104-1.13.113-1.698V6.124zm-3.87 9.124c-.003.096-.01.19-.02.285-.035.386-.134.755-.36 1.08-.363.52-.88.764-1.482.825-.357.037-.716.04-1.076.04H6.89c-.39 0-.78-.01-1.167-.05-.476-.05-.915-.2-1.285-.532-.326-.293-.504-.663-.59-1.084-.048-.234-.065-.472-.065-.712V8.998c0-.226.013-.452.05-.676.074-.45.272-.835.627-1.126.35-.29.763-.4 1.2-.438.343-.03.688-.036 1.032-.036h10.15c.404 0 .808.005 1.21.053.438.052.837.19 1.177.487.34.3.522.68.6 1.116.043.245.06.494.063.743v6.128z"/><path d="M16.683 8.143c-.008-.016-.025-.024-.044-.024h-1.276c-.026 0-.04.017-.04.04v4.204c0 .098-.003.197-.01.295-.023.33-.092.65-.268.94-.282.46-.701.71-1.217.78-.186.025-.373.033-.56.017-.47-.04-.872-.226-1.14-.627-.196-.293-.26-.622-.252-.965.015-.648.36-1.1.935-1.37.307-.146.638-.2.975-.21.164-.005.328.002.49.02.046.005.067-.008.067-.057V9.218c0-.04-.013-.058-.053-.063-.27-.03-.54-.038-.81-.02-.685.047-1.33.22-1.916.56-.878.508-1.45 1.24-1.68 2.233-.128.543-.148 1.094-.058 1.648.137.84.497 1.55 1.104 2.12.462.434 1.01.71 1.63.85.373.084.75.115 1.13.097.607-.03 1.178-.185 1.694-.5.635-.388 1.063-.93 1.28-1.65.114-.378.155-.766.156-1.16V8.183c0-.013-.005-.027-.015-.04l.002.003z"/></svg>
                                        @endif
                                    </div>
                                    <span class="text-[9px] truncate" style="color: {{ $themeConfig['textPrimary'] ?? '#fff' }};">
                                        {{ $embed->title ?? match($embed->type) {
                                            \App\Enums\EmbedType::YouTube => 'YouTube',
                                            \App\Enums\EmbedType::Spotify => 'Spotify',
                                            \App\Enums\EmbedType::SoundCloud => 'SoundCloud',
                                            \App\Enums\EmbedType::Twitch => 'Twitch',
                                            \App\Enums\EmbedType::Vimeo => 'Vimeo',
                                            \App\Enums\EmbedType::TikTok => 'TikTok',
                                            \App\Enums\EmbedType::AppleMusic => 'Apple Music',
                                            default => 'Embed',
                                        } }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Social at bottom (default) -->
                @if($socialPosition === 'bottom' && $profileUser->socialLinks->isNotEmpty())
                <div class="mt-3 flex justify-center gap-1.5 flex-wrap">
                    @foreach($profileUser->socialLinks as $social)
                    <div class="w-7 h-7 rounded-full flex items-center justify-center"
                         style="background: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }}; border: 1px solid {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};">
                        <x-social-icon :platform="$social->platform->value" class="w-3.5 h-3.5" style="color: {{ $social->platform->color() }};" />
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <p class="text-center text-xs text-builder-text-dim mt-3">Pré-visualização</p>
</div>
