<div>
    <!-- Editor Section (left panel content) -->
    <div class="space-y-6">
        <!-- Section: Profile Settings -->
        <livewire:builder.profile-settings />

        <!-- Section: Content (Links + Embeds unified) -->
        <livewire:builder.content-list />

        <!-- Section: Social Links -->
        <livewire:builder.social-link-manager />

        <!-- Section: Theme -->
        <livewire:builder.theme-selector />

        <!-- Section: Share -->
        <x-builder.card>
            <div x-data="{
                copied: false,
                showQr: false,
                profileUrl: '{{ route('profile.show', ['username' => auth()->user()->username]) }}',
                async copyLink() {
                    await navigator.clipboard.writeText(this.profileUrl);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                    $dispatch('toast', { message: 'Link copiado!', type: 'success' });
                }
            }">
                <h2 class="text-lg font-semibold text-builder-text mb-4">Compartilhar</h2>

                {{-- Profile URL display --}}
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex-1 px-3 py-2 rounded-lg bg-builder-surface text-sm text-builder-text-muted border border-builder-border truncate font-mono">
                        <span x-text="profileUrl"></span>
                    </div>
                    <button @click="copyLink()"
                            class="shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-all"
                            :class="copied ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-builder-primary text-builder-primary-text hover:bg-builder-primary-hover'">
                        <span x-show="!copied">Copiar</span>
                        <span x-show="copied" x-cloak>Copiado!</span>
                    </button>
                </div>

                {{-- QR Code toggle --}}
                <button @click="showQr = !showQr"
                        class="text-sm text-builder-text-muted hover:text-builder-text transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                    </svg>
                    <span x-text="showQr ? 'Esconder QR Code' : 'Mostrar QR Code'"></span>
                </button>

                {{-- QR Code --}}
                <div x-show="showQr" x-collapse class="mt-4">
                    <div class="flex flex-col items-center gap-3 p-6 rounded-xl bg-white">
                        <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(profileUrl) + '&bgcolor=FFFFFF&color=000000&margin=10'"
                             alt="QR Code"
                             class="w-48 h-48">
                        <p class="text-xs text-zinc-500">Escaneie para acessar sua pagina</p>
                    </div>
                    <div class="mt-3 flex justify-center">
                        <a :href="'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' + encodeURIComponent(profileUrl) + '&bgcolor=FFFFFF&color=000000&format=png&margin=20'"
                           download="linkaqui-qrcode.png"
                           class="text-xs px-3 py-1.5 rounded-lg bg-builder-surface-hover border border-builder-border text-builder-text-muted hover:text-builder-text hover:border-builder-border-focus transition-colors">
                            Baixar QR Code
                        </a>
                    </div>
                </div>
            </div>
        </x-builder.card>

        <!-- Section: Analytics -->
        <x-builder.card>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-builder-text">Analytics</h2>
                <a href="{{ route('analytics') }}"
                   class="text-xs px-3 py-1.5 rounded-lg bg-builder-primary text-builder-primary-text hover:bg-builder-primary-hover transition-colors">
                    Ver analytics
                </a>
            </div>
        </x-builder.card>
    </div>

    <!-- Preview Section (right panel via named slot) -->
    <x-slot:preview>
        <livewire:builder.live-preview />
    </x-slot:preview>
</div>
