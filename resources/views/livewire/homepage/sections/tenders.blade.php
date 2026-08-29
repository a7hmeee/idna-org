@php
    $tenders = collect($latestTenders ?? []);
@endphp

@if ($tenders->isNotEmpty())
<section id="tenders" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold" style="background: #FEF3C7; color: #D97706;">
                    <i data-lucide="scroll-text" class="w-3.5 h-3.5"></i>
                    المناقصات
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-[38px] font-black text-text leading-tight">{{ $sectionTitle ?? 'المناقصات والعطاءات' }}</h2>
                @if ($sectionSubtitle)
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed">{{ $sectionSubtitle }}</p>
                @endif
            </div>
            @if (Route::has('public.tenders.index'))
                <a href="{{ route('public.tenders.index') }}" wire:navigate class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-dark transition-colors no-underline shrink-0">
                    <span>جميع المناقصات</span>
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            @endif
        </div>

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
