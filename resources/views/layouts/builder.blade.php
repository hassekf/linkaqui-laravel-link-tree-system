<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Page Builder' }} | LinkAqui</title>
    @vite(['resources/css/builder.css', 'resources/js/builder.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-builder-bg text-builder-text font-builder antialiased">
    <!-- Top Bar -->
    <header class="h-14 border-b border-builder-border flex items-center justify-between px-4 bg-builder-surface">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-builder-text-muted hover:text-builder-text transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span class="text-sm font-semibold">LinkAqui</span>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('products') }}"
               class="text-xs px-3 py-1.5 rounded-lg text-builder-text-muted hover:text-builder-text border border-builder-border hover:border-builder-border-focus transition-colors">
                Produtos
            </a>
            <a href="{{ route('analytics') }}"
               class="text-xs px-3 py-1.5 rounded-lg text-builder-text-muted hover:text-builder-text border border-builder-border hover:border-builder-border-focus transition-colors">
                Analytics
            </a>
            @if(Route::has('profile.show'))
            <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}"
               target="_blank"
               class="text-xs px-3 py-1.5 rounded-lg bg-builder-primary text-builder-primary-text hover:bg-builder-primary-hover transition-colors">
                Ver minha página
            </a>
            @endif
            <div class="w-8 h-8 rounded-full overflow-hidden">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-full h-full object-cover">
            </div>
        </div>
    </header>

    @if(isset($preview) && $preview->isNotEmpty())
    <!-- Main Content: Two Panels -->
    <div class="flex flex-col lg:flex-row h-auto lg:h-[calc(100vh-3.5rem)]">
        <!-- Left Panel: Editor (scrollable) -->
        <div class="w-full lg:w-[60%] overflow-y-auto p-6 lg:border-r border-builder-border">
            {{ $slot }}
        </div>

        <!-- Right Panel: Live Preview -->
        <div class="w-full lg:w-[40%] flex flex-col bg-builder-bg items-center justify-start p-6 overflow-y-auto border-t lg:border-t-0 border-builder-border">
            {{ $preview }}
        </div>
    </div>
    @else
    <!-- Main Content: Single Panel (centered) -->
    <div class="h-[calc(100vh-3.5rem)] overflow-y-auto">
        <div class="max-w-5xl mx-auto p-6">
            {{ $slot }}
        </div>
    </div>
    @endif

    <x-builder.toast />
    @livewireScripts
</body>
</html>
