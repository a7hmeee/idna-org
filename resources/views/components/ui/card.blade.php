@props(['hover' => false, 'padding' => true])

<div {{ $attributes->class([
    'card' => !$hover,
    'card-hover' => $hover,
    'p-6' => $padding,
]) }}>
    {{ $slot }}
</div>
