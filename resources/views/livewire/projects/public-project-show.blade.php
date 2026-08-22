<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'projects',
        'pageTitle' => $project->name_ar,
        'pageSubtitle' => $project->summary ?? null,
        'pageBadge' => 'مشروع',
        'pageBadgeIcon' => 'hard-hat',
        'compact' => true,
    ])

    {{-- Info badges below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div class="flex flex-wrap items-center gap-2 py-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                @if($project->project_status->value === 'completed') bg-success/10 text-success
                @elseif($project->project_status->value === 'in_progress') bg-warning/10 text-warning
                @elseif($project->project_status->value === 'suspended') bg-danger/10 text-danger
                @else bg-info/10 text-info @endif">
                {{ $project->project_status->label() }}
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-primary/10 text-primary">
                <i data-lucide="folder" class="w-3 h-3"></i>
                {{ $project->category?->label() }}
            </span>
            @if ($project->location)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-surface-secondary text-text-secondary">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    {{ $project->location }}
                </span>
            @endif
            @if ($project->is_featured)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-700">
                    <i data-lucide="star" class="w-3 h-3"></i>
                    مميز
                </span>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. PROJECT DETAIL --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Cover Image --}}
                    @if ($project->cover_image_url)
                        <div class="rounded-2xl overflow-hidden border border-gray-200">
                            <img src="{{ $project->cover_image_url }}" alt="{{ $project->name_ar }}" class="w-full h-64 sm:h-80 object-cover" />
                        </div>
                    @endif

                    {{-- Summary --}}
                    @if ($project->summary)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">نبذة عن المشروع</h2>
                            <p class="text-text-secondary leading-relaxed">{{ $project->summary }}</p>
                        </div>
                    @endif

                    {{-- Description --}}
                    @if ($project->description)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">الوصف</h2>
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line">{{ $project->description }}</div>
                        </div>
                    @endif

                    {{-- Progress --}}
                    @if ($project->project_status->value === 'in_progress' || $project->implementation_percentage > 0)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">نسبة الإنجاز</h2>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000
                                            @if($project->implementation_percentage >= 100) bg-success
                                            @elseif($project->implementation_percentage >= 50) bg-primary
                                            @else bg-warning @endif"
                                            style="width: {{ $project->implementation_percentage }}%">
                                        </div>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold text-primary" dir="ltr">{{ $project->implementation_percentage }}%</span>
                            </div>
                        </div>
                    @endif

                    {{-- Gallery --}}
                    @if ($project->gallery_urls)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">معرض الصور</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($project->gallery_urls as $url)
                                    <a href="{{ $url }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-100 hover:opacity-90 transition-opacity">
                                        <img src="{{ $url }}" class="w-full h-32 object-cover" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Contractor & Funding --}}
                    @if ($project->contractor || $project->funding_entity)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">الأطراف المعنية</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if ($project->contractor)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                            <i data-lucide="building-2" class="w-5 h-5 text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-text-tertiary">الجهة المنفذة</p>
                                            <p class="text-sm font-semibold text-text">{{ $project->contractor }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($project->funding_entity)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                            <i data-lucide="banknote" class="w-5 h-5 text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-text-tertiary">الجهة الممولة</p>
                                            <p class="text-sm font-semibold text-text">{{ $project->funding_entity }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Related Projects --}}
                    @if ($relatedProjects->isNotEmpty())
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">مشاريع أخرى</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($relatedProjects as $related)
                                    <a href="{{ route('public.projects.show', $related->slug) }}" wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors no-underline group">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-surface-secondary">
                                            @if ($related->cover_image_url)
                                                <img src="{{ $related->cover_image_url }}" class="w-full h-full object-cover" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i data-lucide="hard-hat" class="w-5 h-5 text-text-tertiary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">{{ $related->name_ar }}</h3>
                                            <p class="text-xs text-text-tertiary">{{ $related->category?->label() }} · {{ $related->project_status->label() }}</p>
                                        </div>
                                        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-300 group-hover:text-primary transition-colors shrink-0"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-4">

                    {{-- Quick Info --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3">معلومات المشروع</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">التصنيف</span>
                                <span class="text-text font-semibold">{{ $project->category?->label() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">حالة المشروع</span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-xs font-semibold
                                    @if($project->project_status->value === 'completed') bg-success/10 text-success
                                    @elseif($project->project_status->value === 'in_progress') bg-warning/10 text-warning
                                    @elseif($project->project_status->value === 'suspended') bg-danger/10 text-danger
                                    @else bg-info/10 text-info @endif">
                                    {{ $project->project_status->label() }}
                                </span>
                            </div>
                            @if ($project->location)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">الموقع</span>
                                    <span class="text-text font-semibold">{{ $project->location }}</span>
                                </div>
                            @endif
                            @if ($project->budget)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">الميزانية</span>
                                    <span class="text-text font-semibold" dir="ltr">{{ number_format($project->budget, 2) }} {{ $project->budget_currency }}</span>
                                </div>
                            @endif
                            @if ($project->start_date)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">تاريخ البداية</span>
                                    <span class="text-text font-semibold">{{ $project->start_date->format('Y/m/d') }}</span>
                                </div>
                            @endif
                            @if ($project->expected_completion_date)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">تاريخ الانتهاء المتوقع</span>
                                    <span class="text-text font-semibold">{{ $project->expected_completion_date->format('Y/m/d') }}</span>
                                </div>
                            @endif
                            @if ($project->actual_completion_date)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">تاريخ الانتهاء الفعلي</span>
                                    <span class="text-text font-semibold">{{ $project->actual_completion_date->format('Y/m/d') }}</span>
                                </div>
                            @endif
                            @if ($project->contractor)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">المنفذ</span>
                                    <span class="text-text font-semibold">{{ $project->contractor }}</span>
                                </div>
                            @endif
                            @if ($project->funding_entity)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">الممول</span>
                                    <span class="text-text font-semibold">{{ $project->funding_entity }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Progress in Sidebar --}}
                    @if ($project->implementation_percentage > 0)
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="font-bold text-text mb-3">نسبة الإنجاز</h3>
                            <div class="text-center">
                                <div class="relative w-24 h-24 mx-auto mb-3">
                                    <svg class="w-24 h-24 -rotate-90" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="#F3F4F6" stroke-width="8" />
                                        <circle cx="60" cy="60" r="54" fill="none"
                                            stroke="{{ $project->implementation_percentage >= 100 ? '#10B981' : ($project->implementation_percentage >= 50 ? '#0F6A3D' : '#F59E0B') }}"
                                            stroke-width="8"
                                            stroke-dasharray="339.292"
                                            stroke-dashoffset="{{ 339.292 - (339.292 * $project->implementation_percentage / 100) }}"
                                            stroke-linecap="round" />
                                    </svg>
                                    <span class="absolute inset-0 flex items-center justify-center text-lg font-bold text-text" dir="ltr">{{ $project->implementation_percentage }}%</span>
                                </div>
                                <p class="text-xs text-text-tertiary">
                                    @if($project->project_status->value === 'completed')
                                        تم إنجاز المشروع
                                    @elseif($project->project_status->value === 'in_progress')
                                        المشروع قيد التنفيذ
                                    @elseif($project->project_status->value === 'suspended')
                                        المشروع معلق
                                    @else
                                        المشروع قيد التخطيط
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Views --}}
                    <div class="text-center text-xs text-text-tertiary">
                        <i data-lucide="eye" class="w-3 h-3 inline"></i>
                        {{ number_format($project->views_count) }} مشاهدة
                    </div>

                    {{-- Quick Links --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3 text-sm">روابط سريعة</h3>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('public.projects.index') }}" wire:navigate
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-gray-50 hover:text-text transition-colors no-underline">
                                <i data-lucide="hard-hat" class="w-4 h-4"></i>
                                <span>جميع المشاريع</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
