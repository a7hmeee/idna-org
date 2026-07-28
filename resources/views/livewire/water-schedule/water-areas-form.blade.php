<div>
    <x-slot name="title">{{ $areaId ? 'تعديل منطقة' : 'إضافة منطقة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $areaId ? 'تعديل منطقة' : 'إضافة منطقة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة مناطق توزيع المياه</p>
        </div>
        <a href="{{ route('dashboard.water-schedule.areas') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    <div class="bg-surface rounded-xl border border-border p-6 max-w-2xl">
        <form wire:submit="save">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الاسم</label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: حي البلد" />
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="وصف المنطقة..."></textarea>
                    @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                    <input type="number" wire:model="displayOrder" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('displayOrder') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" wire:model="isActive" id="isActive" class="rounded border-border text-primary focus:ring-primary/20" />
                    <label for="isActive" class="text-sm font-semibold text-text">نشط</label>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $areaId ? 'تحديث' : 'إضافة' }}</span>
                    <span wire:loading>جاري الحفظ...</span>
                </button>
                <a href="{{ route('dashboard.water-schedule.areas') }}" class="px-6 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
            </div>
        </form>
    </div>
</div>
