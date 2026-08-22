@props([
    'title' => '',
    'url' => '#',
    'type' => '',
    'typeLabel' => '',
    'decisionNumber' => '',
    'sessionNumber' => '',
    'summary' => '',
    'date' => '',
    'hasAttachment' => false,
])

<a href="{{ $url }}" wire:navigate
   class="group flex items-start gap-3 bg-white rounded-xl border border-border p-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 no-underline">
    <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0 group-hover:bg-primary group-hover:text-white transition-all">
        <i data-lucide="file-text" class="w-5 h-5 text-primary group-hover:text-white transition-colors"></i>
    </div>
    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2 mb-0.5">
            @if ($typeLabel)
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-primary-light text-primary">{{ $typeLabel }}</span>
            @endif
            @if ($decisionNumber)
                <span class="text-[10px] text-text-muted">#{{ $decisionNumber }}</span>
            @endif
        </div>
        <h4 class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">{{ $title }}</h4>
        @if ($date)
            <span class="text-[10px] text-text-muted">{{ $date }}</span>
        @endif
        @if ($hasAttachment)
            <span class="text-[10px] text-primary font-semibold mr-2">
                <i data-lucide="file-down" class="w-3 h-3 inline"></i> PDF
            </span>
        @endif
    </div>
    <i data-lucide="chevron-left" class="w-4 h-4 text-text-muted group-hover:text-primary flex-shrink-0 mt-1"></i>
</a>
