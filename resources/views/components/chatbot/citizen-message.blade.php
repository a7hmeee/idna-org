<div class="flex justify-end" dir="rtl">
    <div class="max-w-[85%] flex flex-col items-end gap-1">
        <div class="bg-primary text-white rounded-2xl rounded-bl-sm px-4 py-2.5 text-sm leading-relaxed shadow-sm">
            <p class="whitespace-pre-line">{{ e($content) }}</p>
        </div>
        @if (!empty($time))
            <span class="text-[10px] text-text-tertiary px-1">{{ $time }}</span>
        @endif
    </div>
</div>
