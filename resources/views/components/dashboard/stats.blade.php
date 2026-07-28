@props([
    'users' => 1058,
    'departments' => 12,
    'services' => 48,
    'complaints' => 32,
    'projects' => 17,
    'revenue' => 245000,
    'visitors' => 12847,
    'pending' => 89,
])

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3"
     x-data="{
         counters: { users: 0, departments: 0, services: 0, complaints: 0, projects: 0, revenue: 0, visitors: 0, pending: 0 },
         init() {
             const targets = { users: {{ $users }}, departments: {{ $departments }}, services: {{ $services }}, complaints: {{ $complaints }}, projects: {{ $projects }}, revenue: {{ $revenue }}, visitors: {{ $visitors }}, pending: {{ $pending }} };
             const duration = 1500;
             Object.keys(targets).forEach(key => {
                 const end = targets[key];
                 const startTime = performance.now();
                 const animate = (time) => {
                     const elapsed = time - startTime;
                     const progress = Math.min(elapsed / duration, 1);
                     const eased = 1 - Math.pow(1 - progress, 3);
                     this.counters[key] = Math.floor(eased * end);
                     if (progress < 1) requestAnimationFrame(animate);
                 };
                 requestAnimationFrame(animate);
             });
         }
     }">

    {{-- Total Users --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-municipal-50 flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5 text-primary"></i>
            </div>
            <span class="text-[10px] font-bold text-success flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+12.4%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.users.toLocaleString('ar-SA')"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">إجمالي المستخدمين</p>
        <x-dashboard.sparkline color="green" />
    </div>

    {{-- Departments --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-info-light flex items-center justify-center">
                <i data-lucide="building-2" class="w-5 h-5 text-info"></i>
            </div>
            <span class="text-[10px] font-bold text-success flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+2.1%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.departments"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">الأقسام</p>
        <x-dashboard.sparkline color="blue" />
    </div>

    {{-- Services --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-municipal-50 flex items-center justify-center">
                <i data-lucide="layers" class="w-5 h-5 text-primary"></i>
            </div>
            <span class="text-[10px] font-bold text-success flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+9.3%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.services"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">الخدمات الإلكترونية</p>
        <x-dashboard.sparkline color="green" />
    </div>

    {{-- Complaints --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.25s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-warning-light flex items-center justify-center">
                <i data-lucide="message-square-warning" class="w-5 h-5 text-warning"></i>
            </div>
            <span class="text-[10px] font-bold text-danger flex items-center gap-0.5">
                <i data-lucide="trending-down" class="w-3 h-3"></i>-5.3%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.complaints"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">الشكاوى المفتوحة</p>
        <x-dashboard.sparkline color="red" />
    </div>

    {{-- Projects --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-success-light flex items-center justify-center">
                <i data-lucide="folder-kanban" class="w-5 h-5 text-success"></i>
            </div>
            <span class="text-[10px] font-bold text-success flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+15.0%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.projects"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">المشاريع النشطة</p>
        <x-dashboard.sparkline color="green" />
    </div>

    {{-- Revenue --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <i data-lucide="circle-dollar-sign" class="w-5 h-5 text-purple-600"></i>
            </div>
            <span class="text-[10px] font-bold text-success flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+8.7%
            </span>
        </div>
        <p class="text-xl font-bold text-text"><span x-text="(counters.revenue / 1000).toFixed(0)"></span>k</p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">الإيرادات الشهرية</p>
        <x-dashboard.sparkline color="purple" />
    </div>

    {{-- Visitors --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-info-light flex items-center justify-center">
                <i data-lucide="eye" class="w-5 h-5 text-info"></i>
            </div>
            <span class="text-[10px] font-bold text-success flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+24.1%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.visitors.toLocaleString('ar-SA')"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">زوار اليوم</p>
        <x-dashboard.sparkline color="blue" />
    </div>

    {{-- Pending Requests --}}
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-danger-light flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5 text-danger"></i>
            </div>
            <span class="text-[10px] font-bold text-danger flex items-center gap-0.5">
                <i data-lucide="trending-up" class="w-3 h-3"></i>+3.2%
            </span>
        </div>
        <p class="text-xl font-bold text-text" x-text="counters.pending"></p>
        <p class="text-[11px] text-text-tertiary font-medium mb-2">طلبات معلقة</p>
        <x-dashboard.sparkline color="red" />
    </div>
</div>
