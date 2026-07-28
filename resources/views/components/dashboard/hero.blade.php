@props(['userName' => 'محمد'])

<div class="relative overflow-hidden rounded-2xl hero-gradient p-6 sm:p-8 animate-fade-up">
    <div class="relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-white/15 rounded-full px-3 py-1 text-[11px] font-bold text-white/80">
                        <i data-lucide="calendar" class="w-3 h-3 inline-block ml-1"></i>
                        <span id="currentDate"></span>
                    </div>
                    <div class="bg-white/15 rounded-full px-3 py-1 text-[11px] font-bold text-white/80 flex items-center gap-1">
                        <i data-lucide="sun" class="w-3 h-3"></i>
                        <span>28°C | إذنا</span>
                    </div>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2" style="text-wrap: balance">
                    مرحبًا بعودتك يا {{ $userName }} 👋
                </h1>
                <p class="text-municipal-200 text-sm sm:text-base font-medium">
                    لديك <span class="font-bold text-white">14</span> طلب خدمة مفتوح و<span class="font-bold text-white">21</span> شكوى بانتظار المراجعة اليوم.
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button class="px-5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-sm font-bold transition-all flex items-center gap-2 border border-white/10">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    خدمة جديدة
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-sm font-bold transition-all flex items-center gap-2 border border-white/10">
                    <i data-lucide="folder-plus" class="w-4 h-4"></i>
                    مشروع جديد
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-white text-primary text-sm font-bold transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    تقرير سريع
                </button>
            </div>
        </div>
    </div>
    <div class="absolute top-0 left-0 w-72 h-72 bg-white/[0.03] rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-56 h-56 bg-white/[0.03] rounded-full translate-x-1/4 translate-y-1/4"></div>
    <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white/[0.02] rounded-full"></div>
</div>
