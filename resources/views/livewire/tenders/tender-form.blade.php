<div>
    <x-slot name="title">{{ $tenderId ? 'تعديل مناقصة' : 'إضافة مناقصة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $tenderId ? 'تعديل مناقصة' : 'إضافة مناقصة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة مناقصات البلدية</p>
        </div>
        <a href="{{ route('dashboard.tenders') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    <form wire:submit="save">
        <div class="space-y-6">

            {{-- Card 1: Basic Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المعلومات الأساسية</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">عنوان المناقصة (عربي) *</label>
                        <input type="text" wire:model="titleAr" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: مناقصة صيانة طرق" />
                        @error('titleAr') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">العنوان (إنجليزي)</label>
                        <input type="text" wire:model="titleEn" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اختياري" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رقم المناقصة</label>
                        <input type="text" wire:model="tenderNumber" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اختياري" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">التصنيف</label>
                        <input type="text" wire:model="category" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: أشغال، توريدات" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الجهة المصدرة *</label>
                        <input type="text" wire:model="issuingDepartment" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: بلدية إذنا" />
                        @error('issuingDepartment') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Details --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">التفاصيل</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نبذة *</label>
                        <textarea wire:model="summary" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="نبذة مختصرة عن المناقصة"></textarea>
                        @error('summary') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الوصف *</label>
                        <textarea wire:model="description" rows="6" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="الوصف الكامل للمناقصة"></textarea>
                        @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Eligibility Requirements --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">شروط التأهيل</h2>
                @foreach ($eligibilityRequirements as $index => $req)
                    <div class="flex items-center gap-2 mb-2" wire:key="req-{{ $index }}">
                        <input type="text" wire:model="eligibilityRequirements.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: أن يكون المتقدم مسجلاً في وزارة الاقتصاد" />
                        <button type="button" wire:click="removeEligibilityRequirement({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addEligibilityRequirement" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة شرط</span>
                </button>
            </div>

            {{-- Card 4: Application Instructions --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">تعليمات التقديم</h2>
                @foreach ($applicationInstructions as $index => $inst)
                    <div class="flex items-center gap-2 mb-2" wire:key="inst-{{ $index }}">
                        <input type="text" wire:model="applicationInstructions.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: تقديم العروض في مظاريف مغلقة" />
                        <button type="button" wire:click="removeApplicationInstruction({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addApplicationInstruction" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة تعليمة</span>
                </button>
            </div>

            {{-- Card 5: Documents --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">مستندات المناقصة</h2>
                @foreach ($tenderDocuments as $index => $doc)
                    <div class="flex items-center gap-2 mb-2" wire:key="doc-{{ $index }}">
                        <input type="text" wire:model="tenderDocuments.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: كراسة الشروط" />
                        <button type="button" wire:click="removeTenderDocument({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addTenderDocument" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة مستند</span>
                </button>

                <div class="mt-6">
                    <h3 class="text-sm font-bold text-text mb-3">مستندات النتائج (اختياري)</h3>
                    @foreach ($resultDocuments as $index => $doc)
                        <div class="flex items-center gap-2 mb-2" wire:key="rdoc-{{ $index }}">
                            <input type="text" wire:model="resultDocuments.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: محضر الترسية" />
                            <button type="button" wire:click="removeResultDocument({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addResultDocument" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>إضافة مستند نتيجة</span>
                    </button>
                </div>
            </div>

            {{-- Card 6: Contact Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">معلومات الاتصال</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">معلومات الاتصال</label>
                        <textarea wire:model="contactInfo" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="العنوان، أوقات الدوام..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف</label>
                        <input type="text" wire:model="contactPhone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="022..." />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني</label>
                        <input type="email" wire:model="contactEmail" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="procurement@idhna.ps" />
                        @error('contactEmail') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 7: Budget --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الميزانية</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الميزانية</label>
                        <input type="text" wire:model="budget" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: 200000" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">العملة</label>
                        <select wire:model="budgetCurrency" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="ILS">شيكل</option>
                            <option value="JOD">دينار أردني</option>
                            <option value="USD">دولار أمريكي</option>
                            <option value="EUR">يورو</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Card 8: Dates & Publishing --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">التواريخ والنشر</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ النشر *</label>
                        <input type="date" wire:model="publicationDate" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('publicationDate') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">آخر موعد للتقديم *</label>
                        <input type="date" wire:model="submissionDeadline" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('submissionDeadline') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ فتح المظاريف</label>
                        <input type="date" wire:model="openingDate" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الحالة</label>
                        <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($statuses as $s)
                                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-6 pb-2.5">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="isPublic" id="isPublic" class="rounded border-border text-primary focus:ring-primary/20" />
                            <label for="isPublic" class="text-sm font-semibold text-text">ظاهر للعموم</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="isFeatured" id="isFeatured" class="rounded border-border text-primary focus:ring-primary/20" />
                            <label for="isFeatured" class="text-sm font-semibold text-text">مناقصة مميزة</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $tenderId ? 'تحديث المناقصة' : 'إضافة المناقصة' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.tenders') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>
</div>
