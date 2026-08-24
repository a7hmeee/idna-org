<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'steps' => [],
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
    'steps' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($steps)): ?>
    <div>
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 20px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="list-ordered" style="width:14px;height:14px;color:#0F6A3D;"></i>
            خطوات التقديم
        </h3>
        <div style="position:relative;">
            <ul style="list-style:none;padding:0;margin:0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $stepTitle = is_string($step) ? $step : ($step['title'] ?? '');
                        $stepDesc = is_string($step) ? '' : ($step['description'] ?? '');
                        $isLast = $loop->last;
                    ?>
                    <li <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'step-'.e($index).''; ?>wire:key="step-<?php echo e($index); ?>" style="position:relative;display:flex;align-items:flex-start;gap:12px;<?php echo e(!$isLast ? 'padding-bottom:24px;' : ''); ?>">
                        
                        <div style="position:relative;display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                            
                            <div style="width:32px;height:32px;border-radius:9999px;background:#0F6A3D;color:white;font-size:12px;font-weight:900;display:flex;align-items:center;justify-content:center;z-index:2;box-shadow:0 2px 8px rgba(15,106,61,0.25);">
                                <?php echo e($index + 1); ?>

                            </div>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isLast): ?>
                                <div style="width:2px;flex:1;background:#0F6A3D;border-radius:9999px;margin-top:6px;opacity:0.2;"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div style="min-width:0;flex:1;padding-top:5px;">
                            <p style="font-size:13px;font-weight:700;color:#1F2937;margin:0;"><?php echo e($stepTitle); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($stepDesc)): ?>
                                <p style="font-size:12px;color:#6B7280;margin:5px 0 0;line-height:1.7;"><?php echo e($stepDesc); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/services/service-steps.blade.php ENDPATH**/ ?>