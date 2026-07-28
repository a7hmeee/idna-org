@props([
    'label' => null,
    'error' => null,
    'icon' => null,
    'type' => 'text',
    'id' => null,
])

@php
    $fieldName = $attributes->wire('model')?->value ?? $attributes->get('name');
    $id = $id ?? 'input-' . md5($fieldName ?? uniqid());
    $hasError = $error || ($fieldName && $errors->has($fieldName));
@endphp

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $id }}" class="block text-xs font-bold text-text mb-1.5">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if ($icon)
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                <i data-lucide="{{ $icon }}" class="w-4 h-4 text-text-tertiary"></i>
            </div>
        @endif

        <input
            id="{{ $id }}"
            type="{{ $type }}"
            {{ $attributes->whereDoesntStartWith('class')->merge([
                'class' => 'w-full bg-municipal-50 border border-border rounded-xl px-3.5 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-semibold transition-all duration-200 focus:bg-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none' .
                    ($icon ? ' ps-10' : '') .
                    ($hasError ? ' border-danger focus:border-danger focus:ring-danger/15' : '')
            ]) }}
        />
    </div>

    @if ($error)
        <p class="mt-1.5 text-xs font-semibold text-danger flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3 h-3 shrink-0"></i>
            {{ $error }}
        </p>
    @elseif ($fieldName && $errors->has($fieldName))
        <p class="mt-1.5 text-xs font-semibold text-danger flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3 h-3 shrink-0"></i>
            {{ $errors->first($fieldName) }}
        </p>
    @endif
</div>
