<div>
    <x-slot name="title">{{ $slideId ? 'تعديل الشريحة' : 'إضافة شريحة جديدة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $slideId ? 'تعديل الشريحة' : 'إضافة شريحة جديدة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">بيانات الشريحة المعروضة في الواجهة الرئيسية</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="bg-surface rounded-xl border border-border p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">العنوان <span class="text-danger">*</span></label>
                <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('title') border-danger @enderror" />
                @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">العنوان الفرعي</label>
                <input type="text" wire:model="subtitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('subtitle') border-danger @enderror" />
                @error('subtitle') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                <textarea wire:model="description" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('description') border-danger @enderror"></textarea>
                @error('description') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">صورة البنر</label>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="file" wire:model="image" accept="image/*" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all @error('image') border-danger @enderror" />
                        <div class="flex items-center gap-3 mt-3">
                            <button type="button" wire:click="$dispatch('open-media-picker', { target: 'image' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                                <i data-lucide="images" class="w-4 h-4"></i>
                                اختيار من الوسائط
                            </button>
                        </div>
                        @error('image') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="image" class="mt-2 text-xs text-text-tertiary">جاري رفع الصورة...</div>
                    </div>
                    @if ($existingImageUrl)
                        <div class="relative shrink-0">
                            <img src="{{ $existingImageUrl }}" alt="صورة البنر" class="w-32 h-20 rounded-xl object-cover border border-border">
                            <button type="button" wire:click="removeImage" class="absolute -top-2 -right-2 w-5 h-5 bg-danger text-white rounded-full flex items-center justify-center text-xs">×</button>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الوسم (Badge)</label>
                <input type="text" wire:model="badgeText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('badgeText') border-danger @enderror" placeholder="جديد" />
                @error('badgeText') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="text-lg font-semibold text-text mb-4">الأزرار</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">نص الزر الرئيسي</label>
                    <input type="text" wire:model="buttonText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('buttonText') border-danger @enderror" />
                    @error('buttonText') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رابط الزر الرئيسي</label>
                    <input type="url" wire:model="buttonUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('buttonUrl') border-danger @enderror" />
                    @error('buttonUrl') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">نص الزر الثانوي</label>
                    <input type="text" wire:model="secondaryButtonText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('secondaryButtonText') border-danger @enderror" />
                    @error('secondaryButtonText') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رابط الزر الثانوي</label>
                    <input type="url" wire:model="secondaryButtonUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('secondaryButtonUrl') border-danger @enderror" />
                    @error('secondaryButtonUrl') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="text-lg font-semibold text-text mb-4">الإعدادات</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الترتيب</label>
                    <input type="number" wire:model="sortOrder" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('sortOrder') border-danger @enderror" />
                    @error('sortOrder') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">تاريخ البداية</label>
                    <input type="datetime-local" wire:model="startsAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('startsAt') border-danger @enderror" />
                    @error('startsAt') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">تاريخ النهاية</label>
                    <input type="datetime-local" wire:model="endsAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('endsAt') border-danger @enderror" />
                    @error('endsAt') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <input type="checkbox" wire:model="isActive" id="isActive" class="rounded border-border text-primary focus:ring-primary/20" />
                <label for="isActive" class="text-sm text-text">نشط</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dashboard.homepage.slides') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $slideId ? 'تحديث الشريحة' : 'إضافة الشريحة' }}</span>
                <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i> جاري الحفظ...</span>
            </button>
        </div>
    </form>

    <livewire:shared.media-picker :target="'image'" :restrict-collection="'hero'" />
</div>
