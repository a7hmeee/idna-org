<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'تسجيل الدخول'); ?> - <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>[x-cloak]{display:none!important}</style>
    
    <style>
        :root, :host {
            --font-sans: "Cairo", ui-sans-serif, system-ui, sans-serif;
            --color-primary: #2E6F1F;
            --color-primary-light: #4F8F2F;
            --color-primary-lighter: #6BAA3B;
            --color-municipal-50: #F0F7EE;
            --color-municipal-100: #E0EFDB;
            --color-municipal-200: #C0DFB7;
            --color-municipal-300: #A0CF93;
            --color-municipal-400: #6BAA3B;
            --color-municipal-500: #4F8F2F;
            --color-municipal-600: #2E6F1F;
            --color-municipal-700: #235818;
            --color-municipal-800: #1A4112;
            --color-success: #2E6F1F;
            --color-success-light: #E8F5E3;
            --color-warning: #D97706;
            --color-warning-light: #FEF3C7;
            --color-danger: #DC2626;
            --color-danger-light: #FEE2E2;
            --color-info: #2563EB;
            --color-info-light: #DBEAFE;
            --color-text: #1A1F16;
            --color-text-secondary: #6B7562;
            --color-text-tertiary: #9CA594;
            --color-text-muted: #B0BAA8;
            --color-bg: #F5F7F3;
            --color-surface: #FFFFFF;
            --color-border: #E5E9E2;
            --color-surface-hover: #F0F2EE;
            --color-surface-primary: #FFFFFF;
            --color-surface-secondary: #F5F7F3;
            --color-border-default: #E5E9E2;
        }
        .bg-primary { background-color: var(--color-primary) !important; }
        .bg-primary-light { background-color: var(--color-primary-light) !important; }
        .bg-primary-lighter { background-color: var(--color-primary-lighter) !important; }
        .bg-primary-50 { background-color: var(--color-municipal-50) !important; }
        .bg-primary-100 { background-color: var(--color-municipal-100) !important; }
        .bg-success { background-color: var(--color-success) !important; }
        .bg-success-light { background-color: var(--color-success-light) !important; }
        .bg-warning { background-color: var(--color-warning) !important; }
        .bg-warning-light { background-color: var(--color-warning-light) !important; }
        .bg-danger { background-color: var(--color-danger) !important; }
        .bg-danger-light { background-color: var(--color-danger-light) !important; }
        .bg-info { background-color: var(--color-info) !important; }
        .bg-info-light { background-color: var(--color-info-light) !important; }
        .bg-text { background-color: var(--color-text) !important; }
        .bg-text-secondary { background-color: var(--color-text-secondary) !important; }
        .bg-text-tertiary { background-color: var(--color-text-tertiary) !important; }
        .bg-text-muted { background-color: var(--color-text-muted) !important; }
        .bg-bg { background-color: var(--color-bg) !important; }
        .bg-surface { background-color: var(--color-surface) !important; }
        .bg-surface-hover { background-color: var(--color-surface-hover) !important; }
        .bg-surface-primary { background-color: var(--color-surface-primary) !important; }
        .bg-surface-secondary { background-color: var(--color-surface-secondary) !important; }
        .bg-border { background-color: var(--color-border) !important; }
        .bg-border-default { background-color: var(--color-border-default) !important; }
        .text-primary { color: var(--color-primary) !important; }
        .text-primary-light { color: var(--color-primary-light) !important; }
        .text-primary-lighter { color: var(--color-primary-lighter) !important; }
        .text-primary-50 { color: var(--color-municipal-50) !important; }
        .text-primary-100 { color: var(--color-municipal-100) !important; }
        .text-primary-200 { color: var(--color-municipal-200) !important; }
        .text-primary-300 { color: var(--color-municipal-300) !important; }
        .text-primary-400 { color: var(--color-municipal-400) !important; }
        .text-primary-500 { color: var(--color-municipal-500) !important; }
        .text-primary-600 { color: var(--color-municipal-600) !important; }
        .text-primary-700 { color: var(--color-municipal-700) !important; }
        .text-primary-800 { color: var(--color-municipal-800) !important; }
        .text-success { color: var(--color-success) !important; }
        .text-success-light { color: var(--color-success-light) !important; }
        .text-warning { color: var(--color-warning) !important; }
        .text-warning-light { color: var(--color-warning-light) !important; }
        .text-danger { color: var(--color-danger) !important; }
        .text-danger-light { color: var(--color-danger-light) !important; }
        .text-info { color: var(--color-info) !important; }
        .text-info-light { color: var(--color-info-light) !important; }
        .text-text { color: var(--color-text) !important; }
        .text-text-secondary { color: var(--color-text-secondary) !important; }
        .text-text-tertiary { color: var(--color-text-tertiary) !important; }
        .text-text-muted { color: var(--color-text-muted) !important; }
        .text-bg { color: var(--color-bg) !important; }
        .text-surface { color: var(--color-surface) !important; }
        .text-border { color: var(--color-border) !important; }
        .text-text-primary { color: var(--color-text) !important; }
        .border-border { border-color: var(--color-border) !important; }
        .border-border-default { border-color: var(--color-border-default) !important; }
        .border-primary { border-color: var(--color-primary) !important; }
        .border-danger { border-color: var(--color-danger) !important; }
        .border-success { border-color: var(--color-success) !important; }
        .font-sans { font-family: var(--font-sans) !important; }
        .from-primary { --tw-gradient-from: var(--color-primary) !important; }
        .via-primary-light { --tw-gradient-via: var(--color-primary-light) !important; }
        .to-primary-lighter { --tw-gradient-to: var(--color-primary-lighter) !important; }
        .from-municipal-700 { --tw-gradient-from: var(--color-municipal-700) !important; }
        .via-municipal-600 { --tw-gradient-via: var(--color-municipal-600) !important; }
        .to-municipal-500 { --tw-gradient-to: var(--color-municipal-500) !important; }
        .hover\:text-primary:hover { color: var(--color-primary) !important; }
        .hover\:text-primary-dark:hover { color: var(--color-municipal-800) !important; }
        .hover\:bg-surface-hover:hover { background-color: var(--color-surface-hover) !important; }
        .hover\:bg-primary:hover { background-color: var(--color-primary) !important; }
        .hover\:bg-danger:hover { background-color: var(--color-danger) !important; }
        .hover\:bg-danger-light:hover { background-color: var(--color-danger-light) !important; }
        .hover\:bg-success:hover { background-color: var(--color-success) !important; }
        .hover\:bg-success-light:hover { background-color: var(--color-success-light) !important; }
        .hover\:bg-warning:hover { background-color: var(--color-warning) !important; }
        .hover\:bg-warning-light:hover { background-color: var(--color-warning-light) !important; }
        .group:hover .group-hover\:text-text { color: var(--color-text) !important; }
        .focus\:border-primary:focus { border-color: var(--color-primary) !important; }
        .focus\:ring-primary { --tw-ring-color: var(--color-primary) !important; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="min-h-screen bg-surface font-sans antialiased">
    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-municipal-700 via-municipal-600 to-municipal-500">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 800 600" fill="none">
                    <circle cx="200" cy="150" r="120" fill="white" opacity="0.08"/>
                    <circle cx="600" cy="400" r="200" fill="white" opacity="0.06"/>
                    <circle cx="100" cy="500" r="80" fill="white" opacity="0.05"/>
                    <rect x="300" y="50" width="200" height="200" rx="20" fill="white" opacity="0.04" transform="rotate(15 400 150)"/>
                    <rect x="500" y="300" width="150" height="150" rx="16" fill="white" opacity="0.04" transform="rotate(-10 575 375)"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col items-center justify-center w-full p-12 text-white">
                <div class="mb-8">
                    <img
                        src="<?php echo e(App\Domains\SharedKernel\Services\MediaResolver::logoUrl()); ?>"
                        alt="<?php echo e(config('app.name')); ?>"
                        class="w-28 h-28 object-contain drop-shadow-lg"
                    />
                </div>

                <h1 class="text-4xl font-bold mb-3 text-center"><?php echo e(config('app.name')); ?></h1>
                <p class="text-municipal-200 text-lg text-center max-w-md leading-relaxed">
                    نظام إدارة الخدمات البلدية الذكي
                </p>

                <div class="mt-16 grid grid-cols-3 gap-8 max-w-lg">
                    <div class="text-center">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm text-municipal-200 font-medium">الأقسام</p>
                    </div>
                    <div class="text-center">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm text-municipal-200 font-medium">الخدمات</p>
                    </div>
                    <div class="text-center">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm text-municipal-200 font-medium">التقارير</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex-1 flex items-center justify-center p-6 sm:p-8 lg:p-12">
            <div class="w-full max-w-md">
                
                <div class="lg:hidden text-center mb-10">
                    <img
                        src="<?php echo e(App\Domains\SharedKernel\Services\MediaResolver::logoUrl()); ?>"
                        alt="<?php echo e(config('app.name')); ?>"
                        class="w-20 h-20 mx-auto object-contain mb-4"
                    />
                    <h1 class="text-2xl font-bold text-primary"><?php echo e(config('app.name')); ?></h1>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-text mb-2">مرحباً بعودتك</h2>
                    <p class="text-text-tertiary">سجّل دخولك للوصول إلى لوحة التحكم</p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="mb-6 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
                        <span class="text-sm text-success font-medium"><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php echo e($slot); ?>


                <p class="mt-8 text-center text-xs text-text-tertiary">
                    &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. جميع الحقوق محفوظة.
                </p>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/layouts/auth.blade.php ENDPATH**/ ?>