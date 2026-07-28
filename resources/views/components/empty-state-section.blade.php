@props([
    'icon' => 'inbox',
    'title' => 'لا توجد بيانات',
    'description' => '',
])

<div {{ $attributes->class(['flex flex-col items-center justify-center py-10 text-center']) }}>
    <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center mb-3">
        <i data-lucide="{{ $icon }}" class="w-6 h-6 text-primary/50"></i>
    </div>
    <h3 class="text-sm font-bold text-text">{{ $title }}</h3>
    @if ($description)
        <p class="text-xs text-text-secondary mt-1 max-w-sm">{{ $description }}</p>
    @endif
</div>
