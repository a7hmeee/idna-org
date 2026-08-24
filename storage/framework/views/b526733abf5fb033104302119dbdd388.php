<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'fees' => [],
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
    'fees' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($fees)): ?>
    <div>
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="wallet" style="width:14px;height:14px;color:#0F6A3D;"></i>
            الرسوم
        </h3>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $feeTitle = is_string($fee) ? $fee : ($fee['title'] ?? '');
                    $feeNotes = is_string($fee) ? '' : ($fee['notes'] ?? '');
                    $feeAmount = is_string($fee) ? 0 : (float) ($fee['amount'] ?? 0);
                    $feeCurrency = is_string($fee) ? 'ILS' : ($fee['currency'] ?? 'ILS');
                ?>
                <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'fee-'.e($index).''; ?>wire:key="fee-<?php echo e($index); ?>" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-radius:10px;background:#F9FAFB;border:1px solid #F3F4F6;">
                    <div style="min-width:0;flex:1;">
                        <p style="font-size:13px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($feeTitle); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($feeNotes)): ?>
                            <p style="font-size:12px;color:#9CA3AF;margin:4px 0 0;"><?php echo e($feeNotes); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div style="text-align:left;flex-shrink:0;margin-right:12px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($feeAmount > 0): ?>
                            <p style="font-size:13px;font-weight:700;color:#0F6A3D;margin:0;"><?php echo e(number_format($feeAmount, 2)); ?> <span style="font-size:10px;font-weight:500;color:#9CA3AF;"><?php echo e($feeCurrency); ?></span></p>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:#ECFDF5;color:#059669;">
                                <i data-lucide="check" style="width:11px;height:11px;"></i>
                                مجانية
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/services/service-fees.blade.php ENDPATH**/ ?>