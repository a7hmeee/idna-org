@props([
    'complaints' => [],
    'activities' => [],
    'departments' => [],
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Latest Complaints --}}
    <div class="bg-surface rounded-2xl border border-border p-5 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-text">آخر الشكاوى</h3>
                <p class="text-xs text-text-tertiary font-medium mt-0.5">غير المقروءة</p>
            </div>
            <a href="{{ route('dashboard.complaints') }}" wire:navigate class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
        </div>
        <div class="space-y-3">
            @forelse ($complaints as $complaint)
            <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-surface-hover transition-colors cursor-pointer">
                <div class="w-2 h-2 rounded-full shrink-0 mt-1.5" style="background: {{ $complaint['dotColor'] ?? '#EF4444' }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-text truncate">{{ $complaint['title'] }}</p>
                    <p class="text-[10px] text-text-tertiary font-medium">{{ $complaint['time'] }}</p>
                </div>
                <x-ui.badge :variant="$complaint['badgeVariant'] ?? 'danger'" class="shrink-0">{{ $complaint['badge'] }}</x-ui.badge>
            </div>
            @empty
            <div class="flex flex-col items-center text-center py-8">
                <div class="w-14 h-14 rounded-2xl bg-municipal-50 flex items-center justify-center mb-3">
                    <i data-lucide="message-square-warning" class="w-6 h-6 text-municipal-300"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد شكاوى</p>
                <p class="text-xs text-text-tertiary mt-1">جميع الشكاوى تمت معالجتها</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Activity Timeline --}}
    <div class="bg-surface rounded-2xl border border-border p-5 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-text">آخر النشاطات</h3>
                <p class="text-xs text-text-tertiary font-medium mt-0.5">اليوم</p>
            </div>
            <a href="{{ route('dashboard.municipality') }}" wire:navigate class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
        </div>
        <div class="space-y-0">
            @forelse ($activities as $activity)
            <div class="timeline-item">
                <div class="timeline-dot"><div class="w-2 h-2 rounded-full" style="background: {{ $activity['dotColor'] ?? 'var(--color-primary)' }}"></div></div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: {{ $activity['iconBg'] ?? 'var(--color-municipal-50)' }}">
                        <i data-lucide="{{ $activity['icon'] ?? 'activity' }}" class="w-4 h-4" style="color: {{ $activity['iconColor'] ?? 'var(--color-primary)' }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-text">{{ $activity['title'] }}</p>
                        <p class="text-[10px] text-text-tertiary font-medium">{{ $activity['description'] }}</p>
                        <p class="text-[9px] text-text-muted font-medium mt-0.5">{{ $activity['time'] }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center text-center py-8">
                <div class="w-14 h-14 rounded-2xl bg-municipal-50 flex items-center justify-center mb-3">
                    <i data-lucide="activity" class="w-6 h-6 text-municipal-300"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد نشاطات</p>
                <p class="text-xs text-text-tertiary mt-1">سيتم تسجيل النشاطات هنا عند حدوثها</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Departments Overview --}}
    <div class="bg-surface rounded-2xl border border-border p-5 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-text">الأقسام</h3>
                <p class="text-xs text-text-tertiary font-medium mt-0.5">نظرة عامة</p>
            </div>
            <a href="{{ route('dashboard.departments') }}" wire:navigate class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
        </div>
        <div class="space-y-4">
            @forelse ($departments as $dept)
            <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-surface-hover transition-colors cursor-pointer">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background: {{ $dept['iconBg'] ?? 'var(--color-municipal-50)' }}">
                    <i data-lucide="{{ $dept['icon'] ?? 'building-2' }}" class="w-[18px] h-[18px]" style="color: {{ $dept['iconColor'] ?? 'var(--color-primary)' }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-bold text-text">{{ $dept['name'] }}</p>
                        <span class="text-[10px] text-text-tertiary font-semibold">{{ $dept['staff'] }}</span>
                    </div>
                    <div class="progress-bar mb-1"><div class="progress-fill" style="width: {{ $dept['progress'] }}%; background: {{ $dept['progressColor'] ?? 'var(--color-primary)' }}"></div></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold" style="color: {{ $dept['progressColor'] ?? 'var(--color-success)' }}">{{ $dept['progress'] }}% إنجاز</span>
                        <span class="text-[9px] text-text-muted font-medium">{{ $dept['status'] }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center text-center py-8">
                <div class="w-14 h-14 rounded-2xl bg-municipal-50 flex items-center justify-center mb-3">
                    <i data-lucide="building-2" class="w-6 h-6 text-municipal-300"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد أقسام مضافة</p>
                <p class="text-xs text-text-tertiary mt-1">ستظهر الأقسام هنا بعد إضافتها</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
