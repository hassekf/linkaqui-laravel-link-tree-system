<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased" style="background-color: #0a0a0f; color: #ffffff; font-family: 'Inter', system-ui, sans-serif;">
        {{-- Static gradient background --}}
        <div class="fixed inset-0 pointer-events-none" aria-hidden="true"
             style="background: radial-gradient(ellipse 80% 60% at 20% 10%, rgba(124,92,252,0.12), transparent),
                                radial-gradient(ellipse 60% 50% at 80% 50%, rgba(59,130,246,0.08), transparent),
                                radial-gradient(ellipse 70% 60% at 40% 90%, rgba(168,85,247,0.08), transparent);"></div>

        <div class="relative z-10 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="text-2xl font-bold tracking-tight">
                        Link<span style="color: #7c5cfc;">Aqui</span>
                    </span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
