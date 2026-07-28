<div>
    <x-slot name="title">أعضاء المجلس البلدي</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">أعضاء المجلس البلدي</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة أعضاء المجلس البلدي</p>
        </div>
        @if ($canCreate)
        <a href="{{ route('dashboard.municipality.council-members.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة عضو
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden mb-6">
        <div class="p-4 border-b border-border">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث عن عضو..." class="w-full bg-surface-secondary border border-border rounded-xl pr-9 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    @foreach ($statusOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="position" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع المناصب</option>
                    @foreach ($positionOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-text-tertiary flex items-center justify-end">
                    <span>إجمالي: {{ $members->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    @if ($members->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                <i data-lucide="users" class="w-7 h-7 text-text-muted"></i>
            </div>
            <p class="text-sm font-bold text-text">لا توجد أعضاء</p>
            <p class="text-xs text-text-tertiary mt-1">أضف عضواً جديداً للبدء.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($members as $member)
            <div wire:key="member-{{ $member->id }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-surface-secondary shrink-0 overflow-hidden flex items-center justify-center">
                        @if ($member->photo_url)
                            <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover" />
                        @else
                            <span class="text-lg font-bold text-text-tertiary">{{ mb_substr($member->full_name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('dashboard.municipality.council-members.show', $member) }}" wire:navigate class="text-sm font-bold text-text hover:text-primary transition-colors block truncate">
                            {{ $member->full_name }}
                        </a>
                        <p class="text-xs text-text-secondary mt-0.5">{{ $positionOptions[$member->position] ?? $member->position }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            @php
                                $statusColors = [
                                    'active' => 'bg-success-light text-success',
                                    'inactive' => 'bg-warning-light text-warning',
                                    'former' => 'bg-surface-secondary text-text-muted',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $statusColors[$member->status] ?? 'bg-surface-secondary text-text-muted' }}">{{ $statusOptions[$member->status] ?? $member->status }}</span>
                            @if ($member->is_featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-info-light text-info">مميز</span>
                            @endif
                            @if ($member->is_public)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-surface-secondary text-text-muted">عام</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-4 pt-3 border-t border-border">
                    <a href="{{ route('dashboard.municipality.council-members.show', $member) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </a>
                    @if ($canUpdate)
                    <a href="{{ route('dashboard.municipality.council-members.edit', $member) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </a>
                    @endif
                    @if ($canTogglePublic)
                    <button wire:click="togglePublic({{ $member->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $member->is_public ? 'warning' : 'success' }} transition-colors" title="{{ $member->is_public ? 'إخفاء عن العامة' : 'ظهور للعامة' }}">
                        <i data-lucide="{{ $member->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                    </button>
                    @endif
                    @if ($canToggleFeatured)
                    <button wire:click="toggleFeatured({{ $member->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $member->is_featured ? 'warning' : 'info' }} transition-colors" title="{{ $member->is_featured ? 'إزالة المميز' : 'تمييز' }}">
                        <i data-lucide="star" class="w-4 h-4"></i>
                    </button>
                    @endif
                    @if ($canDelete)
                    <button wire:click="confirmDelete({{ $member->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if ($members->hasPages())
        <div class="mt-6">
            <x-ui.pagination :paginator="$members" />
        </div>
        @endif
    @endif

    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف العضو</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا العضو؟</p>
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
