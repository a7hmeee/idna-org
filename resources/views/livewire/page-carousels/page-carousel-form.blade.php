<div>
    <x-slot name="title">{{ $slideId ? 'تعديل الشريحة' : 'إضافة شريحة جديدة' }}</x-slot>

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dashboard.page-carousels') }}" wire:navigate class="p-2 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-text transition-all">
            <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $slideId ? 'تعديل الشريحة' : 'إضافة شريحة جديدة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $slideId ? 'تعديل بيانات الشريحة' : 'إنشاء شريحة جديدة لكاروسيل الصفحات' }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="bg-surface rounded-xl border border-border p-6">
            <h2 class="text-lg font-bold text-text mb-4">معلومات الشريحة</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الصفحة المستهدفة</label>
                    <select wire:model="pageKey" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        @foreach ($pageKeys as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('pageKey') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">عنوان الشريحة</label>
                    <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('title') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">عنوان فرعي</label>
                    <input type="text" wire:model="subtitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('subtitle') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">نص الزر</label>
                    <input type="text" wire:model="buttonText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('buttonText') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رابط الزر</label>
                    <input type="text" wire:model="buttonUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('buttonUrl') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">النص الظاهر (Badge)</label>
                    <input type="text" wire:model="badgeText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('badgeText') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                <textarea wire:model="description" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Desktop Image --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <h2 class="text-lg font-bold text-text mb-4">صورة سطح المكتب</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رفع صورة</label>
                    <input type="file" wire:model="image" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark transition-all" />
                    <p class="text-xs text-text-tertiary mt-1">jpg, jpeg, png, webp — كحد أقصى 2 ميجابايت</p>
                    @error('image') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    @if ($image)
                        <p class="text-xs text-text-tertiary mb-1.5">معاينة الصورة الجديدة:</p>
                        <img src="{{ $image->temporaryUrl() }}" class="w-full max-h-32 rounded-xl object-cover border border-border" />
                    @elseif ($existingImageUrl)
                        <div class="flex items-start gap-3">
                            <div>
                                <p class="text-xs text-text-tertiary mb-1.5">الصورة الحالية:</p>
                                <img src="{{ $existingImageUrl }}" class="w-full max-h-32 rounded-xl object-cover border border-border" />
                            </div>
                            <button type="button" wire:click="removeImage" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all mt-6">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @else
                        <div class="w-full h-32 rounded-xl bg-surface-secondary border border-border flex items-center justify-center">
                            <div class="text-center">
                                <i data-lucide="image-plus" class="w-8 h-8 text-text-tertiary mx-auto"></i>
                                <p class="text-xs text-text-tertiary mt-1">لم يتم رفع صورة بعد</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Mobile Image --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <h2 class="text-lg font-bold text-text mb-4">صورة الجوال <span class="text-xs font-normal text-text-tertiary">(اختياري)</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رفع صورة</label>
                    <input type="file" wire:model="mobileImage" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark transition-all" />
                    <p class="text-xs text-text-tertiary mt-1">jpg, jpeg, png, webp — كحد أقصى 2 ميجابايت</p>
                    @error('mobileImage') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    @if ($mobileImage)
                        <p class="text-xs text-text-tertiary mb-1.5">معاينة صورة الجوال الجديدة:</p>
                        <img src="{{ $mobileImage->temporaryUrl() }}" class="w-full max-h-32 rounded-xl object-cover border border-border" />
                    @elseif ($existingMobileImageUrl)
                        <div class="flex items-start gap-3">
                            <div>
                                <p class="text-xs text-text-tertiary mb-1.5">صورة الجوال الحالية:</p>
                                <img src="{{ $existingMobileImageUrl }}" class="w-full max-h-32 rounded-xl object-cover border border-border" />
                            </div>
                            <button type="button" wire:click="removeMobileImage" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all mt-6">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @else
                        <div class="w-full h-32 rounded-xl bg-surface-secondary border border-border flex items-center justify-center">
                            <div class="text-center">
                                <i data-lucide="smartphone" class="w-8 h-8 text-text-tertiary mx-auto"></i>
                                <p class="text-xs text-text-tertiary mt-1">لم يتم رفع صورة جوال بعد</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Settings --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <h2 class="text-lg font-bold text-text mb-4">الإعدادات</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                    <input type="number" wire:model="sortOrder" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('sortOrder') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">تاريخ البدء</label>
                    <input type="datetime-local" wire:model="startsAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('startsAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">تاريخ الانتهاء</label>
                    <input type="datetime-local" wire:model="endsAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('endsAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="isActive" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                    <span class="text-sm font-semibold text-text">نشط</span>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.page-carousels') }}" wire:navigate class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $slideId ? 'حفظ التغييرات' : 'إنشاء الشريحة' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
        </div>
    </form>
</div>