@props([
    'title' => '',
    'subtitle' => '',
    'actionText' => null,
    'actionUrl' => '#',
])

<div class="bg-white rounded-2xl border border-[#E6EEE5] p-5 {{ $attributes->get('class') }}">
    @if ($title || $subtitle)
    <div class="flex items-center justify-between mb-4">
        <div>
            @if ($title)
            <h3 class="text-sm font-bold text-[#1A2E15]">{{ $title }}</h3>
            @endif
            @if ($subtitle)
            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if ($actionText)
        <button class="text-xs font-bold text-[#2E6F1F] hover:text-[#235818] transition-colors">{{ $actionText }}</button>
        @endif
    </div>
    @endif
    {{ $slot }}
</div>
