@props([
    'service' => null,
    'route' => '#',
    'showCategory' => true,
    'icon' => null,
])

@php
    $svc = $service;
    $iconName = $icon ?? ($svc->category?->icon ?? 'file-text');
@endphp

<a href="{{ $route }}" wire:navigate
   class="group block bg-white rounded-2xl border border-gray-100 p-5 text-decoration-none transition-all duration-300"
   style="box-shadow:0 1px 2px rgba(0,0,0,0.03),0 1px 3px rgba(0,0,0,0.04);"
   onmouseover="this.style.borderColor='rgba(46,125,50,0.2)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06),0 2px 4px rgba(0,0,0,0.04)';this.style.transform='translateY(-2px)'"
   onmouseout="this.style.borderColor='#E5E7EB';this.style.boxShadow='0 1px 2px rgba(0,0,0,0.03),0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
    <div class="flex items-start gap-3">
        <div class="w-11 h-11 rounded-xl bg-primary/5 group-hover:bg-primary flex items-center justify-center flex-shrink-0 transition-all duration-300">
            <i data-lucide="{{ $iconName }}" class="w-5 h-5 text-primary group-hover:text-white transition-colors duration-300"></i>
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="text-sm font-bold text-gray-900 group-hover:text-primary transition-colors mb-1">{{ $svc->name }}</h3>
            @if ($svc->summary)
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-2">{{ $svc->summary }}</p>
            @endif
            <div class="flex items-center gap-2 flex-wrap">
                @if ($showCategory && $svc->category)
                    <span class="text-[10px] font-semibold text-primary bg-primary/5 px-2.5 py-1 rounded-md">{{ $svc->category->name }}</span>
                @endif
                @if ($svc->requires_login)
                    <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md">يتطلب تسجيل دخول</span>
                @endif
                @if ($svc->processing_time)
                    <span class="text-[10px] text-gray-400 inline-flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        {{ $svc->processing_time }}
                    </span>
                @endif
            </div>
        </div>
        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-200 group-hover:text-primary transition-all duration-300 group-hover:-translate-x-0.5 mt-2 flex-shrink-0"></i>
    </div>
</a>
