<div class="flex flex-col items-center justify-center py-8 px-4" dir="rtl">
    <div class="w-12 h-12 rounded-xl bg-danger-light flex items-center justify-center mb-3">
        <i data-lucide="alert-circle" class="w-6 h-6 text-danger"></i>
    </div>
    <p class="text-sm font-medium text-text mb-1">حدث خطأ</p>
    <p class="text-xs text-text-secondary text-center max-w-xs">{{ $message }}</p>
    <button type="button"
            wire:click="$refresh"
            class="mt-3 text-xs px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-all cursor-pointer border-none">
        إعادة المحاولة
    </button>
</div>
