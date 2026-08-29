@php
    $stats = [
        ['label' => 'شرائح البنر النشطة', 'value' => $slidesCount, 'total' => $totalSlides, 'icon' => 'images', 'color' => 'primary'],
        ['label' => 'الروابط السريعة النشطة', 'value' => $quickLinksCount, 'total' => $totalQuickLinks, 'icon' => 'link', 'color' => 'success'],
        ['label' => 'الإحصائيات النشطة', 'value' => $statisticsCount, 'total' => $totalStatistics, 'icon' => 'bar-chart-3', 'color' => 'warning'],
        ['label' => 'الأقسام الممكّنة', 'value' => $enabledSectionsCount, 'total' => $totalSections, 'icon' => 'layers', 'color' => 'info'],
    ];
@endphp

<div>
    <x-slot name="title">لوحة الصفحة الرئيسية</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">لوحة الصفحة الرئيسية</h1>
            <p class="text-sm text-text-tertiary mt-1">نظرة عامة على إعدادات الصفحة الرئيسية</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2">
            <i data-lucide="external-link" class="w-4 h-4"></i>
            <span>عرض الصفحة</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ($stats as $stat)
            <div class="bg-surface rounded-xl border border-border p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}/10 flex items-center justify-center">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}"></i>
                    </div>
                    <span class="text-xs text-text-tertiary">من أصل {{ $stat['total'] }}</span>
                </div>
                <p class="text-2xl font-bold text-text">{{ $stat['value'] }}</p>
                <p class="text-sm text-text-tertiary mt-1">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="font-semibold text-text mb-4">روابط سريعة</h3>
            <div class="space-y-3">
                <a href="{{ route('dashboard.homepage.settings') }}" wire:navigate class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">إعدادات الصفحة</p>
                        <p class="text-xs text-text-tertiary">تعديل عنوان الموقع، رابط البورتال، رسالة رئيس البلدية</p>
                    </div>
                </a>
                <a href="{{ route('dashboard.homepage.slides') }}" wire:navigate class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="images" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">شرائح البنر</p>
                        <p class="text-xs text-text-tertiary">إدارة الشرائح المعروضة في الواجهة الرئيسية</p>
                    </div>
                </a>
                <a href="{{ route('dashboard.homepage.sections') }}" wire:navigate class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="layers" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">أقسام الصفحة</p>
                        <p class="text-xs text-text-tertiary">إظهار، إخفاء، وترتيب أقسام الصفحة الرئيسية</p>
                    </div>
                </a>
                <a href="{{ route('dashboard.homepage.quick-links') }}" wire:navigate class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="link" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">الروابط السريعة</p>
                        <p class="text-xs text-text-tertiary">إدارة روابط الوصول السريع في الصفحة الرئيسية</p>
                    </div>
                </a>
                <a href="{{ route('dashboard.homepage.statistics') }}" wire:navigate class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">الإحصائيات</p>
                        <p class="text-xs text-text-tertiary">إدارة الأرقام والإحصائيات المعروضة</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="font-semibold text-text mb-4">معاينة سريعة</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-text-tertiary mb-1">عنوان الموقع</p>
                    <p class="text-sm font-semibold text-text">{{ $settings->site_title ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-text-tertiary mb-1">رابط البورتال</p>
                    <p class="text-sm text-primary truncate">{{ $settings->portal_url ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-text-tertiary mb-1">عنوان الترحيب</p>
                    <p class="text-sm text-text">{{ $settings->welcome_title ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-text-tertiary mb-1">آخر تحديث</p>
                    <p class="text-sm text-text">{{ $settings->updated_at?->diffForHumans() ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
