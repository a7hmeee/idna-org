<div>
    <x-slot name="title">مركز الإشعارات</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">مركز الإشعارات</h1>
            <p class="text-sm text-text-tertiary mt-1">
                إدارة إشعاراتك
                @if ($unreadCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-danger text-white text-xs font-bold ms-2">{{ $unreadCount }}</span>
                @endif
            </p>
        </div>
        @if ($unreadCount > 0)
        <button wire:click="markAllAsRead" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
            <i data-lucide="check-check" class="w-4 h-4"></i>
            تحديد الكل كمقروء
        </button>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        @forelse ($notifications as $notification)
            <div class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors {{ $notification->is_read ? '' : 'bg-primary/5' }}" wire:key="notification-{{ $notification->id }}">
                <div class="flex items-start gap-4 p-4">
                    <div class="shrink-0 mt-0.5">
                        @if (! $notification->is_read)
                            <span class="block w-2.5 h-2.5 rounded-full bg-primary"></span>
                        @else
                            <span class="block w-2.5 h-2.5 rounded-full bg-surface-secondary"></span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-text {{ $notification->is_read ? '' : 'text-primary' }}">{{ $notification->title }}</p>
                                <p class="text-xs text-text-tertiary mt-0.5">{{ $notification->message }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if (! $notification->is_read)
                                <button wire:click="markAsRead({{ $notification->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تحديد كمقروء">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                                @endif
                                <button wire:click="viewDetail({{ $notification->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض التفاصيل">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-2">
                            @if ($notification->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-secondary text-[10px] font-semibold text-text-secondary">{{ $notification->category }}</span>
                            @endif
                            @if ($notification->type)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-primary/10 text-[10px] font-semibold text-primary">{{ $notification->type }}</span>
                            @endif
                            <span class="text-[11px] text-text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if ($notification->action_url && $notification->action_label)
                            <a href="{{ $notification->action_url }}" class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-primary hover:underline" wire:navigate>
                                {{ $notification->action_label }}
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-4 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                    <i data-lucide="bell-off" class="w-12 h-12 text-text-tertiary/40"></i>
                    <p class="text-sm text-text-tertiary">لا توجد إشعارات</p>
                    <p class="text-xs text-text-tertiary">ستظهر الإشعارات الجديدة هنا.</p>
                </div>
            </div>
        @endforelse

        @if ($notifications->hasPages())
            <div class="p-4 border-t border-border">
                <x-ui.pagination :paginator="$notifications" />
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedNotification)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDetailModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">تفاصيل الإشعار</h3>
                <button wire:click="closeDetailModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">العنوان</label>
                    <p class="text-sm font-semibold text-text">{{ $selectedNotification->title }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">الرسالة</label>
                    <p class="text-sm text-text bg-surface-secondary rounded-xl p-3">{{ $selectedNotification->message }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">النوع</label>
                        <p class="text-sm text-text">{{ $selectedNotification->type ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الفئة</label>
                        <p class="text-sm text-text">{{ $selectedNotification->category ?? '—' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الحالة</label>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                            @if($selectedNotification->is_read) bg-surface-secondary text-text-tertiary @else bg-primary/10 text-primary @endif">
                            @if($selectedNotification->is_read) مقروء @else جديد @endif
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">التاريخ</label>
                        <p class="text-sm text-text">{{ $selectedNotification->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if ($selectedNotification->action_url && $selectedNotification->action_label)
                    <div>
                        <a href="{{ $selectedNotification->action_url }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:navigate>
                            {{ $selectedNotification->action_label }}
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-end gap-3 px-5 pb-5">
                <button wire:click="closeDetailModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إغلاق</button>
            </div>
        </div>
    </div>
    @endif
</div>
