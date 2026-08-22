<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo e($title ?? $municipalityName ?? 'البلدية'); ?></title>
    <meta name="description" content="<?php echo e($metaDescription ?? ''); ?>">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">

    <meta property="og:title" content="<?php echo e($ogTitle ?? ($title ?? $municipalityName ?? 'البلدية')); ?>">
    <meta property="og:description" content="<?php echo e($ogDescription ?? $metaDescription ?? ''); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($logoUrl)): ?>
    <meta property="og:image" content="<?php echo e($logoUrl); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>[x-cloak] { display: none !important; } html { scroll-behavior: smooth; }</style>
</head>
<body class="bg-background font-sans text-text antialiased">

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($hideDefaultHeader ?? false)): ?>
        <?php echo $__env->make('livewire.homepage.sections.header', [
            'municipalityName' => $municipalityName ?? '',
            'municipalitySubtitle' => $municipalitySubtitle ?? '',
            'logoUrl' => $logoUrl ?? null,
            'portalUrl' => $portalUrl ?? '',
            'sectionKeys' => $sectionKeys ?? [],
            'settings' => $settings ?? [],
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php
        $urgentAnnouncements = collect();
        try {
            if (interface_exists(\App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface::class)) {
                $urgentAnnouncements = app(\App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface::class)->getUrgent();
            }
        } catch (\Throwable $e) {}
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($urgentAnnouncements->isNotEmpty()): ?>
        <div x-data="{ showUrgent: true }" x-show="showUrgent" class="relative bg-danger text-white text-xs font-medium">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 overflow-hidden flex-1">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-white/20 text-white text-[10px] font-bold whitespace-nowrap">
                        <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                        <span>عاجل</span>
                    </span>
                    <div class="flex items-center gap-4 overflow-x-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $urgentAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $urgent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('public.announcements.show', $urgent->slug)); ?>" wire:navigate class="text-white/90 hover:text-white transition-colors whitespace-nowrap no-underline">
                                <?php echo e($urgent->title); ?>

                            </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                                <span class="w-px h-4 bg-white/20"></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                <button @click="showUrgent = false" class="text-white/60 hover:text-white transition-colors shrink-0 cursor-pointer bg-none border-none">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo e($slot); ?>


    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($hideDefaultFooter ?? false)): ?>
        <?php echo $__env->make('livewire.homepage.sections.footer', [
            'municipality' => $municipality ?? [],
            'municipalityName' => $municipalityName ?? '',
            'municipalitySubtitle' => $municipalitySubtitle ?? '',
            'logoUrl' => $logoUrl ?? null,
            'contacts' => $contacts ?? [],
            'socialPlatforms' => $socialPlatforms ?? [],
            'portalUrl' => $portalUrl ?? '',
            'sectionKeys' => $sectionKeys ?? [],
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <button x-data="{ visible: false }"
            x-init="window.addEventListener('scroll', () => visible = window.scrollY > 400, { passive: true })"
            x-show="visible"
            x-transition
            @click="window.scrollTo({top:0,behavior:'smooth'})"
            class="fixed bottom-6 left-6 z-50 w-12 h-12 rounded-xl bg-primary text-white shadow-lg hover:bg-primary-dark transition-all flex items-center justify-center cursor-pointer"
            aria-label="العودة إلى الأعلى">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('chatbot.chatbot-widget', []);

$__keyOuter = $__key ?? null;

$__key = 'chatbot-public-widget';
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3987552741-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/layouts/home.blade.php ENDPATH**/ ?>