@php
    $projects = collect($latestProjects ?? []);
@endphp

@if ($projects->isNotEmpty())
<section data-reveal id="projects" class="section-py" style="background: #f8fafc;">
    <div class="container-home">
        <x-home.section-head
            eyebrow="مشاريع البلدية"
            eyebrowIcon="folder-kanban"
            :title="$sectionTitle ?? 'مشاريعنا'"
            :subtitle="$sectionSubtitle ?? null"
            :actionUrl="Route::has('public.projects.index') ? route('public.projects.index') : null"
            actionLabel="جميع المشاريع"
        />

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($projects as $project)
                <a href="{{ $project['url'] ?? '#' }}" class="block group bg-white rounded-2xl border border-border/60 overflow-hidden hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline shadow-card">
                    @if (!empty($project['image']))
                        <div class="aspect-[16/10] overflow-hidden">
                            <img src="{{ $project['image'] }}" alt="{{ $project['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        </div>
                    @else
                        <div class="aspect-[16/10] flex items-center justify-center" style="background: #EAF5EE;">
                            <i data-lucide="folder-kanban" class="w-12 h-12" style="color: #A0C97E;"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            @if (!empty($project['status']))
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full" style="background: #EAF5EE; color: #176B32;">{{ $project['status'] }}</span>
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
                                    <span style="color: #176B32;">{{ $project['progress'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full" style="background: #E3EAE4;">
                                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $project['progress'] }}%; background: linear-gradient(90deg, #176B32, #4C9A63);"></div>
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
