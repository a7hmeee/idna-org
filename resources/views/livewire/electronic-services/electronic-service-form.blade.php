<div>
    <x-slot name="title">{{ $serviceId ? 'تعديل الخدمة' : 'إضافة خدمة جديدة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $serviceId ? 'تعديل الخدمة' : 'إضافة خدمة جديدة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $serviceId ? 'تعديل بيانات الخدمة الإلكترونية' : 'إنشاء خدمة إلكترونية جديدة' }}</p>
        </div>
        <a href="{{ route('dashboard.electronic-services.services') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
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
                <h2 class="text-sm font-bold text-text">معلومات أساسية</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">اسم الخدمة</label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">التصنيف</label>
                    <select wire:model="service_category_id" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">اختر التصنيف</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('service_category_id') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">الدائرة المسؤولة</label>
                    <select wire:model="department_id" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">اختر الدائرة</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">الرابط المختصر</label>
                    <input type="text" wire:model="slug" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('slug') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">مدة الإنجاز</label>
                    <input type="text" wire:model="processing_time" placeholder="مثال: 5-10 أيام عمل" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">نبذة مختصرة</label>
                    <textarea wire:model="summary" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">وصف الخدمة</label>
                    <textarea wire:model="description" rows="4" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">من يستطيع التقديم (الشروط)</label>
                    <textarea wire:model="eligibility" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">المتطلبات</h2>
            </div>
            <div class="space-y-3">
                @foreach ($requirements as $index => $req)
                <div wire:key="req-{{ $index }}" class="flex items-start gap-3 p-3 rounded-xl bg-surface-secondary">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" wire:model="requirements.{{ $index }}.title" placeholder="عنوان المتطلب" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <input type="text" wire:model="requirements.{{ $index }}.description" placeholder="الوصف" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <label class="flex items-center gap-2 text-sm text-text-secondary">
                            <input type="checkbox" wire:model="requirements.{{ $index }}.is_required" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                            إجباري
                        </label>
                    </div>
                    <button type="button" wire:click="removeRequirement({{ $index }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                @endforeach
            </div>
            <button type="button" wire:click="addRequirement" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-primary hover:bg-primary-50 transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                إضافة متطلب
            </button>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">الوثائق المطلوبة</h2>
            </div>
            <div class="space-y-3">
                @foreach ($documents as $index => $doc)
                <div wire:key="doc-{{ $index }}" class="flex items-start gap-3 p-3 rounded-xl bg-surface-secondary">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" wire:model="documents.{{ $index }}.name" placeholder="اسم الوثيقة" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <input type="text" wire:model="documents.{{ $index }}.description" placeholder="الوصف" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <label class="flex items-center gap-2 text-sm text-text-secondary">
                            <input type="checkbox" wire:model="documents.{{ $index }}.is_required" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                            إجباري
                        </label>
                    </div>
                    <button type="button" wire:click="removeDocument({{ $index }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                @endforeach
            </div>
            <button type="button" wire:click="addDocument" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-primary hover:bg-primary-50 transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                إضافة وثيقة
            </button>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="list-ordered" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">خطوات التقديم</h2>
            </div>
            <div class="space-y-3">
                @foreach ($steps as $index => $step)
                <div wire:key="step-{{ $index }}" class="flex items-start gap-3 p-3 rounded-xl bg-surface-secondary">
                    <div class="w-7 h-7 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                        <span class="text-xs font-bold text-primary">{{ $index + 1 }}</span>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" wire:model="steps.{{ $index }}.title" placeholder="عنوان الخطوة" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <input type="text" wire:model="steps.{{ $index }}.description" placeholder="الوصف" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                    </div>
                    <button type="button" wire:click="removeStep({{ $index }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                @endforeach
            </div>
            <button type="button" wire:click="addStep" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-primary hover:bg-primary-50 transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                إضافة خطوة
            </button>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">الرسوم</h2>
            </div>
            <div class="space-y-3">
                @foreach ($fees as $index => $fee)
                <div wire:key="fee-{{ $index }}" class="flex items-start gap-3 p-3 rounded-xl bg-surface-secondary">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <input type="text" wire:model="fees.{{ $index }}.title" placeholder="عنوان الرسم" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <input type="text" wire:model="fees.{{ $index }}.amount" placeholder="المبلغ" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                        <select wire:model="fees.{{ $index }}.currency" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all">
                            <option value="ILS">شيكل</option>
                            <option value="USD">دولار</option>
                            <option value="JOD">دينار</option>
                        </select>
                        <input type="text" wire:model="fees.{{ $index }}.notes" placeholder="ملاحظات" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-sm text-text focus:border-primary outline-none transition-all" />
                    </div>
                    <button type="button" wire:click="removeFee({{ $index }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                @endforeach
            </div>
            <button type="button" wire:click="addFee" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-primary hover:bg-primary-50 transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                إضافة رسم
            </button>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="globe" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">الربط مع البوابة الخارجية</h2>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">رابط البوابة الخارجية للتقديم</label>
                    <input type="url" wire:model="portal_url" placeholder="https://..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    @error('portal_url') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary w-fit">
                    <input type="checkbox" wire:model="requires_login" id="requires_login" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                    <label for="requires_login" class="text-sm text-text-secondary">يتطلب تسجيل دخول</label>
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                </div>
                <h2 class="text-sm font-bold text-text">إعدادات النشر</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary">
                    <input type="checkbox" wire:model="is_public" id="is_public" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                    <label for="is_public" class="text-sm text-text-secondary">ظاهر للعامة</label>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary">
                    <input type="checkbox" wire:model="is_featured" id="is_featured" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                    <label for="is_featured" class="text-sm text-text-secondary">خدمة مميزة</label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">الحالة</label>
                    <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        @foreach ($statusOptions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-secondary mb-1.5">ترتيب الظهور</label>
                    <input type="number" wire:model="sort_order" min="0" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <div>
                @if ($serviceId && $canPublish && $status !== 'active')
                <button type="button" wire:click="publish" class="px-5 py-2.5 rounded-xl bg-success text-white text-sm font-semibold hover:bg-success/90 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>نشر الخدمة</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                    </span>
                </button>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.electronic-services.services') }}" wire:navigate class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $serviceId ? 'حفظ التغييرات' : 'إنشاء الخدمة' }}</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
