<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LinkAqui — Sua página de links, do seu jeito' }}</title>
    <meta name="description" content="Crie sua página de links personalizada, gratuita e open source. Compartilhe todos os seus links em um só lugar.">
    @vite(['resources/css/landing.css'])
</head>
<body class="min-h-screen bg-landing-bg text-landing-text font-landing antialiased">
    {{ $slot }}
</body>
</html>
