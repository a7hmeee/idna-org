@props([
    'value' => '0',
    'label' => '',
    'suffix' => '',
    'icon' => '',
    'description' => '',
])

<div class="text-center p-5 sm:p-6 rounded-2xl transition-all duration-300 group" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
    @if ($icon)
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors" style="background:rgba(23,107,50,0.3);">
            <i data-lucide="{{ $icon }}" class="w-6 h-6" style="color:#A5D6A7;"></i>
        </div>
    @endif
    <p class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-none">
        {{ number_format((int) $value) }}
        @if ($suffix)
            <span class="text-sm sm:text-base font-bold mr-0.5" style="color:#C8A85A;">{{ $suffix }}</span>
        @endif
    </p>
    <p class="text-xs sm:text-sm font-medium mt-1.5" style="color:#A5D6A7;">{{ $label }}</p>
    @if ($description)
        <p class="text-[10px] mt-1 opacity-60" style="color:#A5D6A7;">{{ $description }}</p>
    @endif
</div>
