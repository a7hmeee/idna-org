@props([
    'href' => '#',
    'external' => false,
])

@if ($external)
    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" {{ $attributes->class(['inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-dark transition-all flex-shrink-0']) }}>
        {{ $slot }}
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
    </a>
@else
    <a href="{{ $href }}" wire:navigate {{ $attributes->class(['inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-dark transition-all flex-shrink-0']) }}>
        {{ $slot }}
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
    </a>
@endif
