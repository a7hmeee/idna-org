<div>
    <x-slot name="title">إدارة كاروسيل الصفحات</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إدارة كاروسيل الصفحات</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة جميع الشرائح المعروضة في صفحات الموقع الداخلية</p>
        </div>
        @can('createSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
            <a href="{{ route('dashboard.page-carousels.create') }}"
               class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>إضافة شريحة</span>
            </a>
        @endcan
    </div>

    @if (session('debug'))
        <div class="mb-4 rounded-xl bg-warning-light border border-warning/20 px-4 py-3">
            <span class="text-sm text-warning font-medium">{{ session('debug') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                <div class="flex-1 w-full">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">الكل</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
                <span class="text-xs text-text-tertiary bg-surface-secondary px-3 py-1.5 rounded-lg whitespace-nowrap">
                    <span class="font-semibold">{{ $slides->total() }}</span> شريحة
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الصورة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">العنوان</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الصفحة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الجدولة</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($slides as $slide)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors">
                            <td class="px-4 py-3">
                                @if ($slide->image_url)
                                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-16 h-10 rounded-lg object-cover border border-border">
                                @else
                                    <div class="w-16 h-10 rounded-lg bg-surface-secondary border border-border flex items-center justify-center">
                                        <i data-lucide="image-off" class="w-4 h-4 text-text-tertiary"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $slide->title }}</p>
                                @if ($slide->description)
                                    <p class="text-xs text-text-tertiary mt-0.5 line-clamp-1">{{ $slide->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($slide->page_key)
                                    <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium whitespace-nowrap">{{ $slide->page_label }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary">{{ $slide->sort_order }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @can('updateSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                                    <button wire:click="toggle({{ $slide->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors @if($slide->is_active) bg-success/10 text-success hover:bg-success/20 @else bg-danger/10 text-danger hover:bg-danger/20 @endif">
                                        <i data-lucide="{{ $slide->is_active ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                                        {{ $slide->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold @if($slide->is_active) bg-success/10 text-success @else bg-danger/10 text-danger @endif">
                                        {{ $slide->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                @endcan
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $now = now();
                                    $starts = $slide->starts_at;
                                    $ends = $slide->ends_at;
                                    $isScheduled = $starts && $starts->isFuture();
                                    $isExpired = $ends && $ends->isPast();
                                @endphp
                                @if ($isScheduled)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-warning/10 text-warning">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        مجدول
                                    </span>
                                @elseif ($isExpired)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-danger/10 text-danger">
                                        <i data-lucide="calendar-x" class="w-3 h-3"></i>
                                        منتهي
                                    </span>
                                @else
                                    <span class="text-xs text-text-tertiary">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @can('updateSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                                        <a href="{{ route('dashboard.page-carousels.edit', $slide->id) }}" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" wire:navigate>
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endcan
                                    @can('deleteSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                                        <button wire:click="confirmDelete({{ $slide->id }})" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="images" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد شرائح بعد</p>
                                    @can('createSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                                        <a href="{{ route('dashboard.page-carousels.create') }}"
                                           class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة شريحة جديدة</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($slides->hasPages())
            <div class="p-4 border-t border-border">
                {{ $slides->links() }}
            </div>
        @endif
    </div>

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
                <p class="text-sm text-text-secondary mb-6">هل أنت متأكد من حذف هذه الشريحة؟</p>
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
</div>
