@props([
    'src' => null,
    'name' => '',
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => 'w-7 h-7 text-xs',
        'sm' => 'w-9 h-9 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if ($src)
    <img {{ $attributes->class([$sizeClass, 'rounded-full object-cover ring-2 ring-surface-card']) }} src="{{ $src }}" alt="{{ $name }}" />
@else
    <div {{ $attributes->class([$sizeClass, 'rounded-full bg-municipal-100 text-primary font-bold flex items-center justify-center ring-2 ring-surface-card']) }}>
        {{ \Illuminate\Support\Str::initials($name, 2) }}
    </div>
@endif
