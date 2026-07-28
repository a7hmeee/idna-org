@props([
    'size' => 'md',
    'text' => null,
])

@php
    $sizes = [
        'sm' => 'w-4 h-4 border',
        'md' => 'w-6 h-6 border-2',
        'lg' => 'w-8 h-8 border-[3px]',
        'xl' => 'w-12 h-12 border-[3px]',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="flex flex-col items-center justify-center gap-3">
    <div {{ $attributes->class([$sizeClass, 'border-primary border-t-transparent rounded-full animate-spin']) }}></div>
    @if ($text)
        <p class="text-sm text-text-tertiary animate-pulse">{{ $text }}</p>
    @endif
</div>
