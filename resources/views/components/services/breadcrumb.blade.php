@props([
    'items' => [],
])

<nav class="flex items-center gap-2 text-sm flex-wrap" aria-label="Breadcrumb">
    @foreach ($items as $item)
        @if ($loop->first)
            <a href="{{ $item['url'] ?? '#' }}"
               class="text-gray-500 hover:text-primary transition-colors no-underline font-medium"
               wire:navigate>{{ $item['label'] }}</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform:rotate(180deg)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18l6-6-6-6"/>
            </svg>
        @elseif ($loop->last)
            <span class="text-gray-900 font-bold">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] ?? '#' }}"
               class="text-gray-500 hover:text-primary transition-colors no-underline font-medium"
               wire:navigate>{{ $item['label'] }}</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform:rotate(180deg)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18l6-6-6-6"/>
            </svg>
        @endif
    @endforeach
</nav>
