@props([
    'icon' => 'grid-3x3',
    'title' => '',
    'description' => '',
    'url' => '#',
    'processingTime' => null,
    'requiresLogin' => null,
    'categoryName' => '',
    'serviceUrl' => '#',
    'portalUrl' => null,
])

<a href="{{ $url }}" @if($url !== '#') wire:navigate @endif
   class="group bg-white rounded-2xl border border-border/60 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 no-underline block">
    <div class="p-5">
        <div class="flex items-start justify-between gap-2 mb-3">
            <h3 class="font-bold text-text group-hover:text-primary transition-colors line-clamp-1 text-sm">{{ $title }}</h3>
            @if ($categoryName)
                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-light text-primary flex-shrink-0">{{ $categoryName }}</span>
            @endif
        </div>
        @if ($description)
            <p class="text-xs text-text-secondary leading-relaxed line-clamp-2 mb-3">{{ $description }}</p>
        @endif
        <div class="flex items-center gap-2 mb-4">
            @if ($processingTime)
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-text-muted">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    {{ $processingTime }}
                </span>
            @endif
            @if ($requiresLogin)
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-info-light text-info">
                    <i data-lucide="log-in" class="w-2.5 h-2.5"></i>
                    يتطلب تسجيل دخول
                </span>
            @endif
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-border/50">
            <span class="text-xs font-semibold text-primary group-hover:gap-2 transition-all inline-flex items-center gap-1">
                <span>التفاصيل</span>
                <i data-lucide="arrow-left" class="w-3 h-3"></i>
            </span>
            @if ($portalUrl)
                <span class="text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-lg inline-flex items-center gap-1 hover:bg-primary-dark transition-colors">
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                    ابدأ الخدمة
                </span>
            @endif
        </div>
    </div>
</a>
