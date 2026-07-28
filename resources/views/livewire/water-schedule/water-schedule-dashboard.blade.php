<div>
    <x-slot name="title">جدول توزيع المياه</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">جدول توزيع المياه</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة جدول ضخ المياه اليومي</p>
        </div>
        <div class="flex items-center gap-2">
            @can('water.view')
                <a href="{{ route('dashboard.water-schedule.areas') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>المناطق</span>
                </a>
                <a href="{{ route('dashboard.water-schedule.maintenance') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                    <span>الصيانة</span>
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

    @if (session('info'))
        <div class="mb-4 rounded-xl bg-blue-50 border border-blue-200 px-4 py-3 flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5 shrink-0"></i>
            <span class="text-sm text-blue-700 font-medium">{{ session('info') }}</span>
        </div>
    @endif

    {{-- Controls --}}
    <div class="bg-surface rounded-xl border border-border p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <label class="text-sm font-semibold text-text shrink-0">تاريخ الجدول</label>
                <input type="date" wire:model.live="date" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
            </div>
            <div class="flex items-center gap-2 mr-auto">
                <button wire:click="copyPreviousDay" class="px-4 py-2 rounded-xl bg-primary/10 text-primary text-sm font-semibold hover:bg-primary/20 transition-colors inline-flex items-center gap-2" wire:loading.attr="disabled">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                    <span>نسخ جدول أمس</span>
                </button>
                <button wire:click="publish" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:loading.attr="disabled">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                    <span>نشر الجدول</span>
                </button>
                <button wire:click="save" class="px-4 py-2 rounded-xl bg-success text-white text-sm font-semibold hover:bg-success/90 transition-colors inline-flex items-center gap-2" wire:loading.attr="disabled">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>حفظ</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Schedule Table --}}
    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المنطقة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">وقت البداية</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">وقت النهاية</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scheduleItems as $index => $item)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="schedule-{{ $item['area_id'] }}">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $item['area_name'] }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="time" wire:model="scheduleItems.{{ $index }}.start_time" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all w-28" />
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="time" wire:model="scheduleItems.{{ $index }}.end_time" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all w-28" />
                            </td>
                            <td class="px-4 py-3 text-center">
                                <select wire:model="scheduleItems.{{ $index }}.status" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" wire:model="scheduleItems.{{ $index }}.notes" class="w-full bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="ملاحظات..." />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="droplets" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد مناطق نشطة</p>
                                    <a href="{{ route('dashboard.water-schedule.areas') }}" class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة مناطق جديدة</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
