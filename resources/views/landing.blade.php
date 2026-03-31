<x-layouts.landing>
    {{-- Static gradient background --}}
    <div class="fixed inset-0 pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(ellipse 80% 60% at 20% 10%, rgba(124,92,252,0.15), transparent),
                            radial-gradient(ellipse 60% 50% at 80% 50%, rgba(59,130,246,0.1), transparent),
                            radial-gradient(ellipse 70% 60% at 40% 90%, rgba(168,85,247,0.1), transparent);"></div>

    {{-- Header --}}
    <header class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-landing-bg/60 border-b border-landing-card-border">
        <nav class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">
                Link<span class="text-landing-accent">Aqui</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-landing-text-muted hover:text-landing-text transition-colors">
                    Entrar
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg bg-landing-accent hover:bg-landing-accent-hover text-white transition-colors">
                    Criar conta
                </a>
            </div>
        </nav>
    </header>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-24 px-6 lg:pt-44 lg:pb-36">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                Sua página de links,
                <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-landing-accent to-blue-400">do seu jeito</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-landing-text-muted max-w-2xl mx-auto leading-relaxed">
                Crie uma página personalizada para compartilhar todos os seus links. Gratuito, open source e sem limites.
            </p>
            <div class="mt-10">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-xl bg-landing-accent hover:bg-landing-accent-hover text-white transition-all animate-hero-glow">
                    Criar minha página grátis
                    <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="relative py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Tudo que você precisa</h2>
                <p class="mt-4 text-landing-text-muted text-lg">Recursos pensados para simplificar sua presença online</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Feature 1 --}}
                <div class="group p-6 rounded-2xl bg-landing-card-bg backdrop-blur-sm border border-landing-card-border hover:border-landing-accent/40 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-landing-accent/15 flex items-center justify-center mb-4 group-hover:bg-landing-accent/25 transition-colors">
                        <svg class="w-6 h-6 text-landing-accent" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Links personalizáveis</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Adicione quantos links quiser com ícones e cores customizadas</p>
                </div>

                {{-- Feature 2 --}}
                <div class="group p-6 rounded-2xl bg-landing-card-bg backdrop-blur-sm border border-landing-card-border hover:border-landing-accent/40 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center mb-4 group-hover:bg-blue-500/25 transition-colors">
                        <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Redes sociais</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Conecte todas as suas redes sociais em um só lugar</p>
                </div>

                {{-- Feature 3 --}}
                <div class="group p-6 rounded-2xl bg-landing-card-bg backdrop-blur-sm border border-landing-card-border hover:border-landing-accent/40 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/15 flex items-center justify-center mb-4 group-hover:bg-pink-500/25 transition-colors">
                        <svg class="w-6 h-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Embeds</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Incorpore vídeos do YouTube e músicas do Spotify</p>
                </div>

                {{-- Feature 4 --}}
                <div class="group p-6 rounded-2xl bg-landing-card-bg backdrop-blur-sm border border-landing-card-border hover:border-landing-accent/40 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center mb-4 group-hover:bg-amber-500/25 transition-colors">
                        <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402M6.75 21A3.75 3.75 0 0 1 3 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 0 0 3.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Temas</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Escolha entre temas modernos e elegantes</p>
                </div>

                {{-- Feature 5 --}}
                <div class="group p-6 rounded-2xl bg-landing-card-bg backdrop-blur-sm border border-landing-card-border hover:border-landing-accent/40 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/15 flex items-center justify-center mb-4 group-hover:bg-emerald-500/25 transition-colors">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Analytics</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Acompanhe visitas e cliques na sua página</p>
                </div>

                {{-- Feature 6 --}}
                <div class="group p-6 rounded-2xl bg-landing-card-bg backdrop-blur-sm border border-landing-card-border hover:border-landing-accent/40 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/15 flex items-center justify-center mb-4 group-hover:bg-cyan-500/25 transition-colors">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Open Source</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">100% gratuito e código aberto</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it Works Section --}}
    <section class="relative py-24 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Como funciona</h2>
                <p class="mt-4 text-landing-text-muted text-lg">Três passos simples para começar</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full bg-landing-accent/20 border-2 border-landing-accent flex items-center justify-center mx-auto mb-5 text-xl font-bold text-landing-accent">
                        1
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Cadastre-se</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Crie sua conta em segundos</p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full bg-landing-accent/20 border-2 border-landing-accent flex items-center justify-center mx-auto mb-5 text-xl font-bold text-landing-accent">
                        2
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Personalize</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Adicione seus links e escolha um tema</p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full bg-landing-accent/20 border-2 border-landing-accent flex items-center justify-center mx-auto mb-5 text-xl font-bold text-landing-accent">
                        3
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Compartilhe</h3>
                    <p class="text-landing-text-muted text-sm leading-relaxed">Pronto! Compartilhe seu link único</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="relative py-12 px-6 border-t border-landing-card-border">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="text-lg font-bold tracking-tight">
                    Link<span class="text-landing-accent">Aqui</span>
                </span>
                <span class="text-landing-text-muted text-sm">Open source e 100% gratuito</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="text-landing-text-muted hover:text-landing-text transition-colors" aria-label="GitHub">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2Z" clip-rule="evenodd" />
                    </svg>
                </a>
                <span class="text-landing-text-muted text-xs">&copy; {{ date('Y') }} LinkAqui</span>
            </div>
        </div>
    </footer>
</x-layouts.landing>
