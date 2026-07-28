<div>
    <x-slot name="title">الخدمات الإلكترونية</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الخدمات الإلكترونية</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة الخدمات الإلكترونية للبوابة</p>
        </div>
        @if ($canCreate)
        <a href="{{ route('dashboard.electronic-services.services.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة خدمة
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
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="relative md:col-span-2">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث عن خدمة..." class="w-full bg-surface-secondary border border-border rounded-xl pr-9 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="categoryId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع التصنيفات</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="departmentId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الدوائر</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    @foreach ($statusOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if ($services->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                <i data-lucide="laptop" class="w-7 h-7 text-text-muted"></i>
            </div>
            <p class="text-sm font-bold text-text">لا توجد خدمات</p>
            <p class="text-xs text-text-tertiary mt-1">أضف خدمة جديدة للبدء.</p>
        </div>
    @else
        <div class="bg-surface rounded-xl border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border bg-surface-secondary/50">
                            <th class="text-right px-4 py-3 text-xs font-bold text-text-tertiary">الاسم</th>
                            <th class="text-right px-4 py-3 text-xs font-bold text-text-tertiary">التصنيف</th>
                            <th class="text-right px-4 py-3 text-xs font-bold text-text-tertiary">الدائرة</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-text-tertiary">الحالة</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-text-tertiary">المشاهدات</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-text-tertiary">نقرات البوابة</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-text-tertiary">مميز</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-text-tertiary">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                        <tr wire:key="service-{{ $service->id }}" class="border-b border-border/50 hover:bg-surface-secondary/30 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('dashboard.electronic-services.services.show', $service) }}" wire:navigate class="text-sm font-bold text-text hover:text-primary transition-colors">
                                    {{ $service->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-xs text-text-secondary">{{ $service->category?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-text-secondary">{{ $service->department?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-success-light text-success',
                                        'draft' => 'bg-surface-secondary text-text-muted',
                                        'inactive' => 'bg-warning-light text-warning',
                                        'archived' => 'bg-danger-light text-danger',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $statusColors[$service->status] ?? 'bg-surface-secondary text-text-muted' }}">{{ $statusOptions[$service->status] ?? $service->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs font-semibold text-text">{{ number_format($service->views_count) }}</td>
                            <td class="px-4 py-3 text-center text-xs font-semibold text-text">{{ number_format($service->portal_clicks_count) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($service->is_featured)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-info-light text-info"><i data-lucide="star" class="w-3 h-3"></i></span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('dashboard.electronic-services.services.show', $service) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if ($canUpdate)
                                    <a href="{{ route('dashboard.electronic-services.services.edit', $service) }}" wire:navigate class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    @endif
                                    @if ($canTogglePublic)
                                    <button wire:click="togglePublic({{ $service->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $service->is_public ? 'warning' : 'success' }} transition-colors" title="{{ $service->is_public ? 'إخفاء' : 'إظهار' }}">
                                        <i data-lucide="{{ $service->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @if ($canToggleFeatured)
                                    <button wire:click="toggleFeatured({{ $service->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-{{ $service->is_featured ? 'warning' : 'info' }} transition-colors" title="{{ $service->is_featured ? 'إزالة التمييز' : 'تمييز' }}">
                                        <i data-lucide="star" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @if ($canDelete)
                                    <button wire:click="confirmDelete({{ $service->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($services->hasPages())
        <div class="mt-6">
            <x-ui.pagination :paginator="$services" />
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
                <h3 class="text-lg font-bold text-text mb-2">حذف الخدمة</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذه الخدمة؟</p>
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
