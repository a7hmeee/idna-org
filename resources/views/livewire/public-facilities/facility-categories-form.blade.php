<div>
    <x-slot name="title">{{ $categoryId ? 'تعديل تصنيف' : 'إضافة تصنيف' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $categoryId ? 'تعديل تصنيف' : 'إضافة تصنيف' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة تصنيفات المرافق العامة</p>
        </div>
        <a href="{{ route('dashboard.facilities.categories') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    <form wire:submit="save">
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-text mb-1.5">الاسم *</label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: حدائق" />
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الأيقونة</label>
                    <input type="text" wire:model="icon" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: tree-pine" />
                    <p class="text-xs text-text-tertiary mt-1">اسم أيقونة من Lucide Icons</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                    <input type="number" wire:model="displayOrder" min="0" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="وصف التصنيف"></textarea>
                </div>
                <div class="flex items-center gap-3 pb-2.5">
                    <input type="checkbox" wire:model="isActive" id="isActive" class="rounded border-border text-primary focus:ring-primary/20" />
                    <label for="isActive" class="text-sm font-semibold text-text">نشط</label>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $categoryId ? 'تحديث التصنيف' : 'إضافة التصنيف' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.facilities.categories') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>
</div>
