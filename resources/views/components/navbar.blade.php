@props([
    'title' => 'لوحة التحكم',
    'breadcrumbItems' => [],
])

<header {{ $attributes->class(['sticky top-4 z-30 mx-4 mt-4 bg-surface/80 backdrop-blur-xl rounded-2xl border border-border shadow-sm']) }}>
    <div class="flex items-center justify-between h-14 px-5">
        {{-- Left: Mobile Menu + Breadcrumb --}}
        <div class="flex items-center gap-3">
            <button @click="mobileSidebar = true" class="lg:hidden p-2 rounded-xl hover:bg-municipal-50 transition-colors">
                <i data-lucide="menu" class="w-5 h-5 text-text-secondary"></i>
            </button>

            @if (count($breadcrumbItems))
                <x-breadcrumb :items="$breadcrumbItems" />
            @else
                <nav class="hidden sm:flex items-center gap-2 text-xs">
                    <a href="{{ route('dashboard') }}" class="text-text-tertiary hover:text-primary transition-colors font-semibold">الرئيسية</a>
                    <i data-lucide="chevron-left" class="w-3 h-3 text-text-tertiary"></i>
                    <span class="text-text font-bold">{{ $title }}</span>
                </nav>
            @endif
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-1">
            {{-- Search --}}
            <div class="hidden md:flex items-center gap-2 bg-municipal-50 rounded-xl px-3.5 py-2 border border-transparent focus-within:border-accent focus-within:bg-surface transition-all w-56">
                <i data-lucide="search" class="w-4 h-4 text-text-tertiary shrink-0"></i>
                <input type="text" placeholder="ابحث في اللوحة..." class="bg-transparent border-none outline-none text-xs text-text placeholder-text-tertiary/60 w-full font-semibold" style="outline:none;box-shadow:none">
                <kbd class="hidden lg:inline-flex items-center px-1.5 py-0.5 rounded-md bg-surface text-[9px] font-bold text-text-tertiary border border-border leading-none">⌘K</kbd>
            </div>

            {{-- Notifications --}}
            <div x-data="{ notifOpen: false }" class="relative">
                <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl hover:bg-municipal-50 transition-colors">
                    <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-danger rounded-full ring-2 ring-surface"></div>
                    <i data-lucide="bell" class="w-[18px] h-[18px] text-text-secondary"></i>
                </button>

                {{-- Notifications Dropdown --}}
                <div
                    x-show="notifOpen"
                    @click.outside="notifOpen = false"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute left-0 mt-2 w-80 bg-surface rounded-2xl shadow-xl border border-border py-2 z-50 origin-top"
                >
                    <div class="px-4 py-2 border-b border-border">
                        <p class="text-sm font-bold text-text">الإشعارات</p>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        <div class="px-4 py-3 hover:bg-municipal-50 transition-colors cursor-pointer border-b border-border/50">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-danger mt-1.5 shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-text truncate">شكوى عاجلة جديدة</p>
                                    <p class="text-[10px] text-text-tertiary font-medium">منذ 5 دقائق</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 hover:bg-municipal-50 transition-colors cursor-pointer border-b border-border/50">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-warning mt-1.5 shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-text truncate">طلب موافقة على مشروع</p>
                                    <p class="text-[10px] text-text-tertiary font-medium">منذ ساعة</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 hover:bg-municipal-50 transition-colors cursor-pointer">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-success mt-1.5 shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-text truncate">تم معالجة شكوى #2842</p>
                                    <p class="text-[10px] text-text-tertiary font-medium">منذ 3 ساعات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-2 border-t border-border text-center">
                        <a href="#" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">عرض جميع الإشعارات</a>
                    </div>
                </div>
            </div>

            {{-- Messages --}}
            <button class="relative p-2 rounded-xl hover:bg-municipal-50 transition-colors">
                <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-success rounded-full ring-2 ring-surface"></div>
                <i data-lucide="message-square" class="w-[18px] h-[18px] text-text-secondary"></i>
            </button>

            {{-- Dark Mode Toggle --}}
            <button
                x-data="{ dark: false }"
                @click="dark = !dark; document.documentElement.classList.toggle('dark')"
                class="p-2 rounded-xl hover:bg-municipal-50 transition-colors"
                title="{{ __('تغيير المظهر') }}"
            >
                <i x-show="!dark" data-lucide="moon" class="w-[18px] h-[18px] text-text-secondary"></i>
                <i x-show="dark" x-cloak data-lucide="sun" class="w-[18px] h-[18px] text-text-secondary"></i>
            </button>

            {{-- Divider --}}
            <div class="w-px h-6 bg-border mx-1.5"></div>

            {{-- User Menu --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-municipal-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-primary text-surface font-bold text-xs flex items-center justify-center shadow-sm">
                        {{ substr(auth()->user()?->name ?? 'م', 0, 2) }}
                    </div>
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-bold text-text leading-tight">{{ auth()->user()?->name ?? 'محمد الشيخ' }}</p>
                        <p class="text-[9px] text-text-tertiary leading-tight font-medium">
                            {{ auth()->user()?->roles->first()?->name ?: 'مدير البلدية' }}
                        </p>
                    </div>
                    <i data-lucide="chevron-down" class="w-[18px] h-[18px] text-text-tertiary hidden sm:block"></i>
                </button>

                {{-- User Dropdown --}}
                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute left-0 mt-2 w-52 bg-surface rounded-2xl shadow-xl border border-border py-1.5 z-50 origin-top"
                >
                    <div class="px-4 py-3 border-b border-border">
                        <p class="text-sm font-bold text-text">{{ auth()->user()?->name ?? 'محمد الشيخ' }}</p>
                        <p class="text-[11px] text-text-tertiary">{{ auth()->user()?->email ?? 'admin@idhna.ps' }}</p>
                    </div>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-text-secondary hover:bg-municipal-50 hover:text-primary transition-colors">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        الملف الشخصي
                    </a>
                    <a href="{{ route('password.change') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-text-secondary hover:bg-municipal-50 hover:text-primary transition-colors">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                        تغيير كلمة المرور
                    </a>
                    <div class="border-t border-border my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-danger hover:bg-danger-light transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4 rotate-180"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>