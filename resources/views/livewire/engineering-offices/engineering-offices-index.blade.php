<div>
    <x-slot name="title">المكاتب الهندسية</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">المكاتب الهندسية</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة المكاتب الهندسية المعتمدة</p>
        </div>
        @if ($canCreate)
        <a href="{{ route('dashboard.engineering-offices.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة مكتب هندسي
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <i data-lucide="search" class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث باسم المكتب أو المهندس أو رقم الترخيص..." class="w-full bg-surface border border-border rounded-xl px-4 pr-10 py-2.5 text-sm text-text placeholder-text-tertiary/60 font-medium focus:outline-none focus:border-primary transition-colors">
        </div>

        <select wire:model.live="approvalStatus" class="bg-surface border border-border rounded-xl px-4 py-2.5 text-sm text-text font-medium focus:outline-none focus:border-primary transition-colors">
            <option value="">جميع حالات الاعتماد</option>
            @foreach ($approvalStatusOptions as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="bg-surface border border-border rounded-xl px-4 py-2.5 text-sm text-text font-medium focus:outline-none focus:border-primary transition-colors">
            <option value="">جميع الحالات</option>
            @foreach ($statusOptions as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @if ($offices->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-20 h-20 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
            <i data-lucide="hard-hat" class="w-9 h-9 text-text-muted"></i>
        </div>
        <p class="text-base font-bold text-text">لا توجد مكاتب هندسية</p>
        <p class="text-sm text-text-tertiary mt-1">قم بإضافة مكتب هندسي جديد للبدء</p>
    </div>
    @else
    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-border">
                    <th class="text-right px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">اسم المكتب</th>
                    <th class="text-right px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">المهندس</th>
                    <th class="text-right px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">رقم الترخيص</th>
                    <th class="text-right px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">الهاتف</th>
                    <th class="text-center px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">حالة الاعتماد</th>
                    <th class="text-center px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">الحالة</th>
                    <th class="text-center px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">انتهاء الاعتماد</th>
                    <th class="text-center px-4 py-3 text-[11px] font-bold text-text-tertiary uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach ($offices as $office)
                <tr wire:key="office-{{ $office->id }}" class="hover:bg-surface-secondary transition-colors">
                    <td class="px-4 py-3">
                        <a href="{{ route('dashboard.engineering-offices.show', $office) }}" wire:navigate class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">{{ $office->office_name }}</a>
                    </td>
                    <td class="px-4 py-3 text-sm text-text">{{ $office->engineer_name ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-text" dir="ltr">{{ $office->license_number ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-text" dir="ltr">{{ $office->phone ?? $office->mobile ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $approvalBadge = match($office->approval_status) {
                                'approved' => 'bg-success-light text-success',
                                'pending' => 'bg-warning-light text-warning',
                                'suspended' => 'bg-danger-light text-danger',
                                'expired' => 'bg-surface-secondary text-text-muted',
                                default => 'bg-surface-secondary text-text-muted',
                            };
                            $approvalLabel = $approvalStatusOptions[$office->approval_status] ?? $office->approval_status;
                        @endphp
                        <span class="inline-flex text-[10px] font-bold {{ $approvalBadge }} px-2 py-0.5 rounded-md">{{ $approvalLabel }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $statusBadge = match($office->status) {
                                'active' => 'bg-success-light text-success',
                                'inactive' => 'bg-surface-secondary text-text-muted',
                                default => 'bg-surface-secondary text-text-muted',
                            };
                            $statusLabel = $statusOptions[$office->status] ?? $office->status;
                        @endphp
                        <span class="text-[10px] font-bold {{ $statusBadge }} px-2 py-0.5 rounded-md">{{ $statusLabel }}</span>
                    </td>
                    <td class="px-4 py-3 text-center text-sm text-text-tertiary">{{ $office->expires_at?->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('dashboard.engineering-offices.show', $office) }}" wire:navigate class="p-2 rounded-lg hover:bg-surface transition-colors" title="عرض">
                                <i data-lucide="eye" class="w-4 h-4 text-text-tertiary hover:text-text"></i>
                            </a>
                            @if ($canUpdate)
                            <a href="{{ route('dashboard.engineering-offices.edit', $office) }}" wire:navigate class="p-2 rounded-lg hover:bg-surface transition-colors" title="تعديل">
                                <i data-lucide="pencil" class="w-4 h-4 text-text-tertiary hover:text-text"></i>
                            </a>
                            @endif
                            @if ($canApprove && $office->approval_status !== 'approved')
                            <button wire:click="approve({{ $office->id }})" wire:confirm="هل أنت متأكد من اعتماد هذا المكتب؟" class="p-2 rounded-lg hover:bg-surface transition-colors" title="اعتماد">
                                <i data-lucide="check-circle" class="w-4 h-4 text-text-tertiary hover:text-success"></i>
                            </button>
                            @endif
                            @if ($canSuspend && $office->approval_status !== 'suspended')
                            <button wire:click="suspend({{ $office->id }})" wire:confirm="هل أنت متأكد من إيقاف هذا المكتب؟" class="p-2 rounded-lg hover:bg-surface transition-colors" title="إيقاف">
                                <i data-lucide="ban" class="w-4 h-4 text-text-tertiary hover:text-danger"></i>
                            </button>
                            @endif
                            @if ($canTogglePublic)
                            <button wire:click="togglePublic({{ $office->id }})" class="p-2 rounded-lg hover:bg-surface transition-colors" title="{{ $office->is_public ? 'إخفاء عن العامة' : 'إظهار للعامة' }}">
                                <i data-lucide="{{ $office->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4 text-text-tertiary hover:text-text"></i>
                            </button>
                            @endif
                            @if ($canDelete)
                            <button wire:click="confirmDelete({{ $office->id }})" class="p-2 rounded-lg hover:bg-surface transition-colors" title="حذف">
                                <i data-lucide="trash-2" class="w-4 h-4 text-text-tertiary hover:text-danger"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <x-ui.pagination :paginator="$offices" />
    </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-dropdown border border-border p-6 w-full max-w-sm">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-danger-light flex items-center justify-center mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text">حذف المكتب الهندسي</h3>
                <p class="text-sm text-text-tertiary mt-2">هل أنت متأكد من حذف هذا المكتب الهندسي؟ لا يمكن التراجع عن هذا الإجراء.</p>
                <div class="flex items-center gap-3 mt-6 w-full">
                    <button wire:click="delete" wire:loading.attr="disabled" class="flex-1 px-4 py-2.5 rounded-xl bg-danger text-white text-sm font-bold hover:bg-danger/90 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>تأكيد الحذف</span>
                        <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i></span>
                    </button>
                    <button wire:click="closeDeleteModal" class="flex-1 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-bold hover:bg-surface-secondary transition-colors">إلغاء</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>