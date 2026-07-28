<div>
    <x-slot name="title">إعدادات الصفحة الرئيسية</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إعدادات الصفحة الرئيسية</h1>
            <p class="text-sm text-text-tertiary mt-1">تخصيص محتوى ونصوص الصفحة الرئيسية</p>
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
            <div class="flex items-start gap-3 mb-4 p-3 bg-info/5 border border-info/20 rounded-xl">
                <i data-lucide="info" class="w-5 h-5 text-info mt-0.5 shrink-0"></i>
                <div>
                    <p class="text-sm font-semibold text-text">اسم البلدية ووصفها</p>
                    <p class="text-xs text-text-tertiary mt-1">يتم إدارة اسم البلدية والوصف من قسم <a href="{{ route('dashboard.municipality.general-info') }}" class="text-primary hover:underline">معلومات البلدية</a>.</p>
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="text-lg font-semibold text-text mb-4">بوابة الخدمات</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رابط البوابة الإلكترونية</label>
                    <input type="url" wire:model="portalUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('portalUrl') border-danger @enderror" placeholder="https://" />
                    @error('portalUrl') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">نص الزر الرئيسي</label>
                    <input type="text" wire:model="primaryButtonText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('primaryButtonText') border-danger @enderror" placeholder="الدخول إلى البوابة" />
                    @error('primaryButtonText') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">نص الزر الثانوي</label>
                    <input type="text" wire:model="secondaryButtonText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('secondaryButtonText') border-danger @enderror" placeholder="تعرف على البلدية" />
                    @error('secondaryButtonText') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رابط الزر الثانوي</label>
                    <input type="url" wire:model="secondaryButtonUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('secondaryButtonUrl') border-danger @enderror" placeholder="https://" />
                    @error('secondaryButtonUrl') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="text-lg font-semibold text-text mb-4">قسم الترحيب</h3>
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">عنوان الترحيب</label>
                    <input type="text" wire:model="welcomeTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('welcomeTitle') border-danger @enderror" placeholder="مرحباً بكم في بلدية إذنا" />
                    @error('welcomeTitle') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">وصف الترحيب</label>
                    <textarea wire:model="welcomeDescription" rows="4" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('welcomeDescription') border-danger @enderror" placeholder="نبذة ترحيبية عن البلدية..."></textarea>
                    @error('welcomeDescription') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="text-lg font-semibold text-text mb-4">دعوة للتواصل (CTA)</h3>
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">العنوان</label>
                    <input type="text" wire:model="contactCtaTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('contactCtaTitle') border-danger @enderror" placeholder="تواصل معنا" />
                    @error('contactCtaTitle') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                    <textarea wire:model="contactCtaDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('contactCtaDescription') border-danger @enderror" placeholder="نص دعوة للتواصل..."></textarea>
                    @error('contactCtaDescription') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نص الزر</label>
                        <input type="text" wire:model="contactCtaButtonText" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('contactCtaButtonText') border-danger @enderror" placeholder="اتصل بنا" />
                        @error('contactCtaButtonText') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رابط الزر</label>
                        <input type="url" wire:model="contactCtaButtonUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('contactCtaButtonUrl') border-danger @enderror" placeholder="https://" />
                        @error('contactCtaButtonUrl') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>حفظ الإعدادات</span>
                <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i> جاري الحفظ...</span>
            </button>
        </div>
    </form>
</div>
