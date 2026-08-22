<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'projects',
        'fallbackTitle' => "مشاريع البلدية",
        'fallbackDescription' => "استعرض جميع مشاريع بلدية إذنا، وتابع نسب الإنجاز والتفاصيل الكاملة لكل مشروع.",
        'fallbackBadge' => 'المشاريع',
        'fallbackIcon' => 'hard-hat',
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. PROJECTS LIST --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search + Filters --}}
            <div class="flex flex-col gap-4 mb-7">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <select wire:model.live="category" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">جميع التصنيفات</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->value }}">{{ $c->label() }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="projectStatus" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">جميع الحالات</option>
                            @foreach ($projectStatuses as $ps)
                                <option value="{{ $ps->value }}">{{ $ps->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative w-full max-w-xs">
                        <i data-lucide="search" class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary pointer-events-none"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               placeholder="ابحث عن مشروع..."
                               class="w-full bg-white border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                </div>
            </div>

            {{-- Results Count --}}
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-text-tertiary">
                    يوجد <span class="font-bold text-text">{{ $projects->total() ?? 0 }}</span> مشروع
                </p>
            </div>

            {{-- Projects Grid --}}
            @if ($projects->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="hard-hat" class="w-8 h-8 text-text-tertiary"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text mb-2">لا توجد مشاريع حالياً</h3>
                    <p class="text-sm text-text-tertiary">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($projects as $project)
                        <a href="{{ route('public.projects.show', $project->slug) }}" wire:navigate
                           class="group block bg-white rounded-2xl border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-lg hover:border-primary/20"
                           style="text-decoration:none;">
                            {{-- Cover Image --}}
                            <div class="relative h-44 bg-surface-secondary overflow-hidden">
                                @if ($project->cover_image_url)
                                    <img src="{{ $project->cover_image_url }}" alt="{{ $project->name_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="image" class="w-10 h-10 text-text-tertiary/40"></i>
                                    </div>
                                @endif
                                {{-- Category Badge --}}
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-white/90 text-xs font-semibold text-text shadow-sm">
                                    {{ $project->category?->label() }}
                                </span>
                                @if ($project->is_featured)
                                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-yellow-100/90 text-xs font-semibold text-yellow-700 shadow-sm inline-flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3"></i>
                                        مميز
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-4">
                                <h3 class="font-bold text-text group-hover:text-primary transition-colors mb-2">{{ $project->name_ar }}</h3>
                                @if ($project->summary)
                                    <p class="text-sm text-text-secondary line-clamp-2 mb-3">{{ $project->summary }}</p>
                                @endif

                                {{-- Progress Bar --}}
                                @if ($project->project_status->value === 'in_progress' || $project->implementation_percentage > 0)
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-text-tertiary">نسبة الإنجاز</span>
                                            <span class="font-semibold" dir="ltr">{{ $project->implementation_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-700
                                                @if($project->implementation_percentage >= 100) bg-success
                                                @elseif($project->implementation_percentage >= 50) bg-primary
                                                @else bg-warning @endif"
                                                style="width: {{ $project->implementation_percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Meta --}}
                                <div class="flex items-center gap-3 text-xs text-text-tertiary flex-wrap">
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="folder" class="w-3 h-3"></i>
                                        {{ $project->category?->label() }}
                                    </span>
                                    @if ($project->location)
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            {{ $project->location }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        {{ number_format($project->views_count) }}
                                    </span>
                                </div>

                                {{-- Status Badge --}}
                                <div class="mt-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                        @if($project->project_status->value === 'completed') bg-success/10 text-success
                                        @elseif($project->project_status->value === 'in_progress') bg-warning/10 text-warning
                                        @elseif($project->project_status->value === 'suspended') bg-danger/10 text-danger
                                        @else bg-info/10 text-info @endif">
                                        {{ $project->project_status->label() }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($projects->hasPages())
                <div class="mt-10">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>

</div>
