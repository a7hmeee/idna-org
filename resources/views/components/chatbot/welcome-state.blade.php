<div class="flex justify-start gap-2" dir="rtl">
    <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center shrink-0 mt-1">
        <i data-lucide="bot" class="w-4 h-4 text-primary"></i>
    </div>
    <div class="max-w-[85%] flex flex-col items-start gap-1">
        <div class="bg-surface border border-border rounded-2xl rounded-br-sm px-4 py-3 text-sm leading-relaxed text-text shadow-sm">
            <p class="whitespace-pre-line">مرحباً بك في المساعد الذكي لبلدية إذنا\n\nيمكنني مساعدتك في الوصول إلى الخدمات والمعلومات البلدية، وتقديم الشكاوى والطلبات ومتابعتها.\n\nيمكنك كتابة سؤالك مباشرة أو اختيار أحد الخيارات السريعة أدناه.</p>
                @if (!empty($actions) && count($actions) > 0)
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach ($actions as $action)
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
                        @endforeach
                    </div>
                @endif
        </div>
    </div>
</div>
