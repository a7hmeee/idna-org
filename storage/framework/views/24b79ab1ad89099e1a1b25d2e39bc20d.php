<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator']));

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

foreach (array_filter((['paginator']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasPages()): ?>
    <?php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $window = 2;
        $elements = [];
        $showInfo = true;

        if ($current - $window > 1) {
            $elements[] = [$paginator->url(1) => 1];
        }
        if ($current - $window > 2) {
            $elements[] = '...';
        }
        for ($i = max(1, $current - $window); $i <= min($last, $current + $window); $i++) {
            $elements[] = [$paginator->url($i) => $i];
        }
        if ($current + $window < $last - 1) {
            $elements[] = '...';
        }
        if ($current + $window < $last) {
            $elements[] = [$paginator->url($last) => $last];
        }
    ?>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4" dir="rtl">
        
        <p style="font-size:12px;color:#9CA3AF;margin:0;">
            عرض
            <span style="font-weight:600;color:#4B5563;"><?php echo e($paginator->firstItem()); ?></span>
            إلى
            <span style="font-weight:600;color:#4B5563;"><?php echo e($paginator->lastItem()); ?></span>
            من أصل
            <span style="font-weight:600;color:#4B5563;"><?php echo e($paginator->total()); ?></span>
            نتيجة
        </p>

        
        <div class="flex items-center gap-1">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->onFirstPage()): ?>
                <span style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#D1D5DB;cursor:default;">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev"
                   style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#6B7280;text-decoration:none;transition:all 0.2s;"
                   onmouseover="this.style.background='#F3F4F6';this.style.borderColor='#E5E7EB';this.style.color='#0F6A3D'"
                   onmouseout="this.style.background='#F9FAFB';this.style.borderColor='#F3F4F6';this.style.color='#6B7280'">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_string($element)): ?>
                    <span style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#D1D5DB;cursor:default;"><?php echo e($element); ?></span>
                <?php elseif(is_array($element)): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url => $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page == $paginator->currentPage()): ?>
                            <span style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;background:#0F6A3D;color:white;box-shadow:0 2px 8px rgba(15,106,61,0.2);cursor:default;"><?php echo e($page); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>"
                               style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#6B7280;text-decoration:none;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6';this.style.color='#0F6A3D'"
                               onmouseout="this.style.background='transparent';this.style.color='#6B7280'"><?php echo e($page); ?></a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"
                   style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#6B7280;text-decoration:none;transition:all 0.2s;"
                   onmouseover="this.style.background='#F3F4F6';this.style.borderColor='#E5E7EB';this.style.color='#0F6A3D'"
                   onmouseout="this.style.background='#F9FAFB';this.style.borderColor='#F3F4F6';this.style.color='#6B7280'">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </a>
            <?php else: ?>
                <span style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#D1D5DB;cursor:default;">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/ui/pagination.blade.php ENDPATH**/ ?>