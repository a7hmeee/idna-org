@props([
    'icon' => 'inbox',
    'title' => 'No data found',
    'description' => '',
    'action' => null,
])

<div class="empty-state">
    <div class="w-16 h-16 rounded-2xl bg-municipal-50 flex items-center justify-center mb-4">
        <i data-lucide="{{ $icon }}" class="w-7 h-7 text-municipal-300"></i>
    </div>
    <h3 class="text-lg font-semibold text-text mb-1">{{ $title }}</h3>
    @if ($description)
        <p class="text-sm text-text-tertiary max-w-sm">{{ $description }}</p>
    @endif
    @if ($action)
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
