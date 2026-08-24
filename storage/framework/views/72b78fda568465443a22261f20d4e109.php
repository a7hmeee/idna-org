<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'service' => null,
    'route' => '#',
    'showCategory' => true,
    'icon' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'service' => null,
    'route' => '#',
    'showCategory' => true,
    'icon' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $svc = $service;
    $iconName = $icon ?? ($svc->category?->icon ?? 'file-text');
?>

<a href="<?php echo e($route); ?>" wire:navigate
   class="group block bg-white rounded-2xl border border-gray-100 p-5 text-decoration-none transition-all duration-300"
   style="box-shadow:0 1px 2px rgba(0,0,0,0.03),0 1px 3px rgba(0,0,0,0.04);"
   onmouseover="this.style.borderColor='rgba(46,125,50,0.2)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06),0 2px 4px rgba(0,0,0,0.04)';this.style.transform='translateY(-2px)'"
   onmouseout="this.style.borderColor='#E5E7EB';this.style.boxShadow='0 1px 2px rgba(0,0,0,0.03),0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
    <div class="flex items-start gap-3">
        <div class="w-11 h-11 rounded-xl bg-primary/5 group-hover:bg-primary flex items-center justify-center flex-shrink-0 transition-all duration-300">
            <i data-lucide="<?php echo e($iconName); ?>" class="w-5 h-5 text-primary group-hover:text-white transition-colors duration-300"></i>
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="text-sm font-bold text-gray-900 group-hover:text-primary transition-colors mb-1"><?php echo e($svc->name); ?></h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($svc->summary): ?>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-2"><?php echo e($svc->summary); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="flex items-center gap-2 flex-wrap">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCategory && $svc->category): ?>
                    <span class="text-[10px] font-semibold text-primary bg-primary/5 px-2.5 py-1 rounded-md"><?php echo e($svc->category->name); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($svc->requires_login): ?>
                    <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md">يتطلب تسجيل دخول</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($svc->processing_time): ?>
                    <span class="text-[10px] text-gray-400 inline-flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <?php echo e($svc->processing_time); ?>

                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-200 group-hover:text-primary transition-all duration-300 group-hover:-translate-x-0.5 mt-2 flex-shrink-0"></i>
    </div>
</a>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/services/service-card.blade.php ENDPATH**/ ?>