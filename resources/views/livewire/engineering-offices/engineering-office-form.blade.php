<div>
    <x-slot name="title">{{ $officeId ? 'تعديل مكتب هندسي' : 'إضافة مكتب هندسي' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $officeId ? 'تعديل مكتب هندسي' : 'إضافة مكتب هندسي' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $officeId ? 'تعديل بيانات المكتب الهندسي' : 'إضافة مكتب هندسي جديد' }}</p>
        </div>
        <a href="{{ route('dashboard.engineering-offices') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
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
        {{-- 1. بيانات المكتب --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="building-2" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">بيانات المكتب</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text mb-1.5">اسم المكتب <span class="text-danger">*</span></label>
                    <input type="text" wire:model="office_name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="اسم المكتب الهندسي">
                    @error('office_name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">الرابط المختصر</label>
                    <input type="text" wire:model="slug" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="slug" dir="ltr">
                    @error('slug') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">رقم الترخيص</label>
                    <input type="text" wire:model="license_number" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="رقم الترخيص" dir="ltr">
                    @error('license_number') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">ترتيب الظهور</label>
                    <input type="number" wire:model="sort_order" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" min="0">
                </div>
            </div>
        </div>

        {{-- 2. بيانات المهندس --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">بيانات المهندس</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">اسم المهندس</label>
                    <input type="text" wire:model="engineer_name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="اسم المهندس المسؤول">
                    @error('engineer_name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- 3. معلومات التواصل --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="phone" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">معلومات التواصل</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">الهاتف</label>
                    <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="الهاتف الثابت" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">الجوال</label>
                    <input type="text" wire:model="mobile" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="رقم الجوال" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">البريد الإلكتروني</label>
                    <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="email@example.com" dir="ltr">
                    @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">العنوان</label>
                    <input type="text" wire:model="address" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="العنوان">
                </div>
            </div>
        </div>

        {{-- 4. التخصصات --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">التخصصات</h2>
            </div>
            <div class="space-y-3">
                @foreach ($specializations as $index => $spec)
                <div wire:key="spec-{{ $index }}" class="flex items-center gap-2">
                    <input type="text" wire:model="specializations.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="اسم التخصص">
                    <button type="button" wire:click="removeSpecialization({{ $index }})" class="p-2.5 rounded-xl hover:bg-danger-light transition-colors">
                        <i data-lucide="x" class="w-4 h-4 text-danger"></i>
                    </button>
                </div>
                @endforeach
                <button type="button" wire:click="addSpecialization" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-50 text-primary text-xs font-bold hover:bg-primary-100 transition-colors">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    إضافة تخصص
                </button>
            </div>
            @error('specializations') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- 5. حالة الاعتماد --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">حالة الاعتماد</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">حالة الاعتماد <span class="text-danger">*</span></label>
                    <select wire:model="approval_status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text font-medium focus:outline-none focus:border-primary transition-colors">
                        @foreach ($approvalStatusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('approval_status') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">الحالة <span class="text-danger">*</span></label>
                    <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text font-medium focus:outline-none focus:border-primary transition-colors">
                        @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-text mb-1.5">تاريخ انتهاء الاعتماد</label>
                    <input type="date" wire:model="expires_at" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text font-medium focus:outline-none focus:border-primary transition-colors">
                    @error('expires_at') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- 6. الملاحظات --}}
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">الملاحظات</h2>
            </div>
            <textarea wire:model="notes" rows="4" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors" placeholder="ملاحظات إضافية..."></textarea>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.engineering-offices') }}" wire:navigate class="px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                <span wire:loading.remove>{{ $officeId ? 'حفظ التغييرات' : 'إضافة المكتب' }}</span>
                <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i></span>
            </button>
        </div>
    </form>
</div>