<div>
    <x-slot name="title">إحصائيات الخدمات</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إحصائيات الخدمات الإلكترونية</h1>
            <p class="text-sm text-text-tertiary mt-1">نظرة عامة على أداء الخدمات و interaction المواطنين</p>
        </div>
        <a href="{{ route('dashboard.electronic-services.services') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            العودة للخدمات
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-surface rounded-xl border border-border p-4">
            <p class="text-xs text-text-tertiary">إجمالي الخدمات</p>
            <p class="text-2xl font-bold text-text mt-1">{{ number_format($stats['total_services']) }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-4">
            <p class="text-xs text-text-tertiary">الخدمات النشطة</p>
            <p class="text-2xl font-bold text-success mt-1">{{ number_format($stats['active_services']) }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-4">
            <p class="text-xs text-text-tertiary">إجمالي المشاهدات</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ number_format($stats['total_views']) }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-4">
            <p class="text-xs text-text-tertiary">نسبة التحويل</p>
            <p class="text-2xl font-bold text-info mt-1">{{ $stats['conversion_rate'] }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="eye" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">الأكثر مشاهدة</h2>
            </div>
            @if ($topViewed->isEmpty())
                <p class="text-sm text-text-tertiary py-8 text-center">لا توجد بيانات كافية</p>
            @else
                <div class="space-y-3">
                    @foreach ($topViewed as $index => $service)
                    <div class="flex items-center justify-between py-2 border-b border-border/50 last:border-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-xs font-bold text-text-tertiary w-5">{{ $index + 1 }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-text truncate">{{ $service->name }}</p>
                                <p class="text-xs text-text-tertiary">{{ $service->category?->name ?? '—' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-text shrink-0">{{ number_format($service->views_count) }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">الأكثر نقرة على البوابة</h2>
            </div>
            @if ($topClicked->isEmpty())
                <p class="text-sm text-text-tertiary py-8 text-center">لا توجد بيانات كافية</p>
            @else
                <div class="space-y-3">
                    @foreach ($topClicked as $index => $service)
                    <div class="flex items-center justify-between py-2 border-b border-border/50 last:border-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-xs font-bold text-text-tertiary w-5">{{ $index + 1 }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-text truncate">{{ $service->name }}</p>
                                <p class="text-xs text-text-tertiary">{{ $service->category?->name ?? '—' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-text shrink-0">{{ number_format($service->portal_clicks_count) }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
