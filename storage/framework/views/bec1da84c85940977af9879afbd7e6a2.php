<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'requirements' => [],
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
    'requirements' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($requirements)): ?>
    <div style="margin-bottom:20px;">
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 16px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="clipboard-list" style="width:14px;height:14px;color:#0F6A3D;"></i>
            المتطلبات
        </h3>
        <div style="position:relative;padding-right:0;">
            <ul style="list-style:none;padding:0;margin:0;position:relative;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $reqTitle = is_string($req) ? $req : ($req['title'] ?? '');
                        $reqRequired = is_string($req) ? true : ($req['is_required'] ?? true);
                        $reqDesc = is_string($req) ? '' : ($req['description'] ?? '');
                        $isLast = $loop->last;
                    ?>
                    <li <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'req-'.e($index).''; ?>wire:key="req-<?php echo e($index); ?>" style="position:relative;display:flex;align-items:flex-start;gap:12px;<?php echo e(!$isLast ? 'padding-bottom:20px;' : ''); ?>">
                        
                        <div style="position:relative;display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                            
                            <div style="width:28px;height:28px;border-radius:9999px;display:flex;align-items:center;justify-content:center;z-index:2;<?php echo e($reqRequired ? 'background:#0F6A3D;color:white;' : 'background:#F3F4F6;border:2px solid #E5E7EB;color:#9CA3AF;'); ?>">
                                <i data-lucide="<?php echo e($reqRequired ? 'check' : 'circle'); ?>" style="width:14px;height:14px;"></i>
                            </div>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isLast): ?>
                                <div style="width:2px;flex:1;background:<?php echo e($reqRequired ? '#0F6A3D' : '#E5E7EB'); ?>;border-radius:9999px;margin-top:4px;opacity:0.3;"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div style="min-width:0;flex:1;padding-top:3px;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <p style="font-size:13px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($reqTitle); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reqRequired): ?>
                                    <span style="font-size:9px;font-weight:700;color:#DC2626;background:#FEF2F2;padding:2px 7px;border-radius:4px;">إلزامي</span>
                                <?php else: ?>
                                    <span style="font-size:9px;font-weight:700;color:#9CA3AF;background:#F3F4F6;padding:2px 7px;border-radius:4px;border:1px solid #E5E7EB;">اختياري</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($reqDesc)): ?>
                                <p style="font-size:12px;color:#9CA3AF;margin:5px 0 0;line-height:1.6;"><?php echo e($reqDesc); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/services/service-requirements.blade.php ENDPATH**/ ?>