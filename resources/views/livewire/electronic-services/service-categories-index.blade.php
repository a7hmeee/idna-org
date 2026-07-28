<div>
    <x-slot name="title">تصنيفات الخدمات</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">تصنيفات الخدمات</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة تصنيفات الخدمات الإلكترونية</p>
        </div>
        @if ($canCreate)
        <a href="{{ route('dashboard.electronic-services.categories.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة تصنيف
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث عن تصنيف..." class="w-full bg-surface-secondary border border-border rounded-xl pr-9 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    @foreach ($statusOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-text-tertiary flex items-center justify-end">
                    <span>إجمالي: {{ $categories->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    @if ($categories->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                <i data-lucide="folder-tree" class="w-7 h-7 text-text-muted"></i>
            </div>
            <p class="text-sm font-bold text-text">لا توجد تصنيفات</p>
            <p class="text-xs text-text-tertiary mt-1">أضف تصنيفاً جديداً للبدء.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($categories as $category)
            <div wire:key="category-{{ $category->id }}" class="bg-surface rounded-xl border border-border overflow-hidden hover:shadow-elevated transition-all group">
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        @if ($category->icon)
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $category->icon }}" class="w-5 h-5 text-primary"></i>
                        </div>
                        @else
                        <div class="w-10 h-10 rounded-xl bg-surface-secondary flex items-center justify-center shrink-0">
                            <i data-lucide="folder" class="w-5 h-5 text-text-muted"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('dashboard.electronic-services.categories.show', $category) }}" wire:navigate class="text-sm font-bold text-text hover:text-primary transition-colors block truncate">
                                {{ $category->name }}
                            </a>
                            @if ($category->parent)
                            <p class="text-xs text-text-tertiary mt-0.5">تصنيف فرعي: {{ $category->parent->name }}</p>
                            @endif
                            <p class="text-xs text-text-secondary mt-1">
                                <span class="font-semibold">{{ $category->services_count ?? 0 }}</span> خدمات
                            </p>
                        </div>
                    </div>
                    @if ($category->description)
                    <p class="text-xs text-text-tertiary mt-2 line-clamp-2">{{ $category->description }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
                        <div>
                            @php
                                $statusColors = [
                                    'active' => 'bg-success-light text-success',
                                    'inactive' => 'bg-warning-light text-warning',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $statusColors[$category->status] ?? 'bg-surface-secondary text-text-muted' }}">{{ $statusOptions[$category->status] ?? $category->status }}</span>
                            @if (!$category->is_public)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-warning-light text-warning mr-1">مخفي</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('dashboard.electronic-services.categories.show', $category) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            @if ($canUpdate)
                            <a href="{{ route('dashboard.electronic-services.categories.edit', $category) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            @endif
                            @if ($canTogglePublic)
                            <button wire:click="togglePublic({{ $category->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $category->is_public ? 'warning' : 'success' }} transition-colors" title="{{ $category->is_public ? 'إخفاء عن العامة' : 'ظهور للعامة' }}">
                                <i data-lucide="{{ $category->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                            </button>
                            @endif
                            @if ($canDelete)
                            <button wire:click="confirmDelete({{ $category->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if ($categories->hasPages())
        <div class="mt-6">
            <x-ui.pagination :paginator="$categories" />
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
                <h3 class="text-lg font-bold text-text mb-2">حذف التصنيف</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا التصنيف؟</p>
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
