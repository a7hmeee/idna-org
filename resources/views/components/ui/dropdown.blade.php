@props([
    'align' => 'end',
    'width' => 'w-56',
    'trigger' => null,
])

@php
    $id = 'dropdown-' . md5($attributes->id ?? uniqid());
    $alignClasses = match($align) {
        'left' => 'start-0',
        'right' => 'end-0',
        default => 'end-0',
    };
@endphp

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="relative inline-block"
>
    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        class="absolute z-50 mt-2 {{ $width }} {{ $alignClasses }} bg-surface-card rounded-xl shadow-xl border border-border-light py-1.5 origin-top"
    >
        {{ $slot }}
    </div>
</div>
