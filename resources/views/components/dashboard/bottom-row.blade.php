@props([
    'news' => [],
])

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    {{-- Calendar Widget --}}
    <div class="bg-surface rounded-2xl border border-border p-5 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-text" id="calendarMonth"></h3>
            <div class="flex items-center gap-1">
                <button class="p-1.5 rounded-lg hover:bg-municipal-50 transition-colors" id="calPrev"><i data-lucide="chevron-right" class="w-4 h-4 text-text-secondary"></i></button>
                <button class="p-1.5 rounded-lg hover:bg-municipal-50 transition-colors" id="calNext"><i data-lucide="chevron-left" class="w-4 h-4 text-text-secondary"></i></button>
            </div>
        </div>
        <div class="grid grid-cols-7 gap-0.5 text-center mb-2">
            <span class="text-[10px] font-bold text-text-muted py-1">س</span>
            <span class="text-[10px] font-bold text-text-muted py-1">ح</span>
            <span class="text-[10px] font-bold text-text-muted py-1">ن</span>
            <span class="text-[10px] font-bold text-text-muted py-1">ث</span>
            <span class="text-[10px] font-bold text-text-muted py-1">ر</span>
            <span class="text-[10px] font-bold text-text-muted py-1">خ</span>
            <span class="text-[10px] font-bold text-primary py-1">ج</span>
        </div>
        <div class="grid grid-cols-7 gap-0.5 text-center" id="calendarDays"></div>
        <div class="mt-4 pt-4 border-t border-border space-y-2">
            <div class="flex flex-col items-center text-center py-4">
                <p class="text-xs text-text-tertiary">لا توجد أحداث مضافـة</p>
            </div>
        </div>
    </div>

    {{-- Recent News --}}
    <div class="lg:col-span-2 bg-surface rounded-2xl border border-border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-text">آخر الأخبار</h3>
                <p class="text-xs text-text-tertiary font-medium mt-0.5">أخبار وأنشطة البلدية</p>
            </div>
            <a href="#" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض الكل</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse ($news as $item)
            <div class="rounded-xl overflow-hidden border border-border hover:shadow-elevated transition-all cursor-pointer group">
                <div class="h-32 flex items-center justify-center" style="background: {{ $item['gradient'] ?? 'linear-gradient(135deg, rgba(46,125,50,0.1), rgba(129,199,132,0.1))' }}">
                    <i data-lucide="{{ $item['icon'] ?? 'building-2' }}" class="w-10 h-10" style="color: {{ $item['iconColor'] ?? 'rgba(46,125,50,0.3)' }}"></i>
                </div>
                <div class="p-3">
                    <x-ui.badge :variant="$item['badgeVariant'] ?? 'success'" class="text-[9px]">{{ $item['category'] }}</x-ui.badge>
                    <p class="text-xs font-bold text-text mt-1.5 group-hover:text-primary transition-colors line-clamp-2">{{ $item['title'] }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[9px] text-text-muted font-medium">{{ $item['date'] }}</span>
                        <span class="text-[9px] text-text-muted font-medium">{{ $item['department'] }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="sm:col-span-2 flex flex-col items-center text-center py-8">
                <div class="w-14 h-14 rounded-2xl bg-municipal-50 flex items-center justify-center mb-3">
                    <i data-lucide="newspaper" class="w-6 h-6 text-municipal-300"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد أخبار</p>
                <p class="text-xs text-text-tertiary mt-1">ستظهر أخبار البلدية هنا عند نشرها</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Access --}}
    <div class="bg-surface rounded-2xl border border-border p-5 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-text">وصول سريع</h3>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <a href="#" class="quick-action hover:quick-action-hover !p-4">
                <div class="quick-action-icon bg-municipal-50">
                    <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                </div>
                <span class="text-[11px] font-bold text-center">تقديم<br>معاملة</span>
            </a>
            <a href="#" class="quick-action hover:quick-action-hover !p-4">
                <div class="quick-action-icon bg-warning-light">
                    <i data-lucide="message-square-warning" class="w-5 h-5 text-warning"></i>
                </div>
                <span class="text-[11px] font-bold text-center">تقديم<br>شكوى</span>
            </a>
            <a href="#" class="quick-action hover:quick-action-hover !p-4">
                <div class="quick-action-icon bg-success-light">
                    <i data-lucide="receipt" class="w-5 h-5 text-success"></i>
                </div>
                <span class="text-[11px] font-bold text-center">الاستعلام عن<br>فاتورة</span>
            </a>
            <a href="#" class="quick-action hover:quick-action-hover !p-4">
                <div class="quick-action-icon bg-info-light">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-info"></i>
                </div>
                <span class="text-[11px] font-bold text-center">حجز<br>موعد</span>
            </a>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthNames = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            let currentDate = new Date();

            function renderCalendar(date) {
                const year = date.getFullYear();
                const month = date.getMonth();
                const el = document.getElementById('calendarMonth');
                if (el) el.textContent = monthNames[month] + ' ' + year;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const today = new Date();
                let html = '';
                // Shift for RTL: convert Sunday-based to Saturday-based
                const offset = (firstDay + 1) % 7;
                for (let i = 0; i < offset; i++) {
                    html += '<span></span>';
                }
                for (let d = 1; d <= daysInMonth; d++) {
                    const isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                    html += `<span class="${isToday ? 'cal-day today' : 'cal-day'}">${d}</span>`;
                }
                const daysEl = document.getElementById('calendarDays');
                if (daysEl) daysEl.innerHTML = html;
            }

            renderCalendar(currentDate);

            const prevBtn = document.getElementById('calPrev');
            const nextBtn = document.getElementById('calNext');
            if (prevBtn) prevBtn.addEventListener('click', function() { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(currentDate); });
            if (nextBtn) nextBtn.addEventListener('click', function() { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(currentDate); });
        });
    </script>
    @endpush
@endonce
