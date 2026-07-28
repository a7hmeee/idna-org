@props([
    'name' => '',
    'maxWidth' => 'md',
    'title' => '',
])

@php
    $maxWidths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
    $widthClass = $maxWidths[$maxWidth] ?? $maxWidths['md'];
@endphp

<div
    x-data="{ open: false }"
    x-on:open-modal.window="open = true"
    x-on:close-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div
        class="fixed inset-0 bg-black/40 backdrop-blur-sm"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
    ></div>

    <div
        class="relative {{ $widthClass }} w-full bg-surface-card rounded-2xl shadow-xl overflow-hidden"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.stop
    >
        @if ($title)
            <div class="flex items-center justify-between px-6 py-4 border-b border-border-light">
                <h3 class="text-lg font-bold text-text">{{ $title }}</h3>
                <button
                    @click="open = false"
                    class="p-1.5 rounded-lg hover:bg-surface-hover text-text-tertiary hover:text-text transition-colors"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        @endif

        <div class="p-6">
            {{ $slot }}
        </div>

        @if (isset($footer))
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border-light bg-surface">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
