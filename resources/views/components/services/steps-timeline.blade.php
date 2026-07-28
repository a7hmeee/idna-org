@props([
    'steps' => [],
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6">
    @foreach ($steps as $index => $step)
        @php
            $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $title = is_string($step) ? $step : ($step['title'] ?? '');
            $desc = is_string($step) ? '' : ($step['description'] ?? '');
            $icon = is_string($step) ? 'check' : ($step['icon'] ?? 'check');
        @endphp
        <div class="relative text-center">
            {{-- Connector line --}}
            @if (!$loop->last)
                <div class="hidden lg:block absolute top-10 right-[calc(50%+40px)] left-0 h-0.5 bg-primary/10 -z-10" style="width:calc(100% - 80px);right:calc(50% + 40px);"></div>
            @endif
            {{-- Icon --}}
            <div class="w-20 h-20 rounded-2xl bg-primary/5 flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-all duration-300">
                <i data-lucide="{{ $icon }}" class="w-9 h-9 text-primary"></i>
            </div>
            {{-- Number --}}
            <div class="w-10 h-10 rounded-full bg-primary text-white text-sm font-black flex items-center justify-center mx-auto mb-3 shadow-md">
                {{ $num }}
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1.5">{{ $title }}</h3>
            @if ($desc)
                <p class="text-xs text-gray-500 leading-relaxed max-w-[200px] mx-auto">{{ $desc }}</p>
            @endif
        </div>
    @endforeach
</div>
