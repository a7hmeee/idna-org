@props([
    'color' => 'green',
    'path' => null,
])

@php
    $colors = [
        'green' => '#22C55E',
        'blue' => '#3B82F6',
        'red' => '#EF4444',
        'purple' => '#7C3AED',
        'amber' => '#F59E0B',
    ];
    $stroke = $colors[$color] ?? $colors['green'];
    $id = 'spark-' . md5($color . $attributes->get('class', ''));

    $paths = [
        'green' => 'M0 20 L8 16 L16 18 L24 12 L32 14 L40 8 L48 10 L56 6 L64 4',
        'blue' => 'M0 16 L8 14 L16 12 L24 15 L32 10 L40 12 L48 8 L56 10 L64 8',
        'red' => 'M0 4 L8 6 L16 4 L24 8 L32 10 L40 14 L48 16 L56 18 L64 20',
        'purple' => 'M0 14 L8 10 L16 12 L24 6 L32 8 L40 4 L48 6 L56 4 L64 3',
    ];
    $linePath = $path ?? ($paths[$color] ?? $paths['green']);
@endphp

<div class="chart-container">
    <svg class="w-full h-6" viewBox="0 0 64 24" fill="none">
        <defs>
            <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="{{ $stroke }}" stop-opacity="1"/>
                <stop offset="100%" stop-color="{{ $stroke }}" stop-opacity="0"/>
            </linearGradient>
        </defs>
        <path d="{{ $linePath }}" stroke="{{ $stroke }}" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.5"/>
        <path d="{{ $linePath }} L64 24 L0 24 Z" fill="url(#{{ $id }})" opacity="0.15"/>
    </svg>
</div>
