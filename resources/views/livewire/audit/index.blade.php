<div>
    <x-slot name="title">سجل المراجعة</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">سجل المراجعة</h1>
            <p class="text-sm text-text-tertiary mt-1">تتبع جميع العمليات والتغييرات في النظام</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="flex flex-col sm:flex-row gap-3 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بالاسم أو البريد الإلكتروني..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="action" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الإجراءات</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}">{{ $act }}</option>
                    @endforeach
                </select>
                <select wire:model.live="module" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الوحدات</option>
                    @foreach ($modules as $mod)
                        <option value="{{ $mod }}">{{ $mod }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" title="من تاريخ" />
                <input type="date" wire:model.live="dateTo" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" title="إلى تاريخ" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المستخدم</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراء</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الوحدة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الوصف</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">التاريخ</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($auditLogs as $log)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="audit-{{ $log->id }}">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $log->user_name ?? '—' }}</p>
                                <p class="text-[11px] text-text-tertiary">{{ $log->user_email ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($log->action === 'create') bg-success/10 text-success
                                    @elseif($log->action === 'update') bg-primary/10 text-primary
                                    @elseif($log->action === 'delete') bg-danger/10 text-danger
                                    @else bg-surface-secondary text-text-secondary @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-text-tertiary">{{ $log->module ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-text-tertiary line-clamp-2 max-w-[200px]">{{ $log->description ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($log->status === 'success') bg-success/10 text-success
                                    @elseif($log->status === 'failed') bg-danger/10 text-danger
                                    @else bg-surface-secondary text-text-secondary @endif">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-text-tertiary">
                                {{ $log->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="viewDetail({{ $log->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض التفاصيل">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="scroll" class="w-12 h-12 text-text-tertiary/40"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد سجلات مراجعة</p>
                                    <p class="text-xs text-text-tertiary">لم يتم تسجيل أي عمليات بعد.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($auditLogs->hasPages())
            <div class="p-4 border-t border-border">
                <x-ui.pagination :paginator="$auditLogs" />
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedLog)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDetailModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border sticky top-0 bg-surface rounded-t-2xl z-10">
                <h3 class="text-lg font-bold text-text">تفاصيل السجل</h3>
                <button wire:click="closeDetailModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">المستخدم</label>
                        <p class="text-sm font-semibold text-text">{{ $selectedLog['user_name'] ?? '—' }}</p>
                        <p class="text-xs text-text-tertiary">{{ $selectedLog['user_email'] ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">التاريخ</label>
                        <p class="text-sm text-text">{{ \Carbon\Carbon::parse($selectedLog['created_at'])->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الإجراء</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                            @if($selectedLog['action'] === 'create') bg-success/10 text-success
                            @elseif($selectedLog['action'] === 'update') bg-primary/10 text-primary
                            @elseif($selectedLog['action'] === 'delete') bg-danger/10 text-danger
                            @else bg-surface-secondary text-text-secondary @endif">
                            {{ $selectedLog['action'] }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الوحدة</label>
                        <p class="text-sm text-text">{{ $selectedLog['module'] ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الحالة</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                            @if($selectedLog['status'] === 'success') bg-success/10 text-success
                            @elseif($selectedLog['status'] === 'failed') bg-danger/10 text-danger
                            @else bg-surface-secondary text-text-secondary @endif">
                            {{ $selectedLog['status'] }}
                        </span>
                    </div>
                </div>

                @if ($selectedLog['description'] ?? null)
                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">الوصف</label>
                    <p class="text-sm text-text bg-surface-secondary rounded-xl p-3">{{ $selectedLog['description'] }}</p>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">ال METHOD</label>
                        <p class="text-sm text-text">{{ $selectedLog['method'] ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">عنوان IP</label>
                        <p class="text-sm text-text font-mono">{{ $selectedLog['ip_address'] ?? '—' }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">الرابط</label>
                    <p class="text-xs text-text font-mono break-all">{{ $selectedLog['url'] ?? '—' }}</p>
                </div>

                @if ($selectedLog['changed_fields'] ?? null)
                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">الحقول المتغيرة</label>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($selectedLog['changed_fields'] as $field)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-secondary text-[10px] font-semibold text-text-secondary">{{ $field }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if ($selectedLog['old_values'] ?? null)
                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">القيم القديمة</label>
                    <pre class="text-xs text-text bg-surface-secondary rounded-xl p-3 overflow-x-auto max-h-40">{{ json_encode($selectedLog['old_values'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
                @endif

                @if ($selectedLog['new_values'] ?? null)
                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">القيم الجديدة</label>
                    <pre class="text-xs text-text bg-surface-secondary rounded-xl p-3 overflow-x-auto max-h-40">{{ json_encode($selectedLog['new_values'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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
