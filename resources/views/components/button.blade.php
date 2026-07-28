@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'loading' => false,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-bold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/40';

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-dark shadow-sm hover:shadow-md active:scale-[0.98]',
        'secondary' => 'bg-secondary text-white hover:bg-primary shadow-sm hover:shadow-md active:scale-[0.98]',
        'ghost' => 'bg-transparent text-text-secondary hover:bg-municipal-50 hover:text-primary active:scale-[0.98]',
        'danger' => 'bg-danger text-white hover:bg-red-600 shadow-sm hover:shadow-md active:scale-[0.98]',
        'outline' => 'bg-transparent text-primary border-2 border-primary hover:bg-primary hover:text-white active:scale-[0.98]',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];

    $iconSizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        'xl' => 'w-5 h-5',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class([$classes]) }}
    @if ($loading) disabled @endif
>
    @if ($loading)
        <svg class="animate-spin {{ $iconSizes[$size] ?? $iconSizes['md'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif ($icon)
        <i data-lucide="{{ $icon }}" class="{{ $iconSizes[$size] ?? $iconSizes['md'] }} shrink-0"></i>
    @endif
    {{ $slot }}
</button>
