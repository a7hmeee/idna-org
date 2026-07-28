<div>
    <x-slot name="title">مناطق المياه</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">مناطق المياه</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة مناطق توزيع المياه</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.water-schedule') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
                <i data-lucide="droplets" class="w-4 h-4"></i>
                <span>جدول الضخ</span>
            </a>
            @can('create', \App\Domains\WaterSchedule\Models\WaterArea::class)
                <a href="{{ route('dashboard.water-schedule.areas.create') }}" class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>إضافة منطقة</span>
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الاسم</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الوصف</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($areas as $area)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="area-{{ $area->id }}">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $area->name }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-text-tertiary">{{ $area->description ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary">{{ $area->display_order }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @can('update', \App\Domains\WaterSchedule\Models\WaterArea::class)
                                    <button wire:click="toggleActive({{ $area->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors @if($area->is_active) bg-success/10 text-success hover:bg-success/20 @else bg-danger/10 text-danger hover:bg-danger/20 @endif">
                                        <i data-lucide="{{ $area->is_active ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                                        {{ $area->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold @if($area->is_active) bg-success/10 text-success @else bg-danger/10 text-danger @endif">
                                        {{ $area->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                @endcan
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', \App\Domains\WaterSchedule\Models\WaterArea::class)
                                        <a href="{{ route('dashboard.water-schedule.areas.edit', $area->id) }}" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" wire:navigate>
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endcan
                                    @can('delete', \App\Domains\WaterSchedule\Models\WaterArea::class)
                                        <button wire:click="confirmDelete({{ $area->id }})" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="map-pin" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد مناطق بعد</p>
                                    @can('create', \App\Domains\WaterSchedule\Models\WaterArea::class)
                                        <a href="{{ route('dashboard.water-schedule.areas.create') }}" class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة منطقة جديدة</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($areas->hasPages())
            <div class="p-4 border-t border-border">
                {{ $areas->links() }}
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
                <p class="text-sm text-text-secondary mb-6">هل أنت متأكد من حذف هذه المنطقة؟</p>
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
