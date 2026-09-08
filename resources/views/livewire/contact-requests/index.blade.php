<div>
    <x-slot name="title">طلبات الاتصال</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">طلبات الاتصال</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة طلبات الاتصال من المواطنين</p>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بالاسم أو البريد أو رقم الهاتف..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    <option value="pending">قيد الانتظار</option>
                    <option value="resolved">تم الحل</option>
                    <option value="closed">مغلق</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">رقم التتبع</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الاسم</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">جهة الاتصال</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الرسالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">المصدر</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">التاريخ</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="request-{{ $request->id }}">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-primary font-semibold">{{ $request->tracking_number }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $request->name }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-text">{{ $request->phone }}</p>
                                @if ($request->email)
                                    <p class="text-xs text-text-tertiary">{{ $request->email }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-text-tertiary line-clamp-2 max-w-[200px]">{{ $request->message }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-text-tertiary">{{ $request->source }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($request->status->value === 'resolved') bg-success/10 text-success
                                    @elseif($request->status->value === 'closed') bg-surface-secondary text-text-tertiary
                                    @else bg-warning/10 text-warning @endif">
                                    @if($request->status->value === 'pending') قيد الانتظار
                                    @elseif($request->status->value === 'resolved') تم الحل
                                    @else مغلق @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-text-tertiary">
                                {{ $request->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="viewDetail({{ $request->id }})" class="p-1.5 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-colors" title="عرض التفاصيل">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    @if ($request->status->value === 'pending')
                                        @can('resolve', \App\Domains\ContactRequests\Models\ContactRequest::class)
                                            <button wire:click="markResolved({{ $request->id }})" wire:confirm="هل أنت متأكد من تحديد هذا الطلب كمحلول؟" class="p-1.5 rounded-lg hover:bg-success/10 text-text-tertiary hover:text-success transition-colors" title="تحديد كمحلول">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="inbox" class="w-12 h-12 text-text-tertiary/40"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد طلبات اتصال</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($requests->hasPages())
            <div class="p-4 border-t border-border">
                <x-ui.pagination :paginator="$requests" />
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedRequest)
        <x-ui.modal wire:model.live="showDetailModal" title="تفاصيل طلب الاتصال" maxWidth="2xl">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">رقم التتبع</label>
                        <p class="text-sm font-mono text-primary">{{ $selectedRequest['tracking_number'] }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">المصدر</label>
                        <p class="text-sm text-text">{{ $selectedRequest['source'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الاسم</label>
                        <p class="text-sm text-text">{{ $selectedRequest['name'] }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الحالة</label>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                            @if($selectedRequest['status']['value'] === 'resolved') bg-success/10 text-success
                            @elseif($selectedRequest['status']['value'] === 'closed') bg-surface-secondary text-text-tertiary
                            @else bg-warning/10 text-warning @endif">
                            @if($selectedRequest['status']['value'] === 'pending') قيد الانتظار
                            @elseif($selectedRequest['status']['value'] === 'resolved') تم الحل
                            @else مغلق @endif
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">الهاتف</label>
                        <p class="text-sm text-text">{{ $selectedRequest['phone'] }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">البريد الإلكتروني</label>
                        <p class="text-sm text-text">{{ $selectedRequest['email'] ?? '—' }}</p>
                    </div>
                </div>

                @if ($selectedRequest['department'] ?? null)
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">القسم</label>
                        <p class="text-sm text-text">{{ $selectedRequest['department'] }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-text-tertiary mb-1">الرسالة</label>
                    <p class="text-sm text-text bg-surface-secondary rounded-xl p-3">{{ $selectedRequest['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">تاريخ الإرسال</label>
                        <p class="text-sm text-text">{{ $selectedRequest['submitted_at'] ? \Carbon\Carbon::parse($selectedRequest['submitted_at'])->format('Y-m-d H:i') : '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1">تاريخ الإنشاء</label>
                        <p class="text-sm text-text">{{ \Carbon\Carbon::parse($selectedRequest['created_at'])->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex items-center gap-3">
                    @if (($selectedRequest['status']['value'] ?? '') === 'pending')
                        @can('resolve', \App\Domains\ContactRequests\Models\ContactRequest::class)
                            <button wire:click="markResolved({{ $selectedRequest['id'] }})" class="px-4 py-2 rounded-xl bg-success text-white text-sm font-semibold hover:bg-success-dark transition-colors">
                                تحديد كمحلول
                            </button>
                        @endcan
                    @endif
                    <button wire:click="closeDetailModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">
                        إغلاق
                    </button>
                </div>
            </x-slot>
        </x-ui.modal>
    @endif
</div>
