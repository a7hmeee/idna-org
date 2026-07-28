@props([
    'badge' => '',
    'title' => '',
    'description' => '',
    'icon' => 'info',
    'align' => 'right',
])

<div {{ $attributes->class(['flex flex-col', $align === 'center' ? 'text-center items-center' : '']) }}>
    @if ($badge)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-light text-primary text-xs font-bold mb-3 w-fit">
            @if ($icon)
                <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5"></i>
            @endif
            <span>{{ $badge }}</span>
        </span>
    @endif
    @if ($title)
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-text leading-tight">{{ $title }}</h2>
    @endif
    @if ($description)
        <p class="text-text-secondary mt-2 text-sm sm:text-base max-w-2xl {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $description }}</p>
    @endif
</div>
