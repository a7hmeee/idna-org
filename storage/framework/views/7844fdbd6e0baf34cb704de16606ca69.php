<div class="flex flex-wrap gap-2 mt-3" dir="rtl">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($action['url'])): ?>
            <a href="<?php echo e($action['url']); ?>" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary text-white text-xs rounded-xl hover:bg-primary-dark transition-colors no-underline font-medium shadow-sm">
                <?php echo e(e($action['label'])); ?>

                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            </a>
        <?php else: ?>
            <?php
                $actionKey = $action['key'] ?? ($action['value'] ?? '');
                $actionLabel = $action['label'] ?? $actionKey;
                $wireValue = $action['value'] ?? $actionKey;
            ?>
            <button type="button"
                    wire:click="quickAction('<?php echo e(e($wireValue)); ?>', '<?php echo e(e($actionLabel)); ?>')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary-light text-primary text-xs rounded-xl hover:bg-primary hover:text-white border border-primary/20 hover:border-primary transition-all cursor-pointer border-none font-medium">
                <?php echo e(e($actionLabel)); ?>

            </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/chatbot/quick-actions.blade.php ENDPATH**/ ?>