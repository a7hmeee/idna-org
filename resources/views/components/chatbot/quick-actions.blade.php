<div class="flex flex-wrap gap-2 mt-3" dir="rtl">
    @foreach ($actions as $action)
        @if (!empty($action['url']))
            <a href="{{ $action['url'] }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary text-white text-xs rounded-xl hover:bg-primary-dark transition-colors no-underline font-medium shadow-sm">
                {{ e($action['label']) }}
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            </a>
        @else
            @php
                $actionKey = $action['key'] ?? ($action['value'] ?? '');
                $actionLabel = $action['label'] ?? $actionKey;
                $wireValue = $action['value'] ?? $actionKey;
            @endphp
            <button type="button"
                    wire:click="quickAction('{{ e($wireValue) }}', '{{ e($actionLabel) }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary-light text-primary text-xs rounded-xl hover:bg-primary hover:text-white border border-primary/20 hover:border-primary transition-all cursor-pointer border-none font-medium">
                {{ e($actionLabel) }}
            </button>
        @endif
    @endforeach
</div>
