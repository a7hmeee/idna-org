<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Area Chart (2 cols) --}}
    <div class="lg:col-span-2 card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-text">تحليل الشكاوى الشهرية</h3>
                <p class="text-xs text-text-tertiary font-medium mt-0.5">آخر 12 شهراً</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 rounded-lg bg-primary text-white text-[10px] font-bold">سنوي</button>
                <button class="px-3 py-1.5 rounded-lg text-text-tertiary hover:bg-municipal-50 text-[10px] font-bold transition-all">شهري</button>
                <button class="px-3 py-1.5 rounded-lg text-text-tertiary hover:bg-municipal-50 text-[10px] font-bold transition-all">أسبوعي</button>
            </div>
        </div>
        <div class="h-64 w-full chart-container">
            <svg class="w-full h-full" viewBox="0 0 500 220" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="areaChartGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#2E7D32" stop-opacity="0.25"/>
                        <stop offset="60%" stop-color="#2E7D32" stop-opacity="0.06"/>
                        <stop offset="100%" stop-color="#2E7D32" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="areaChartGrad2" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#81C784" stop-opacity="0.2"/>
                        <stop offset="100%" stop-color="#81C784" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <line x1="0" y1="180" x2="500" y2="180" stroke="var(--color-border)" stroke-width="1"/>
                <line x1="0" y1="135" x2="500" y2="135" stroke="var(--color-border)" stroke-width="1" stroke-dasharray="4"/>
                <line x1="0" y1="90" x2="500" y2="90" stroke="var(--color-border)" stroke-width="1" stroke-dasharray="4"/>
                <line x1="0" y1="45" x2="500" y2="45" stroke="var(--color-border)" stroke-width="1" stroke-dasharray="4"/>
                <path d="M25 160 L65 140 L105 150 L145 110 L185 100 L225 90 L265 70 L305 50 L345 40 L385 35 L425 30 L465 25 L465 180 L25 180 Z" fill="url(#areaChartGrad)"/>
                <path d="M25 170 L65 155 L105 160 L145 135 L185 125 L225 115 L265 100 L305 85 L345 75 L385 70 L425 65 L465 60 L465 180 L25 180 Z" fill="url(#areaChartGrad2)"/>
                <path d="M25 160 L65 140 L105 150 L145 110 L185 100 L225 90 L265 70 L305 50 L345 40 L385 35 L425 30 L465 25" fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25 170 L65 155 L105 160 L145 135 L185 125 L225 115 L265 100 L305 85 L345 75 L385 70 L425 65 L465 60" fill="none" stroke="#81C784" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="265" cy="70" r="3" fill="#2E7D32" stroke="white" stroke-width="2"/>
            </svg>
        </div>
        <div class="flex items-center gap-4 mt-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-primary"></div>
                <span class="text-[11px] text-text-secondary font-semibold">الشكاوى الواردة</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-municipal-400"></div>
                <span class="text-[11px] text-text-secondary font-semibold">الشكاوى المعالجة</span>
            </div>
        </div>
    </div>

    {{-- Donut Chart (1 col) --}}
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-text">توزيع الخدمات</h3>
                <p class="text-xs text-text-tertiary font-medium mt-0.5">حسب النوع</p>
            </div>
            <i data-lucide="more-horizontal" class="w-5 h-5 text-text-muted cursor-pointer"></i>
        </div>
        <div class="flex flex-col items-center">
            <div class="relative w-44 h-44">
                <svg class="w-full h-full" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="48" fill="none" stroke="var(--color-municipal-50)" stroke-width="8"/>
                    <circle cx="60" cy="60" r="48" fill="none" stroke="var(--color-primary)" stroke-width="8" stroke-dasharray="180 120" stroke-dashoffset="0" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                    <circle cx="60" cy="60" r="48" fill="none" stroke="var(--color-municipal-400)" stroke-width="8" stroke-dasharray="75 225" stroke-dashoffset="-182" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                    <circle cx="60" cy="60" r="48" fill="none" stroke="var(--color-municipal-200)" stroke-width="8" stroke-dasharray="45 255" stroke-dashoffset="-260" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-bold text-text">48</span>
                    <span class="text-[10px] text-text-tertiary font-medium">خدمة</span>
                </div>
            </div>
            <div class="w-full space-y-2.5 mt-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                        <span class="text-xs text-text-secondary font-semibold">رخص البناء</span>
                    </div>
                    <span class="text-xs font-bold text-text">45%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-municipal-400"></div>
                        <span class="text-xs text-text-secondary font-semibold">الخدمات الصحية</span>
                    </div>
                    <span class="text-xs font-bold text-text">28%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-municipal-200"></div>
                        <span class="text-xs text-text-secondary font-semibold">أخرى</span>
                    </div>
                    <span class="text-xs font-bold text-text">27%</span>
                </div>
            </div>
        </div>
    </div>
</div>
