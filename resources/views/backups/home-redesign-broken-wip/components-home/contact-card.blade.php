@props([
    'icon' => 'phone',
    'title' => '',
    'value' => '',
    'url' => null,
    'label' => '',
])

@if ($url)
    <a href="{{ $url }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/10 transition-all">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(200,168,90,0.15);">
            <i data-lucide="{{ $icon }}" class="w-4 h-4" style="color:#C8A85A;"></i>
        </div>
        <div class="min-w-0">
            @if ($label)
                <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.4);">{{ $label }}</p>
            @endif
            <p class="text-sm font-bold text-white">{{ $value }}</p>
        </div>
    </a>
@else
    <div class="flex items-center gap-3 p-3 rounded-xl">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(200,168,90,0.15);">
            <i data-lucide="{{ $icon }}" class="w-4 h-4" style="color:#C8A85A;"></i>
        </div>
        <div class="min-w-0">
            @if ($label)
                <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.4);">{{ $label }}</p>
            @endif
            <p class="text-sm font-bold text-white">{{ $value }}</p>
        </div>
    </div>
@endif
