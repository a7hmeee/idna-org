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
    x-data="{ open: false, _h: false, _t: null }"
    @click.outside="if (!_h) open = false"
    @keydown.escape.window="open = false"
    class="relative inline-block"
>
    <div @click="open = !open" @mouseenter="_h = true; clearTimeout(_t)" @mouseleave="_t = setTimeout(() => _h = false, 150)" class="cursor-pointer">
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
        @mouseenter="_h = true; clearTimeout(_t)"
        @mouseleave="_t = setTimeout(() => _h = false, 150)"
        class="absolute z-50 mt-2 {{ $width }} {{ $alignClasses }} bg-surface-card rounded-xl shadow-xl border border-border-light py-1.5 origin-top"
    >
        {{ $slot }}
    </div>
</div>
