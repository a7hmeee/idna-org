<div>
    <x-slot name="title">قرارات المجلس البلدي</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">قرارات المجلس البلدي</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة وتتبع قرارات المجلس البلدي</p>
        </div>
        @if ($canCreate)
        <a href="{{ route('dashboard.municipality.council-decisions.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة قرار
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث عن قرار..." class="w-full bg-surface-secondary border border-border rounded-xl pr-9 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    @foreach ($statusOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="type" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الأنواع</option>
                    @foreach ($typeOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-text-tertiary flex items-center justify-end">
                    <span>إجمالي: {{ $decisions->total() }}</span>
                </div>
            </div>
        </div>

        @if ($decisions->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="file-text" class="w-7 h-7 text-text-muted"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد قرارات</p>
                <p class="text-xs text-text-tertiary mt-1">أضف قراراً جديداً للبدء.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border bg-background/50">
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">رقم القرار</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">العنوان</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">النوع</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الحالة</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">التاريخ</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($decisions as $decision)
                        <tr wire:key="decision-{{ $decision->id }}" class="border-b border-border last:border-0 hover:bg-background/30 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.municipality.council-decisions.show', $decision) }}" wire:navigate class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                                    {{ $decision->decision_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="min-w-0 max-w-[250px]">
                                    <p class="text-sm font-semibold text-text truncate">{{ $decision->title }}</p>
                                    @if ($decision->summary)
                                    <p class="text-[11px] text-text-tertiary truncate mt-0.5">{{ $decision->summary }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-secondary text-[11px] font-semibold text-text-secondary whitespace-nowrap">{{ $typeOptions[$decision->type] ?? $decision->type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-warning-light text-warning',
                                        'published' => 'bg-success-light text-success',
                                        'archived' => 'bg-surface-secondary text-text-muted',
                                        'cancelled' => 'bg-danger-light text-danger',
                                    ];
                                    $color = $statusColors[$decision->status] ?? 'bg-surface-secondary text-text-muted';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $color }} whitespace-nowrap">{{ $statusOptions[$decision->status] ?? $decision->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-secondary whitespace-nowrap">
                                {{ $decision->decision_date ? \Carbon\Carbon::parse($decision->decision_date)->format('Y-m-d') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('dashboard.municipality.council-decisions.show', $decision) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </a>
                                    @if ($canUpdate)
                                    <a href="{{ route('dashboard.municipality.council-decisions.edit', $decision) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    @endif
                                    @if ($canDelete)
                                    <button wire:click="confirmDelete({{ $decision->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @if ($decision->status === 'draft' && $canPublish)
                                    <button wire:click="publish({{ $decision->id }})" wire:confirm="هل أنت متأكد من نشر هذا القرار؟" class="p-1.5 rounded-lg hover:bg-success-light text-text-tertiary hover:text-success transition-colors" title="نشر">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @if (in_array($decision->status, ['draft', 'published']) && $canArchive)
                                    <button wire:click="archive({{ $decision->id }})" wire:confirm="هل أنت متأكد من أرشفة هذا القرار؟" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-text-muted transition-colors" title="أرشفة">
                                        <i data-lucide="info" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @if (!in_array($decision->status, ['cancelled', 'archived']) && $canCancel)
                                    <button wire:click="cancel({{ $decision->id }})" wire:confirm="هل أنت متأكد من إلغاء هذا القرار؟" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="إلغاء">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($decisions->hasPages())
            <div class="px-6 py-4 border-t border-border">
                <x-ui.pagination :paginator="$decisions" />
            </div>
            @endif
        @endif
    </div>

    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف القرار</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا القرار؟</p>
                <p class="text-xs text-text-muted mt-2">لا يمكن التراجع عن هذه العملية.</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="delete" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>نعم، حذف</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
