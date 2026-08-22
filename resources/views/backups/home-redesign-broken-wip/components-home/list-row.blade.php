@props([
    'icon' => 'file-text',
    'title' => '',
    'url' => '#',
    'meta' => '',
    'status' => '',
    'statusType' => 'success',
    'date' => '',
    'badge' => '',
])

<a href="{{ $url }}" @if($url !== '#') wire:navigate @endif
   class="group flex items-start gap-3 bg-white rounded-xl border border-border p-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 no-underline">
    <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0 group-hover:bg-primary group-hover:text-white transition-all">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 text-primary group-hover:text-white transition-colors"></i>
    </div>
    <div class="min-w-0 flex-1">
        <div class="flex items-start justify-between gap-2">
            <h4 class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">{{ $title }}</h4>
            @if ($status)
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold whitespace-nowrap {{ $statusType === 'success' ? 'bg-success-light text-success' : ($statusType === 'danger' ? 'bg-danger-light text-danger' : 'bg-surface-secondary text-text-muted') }}">
                    {{ $status }}
                </span>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2 mt-1.5">
            @if ($badge)
                <span class="px-2 py-0.5 rounded-md text-[9px] font-semibold bg-surface-secondary text-text-muted">{{ $badge }}</span>
            @endif
            @if ($meta)
                <span class="text-[10px] text-text-muted flex items-center gap-1">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    {{ $meta }}
                </span>
            @endif
            @if ($date)
                <span class="text-[10px] text-text-muted">{{ $date }}</span>
            @endif
        </div>
    </div>
    <i data-lucide="chevron-left" class="w-4 h-4 text-text-muted group-hover:text-primary flex-shrink-0 mt-1"></i>
</a>
