@props([
    'variant' => 'default',
    'dot' => false,
])

@php
    $variants = [
        'default' => 'bg-surface-hover text-text-secondary',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'info' => 'badge-info',
        'primary' => 'bg-municipal-50 text-primary',
    ];
@endphp

<span {{ $attributes->class(['badge', $variants[$variant] ?? $variants['default']]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full bg-current me-1.5"></span>
    @endif
    {{ $slot }}
</span>
