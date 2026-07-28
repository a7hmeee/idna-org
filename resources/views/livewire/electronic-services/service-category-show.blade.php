<div>
    <x-slot name="title">{{ $category->name }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $category->name }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $category->description }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.electronic-services.categories') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                العودة
            </a>
            @if ($canUpdate)
            <a href="{{ route('dashboard.electronic-services.categories.edit', $category) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                تعديل
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="info" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">معلومات التصنيف</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-text-tertiary">الاسم</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $category->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">الرابط المختصر</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $category->slug }}</p>
                    </div>
                    @if ($category->parent)
                    <div>
                        <p class="text-xs text-text-tertiary">التصنيف الأب</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $category->parent->name }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-text-tertiary">عدد الخدمات</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $category->services_count }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">ترتيب الظهور</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $category->sort_order }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">تاريخ الإنشاء</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $category->created_at?->format('Y-m-d') }}</p>
                    </div>
                </div>
                @if ($category->description)
                <div class="mt-4">
                    <p class="text-xs text-text-tertiary">الوصف</p>
                    <p class="text-sm text-text mt-1">{{ $category->description }}</p>
                </div>
                @endif
            </div>

            {{-- Services in this category --}}
            <div class="bg-surface rounded-xl border border-border">
                <div class="p-6 border-b border-border">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                                <i data-lucide="layers" class="w-4 h-4 text-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-text">الخدمات المرتبطة</h2>
                                <p class="text-xs text-text-tertiary mt-0.5">{{ $services->count() }} خدمة</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.electronic-services.services.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            إضافة خدمة
                        </a>
                    </div>
                </div>

                @if ($services->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="layers" class="w-7 h-7 text-text-muted"></i>
                    </div>
                    <p class="text-sm font-bold text-text">لا توجد خدمات في هذا التصنيف</p>
                    <p class="text-xs text-text-tertiary mt-1">ابدأ بإضافة خدمة جديدة لهذا التصنيف</p>
                </div>
                @else
                <div class="divide-y divide-border">
                    @foreach ($services as $service)
                    <a href="{{ route('dashboard.electronic-services.services.show', $service) }}" wire:navigate class="flex items-center gap-4 p-4 hover:bg-surface-secondary transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                            <i data-lucide="laptop" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-text truncate">{{ $service->name }}</p>
                                @if ($service->is_featured)
                                <i data-lucide="star" class="w-3.5 h-3.5 text-warning shrink-0"></i>
                                @endif
                            </div>
                            <p class="text-xs text-text-tertiary mt-0.5 truncate">{{ $service->summary ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            @if ($service->department)
                            <span class="text-[10px] font-semibold text-text-tertiary bg-surface-secondary px-2 py-0.5 rounded-md">{{ $service->department->name }}</span>
                            @endif
                            @php
                                $statusBadge = match($service->status) {
                                    'active' => 'bg-success-light text-success',
                                    'draft' => 'bg-surface-secondary text-text-muted',
                                    'inactive' => 'bg-warning-light text-warning',
                                    default => 'bg-surface-secondary text-text-muted',
                                };
                                $statusLabel = match($service->status) {
                                    'active' => 'نشط',
                                    'draft' => 'مسودة',
                                    'inactive' => 'غير نشط',
                                    default => $service->status,
                                };
                            @endphp
                            <span class="text-[10px] font-bold {{ $statusBadge }} px-2 py-0.5 rounded-md">{{ $statusLabel }}</span>
                            <i data-lucide="chevron-left" class="w-4 h-4 text-text-muted"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
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
                        'active' => 'bg-success-light text-success',
                        'inactive' => 'bg-warning-light text-warning',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold {{ $statusColors[$category->status] ?? 'bg-surface-secondary text-text-muted' }}">
                    {{ App\Domains\ElectronicServices\Enums\ServiceCategoryStatus::tryFrom($category->status)?->label() ?? $category->status }}
                </span>
                @if ($category->is_public)
                <p class="text-xs text-success mt-2 flex items-center gap-1">
                    <i data-lucide="eye" class="w-3 h-3"></i>
                    مرئي للعامة
                </p>
                @else
                <p class="text-xs text-warning mt-2 flex items-center gap-1">
                    <i data-lucide="eye-off" class="w-3 h-3"></i>
                    مخفي عن العامة
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
