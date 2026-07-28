<div>
    <x-slot name="title">{{ $councilDecision->decision_number }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $councilDecision->decision_number }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $councilDecision->title }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.municipality.council-decisions') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                عودة
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">تفاصيل القرار</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-text-tertiary uppercase tracking-wide">العنوان</label>
                        <p class="text-sm font-semibold text-text mt-1">{{ $councilDecision->title }}</p>
                    </div>
                    @if ($councilDecision->summary)
                    <div>
                        <label class="text-xs font-bold text-text-tertiary uppercase tracking-wide">الملخص</label>
                        <p class="text-sm text-text-secondary mt-1">{{ $councilDecision->summary }}</p>
                    </div>
                    @endif
                    @if ($councilDecision->content)
                    <div>
                        <label class="text-xs font-bold text-text-tertiary uppercase tracking-wide">المحتوى</label>
                        <div class="text-sm text-text-secondary mt-1 whitespace-pre-wrap">{{ $councilDecision->content }}</div>
                    </div>
                    @endif
                    @if ($councilDecision->attachment_path)
                    <div>
                        <label class="text-xs font-bold text-text-tertiary uppercase tracking-wide">المرفق</label>
                        <div class="mt-1">
                            <a href="{{ asset('storage/' . $councilDecision->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                                <i data-lucide="file-down" class="w-4 h-4"></i>
                                تحميل المرفق
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">معلومات</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">رقم القرار</span>
                        <span class="text-sm font-bold text-text">{{ $councilDecision->decision_number }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">النوع</span>
                        <span class="text-sm text-text-secondary">{{ \App\Domains\Municipality\Enums\CouncilDecisionType::tryFrom($councilDecision->type)?->label() ?? $councilDecision->type }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">الحالة</span>
                        @php
                            $statusColors = [
                                'draft' => 'bg-warning-light text-warning',
                                'published' => 'bg-success-light text-success',
                                'archived' => 'bg-surface-secondary text-text-muted',
                                'cancelled' => 'bg-danger-light text-danger',
                            ];
                            $color = $statusColors[$councilDecision->status] ?? 'bg-surface-secondary text-text-muted';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $color }}">{{ \App\Domains\Municipality\Enums\CouncilDecisionStatus::tryFrom($councilDecision->status)?->label() ?? $councilDecision->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">تاريخ القرار</span>
                        <span class="text-sm text-text-secondary">{{ $councilDecision->decision_date ? \Carbon\Carbon::parse($councilDecision->decision_date)->format('Y-m-d') : '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">رقم الجلسة</span>
                        <span class="text-sm text-text-secondary">{{ $councilDecision->session_number ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">عام</span>
                        <span class="text-sm text-text-secondary">{{ $councilDecision->is_public ? 'نعم' : 'لا' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">ترتيب العرض</span>
                        <span class="text-sm text-text-secondary">{{ $councilDecision->sort_order }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">إجراءات</h2>
                <div class="space-y-2">
                    @if ($canUpdate)
                    <a href="{{ route('dashboard.municipality.council-decisions.edit', $councilDecision) }}" wire:navigate class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        تعديل
                    </a>
                    @endif
                    @if ($councilDecision->status === 'draft' && $canPublish)
                    <button wire:click="publish" wire:confirm="هل أنت متأكد من نشر هذا القرار؟" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-success text-white text-sm font-semibold hover:bg-success/90 transition-colors">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        نشر
                    </button>
                    @endif
                    @if (in_array($councilDecision->status, ['draft', 'published']) && $canArchive)
                    <button wire:click="archive" wire:confirm="هل أنت متأكد من أرشفة هذا القرار؟" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        أرشفة
                    </button>
                    @endif
                    @if (!in_array($councilDecision->status, ['cancelled', 'archived']) && $canCancel)
                    <button wire:click="cancel" wire:confirm="هل أنت متأكد من إلغاء هذا القرار؟" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        إلغاء
                    </button>
                    @endif
                    @if ($canDelete)
                    <button wire:click="confirmDelete" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-danger/30 text-danger text-sm font-semibold hover:bg-danger-light transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        حذف
                    </button>
                    @endif
                </div>
            </div>
        </div>
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
                <button wire:click="delete" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">
                    نعم، حذف
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
