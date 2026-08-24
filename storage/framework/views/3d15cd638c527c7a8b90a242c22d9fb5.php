<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'documents' => [],
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
    'documents' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($documents)): ?>
    <div>
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="file-text" style="width:14px;height:14px;color:#0F6A3D;"></i>
            الوثائق المطلوبة
        </h3>
        <div style="display:grid;grid-template-columns:1fr;gap:8px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $docName = is_string($doc) ? $doc : ($doc['name'] ?? '');
                    $docDesc = is_string($doc) ? '' : ($doc['description'] ?? '');
                    $docRequired = is_string($doc) ? true : ($doc['is_required'] ?? true);
                ?>
                <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'doc-'.e($index).''; ?>wire:key="doc-<?php echo e($index); ?>" style="display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-radius:10px;background:#F9FAFB;border:1px solid #F3F4F6;">
                    <div style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="file" style="width:13px;height:13px;color:#0F6A3D;"></i>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <p style="font-size:13px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($docName); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($docDesc)): ?>
                            <p style="font-size:12px;color:#9CA3AF;margin:4px 0 0;"><?php echo e($docDesc); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <span style="font-size:9px;font-weight:700;margin-top:4px;display:inline-block;<?php echo e($docRequired ? 'color:#DC2626;' : 'color:#9CA3AF;'); ?>">
                            <?php echo e($docRequired ? 'إلزامي' : 'اختياري'); ?>

                        </span>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/services/service-documents.blade.php ENDPATH**/ ?>