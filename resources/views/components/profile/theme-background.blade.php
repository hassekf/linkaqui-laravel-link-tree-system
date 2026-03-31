@props(['config' => []])

@php
    $backgroundType = $config['backgroundType'] ?? 'mesh';
@endphp

@if($backgroundType === 'mesh')
<div class="fixed inset-0 overflow-hidden -z-10">
    <div class="absolute inset-0" style="background-color: var(--theme-bg);"></div>

    @foreach($config['meshColors'] ?? [] as $i => $color)
    <div class="absolute rounded-full opacity-30 blur-[100px] animate-float-orb"
         style="background: {{ $color }}; width: 400px; height: 400px; animation-delay: {{ $i * 3 }}s; animation-duration: {{ 18 + $i * 4 }}s;
         @if($i === 0) top: -10%; left: -10%;
         @elseif($i === 1) top: 50%; right: -15%;
         @else bottom: -10%; left: 30%;
         @endif"></div>
    @endforeach

    @if($config['grain'] ?? false)
    <div class="absolute inset-0 opacity-[0.03] animate-grain"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E&quot;); background-repeat: repeat; mix-blend-mode: overlay;"></div>
    @endif
</div>
@elseif($backgroundType === 'gradient')
<div class="fixed inset-0 -z-10" style="background: linear-gradient({{ $config['gradientAngle'] ?? '135deg' }}, {{ implode(', ', $config['gradientColors'] ?? ['#0a0a0f', '#1a1a2e']) }});"></div>
@else
<div class="fixed inset-0 -z-10" style="background-color: var(--theme-bg);"></div>
@endif
