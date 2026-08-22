<div class="flex items-center gap-2 mt-3" dir="rtl">
    <p class="text-xs text-text-tertiary">هل كانت هذه الإجابة مفيدة؟</p>
    <div class="flex items-center gap-1">
        <button type="button"
                wire:click="submitFeedback(5, null, {{ $messageId }})"
                class="w-8 h-8 rounded-lg bg-surface border border-border hover:border-success hover:bg-success-light flex items-center justify-center transition-all cursor-pointer"
                aria-label="نعم">
            <i data-lucide="thumbs-up" class="w-4 h-4 text-text-secondary hover:text-success"></i>
        </button>
        <button type="button"
                wire:click="submitFeedback(1, null, {{ $messageId }})"
                class="w-8 h-8 rounded-lg bg-surface border border-border hover:border-danger hover:bg-danger-light flex items-center justify-center transition-all cursor-pointer"
                aria-label="لا">
            <i data-lucide="thumbs-down" class="w-4 h-4 text-text-secondary hover:text-danger"></i>
        </button>
    </div>
</div>
