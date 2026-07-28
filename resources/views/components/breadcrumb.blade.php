@props(['items' => []])

<nav {{ $attributes->class(['hidden sm:flex items-center gap-2 text-xs']) }}>
    @foreach ($items as $index => $item)
        @if (isset($item['url']))
            <a href="{{ $item['url'] }}" class="text-text-tertiary hover:text-primary transition-colors font-semibold">
                {{ $item['label'] }}
            </a>
        @else
            <span class="text-text font-bold">{{ $item['label'] }}</span>
        @endif
        @if (!$loop->last)
            <i data-lucide="chevron-left" class="w-3 h-3 text-text-tertiary shrink-0"></i>
        @endif
    @endforeach
</nav>
