@php
    $projects = collect($latestProjects ?? []);
@endphp

@if ($projects->isNotEmpty())
<section id="projects" class="section-py" style="background: #f8fafc;">
    <div class="container-home">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold" style="background: #E8F0FE; color: #2563EB;">
                    <i data-lucide="folder-kanban" class="w-3.5 h-3.5"></i>
                    مشاريع البلدية
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight">{{ $sectionTitle ?? 'مشاريعنا' }}</h2>
                @if ($sectionSubtitle)
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed">{{ $sectionSubtitle }}</p>
                @endif
            </div>
            @if (Route::has('public.projects.index'))
                <a href="{{ route('public.projects.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-dark transition-colors no-underline shrink-0">
                    <span>جميع المشاريع</span>
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            @endif
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($projects as $project)
                <a href="{{ $project['url'] ?? '#' }}" class="block group bg-white rounded-2xl border border-border/60 overflow-hidden hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline shadow-card">
                    @if (!empty($project['image']))
                        <div class="aspect-[16/10] overflow-hidden">
                            <img src="{{ $project['image'] }}" alt="{{ $project['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        </div>
                    @else
                        <div class="aspect-[16/10] flex items-center justify-center" style="background: #E8F0FE;">
                            <i data-lucide="folder-kanban" class="w-12 h-12" style="color: #93C5FD;"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            @if (!empty($project['status']))
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full" style="background: #E8F0FE; color: #2563EB;">{{ $project['status'] }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-text group-hover:text-primary transition-colors leading-snug">{{ $project['title'] ?? '' }}</h3>
                        @if (!empty($project['summary']))
                            <p class="text-sm text-text-secondary mt-2 line-clamp-2">{{ $project['summary'] }}</p>
                        @endif
                        @if (isset($project['progress']))
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                    <span class="text-text-secondary">نسبة الإنجاز</span>
                                    <span style="color: #2563EB;">{{ $project['progress'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full" style="background: #E2E8F0;">
                                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $project['progress'] }}%; background: linear-gradient(90deg, #2563EB, #3B82F6);"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
