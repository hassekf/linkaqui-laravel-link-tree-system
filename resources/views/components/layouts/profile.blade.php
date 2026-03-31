@props(['fontFamily' => null, 'profileUser' => null, 'themeConfig' => []])

@php($resolvedFont = $fontFamily ?? $themeConfig['fontFamily'] ?? 'Inter')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'LinkAqui' }}</title>

    @if($profileUser)
    <meta property="og:title" content="{{ $profileUser->name }}">
    <meta property="og:description" content="{{ $profileUser->bio ?? 'Meus links' }}">
    <meta property="og:image" content="{{ $profileUser->avatar_url }}">
    <meta property="og:type" content="profile">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($resolvedFont) }}:wght@300;400;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/profile.css', 'resources/js/profile.js'])
    <style>[x-cloak] { display: none !important; }</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen" style="background-color: {{ $themeConfig['background'] ?? '#0a0a0f' }}; font-family: '{{ $resolvedFont }}', system-ui, sans-serif; color: {{ $themeConfig['textPrimary'] ?? '#ffffff' }};">
    <style>
        :root {
            --theme-bg: {{ $themeConfig['background'] ?? '#0a0a0f' }};
            --theme-text: {{ $themeConfig['textPrimary'] ?? '#ffffff' }};
            --theme-text-muted: {{ $themeConfig['textSecondary'] ?? '#9ca3af' }};
            --theme-card-bg: {{ $themeConfig['cardBg'] ?? 'rgba(255,255,255,0.05)' }};
            --theme-card-border: {{ $themeConfig['cardBorder'] ?? 'rgba(255,255,255,0.1)' }};
            --theme-font: '{{ $resolvedFont }}', system-ui, sans-serif;
        }
    </style>
    {{ $slot }}
</body>
</html>
