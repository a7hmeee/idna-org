<div>
    <x-slot name="title">{{ $complaintId ? 'تعديل شكوى' : 'إضافة شكوى' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $complaintId ? 'تعديل شكوى' : 'إضافة شكوى' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة شكاوى المواطنين</p>
        </div>
        <a href="{{ route('dashboard.complaints') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    <form wire:submit="save">
        <div class="space-y-6">

            {{-- Card 1: Citizen Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">معلومات المواطن</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الاسم *</label>
                        <input type="text" wire:model="citizenName" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('citizenName') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف *</label>
                        <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('phone') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني</label>
                        <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الرقم الوطني</label>
                        <input type="text" wire:model="nationalId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('nationalId') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Complaint Details --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">تفاصيل الشكوى</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">التصنيف *</label>
                        <select wire:model="category" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($categories as $c)
                                <option value="{{ $c->value }}">{{ $c->label() }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الدائرة المعنية</label>
                        <select wire:model="departmentId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر دائرة</option>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('departmentId') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">الموضوع *</label>
                        <input type="text" wire:model="subject" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('subject') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">الوصف *</label>
                        <textarea wire:model="description" rows="5" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                        @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Location --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الموقع</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">الموقع (وصف)</label>
                        <input type="text" wire:model="location" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('location') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">خط العرض (Latitude)</label>
                        <input type="number" step="any" wire:model="latitude" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('latitude') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">خط الطول (Longitude)</label>
                        <input type="number" step="any" wire:model="longitude" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('longitude') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 4: Attachments --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المرفقات</h2>
                <div>
                    <input type="file" wire:model="attachments" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    @if (count($existingAttachments) > 0)
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-text-tertiary">{{ count($existingAttachments) }} مرفق موجود</span>
                            <label class="flex items-center gap-1.5 text-xs text-danger cursor-pointer">
                                <input type="checkbox" wire:model="removeAttachments" class="rounded border-border text-danger focus:ring-danger/20" />
                                حذف المرفقات الحالية
                            </label>
                        </div>
                    @endif
                    @error('attachments.*') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Card 5: Priority & Status --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الأولوية والحالة</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الأولوية *</label>
                        <select wire:model="priority" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($priorities as $p)
                                <option value="{{ $p->value }}">{{ $p->label() }}</option>
                            @endforeach
                        </select>
                        @error('priority') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الحالة *</label>
                        <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($statuses as $s)
                                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تعيين إلى</label>
                        <select wire:model="assignedTo" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر موظفاً</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                        @error('assignedTo') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 6: Notes --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">ملاحظات وردود</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">ملاحظات داخلية</label>
                        <textarea wire:model="internalNotes" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                        @error('internalNotes') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الرد العام</label>
                        <textarea wire:model="publicResponse" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all"></textarea>
                        @error('publicResponse') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $complaintId ? 'تحديث الشكوى' : 'إضافة الشكوى' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.complaints') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>
</div>