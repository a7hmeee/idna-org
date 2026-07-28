<div>
    <x-slot name="title">الدوائر والأقسام</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الدوائر والأقسام</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة الدوائر والأقسام البلدية</p>
        </div>
        @if ($canCreate)
        <a href="{{ route('dashboard.departments.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة دائرة
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث عن دائرة..." class="w-full bg-surface-secondary border border-border rounded-xl pr-9 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    @foreach ($statusOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-text-tertiary flex items-center justify-end">
                    <span>إجمالي: {{ $departments->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    @if ($departments->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                <i data-lucide="building-2" class="w-7 h-7 text-text-muted"></i>
            </div>
            <p class="text-sm font-bold text-text">لا توجد دوائر</p>
            <p class="text-xs text-text-tertiary mt-1">أضف دائرة جديدة للبدء.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($departments as $department)
            <div wire:key="department-{{ $department->id }}" class="bg-surface rounded-xl border border-border overflow-hidden hover:shadow-elevated transition-all group">
                {{-- Cover Image --}}
                <div class="h-28 bg-surface-secondary relative overflow-hidden">
                    @if ($department->cover_image_url)
                        <img src="{{ $department->cover_image_url }}" alt="{{ $department->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="building-2" class="w-10 h-10 text-text-muted/40"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 left-2 flex items-center gap-1">
                        @if ($department->is_featured)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-info-light text-info">مميز</span>
                        @endif
                        @if ($department->is_public)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-surface/80 text-text-muted">عام</span>
                        @endif
                    </div>
                </div>
                {{-- Content --}}
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        @if ($department->icon)
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $department->icon }}" class="w-5 h-5 text-primary"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('dashboard.departments.show', $department) }}" wire:navigate class="text-sm font-bold text-text hover:text-primary transition-colors block truncate">
                                {{ $department->name }}
                            </a>
                            @if ($department->manager_name)
                            <p class="text-xs text-text-secondary mt-0.5">{{ $department->manager_name }}</p>
                            @endif
                            @if ($department->phone)
                            <p class="text-xs text-text-tertiary mt-1" dir="ltr">{{ $department->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
                        <div>
                            @php
                                $statusColors = [
                                    'active' => 'bg-success-light text-success',
                                    'inactive' => 'bg-warning-light text-warning',
                                    'maintenance' => 'bg-info-light text-info',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $statusColors[$department->status] ?? 'bg-surface-secondary text-text-muted' }}">{{ $statusOptions[$department->status] ?? $department->status }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('dashboard.departments.show', $department) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            @if ($canUpdate)
                            <a href="{{ route('dashboard.departments.edit', $department) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            @endif
                            @if ($canTogglePublic)
                            <button wire:click="togglePublic({{ $department->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $department->is_public ? 'warning' : 'success' }} transition-colors" title="{{ $department->is_public ? 'إخفاء عن العامة' : 'ظهور للعامة' }}">
                                <i data-lucide="{{ $department->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                            </button>
                            @endif
                            @if ($canToggleFeatured)
                            <button wire:click="toggleFeatured({{ $department->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $department->is_featured ? 'warning' : 'info' }} transition-colors" title="{{ $department->is_featured ? 'إزالة التمييز' : 'تمييز' }}">
                                <i data-lucide="star" class="w-4 h-4"></i>
                            </button>
                            @endif
                            @if ($canDelete)
                            <button wire:click="confirmDelete({{ $department->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if ($departments->hasPages())
        <div class="mt-6">
            <x-ui.pagination :paginator="$departments" />
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
                <h3 class="text-lg font-bold text-text mb-2">حذف الدائرة</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذه الدائرة؟</p>
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
