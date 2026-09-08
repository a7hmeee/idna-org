<div>
    <x-slot name="title">متصفح المحادثات</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">متصفح المحادثات</h1>
            <p class="text-sm text-text-tertiary mt-1">عرض وإدارة محادثات المساعد الذكي</p>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بالجلسة أو اسم المستخدم..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="closed">مغلق</option>
                    <option value="expired">منتهي</option>
                </select>
                <input type="date" wire:model.live="dateFrom" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" title="من تاريخ" />
                <input type="date" wire:model.live="dateTo" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" title="إلى تاريخ" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">معرف الجلسة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المستخدم</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">آخر نية</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الرسائل</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">التاريخ</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conversations as $conversation)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="conv-{{ $conversation->id }}">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-primary font-semibold">{{ $conversation->session_id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($conversation->user)
                                    <p class="font-semibold text-text">{{ $conversation->user->name }}</p>
                                    <p class="text-[11px] text-text-tertiary">{{ $conversation->user->email }}</p>
                                @else
                                    <span class="text-xs text-text-tertiary">زائر</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($conversation->status === 'active') bg-success/10 text-success
                                    @elseif($conversation->status === 'closed') bg-surface-secondary text-text-tertiary
                                    @elseif($conversation->status === 'expired') bg-warning/10 text-warning
                                    @else bg-surface-secondary text-text-secondary @endif">
                                    @if($conversation->status === 'active') نشط
                                    @elseif($conversation->status === 'closed') مغلق
                                    @elseif($conversation->status === 'expired') منتهي
                                    @else {{ $conversation->status }} @endif
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-text-tertiary">{{ $conversation->last_intent ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm text-text-secondary font-semibold">{{ $conversation->messages_count ?? $conversation->messages()->count() }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-text-tertiary">
                                {{ $conversation->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="viewMessages({{ $conversation->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="عرض الرسائل">
                                        <i data-lucide="message-square" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="message-circle" class="w-12 h-12 text-text-tertiary/40"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد محادثات</p>
                                    <p class="text-xs text-text-tertiary">لم يتم العثور على أي محادثات تطابق معايير البحث.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($conversations->hasPages())
            <div class="p-4 border-t border-border">
                <x-ui.pagination :paginator="$conversations" />
            </div>
        @endif
    </div>

    {{-- Messages Modal --}}
    @if ($showMessagesModal && $selectedConversation)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeMessagesModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-5 border-b border-border shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-text">رسائل المحادثة</h3>
                    <p class="text-xs text-text-tertiary mt-0.5">
                        معرف الجلسة: <span class="font-mono text-primary">{{ $selectedConversation->session_id }}</span>
                    </p>
                </div>
                <button wire:click="closeMessagesModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-5 space-y-3">
                @forelse ($selectedConversation->messages->sortBy('created_at') as $message)
                    <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] rounded-2xl px-4 py-2.5 {{ $message->role === 'user' ? 'bg-primary text-white' : 'bg-surface-secondary text-text' }}">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-semibold {{ $message->role === 'user' ? 'text-white/70' : 'text-text-tertiary' }}">
                                    @if ($message->role === 'user') المستخدم @else المساعد @endif
                                </span>
                                <span class="text-[10px] {{ $message->role === 'user' ? 'text-white/50' : 'text-text-muted' }}">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-3 py-8">
                        <i data-lucide="message-circle" class="w-8 h-8 text-text-tertiary/40"></i>
                        <p class="text-sm text-text-tertiary">لا توجد رسائل في هذه المحادثة</p>
                    </div>
                @endforelse
            </div>
            <div class="flex items-center justify-between px-5 py-3 border-t border-border bg-surface-secondary/50 shrink-0">
                <div class="flex items-center gap-3">
                    <span class="text-xs text-text-tertiary">
                        الإجمالي: {{ $selectedConversation->messages->count() }} رسالة
                    </span>
                    @if ($selectedConversation->user)
                        <span class="text-xs text-text-tertiary">
                            المستخدم: {{ $selectedConversation->user->name }}
                        </span>
                    @endif
                </div>
                <button wire:click="closeMessagesModal" class="px-4 py-2 rounded-xl bg-surface border border-border text-text text-sm font-semibold hover:bg-surface-secondary transition-colors">إغلاق</button>
            </div>
        </div>
    </div>
    @endif
</div>
