<div>
    <x-slot name="title">الشكاوى</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الشكاوى</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة شكاوى المواطنين</p>
        </div>
        @can('create', \App\Domains\Complaints\Models\Complaint::class)
            <a href="{{ route('dashboard.complaints.create') }}" class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>إضافة شكوى</span>
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="flex flex-col sm:flex-row gap-3 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث برقم التتبع أو الاسم أو رقم الهاتف..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="statusFilter" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                    @endforeach
                </select>
                <select wire:model.live="departmentFilter" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الدوائر</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="priorityFilter" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الأولويات</option>
                    @foreach ($priorities as $p)
                        <option value="{{ $p->value }}">{{ $p->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">رقم التتبع</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المواطن</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">التصنيف</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الأولوية</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الدائرة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">تاريخ التقديم</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="complaint-{{ $complaint->id }}">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-primary font-semibold">{{ $complaint->tracking_number }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $complaint->citizen_name }}</p>
                                <span class="text-xs text-text-tertiary">{{ $complaint->phone }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-text-tertiary">{{ $complaint->category->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($complaint->priority->value === 'urgent') bg-danger/10 text-danger
                                    @elseif($complaint->priority->value === 'high') bg-warning/10 text-warning
                                    @elseif($complaint->priority->value === 'medium') bg-info/10 text-info
                                    @else bg-surface-secondary text-text-tertiary @endif">
                                    {{ $complaint->priority->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($complaint->status->value === 'resolved' || $complaint->status->value === 'closed') bg-success/10 text-success
                                    @elseif($complaint->status->value === 'rejected') bg-danger/10 text-danger
                                    @elseif($complaint->status->value === 'submitted') bg-info/10 text-info
                                    @elseif($complaint->status->value === 'under_review' || $complaint->status->value === 'assigned') bg-warning/10 text-warning
                                    @else bg-blue/10 text-blue @endif">
                                    {{ $complaint->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-text-tertiary">
                                {{ $complaint->department?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-text-tertiary">
                                {{ $complaint->submitted_at?->format('Y-m-d') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', \App\Domains\Complaints\Models\Complaint::class)
                                        <a href="{{ route('dashboard.complaints.edit', $complaint->id) }}" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" wire:navigate title="تعديل">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endcan
                                    @can('assign', \App\Domains\Complaints\Models\Complaint::class)
                                        <button wire:click="confirmAssign({{ $complaint->id }})" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" title="تعيين">
                                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                    @can('changeStatus', \App\Domains\Complaints\Models\Complaint::class)
                                        <button wire:click="confirmStatusChange({{ $complaint->id }})" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-warning transition-all" title="تغيير الحالة">
                                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                    @can('delete', \App\Domains\Complaints\Models\Complaint::class)
                                        <button wire:click="confirmDelete({{ $complaint->id }})" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all" title="حذف">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="message-square" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد شكاوى بعد</p>
                                    @can('create', \App\Domains\Complaints\Models\Complaint::class)
                                        <a href="{{ route('dashboard.complaints.create') }}" class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة شكوى جديدة</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($complaints->hasPages())
            <div class="p-4 border-t border-border">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-sm mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-danger/10 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-danger"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تأكيد الحذف</h3>
                        <p class="text-xs text-text-tertiary">هذا الإجراء لا يمكن التراجع عنه</p>
                    </div>
                </div>
                <p class="text-sm text-text-secondary mb-6">هل أنت متأكد من حذف هذه الشكوى؟</p>
                <div class="flex items-center justify-end gap-3">
                    <button wire:click="closeDeleteModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="delete" class="px-4 py-2 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>تأكيد الحذف</span>
                        <span wire:loading>جاري الحذف...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Assign Modal --}}
    @if ($showAssignModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-md mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تعيين الشكوى</h3>
                        <p class="text-xs text-text-tertiary">اختر الموظف المسؤول</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-text mb-1.5">الموظف</label>
                    <select wire:model="assignedUserId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">اختر موظفاً</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                    @error('assignedUserId') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button wire:click="closeAssignModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="assign" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>تعيين</span>
                        <span wire:loading>جاري التعيين...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Status Change Modal --}}
    @if ($showStatusModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-md mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-warning/10 flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-warning"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تغيير حالة الشكوى</h3>
                        <p class="text-xs text-text-tertiary">اختر الحالة الجديدة</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-text mb-1.5">الحالة</label>
                    <select wire:model="newStatus" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">اختر حالة</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s->value }}">{{ $s->label() }}</option>
                        @endforeach
                    </select>
                    @error('newStatus') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button wire:click="closeStatusModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="changeStatus" class="px-4 py-2 rounded-xl bg-warning text-white text-sm font-semibold hover:bg-warning/90 transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>تغيير الحالة</span>
                        <span wire:loading>جاري التغيير...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>