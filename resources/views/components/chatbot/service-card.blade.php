<div class="bg-surface border border-border rounded-xl p-3 hover:border-primary/40 hover:shadow-md transition-all cursor-pointer"
     @if(!empty($item['url']))
     onclick="window.open('{{ $item['url'] }}', '_blank')"
     @endif
>
    <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center shrink-0">
            <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-text truncate">{{ e($item['name'] ?? $item['title'] ?? 'خدمة') }}</p>
            @if (!empty($item['category']))
                <span class="badge badge-neutral">{{ e($item['category']) }}</span>
            @endif
            @if (!empty($item['summary']))
                <p class="text-xs text-text-secondary mt-1 line-clamp-2">{{ e($item['summary']) }}</p>
            @endif
            @if (!empty($item['fee']))
                <p class="text-xs text-text-tertiary mt-1">{{ e($item['fee']) }}</p>
            @endif
        </div>
    </div>
    <div class="flex gap-2 mt-3">
        <button type="button"
                wire:click="quickAction('معلومات عن {{ e($item['name'] ?? $item['title'] ?? 'هذه الخدمة']) }}')"
                class="text-[11px] px-3 py-1.5 bg-primary-light text-primary rounded-lg hover:bg-primary hover:text-white transition-all cursor-pointer border-none font-medium">
            نظرة عامة
        </button>
        @if (!empty($item['online_application_url']))
            <a href="{{ $item['online_application_url'] }}" target="_blank" rel="noopener noreferrer"
               class="text-[11px] px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-all no-underline font-medium">
                ابدأ التقديم
                <i data-lucide="external-link" class="w-3 h-3 inline"></i>
            </a>
        @endif
    </div>
</div>
