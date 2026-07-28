@props([
    'category' => null,
    'route' => '#',
])

@php
    $cat = $category;
@endphp

<a href="{{ $route }}" wire:navigate
   class="group block bg-surface rounded-2xl border border-border p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-lg hover:-translate-y-1">
    <div class="flex flex-col h-full">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-primary-light group-hover:bg-primary flex items-center justify-center flex-shrink-0 transition-all duration-300">
                @if ($cat->icon)
                    <i data-lucide="{{ $cat->icon }}" class="w-6 h-6 text-primary group-hover:text-white transition-colors duration-300"></i>
                @else
                    <i data-lucide="folder" class="w-6 h-6 text-primary group-hover:text-white transition-colors duration-300"></i>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-base font-bold text-text group-hover:text-primary transition-colors mb-1">{{ $cat->name }}</h3>
                @if ($cat->description)
                    <p class="text-xs text-text-secondary leading-relaxed line-clamp-2">{{ $cat->description }}</p>
                @endif
            </div>
        </div>
        <div class="mt-auto flex items-center justify-between pt-4 border-t border-border">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-text-tertiary">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                <span>{{ $cat->services_count ?? 0 }} خدمة</span>
            </span>
            <i data-lucide="arrow-left" class="w-4 h-4 text-text-tertiary group-hover:text-primary transition-all duration-300" style="transform:rotate(180deg)"></i>
        </div>
    </div>
</a>
