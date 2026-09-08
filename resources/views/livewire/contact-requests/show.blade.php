<div>
    <x-slot name="title">تفاصيل طلب الاتصال - {{ $request->tracking_number }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">تفاصيل طلب الاتصال</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $request->tracking_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.contact-requests') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                العودة
            </a>
            @can('contact_requests.resolve')
                <button wire:click="openStatusModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    تغيير الحالة
                </button>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">بيانات المتصل</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-text-tertiary">الاسم</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $request->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">الهاتف</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $request->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">البريد الإلكتروني</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $request->email ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">القسم</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $request->department ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="message-square" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الرسالة</h2>
                </div>
                <p class="text-sm text-text bg-surface-secondary rounded-xl p-4">{{ $request->message }}</p>
            </div>

            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الملاحظات</h2>
                </div>

                @if ($request->internal_notes || $request->response_notes)
                    <div class="space-y-4">
                        @if ($request->internal_notes)
                            <div>
                                <p class="text-xs font-semibold text-text-tertiary mb-1">ملاحظات داخلية</p>
                                <p class="text-sm text-text bg-surface-secondary rounded-xl p-3">{{ $request->internal_notes }}</p>
                            </div>
                        @endif
                        @if ($request->response_notes)
                            <div>
                                <p class="text-xs font-semibold text-text-tertiary mb-1">ملاحظات الرد</p>
                                <p class="text-sm text-text bg-surface-secondary rounded-xl p-3">{{ $request->response_notes }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-text-tertiary">لا توجد ملاحظات</p>
                @endif

                @can('contact_requests.resolve')
                    <button wire:click="openNotesModal" class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-secondary text-text text-xs font-semibold hover:bg-border transition-colors">
                        <i data-lucide="pencil" class="w-3 h-3"></i>
                        تعديل الملاحظات
                    </button>
                @endcan
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الحالة</h2>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-warning/10 text-warning',
                        'in_progress' => 'bg-primary/10 text-primary',
                        'resolved' => 'bg-success/10 text-success',
                        'closed' => 'bg-surface-secondary text-text-tertiary',
                    ];
                    $statusLabels = [
                        'pending' => 'قيد الانتظار',
                        'in_progress' => 'قيد المعالجة',
                        'resolved' => 'تم الحل',
                        'closed' => 'مغلق',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusColors[$request->status->value] ?? 'bg-surface-secondary text-text-tertiary' }}">
                    {{ $statusLabels[$request->status->value] ?? $request->status->value }}
                </span>

                <div class="mt-4 space-y-2">
                    <p class="text-xs text-text-tertiary">المصدر: <span class="text-text font-semibold">{{ $request->source ?? '—' }}</span></p>
                    <p class="text-xs text-text-tertiary">رقم التتبع: <span class="text-text font-semibold font-mono">{{ $request->tracking_number }}</span></p>
                </div>
            </div>

            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">التواريخ</h2>
                </div>
                <div class="space-y-2">
                    <p class="text-xs text-text-tertiary">تاريخ الإرسال: <span class="text-text font-semibold">{{ $request->submitted_at?->format('Y-m-d H:i') ?? '—' }}</span></p>
                    <p class="text-xs text-text-tertiary">تاريخ الإنشاء: <span class="text-text font-semibold">{{ $request->created_at->format('Y-m-d H:i') }}</span></p>
                    @if ($request->resolved_at)
                        <p class="text-xs text-text-tertiary">تاريخ الحل: <span class="text-text font-semibold">{{ $request->resolved_at->format('Y-m-d H:i') }}</span></p>
                    @endif
                    @if ($request->in_progress_at)
                        <p class="text-xs text-text-tertiary">تاريخ بدء المعالجة: <span class="text-text font-semibold">{{ $request->in_progress_at->format('Y-m-d H:i') }}</span></p>
                    @endif
                </div>
            </div>

            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="user-check" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">التعيين</h2>
                </div>
                @if ($request->assigned_to)
                    @php $assignedUser = $users->firstWhere('id', (int) $request->assigned_to); @endphp
                    <p class="text-sm font-semibold text-text">{{ $assignedUser?->name ?? $request->assigned_to }}</p>
                @else
                    <p class="text-sm text-text-tertiary">لم يتم التعيين</p>
                @endif

                @can('contact_requests.resolve')
                    <button wire:click="openAssignModal" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-secondary text-text text-xs font-semibold hover:bg-border transition-colors">
                        <i data-lucide="user-plus" class="w-3 h-3"></i>
                        تعيين موظف
                    </button>
                @endcan
            </div>
        </div>
    </div>

    @if ($showStatusModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-sm mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تغيير الحالة</h3>
                        <p class="text-xs text-text-tertiary">اختر الحالة الجديدة</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach ($statuses as $s)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-border cursor-pointer hover:bg-municipal-50/30 transition-colors {{ $status === $s->value ? 'border-primary bg-primary/5' : '' }}">
                            <input type="radio" wire:model="status" value="{{ $s->value }}" class="text-primary focus:ring-primary" />
                            <span class="text-sm text-text font-semibold">{{ $statusLabels[$s->value] ?? $s->value }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button wire:click="closeStatusModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="updateStatus" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>حفظ</span>
                        <span wire:loading>جاري الحفظ...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showNotesModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-lg mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تعديل الملاحظات</h3>
                        <p class="text-xs text-text-tertiary">إضافة أو تعديل الملاحظات</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1.5">ملاحظات داخلية</label>
                        <textarea wire:model="internalNotes" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1.5">ملاحظات الرد</label>
                        <textarea wire:model="responseNotes" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button wire:click="closeNotesModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="saveNotes" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>حفظ</span>
                        <span wire:loading>جاري الحفظ...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showAssignModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-sm mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تعيين موظف</h3>
                        <p class="text-xs text-text-tertiary">اختر الموظف المسؤول</p>
                    </div>
                </div>

                <div>
                    <select wire:model="assignedTo" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">بدون تعيين</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button wire:click="closeAssignModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="assignTo" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>حفظ</span>
                        <span wire:loading>جاري الحفظ...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
