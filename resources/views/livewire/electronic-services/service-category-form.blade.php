<div>
    <x-slot name="title">{{ $categoryId ? 'تعديل التصنيف' : 'إضافة تصنيف جديد' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $categoryId ? 'تعديل التصنيف' : 'إضافة تصنيف جديد' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $categoryId ? 'تعديل بيانات تصنيف الخدمات' : 'إنشاء تصنيف جديد للخدمات الإلكترونية' }}</p>
        </div>
        <a href="{{ route('dashboard.electronic-services.categories') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            العودة
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="info" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">معلومات التصنيف</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">اسم التصنيف</label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">الرابط المختصر (slug)</label>
                    <input type="text" wire:model="slug" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('slug') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">الأيقونة</label>
                    <input type="text" wire:model="icon" placeholder="building-2, shield, ..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('icon') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">التصنيف الأب</label>
                    <select wire:model="parent_id" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">بدون (تصنيف رئيسي)</option>
                        @foreach ($parentCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">ترتيب الظهور</label>
                    <input type="number" wire:model="sort_order" min="0" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-xs font-semibold text-text-secondary mb-1.5">الوصف</label>
                <textarea wire:model="description" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">إعدادات النشر</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary">
                    <input type="checkbox" wire:model="is_public" id="is_public" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                    <label for="is_public" class="text-sm text-text-secondary">ظاهر للعامة</label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">الحالة</label>
                    <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.electronic-services.categories') }}" wire:navigate class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $categoryId ? 'حفظ التغييرات' : 'إنشاء التصنيف' }}</span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                </span>
            </button>
        </div>
    </form>
</div>
