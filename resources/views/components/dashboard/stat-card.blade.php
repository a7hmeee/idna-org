@props([
    'icon' => 'users',
    'color' => 'primary',
    'key' => 'users',
    'label' => '',
    'trend' => '+0%',
    'trendUp' => true,
    'sparkline' => 'M0 20 L8 16 L16 18 L24 12 L32 14 L40 8 L48 10 L56 6 L64 4',
    'sparkColor' => '#22C55E',
    'delay' => 100,
])

@php
    $colorIcons = [
        'primary' => ['bg' => 'icon-grad-primary', 'text' => 'text-[#2E6F1F]'],
        'info' => ['bg' => 'icon-grad-info', 'text' => 'text-[#2563EB]'],
        'warning' => ['bg' => 'icon-grad-warning', 'text' => 'text-[#D97706]'],
        'success' => ['bg' => 'icon-grad-success', 'text' => 'text-[#065F46]'],
        'danger' => ['bg' => 'icon-grad-danger', 'text' => 'text-[#DC2626]'],
        'purple' => ['bg' => 'icon-grad-purple', 'text' => 'text-[#7C3AED]'],
    ];
@endphp

<div class="stat-card animate-fade-up delay-{{ $delay }}">
    <div class="{{ $colorIcons[$color]['bg'] }} w-10 h-10 rounded-xl flex items-center justify-center mb-3">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $colorIcons[$color]['text'] }}"></i>
    </div>
    @if ($key === 'revenue')
        <p class="text-xl font-bold text-[#1A2E15]"><span x-text="(counters.revenue / 1000).toFixed(0)"></span>k</p>
    @else
        <p class="text-xl font-bold text-[#1A2E15]" x-text="counters.{{ $key }}.toLocaleString('ar-SA')"></p>
    @endif
    <p class="text-[11px] text-[#7A9A6E] font-medium mb-2">{{ $label }}</p>
    <div class="flex items-center justify-between">
        <span class="text-[10px] font-bold {{ $trendUp ? 'text-[#22C55E]' : 'text-[#EF4444]' }} flex items-center gap-1">
            <i data-lucide="{{ $trendUp ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>{{ $trend }}
        </span>
        <svg class="w-16 h-6" viewBox="0 0 64 24" fill="none">
            <path d="{{ $sparkline }}" stroke="{{ $sparkColor }}" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.5"/>
            <path d="{{ $sparkline }} L64 24 L0 24 Z" fill="url(#sparkline-{{ $key }})" opacity="0.15"/>
        </svg>
    </div>
</div>
