<div>
    <x-slot name="title">الإحصائيات</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الإحصائيات</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة الأرقام والإحصائيات المعروضة في الصفحة الرئيسية</p>
        </div>
        @can('createStatistic', \App\Domains\Homepage\Models\HomepageSetting::class)
            <a href="{{ route('dashboard.homepage.statistics.create') }}" class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>إضافة إحصائية</span>
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
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">الكل</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">التسمية</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">القيمة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">اللاحقة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($statistics as $stat)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $stat->label }}</p>
                                @if ($stat->description)
                                    <p class="text-xs text-text-tertiary mt-0.5">{{ Str::limit($stat->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-lg text-primary">{{ $stat->value }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary">{{ $stat->suffix ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary">{{ $stat->sort_order }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @can('updateStatistic', \App\Domains\Homepage\Models\HomepageSetting::class)
                                    <button wire:click="toggle({{ $stat->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors @if($stat->is_active) bg-success/10 text-success hover:bg-success/20 @else bg-danger/10 text-danger hover:bg-danger/20 @endif">
                                        <i data-lucide="{{ $stat->is_active ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                                        {{ $stat->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold @if($stat->is_active) bg-success/10 text-success @else bg-danger/10 text-danger @endif">
                                        {{ $stat->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                @endcan
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @can('updateStatistic', \App\Domains\Homepage\Models\HomepageSetting::class)
                                        <a href="{{ route('dashboard.homepage.statistics.edit', $stat->id) }}" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" wire:navigate>
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endcan
                                    @can('deleteStatistic', \App\Domains\Homepage\Models\HomepageSetting::class)
                                        <button wire:click="confirmDelete({{ $stat->id }})" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="bar-chart-3" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد إحصائيات بعد</p>
                                    @can('createStatistic', \App\Domains\Homepage\Models\HomepageSetting::class)
                                        <a href="{{ route('dashboard.homepage.statistics.create') }}" class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة إحصائية جديدة</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($statistics->hasPages())
            <div class="p-4 border-t border-border">
                {{ $statistics->links() }}
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
                <p class="text-sm text-text-secondary mb-6">هل أنت متأكد من حذف هذه الإحصائية؟</p>
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
