<div>
    <x-slot name="title">{{ $jobId ? 'تعديل وظيفة' : 'إضافة وظيفة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $jobId ? 'تعديل وظيفة' : 'إضافة وظيفة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة وظائف البلدية</p>
        </div>
        <a href="{{ route('dashboard.jobs') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
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
                        <label class="block text-sm font-semibold text-text mb-1.5">عنوان الوظيفة *</label>
                        <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: مهندس بلدي" />
                        @error('title') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رقم الإعلان</label>
                        <input type="text" wire:model="jobNumber" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اختياري" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الدائرة</label>
                        <select wire:model="departmentId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر الدائرة</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نوع الوظيفة *</label>
                        <select wire:model="employmentType" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($employmentTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('employmentType') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الموقع *</label>
                        <input type="text" wire:model="location" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: إذنا" />
                        @error('location') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الراتب</label>
                        <input type="text" wire:model="salary" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اختياري" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">عدد الشواغر *</label>
                        <input type="number" wire:model="vacancies" min="1" max="999" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('vacancies') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Job Details --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">تفاصيل الوظيفة</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نبذة *</label>
                        <textarea wire:model="summary" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="نبذة مختصرة عن الوظيفة"></textarea>
                        @error('summary') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الوصف *</label>
                        <textarea wire:model="description" rows="6" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="الوصف الكامل للوظيفة"></textarea>
                        @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Requirements --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المتطلبات</h2>
                @foreach ($requirements as $index => $req)
                    <div class="flex items-center gap-2 mb-2" wire:key="req-{{ $index }}">
                        <input type="text" wire:model="requirements.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: بكالوريوس هندسة" />
                        <button type="button" wire:click="removeRequirement({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addRequirement" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة متطلب</span>
                </button>
                @error('requirements') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Card 4: Responsibilities --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المهام الوظيفية</h2>
                @foreach ($responsibilities as $index => $resp)
                    <div class="flex items-center gap-2 mb-2" wire:key="resp-{{ $index }}">
                        <input type="text" wire:model="responsibilities.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: إعداد التقارير" />
                        <button type="button" wire:click="removeResponsibility({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addResponsibility" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة مهمة</span>
                </button>
                @error('responsibilities') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Card 5: Documents --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المستندات المطلوبة</h2>
                @foreach ($requiredDocuments as $index => $doc)
                    <div class="flex items-center gap-2 mb-2" wire:key="doc-{{ $index }}">
                        <input type="text" wire:model="requiredDocuments.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: السيرة الذاتية" />
                        <button type="button" wire:click="removeDocument({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addDocument" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة مستند</span>
                </button>
                @error('requiredDocuments') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-text mb-1.5">المزايا (اختياري)</label>
                    @foreach ($benefits as $index => $benefit)
                        <div class="flex items-center gap-2 mb-2" wire:key="benefit-{{ $index }}">
                            <input type="text" wire:model="benefits.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: تأمين صحي" />
                            <button type="button" wire:click="removeBenefit({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addBenefit" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>إضافة ميزة</span>
                    </button>
                </div>
            </div>

            {{-- Card 6: Application Method --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">طريقة التقديم</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">طريقة التقديم *</label>
                        <select wire:model="applicationMethod" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($applicationMethods as $method)
                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                            @endforeach
                        </select>
                        @error('applicationMethod') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    @if ($applicationMethod === 'external_link')
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رابط التقديم *</label>
                            <input type="url" wire:model="applicationUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="https://" />
                            @error('applicationUrl') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    @if ($applicationMethod === 'email')
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني *</label>
                            <input type="email" wire:model="applicationEmail" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="hr@idhna.ps" />
                            @error('applicationEmail') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    @if ($applicationMethod === 'phone')
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف *</label>
                            <input type="text" wire:model="applicationPhone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="022..." />
                            @error('applicationPhone') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card 7: Attachment --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">ملف الإعلان</h2>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رفع ملف (PDF, Word, صورة)</label>
                    <input type="file" wire:model="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    @if ($existingAttachment && !$attachment)
                        <p class="text-xs text-text-tertiary mt-2">الملف الحالي: <a href="{{ asset('storage/' . $existingAttachment) }}" target="_blank" class="text-primary underline">عرض</a></p>
                    @endif
                    @error('attachment') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Card 8: Publishing --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">النشر</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ النشر *</label>
                        <input type="date" wire:model="publishAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('publishAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">آخر موعد للتقديم *</label>
                        <input type="date" wire:model="closingAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('closingAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
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
                            <label for="isFeatured" class="text-sm font-semibold text-text">وظيفة مميزة</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $jobId ? 'تحديث الوظيفة' : 'إضافة الوظيفة' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.jobs') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>
</div>
