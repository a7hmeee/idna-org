@php
    $tenders = collect($latestTenders ?? []);
@endphp

@if ($tenders->isNotEmpty())
<section data-reveal id="tenders" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        <x-home.section-head
            eyebrow="المناقصات والعطاءات"
            eyebrowIcon="scroll-text"
            :title="$sectionTitle ?? 'المناقصات والعطاءات'"
            :subtitle="$sectionSubtitle ?? null"
            :actionUrl="Route::has('public.tenders.index') ? route('public.tenders.index') : null"
            actionLabel="جميع المناقصات"
        />

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($tenders as $tender)
                <a href="{{ $tender['url'] ?? '#' }}" class="block group bg-white rounded-2xl border border-border/60 p-5 hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline shadow-card">
                    @if (!empty($tender['tender_number']))
                        <span class="text-[11px] font-mono font-bold" style="color: #9CA3AF;">#{{ $tender['tender_number'] }}</span>
                    @endif
                    <h3 class="font-bold text-text group-hover:text-primary transition-colors leading-snug mt-1">{{ $tender['title'] ?? '' }}</h3>
                    @if (!empty($tender['summary']))
                        <p class="text-sm text-text-secondary mt-2 line-clamp-2">{{ $tender['summary'] }}</p>
                    @endif
                    <div class="mt-4 pt-4 border-t border-border/60 flex items-center justify-between">
                        @if (!empty($tender['deadline']))
                            <span class="text-xs font-semibold" style="color: #DC2626;">
                                <i data-lucide="clock" class="w-3 h-3 inline-block ml-1"></i>
                                {{ $tender['deadline'] }}
                            </span>
                        @endif
                        @if (!empty($tender['budget']))
                            <span class="text-xs font-bold text-text-secondary">{{ number_format($tender['budget']) }} ₪</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
