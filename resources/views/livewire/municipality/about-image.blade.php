<div>
    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-border">
            <div>
                <h3 class="text-base font-bold text-text">صورة نبذة عن البلدية</h3>
                <p class="text-xs text-text-tertiary mt-0.5">تظهر هذه الصورة في قسم "نبذة عن البلدية" بالصفحة الرئيسية.</p>
            </div>
        </div>

        @if (session('about_image_success'))
            <div class="mx-5 mt-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
                <span class="text-sm text-success font-medium">{{ session('about_image_success') }}</span>
            </div>
        @endif

        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                <div class="w-full sm:w-[240px] shrink-0">
                    <div class="rounded-2xl overflow-hidden border border-border bg-surface-secondary aspect-[4/3]">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $media?->alt ?? 'صورة نبذة عن البلدية' }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy" decoding="async" />
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="image" class="w-10 h-10 text-text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <p class="text-[11px] text-text-tertiary mt-2 text-center">
                        {{ $imageUrl ? 'الصورة الحالية' : 'لا توجد صورة بعد' }} — الموصى بها: 1200×900 بكسل (jpg/png/webp، حتى 5MB)
                    </p>
                </div>

                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رفع / استبدال الصورة</label>
                        <div class="relative">
                            <input type="file"
                                   wire:model="file"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer @error('file') border-danger @enderror" />
                            <div wire:loading wire:target="file" class="absolute left-3 top-1/2 -translate-y-1/2">
                                <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-primary"></i>
                            </div>
                        </div>
                        @error('file') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror

                        @if ($file)
                            <div class="mt-3">
                                <img src="{{ $file->temporaryUrl() }}"
                                     alt="معاينة"
                                     class="w-32 h-32 rounded-xl object-cover border border-border"
                                     loading="lazy" />
                                <button type="button"
                                        wire:click="$set('file', null)"
                                        class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-text-tertiary hover:text-danger transition-colors">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    إزالة المعاينة
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        @if ($file)
                            <button wire:click="save"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                                <span wire:loading.remove wire:target="save">{{ $imageUrl ? 'استبدال الصورة' : 'حفظ الصورة' }}</span>
                                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                    جاري الرفع...
                                </span>
                            </button>
                        @endif

                        @if ($imageUrl)
                            <button wire:click="confirmRemove"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-danger/30 text-sm font-semibold text-danger hover:bg-danger-light transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                إزالة الصورة
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($showConfirmRemove)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="cancelRemove"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">إزالة الصورة</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من إزالة صورة نبذة عن البلدية؟</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="cancelRemove" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="remove" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>نعم، إزالة</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        جاري الإزالة...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
