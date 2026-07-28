<div>
    <x-slot name="title">الوظائف</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الوظائف</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة وظائف البلدية</p>
        </div>
        @can('create', \App\Domains\Jobs\Models\Job::class)
            <a href="{{ route('dashboard.jobs.create') }}" class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>إضافة وظيفة</span>
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
                    <option value="draft">مسودة</option>
                    <option value="published">منشور</option>
                    <option value="closed">مغلق</option>
                    <option value="archived">مؤرشف</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">العنوان</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الدائرة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">النوع</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">مشاهدات</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">مميز</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="job-{{ $job->id }}">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $job->title }}</p>
                                @if ($job->job_number)
                                    <span class="text-xs text-text-tertiary">#{{ $job->job_number }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-secondary">{{ $job->department?->name ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-text-tertiary">{{ $job->employment_type->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($job->status->value === 'published') bg-success/10 text-success
                                    @elseif($job->status->value === 'draft') bg-warning/10 text-warning
                                    @elseif($job->status->value === 'closed') bg-danger/10 text-danger
                                    @else bg-municipal-50 text-text-tertiary @endif">
                                    {{ $job->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm text-text-tertiary">{{ number_format($job->views_count) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @can('update', \App\Domains\Jobs\Models\Job::class)
                                    <button wire:click="toggleFeatured({{ $job->id }})" class="transition-colors @if($job->is_featured) text-yellow-500 @else text-text-tertiary hover:text-yellow-500 @endif">
                                        <i data-lucide="star" class="w-4 h-4"></i>
                                    </button>
                                @else
                                    <span class="text-sm @if($job->is_featured) text-yellow-500 @else text-text-tertiary @endif">
                                        <i data-lucide="{{ $job->is_featured ? 'star' : 'star' }}" class="w-4 h-4"></i>
                                    </span>
                                @endcan
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($job->status->value === 'draft')
                                        @can('publish', \App\Domains\Jobs\Models\Job::class)
                                            <button wire:click="publish({{ $job->id }})" class="p-2 rounded-lg hover:bg-success/10 text-text-tertiary hover:text-success transition-all" title="نشر">
                                                <i data-lucide="send" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @endif
                                    @if ($job->status->value === 'published')
                                        @can('update', \App\Domains\Jobs\Models\Job::class)
                                            <button wire:click="close({{ $job->id }})" class="p-2 rounded-lg hover:bg-warning/10 text-text-tertiary hover:text-warning transition-all" title="إغلاق">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @endif
                                    @can('update', \App\Domains\Jobs\Models\Job::class)
                                        <a href="{{ route('dashboard.jobs.edit', $job->id) }}" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" wire:navigate title="تعديل">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endcan
                                    @can('archive', \App\Domains\Jobs\Models\Job::class)
                                        <button wire:click="archive({{ $job->id }})" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-text-secondary transition-all" title="أرشفة">
                                            <i data-lucide="archive" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                    @can('delete', \App\Domains\Jobs\Models\Job::class)
                                        <button wire:click="confirmDelete({{ $job->id }})" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all" title="حذف">
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
                                    <i data-lucide="briefcase" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد وظائف بعد</p>
                                    @can('create', \App\Domains\Jobs\Models\Job::class)
                                        <a href="{{ route('dashboard.jobs.create') }}" class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة وظيفة جديدة</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jobs->hasPages())
            <div class="p-4 border-t border-border">
                {{ $jobs->links() }}
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
                <p class="text-sm text-text-secondary mb-6">هل أنت متأكد من حذف هذه الوظيفة؟</p>
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
