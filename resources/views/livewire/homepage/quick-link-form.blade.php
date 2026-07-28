<div>
    <x-slot name="title">{{ $linkId ? 'تعديل الرابط السريع' : 'إضافة رابط سريع' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $linkId ? 'تعديل الرابط السريع' : 'إضافة رابط سريع' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">بيانات الرابط السريع</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit="save" class="bg-surface rounded-xl border border-border p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">العنوان <span class="text-danger">*</span></label>
                <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('title') border-danger @enderror" />
                @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الأيقونة (اسم Lucide)</label>
                <input type="text" wire:model="icon" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('icon') border-danger @enderror" placeholder="building-2" />
                @error('icon') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
            <textarea wire:model="description" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('description') border-danger @enderror"></textarea>
            @error('description') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الرابط</label>
                <input type="url" wire:model="url" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('url') border-danger @enderror" />
                @error('url') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">النوع</label>
                <select wire:model="type" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('type') border-danger @enderror">
                    <option value="">اختيار النوع</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('type') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الترتيب</label>
                <input type="number" wire:model="sortOrder" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('sortOrder') border-danger @enderror" />
                @error('sortOrder') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3 pt-7">
                <input type="checkbox" wire:model="isExternal" id="isExternal" class="rounded border-border text-primary focus:ring-primary/20" />
                <label for="isExternal" class="text-sm text-text">رابط خارجي</label>
            </div>
            <div class="flex items-center gap-3 pt-7">
                <input type="checkbox" wire:model="isActive" id="isActive" class="rounded border-border text-primary focus:ring-primary/20" />
                <label for="isActive" class="text-sm text-text">نشط</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
            <a href="{{ route('dashboard.homepage.quick-links') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $linkId ? 'تحديث الرابط' : 'إضافة الرابط' }}</span>
                <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i> جاري الحفظ...</span>
            </button>
        </div>
    </form>
</div>
