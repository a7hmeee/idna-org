<div>
    <x-slot name="title">سجل النشاط</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">سجل نشاط تسجيل الدخول</h1>
            <p class="text-sm text-text-tertiary mt-1">مراقبة أنشطة تسجيل الدخول والخروج</p>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="flex flex-col sm:flex-row gap-3 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بالاسم أو البريد أو IP..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="eventType" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الأحداث</option>
                    <option value="login">تسجيل دخول</option>
                    <option value="logout">تسجيل خروج</option>
                    <option value="failed">فشل تسجيل الدخول</option>
                    <option value="password_change">تغيير كلمة المرور</option>
                    <option value="password_reset">إعادة تعيين كلمة المرور</option>
                </select>
                <select wire:model.live="successFilter" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">الكل</option>
                    <option value="1">ناجح</option>
                    <option value="0">فشل</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المستخدم</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحدث</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">السبب</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">عنوان IP</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">معرّف الجلسة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="activity-{{ $activity->id }}">
                            <td class="px-4 py-3">
                                @if ($activity->user)
                                    <p class="font-semibold text-text">{{ $activity->user->name }}</p>
                                    <p class="text-xs text-text-tertiary">{{ $activity->user->email }}</p>
                                @else
                                    <p class="text-xs text-text-tertiary">مستخدم محذوف</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($activity->event_type === 'login') bg-success/10 text-success
                                    @elseif($activity->event_type === 'logout') bg-surface-secondary text-text-tertiary
                                    @elseif($activity->event_type === 'failed') bg-danger/10 text-danger
                                    @elseif($activity->event_type === 'password_change') bg-warning/10 text-warning
                                    @else bg-info/10 text-info @endif">
                                    @if($activity->event_type === 'login') دخول
                                    @elseif($activity->event_type === 'logout') خروج
                                    @elseif($activity->event_type === 'failed') فشل
                                    @elseif($activity->event_type === 'password_change') تغيير كلمة المرور
                                    @elseif($activity->event_type === 'password_reset') إعادة تعيين
                                    @elseif($activity->event_type === 'lockout') قفل
                                    @else {{ $activity->event_type }} @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($activity->successful)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-success/10 text-success">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        ناجح
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-danger/10 text-danger">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                        فشل
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-text-tertiary">
                                {{ $activity->failure_reason ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-text-tertiary font-mono">
                                {{ $activity->ip_address }}
                            </td>
                            <td class="px-4 py-3 text-xs text-text-tertiary font-mono max-w-[120px] truncate">
                                {{ $activity->session_id ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-text-tertiary">
                                {{ $activity->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="shield" class="w-12 h-12 text-text-tertiary/40"></i>
                                    <p class="text-sm text-text-tertiary">لا يوجد نشاط</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($activities->hasPages())
            <div class="p-4 border-t border-border">
                <x-ui.pagination :paginator="$activities" />
            </div>
        @endif
    </div>
</div>
