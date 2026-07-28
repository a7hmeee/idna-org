<header class="sticky top-4 z-40 mx-4 mt-4 bg-white/80 backdrop-blur-xl rounded-2xl border border-[#E6EEE5] shadow-sm">
    <div class="flex items-center justify-between h-14 px-5">
        {{-- Right: Breadcrumb + Mobile Menu --}}
        <div class="flex items-center gap-3">
            <button @click="mobileSidebar = true" class="lg:hidden p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                <i data-lucide="menu" class="w-5 h-5 text-[#4A6B3F]"></i>
            </button>
            <nav class="hidden sm:flex items-center gap-2 text-xs">
                <a href="#" class="text-[#7A9A6E] hover:text-[#2E6F1F] transition-colors duration-200 font-semibold">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-[#A8C09E]"></i>
                <span class="text-[#1A2E15] font-bold">لوحة التحكم</span>
            </nav>
        </div>

        {{-- Left: Actions --}}
        <div class="flex items-center gap-1.5">
            <div class="hidden md:flex items-center gap-2 bg-[#F0F6EF] rounded-xl px-3.5 py-2 border border-transparent focus-within:border-[#A9D19D] focus-within:bg-white transition-all duration-200 w-56">
                <i data-lucide="search" class="w-4 h-4 text-[#7A9A6E] shrink-0"></i>
                <input type="text" placeholder="ابحث في اللوحة..." class="bg-transparent border-none outline-none text-xs text-[#1A2E15] placeholder-[#A8C09E] w-full font-semibold" style="outline:none;box-shadow:none">
                <kbd class="hidden lg:inline-flex items-center px-1.5 py-0.5 rounded-md bg-white text-[9px] font-bold text-[#A8C09E] border border-[#E6EEE5] leading-none">⌘K</kbd>
            </div>

            {{-- Notifications --}}
            <div class="relative" x-data="{ notifOpen: false }">
                <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                    <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#EF4444] rounded-full ring-2 ring-white"></div>
                    <i data-lucide="bell" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
                </button>
                <div x-show="notifOpen" @click.outside="notifOpen = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-dropdown border border-[#E6EEE5] py-1.5 z-50">
                    <div class="px-4 py-3 border-b border-[#E6EEE5] flex items-center justify-between">
                        <p class="text-xs font-bold text-[#1A2E15]">الإشعارات</p>
                        <span class="text-[10px] font-bold text-[#2E6F1F] cursor-pointer hover:underline">تحديد الكل كمقروء</span>
                    </div>
                    <div class="max-h-64 overflow-y-auto sidebar-nav">
                        <div class="px-4 py-3 hover:bg-[#F5F9F4] transition-colors duration-150 cursor-pointer flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#FEE2E2] flex items-center justify-center shrink-0 mt-0.5"><i data-lucide="message-square-warning" class="w-4 h-4 text-[#DC2626]"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#1A2E15]">شكوى جديدة مسجلة</p>
                                <p class="text-[10px] text-[#7A9A6E] font-medium mt-0.5">منذ 5 دقائق</p>
                            </div>
                            <div class="w-2 h-2 rounded-full bg-[#EF4444] shrink-0 mt-1.5"></div>
                        </div>
                    </div>
                    <div class="px-4 py-2.5 border-t border-[#E6EEE5] text-center">
                        <a href="#" class="text-[11px] font-bold text-[#2E6F1F] hover:underline">عرض كل الإشعارات</a>
                    </div>
                </div>
            </div>

            {{-- Messages --}}
            <button class="relative p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#22C55E] rounded-full ring-2 ring-white"></div>
                <i data-lucide="message-square" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
            </button>

            {{-- Dark Mode --}}
            <button class="p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                <i data-lucide="moon" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
            </button>

            {{-- Language --}}
            <button class="p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                <i data-lucide="globe" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
            </button>

            {{-- Separator --}}
            <div class="w-px h-6 bg-[#E6EEE5] mx-1"></div>

            {{-- Profile --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-[#2E6F1F] text-white font-bold text-xs flex items-center justify-center shadow-sm">{{ mb_substr(auth()->user()->name, 0, 2, 'UTF-8') }}</div>
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-bold text-[#1A2E15] leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] text-[#7A9A6E] leading-tight font-medium">مدير البلدية</p>
                    </div>
                    <svg class="w-[14px] h-[14px] text-[#A8C09E] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-dropdown border border-[#E6EEE5] py-1.5 z-50">
                    <div class="px-4 py-3 border-b border-[#E6EEE5]">
                        <p class="text-sm font-bold text-[#1A2E15]">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-[#7A9A6E]">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs text-[#4A6B3F] hover:bg-[#EDF5EB] transition-colors duration-150 font-semibold"><i data-lucide="user" class="w-4 h-4"></i>الملف الشخصي</a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs text-[#4A6B3F] hover:bg-[#EDF5EB] transition-colors duration-150 font-semibold"><i data-lucide="key-round" class="w-4 h-4"></i>تغيير كلمة المرور</a>
                    <div class="border-t border-[#E6EEE5] my-1"></div>
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs text-[#DC2626] hover:bg-[#FEE2E2] transition-colors duration-150 font-semibold"><i data-lucide="log-out" class="w-4 h-4"></i>تسجيل الخروج</button>
                </div>
            </div>
        </div>
    </div>
</header>
