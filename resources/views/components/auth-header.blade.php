@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-xl font-bold tracking-tight" style="color: #ffffff;">{{ $title }}</h1>
    <p class="mt-1 text-sm" style="color: #9ca3af;">{{ $description }}</p>
</div>
