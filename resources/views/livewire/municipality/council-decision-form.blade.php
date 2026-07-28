<div>
    <x-slot name="title">{{ $editingId ? 'تعديل القرار' : 'إضافة قرار' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $editingId ? 'تعديل القرار' : 'إضافة قرار جديد' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">قرارات المجلس البلدي</p>
        </div>
        <a href="{{ route('dashboard.municipality.council-decisions') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            عودة
        </a>
    </div>

    <div class="bg-surface rounded-xl border border-border">
        <form wire:submit="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رقم القرار <span class="text-danger">*</span></label>
                    <input type="text" wire:model="decision_number" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('decision_number') border-danger @enderror" placeholder="مثال: ق-2026-001" />
                    @error('decision_number') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رقم الجلسة</label>
                    <input type="text" wire:model="session_number" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('session_number') border-danger @enderror" />
                    @error('session_number') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">العنوان <span class="text-danger">*</span></label>
                <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('title') border-danger @enderror" />
                @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الملخص</label>
                <textarea wire:model="summary" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('summary') border-danger @enderror"></textarea>
                @error('summary') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">المحتوى</label>
                <textarea wire:model="content" rows="6" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('content') border-danger @enderror"></textarea>
                @error('content') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">النوع <span class="text-danger">*</span></label>
                    <select wire:model="type" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('type') border-danger @enderror">
                        @foreach ($typeOptions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الحالة <span class="text-danger">*</span></label>
                    <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('status') border-danger @enderror">
                        @foreach ($statusOptions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">تاريخ القرار</label>
                    <input type="date" wire:model="decision_date" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('decision_date') border-danger @enderror" />
                    @error('decision_date') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رابط المرفق</label>
                    <input type="text" wire:model="attachment_path" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('attachment_path') border-danger @enderror" placeholder="storage/council-decisions/..." />
                    @error('attachment_path') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                    <input type="number" wire:model="sort_order" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('sort_order') border-danger @enderror" />
                    @error('sort_order') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="is_public" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                    <span class="text-sm font-semibold text-text">عام</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ route('dashboard.municipality.council-decisions') }}" wire:navigate class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'حفظ التعديلات' : 'إضافة' }}</span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
