@props([
    'icon',
    'label',
    'value',
    'trend' => null,
    'trendUp' => true,
    'color' => 'primary',
    'sparkline' => null,
])

@php
    $colors = [
        'primary' => ['bg' => 'bg-municipal-50', 'icon' => 'text-primary'],
        'blue' => ['bg' => 'bg-blue-50', 'icon' => 'text-blue-600'],
        'amber' => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600'],
        'green' => ['bg' => 'bg-green-50', 'icon' => 'text-green-600'],
        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600'],
        'red' => ['bg' => 'bg-red-50', 'icon' => 'text-red-600'],
    ];

    $palette = $colors[$color] ?? $colors['primary'];

    $trendColor = $trendUp ? 'text-success' : 'text-danger';
    $trendIcon = $trendUp ? 'trending-up' : 'trending-down';

    $svgId = 'spark-' . md5($label . $value);
@endphp

<div {{ $attributes->class(['stat-card hover:shadow-lg hover:border-accent hover:-translate-y-0.5 transition-all duration-300 cursor-pointer']) }}>
    <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 rounded-xl {{ $palette['bg'] }} flex items-center justify-center">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $palette['icon'] }}"></i>
        </div>
        @if ($trend)
            <span class="text-[10px] font-bold {{ $trendColor }} flex items-center gap-0.5">
                <i data-lucide="{{ $trendIcon }}" class="w-3 h-3"></i>
                {{ $trend }}
            </span>
        @endif
    </div>

    <p class="text-2xl font-bold text-text">{{ $value }}</p>
    <p class="text-xs text-text-tertiary font-medium">{{ $label }}</p>

    @if ($sparkline)
        <div class="mt-2 chart-container">
            <svg class="w-full h-7" viewBox="0 0 64 24" fill="none">
                <defs>
                    <linearGradient id="{{ $svgId }}" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="{{ $trendUp ? '#22C55E' : '#EF4444' }}" stop-opacity="0.2"/>
                        <stop offset="100%" stop-color="{{ $trendUp ? '#22C55E' : '#EF4444' }}" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="{{ $sparkline }}" stroke="{{ $trendUp ? '#22C55E' : '#EF4444' }}" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.6"/>
                <path d="{{ $sparkline }} L64 24 L0 24 Z" fill="url(#{{ $svgId }})"/>
            </svg>
        </div>
    @endif
</div>
