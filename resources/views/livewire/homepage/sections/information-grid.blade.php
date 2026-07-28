@props([
    'latestNews' => [],
    'waterSchedule' => [],
    'latestProjects' => [],
    'latestJobs' => [],
    'settings' => [],
    'sectionKeys' => [],
    'sectionTitles' => [],
])

@php
    $hasNews = in_array('latest_news', $sectionKeys) && !empty($latestNews);
    $hasProjects = in_array('projects', $sectionKeys) && !empty($latestProjects);
    $hasJobs = !empty($latestJobs);
    $hasWater = !empty($waterSchedule);

    $gridCols = collect([$hasNews, $hasWater, $hasProjects, $hasJobs])->filter(fn($v) => $v)->count();
    if ($gridCols === 0) {
        return;
    }

    $gridClass = match ($gridCols) {
        4 => 'lg:grid-cols-4',
        3 => 'lg:grid-cols-3',
        2 => 'lg:grid-cols-2',
        default => 'lg:grid-cols-1',
    };
@endphp

<section class="py-16 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 {{ $gridClass }} gap-4 sm:gap-5 lg:gap-6">

            {{-- 8.1 Latest News Card --}}
            @if ($hasNews)
                <div class="bg-background rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-border/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-text text-sm sm:text-base">
                            <i data-lucide="newspaper" class="w-4 h-4 inline text-primary ml-1.5"></i>
                            {{ collect($sectionTitles)->firstWhere('key', 'latest_news')['title'] ?? 'آخر الأخبار' }}
                        </h3>
                        @if (Route::has('public.news.index'))
                            <a href="{{ route('public.news.index') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @foreach ($latestNews->take(3) as $news)
                            <div class="group flex gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-pointer">
                                <div class="w-16 h-16 rounded-lg bg-primary-light/50 shrink-0 overflow-hidden flex items-center justify-center">
                                    @if (!empty($news['image_url']))
                                        <img src="{{ $news['image_url'] }}" alt="{{ $news['title'] ?? '' }}" class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <i data-lucide="file-text" class="w-6 h-6 text-primary/40"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] text-text-muted font-medium">{{ isset($news['created_at']) ? \Carbon\Carbon::parse($news['created_at'])->format('Y-m-d') : '' }}</p>
                                    <p class="text-xs font-bold text-text group-hover:text-primary transition-colors line-clamp-2 mt-0.5">{{ $news['title'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (empty($latestNews))
                        <p class="text-xs text-text-secondary text-center py-6">لا توجد أخبار حالياً</p>
                    @endif
                </div>
            @endif

            {{-- 8.2 Water Schedule Card --}}
            @if ($hasWater)
                <div class="bg-background rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-border/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-text text-sm sm:text-base">
                            <i data-lucide="droplet" class="w-4 h-4 inline text-primary ml-1.5"></i>
                            جدول توزيع المياه
                        </h3>
                        @if (Route::has('public.water-schedule'))
                            <a href="{{ route('public.water-schedule') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
                        @endif
                    </div>
                    <div class="space-y-2">
                        @foreach ($waterSchedule as $schedule)
                            @php
                                $areaName = $schedule['area']['name'] ?? 'منطقة غير محددة';
                                $status = $schedule['status'] ?? '';
                                $statusLabel = '';
                                $statusColor = 'bg-gray-100 text-gray-600';
                                if (is_object($status)) {
                                    try { $statusLabel = $status->label(); $statusColor = match($status->value) {
                                        'available' => 'bg-success-light text-success',
                                        'low_pressure' => 'bg-warning-light text-warning',
                                        'maintenance' => 'bg-orange-100 text-orange-600',
                                        'emergency' => 'bg-danger-light text-danger',
                                        'no_water' => 'bg-gray-200 text-gray-600',
                                        default => 'bg-gray-100 text-gray-600',
                                    }; } catch (\Exception $e) { $statusLabel = $status; }
                                } else {
                                    $statusLabel = $status;
                                }
                                $start = $schedule['start_time'] ?? '';
                                $end = $schedule['end_time'] ?? '';
                            @endphp
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-border/40">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-text">{{ $areaName }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if ($start)
                                            <span class="text-[10px] text-text-muted flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                {{ $start }} @if($end) - {{ $end }} @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge {{ $statusColor }} text-[10px] px-2 py-0.5 shrink-0">{{ $statusLabel }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if (empty($waterSchedule))
                        <p class="text-xs text-text-secondary text-center py-6">لا يوجد جدول مياه منشور حالياً</p>
                    @endif
                </div>
            @endif

            {{-- 8.3 Featured Project Card --}}
            @if ($hasProjects)
                <div class="bg-background rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-border/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-text text-sm sm:text-base">
                            <i data-lucide="hard-hat" class="w-4 h-4 inline text-primary ml-1.5"></i>
                            {{ collect($sectionTitles)->firstWhere('key', 'projects')['title'] ?? 'مشاريع مميزة' }}
                        </h3>
                        @if (Route::has('public.projects.index'))
                            <a href="{{ route('public.projects.index') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
                        @endif
                    </div>
                    @foreach ($latestProjects->take(2) as $project)
                        <div class="bg-white rounded-xl border border-border/40 overflow-hidden mb-3 last:mb-0 group hover:shadow-sm transition-shadow">
                            <div class="aspect-[16/9] bg-primary-light/20 overflow-hidden">
                                @if (!empty($project['image_url']))
                                    <img src="{{ $project['image_url'] }}" alt="{{ $project['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="hard-hat" class="w-8 h-8 text-primary/30"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-xs font-bold text-text mb-2 line-clamp-1">{{ $project['title'] ?? '' }}</p>
                                @if (!empty($project['progress']))
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-primary" style="width: {{ min(100, max(0, (int) $project['progress'])) }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-text-muted mt-1">{{ $project['progress'] }}% مكتمل</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if (empty($latestProjects))
                        <p class="text-xs text-text-secondary text-center py-6">لا توجد مشاريع حالياً</p>
                    @endif
                </div>
            @endif

            {{-- 8.4 Jobs Card --}}
            @if ($hasJobs)
                <div class="bg-background rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-border/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-text text-sm sm:text-base">
                            <i data-lucide="briefcase" class="w-4 h-4 inline text-primary ml-1.5"></i>
                            الوظائف الشاغرة
                        </h3>
                        @if (Route::has('public.jobs.index'))
                            <a href="{{ route('public.jobs.index') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
                        @endif
                    </div>
                    <div class="space-y-2">
                        @foreach ($latestJobs as $job)
                            @php
                                $closingAt = $job['closing_at'] ?? null;
                                $isOpen = $closingAt && \Carbon\Carbon::parse($closingAt)->isFuture();
                            @endphp
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-border/40 group hover:border-primary/20 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-text group-hover:text-primary transition-colors">{{ $job['title'] ?? '' }}</p>
                                    @if ($closingAt)
                                        <p class="text-[10px] text-text-muted mt-0.5">آخر موعد: {{ \Carbon\Carbon::parse($closingAt)->format('Y-m-d') }}</p>
                                    @endif
                                </div>
                                <span class="badge {{ ($job['status'] ?? '') === 'published' ? 'badge-success' : 'badge-danger' }} text-[10px] px-2 py-0.5 shrink-0">
                                    {{ ($job['status'] ?? '') === 'published' ? 'مفتوحة' : 'مغلقة' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if (empty($latestJobs))
                        <p class="text-xs text-text-secondary text-center py-6">لا توجد وظائف شاغرة حالياً</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
