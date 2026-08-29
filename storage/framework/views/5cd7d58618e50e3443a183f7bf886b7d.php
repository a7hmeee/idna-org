<?php
    $isWorkflow = in_array($type ?? '', ['workflow_question', 'workflow_confirmation', 'workflow_success', 'workflow_cancelled', 'workflow_resumed', 'workflow_resume', 'workflow_validation_error', 'workflow_interrupt_confirmation', 'workflow_not_found', 'workflow_expired', 'workflow_failure', 'workflow_completed']);
    $hasProgress = $type !== 'workflow_success' && $type !== 'workflow_cancelled' && $type !== 'workflow_resume' && $currentStep !== null && $totalSteps !== null;
    $stepLabel = $currentStepLabel ?? $currentStep ?? null;
    $progressVal = is_numeric($progressPercent) ? min(100, max(0, $progressPercent)) : 0;
?>

<div class="bg-surface border border-border rounded-xl p-4" dir="rtl">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($workflowType)): ?>
                <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div>
                <p class="text-sm font-semibold text-text"><?php echo e(e($message)); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasProgress): ?>
                    <p class="text-[11px] text-text-tertiary">الخطوة <?php echo e($stepLabel); ?> من <?php echo e($totalSteps); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasProgress): ?>
            <div class="flex items-center gap-1.5">
                <div class="w-16 h-1.5 bg-municipal-50 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all" style="width: <?php echo e($progressVal); ?>%"></div>
                </div>
                <span class="text-[10px] text-text-tertiary font-medium"><?php echo e(round($progressVal)); ?>%</span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($type ?? '') === 'workflow_success' || ($type ?? '') === 'workflow_completed'): ?>
        <div class="bg-success-light border border-success/20 rounded-lg p-3 mt-2">
            <div class="flex items-center gap-2 mb-1">
                <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                <p class="text-sm font-semibold text-success">تم الإرسال بنجاح</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($trackingNumber)): ?>
                <p class="text-xs text-text-secondary">رقم المتابعة: <span class="font-mono font-bold text-primary"><?php echo e(e($trackingNumber)); ?></span></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($type ?? '') === 'workflow_cancelled'): ?>
        <div class="bg-danger-light border border-danger/20 rounded-lg p-3 mt-2">
            <div class="flex items-center gap-2">
                <i data-lucide="x-circle" class="w-4 h-4 text-danger"></i>
                <p class="text-sm font-semibold text-danger">تم إلغاء الطلب</p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($type ?? '') === 'workflow_resumed'): ?>
        <div class="bg-info-light border border-info/20 rounded-lg p-3 mt-2">
            <div class="flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-info"></i>
                <p class="text-sm font-semibold text-info">تم استئناف الطلب</p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($type ?? '') === 'workflow_validation_error'): ?>
        <div class="mt-2 bg-danger-light border border-danger/20 rounded-lg p-3">
            <p class="text-xs text-danger"><?php echo e(e($message)); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($type ?? '') === 'workflow_interrupt_confirmation'): ?>
        <div class="mt-2 bg-info-light border border-info/20 rounded-lg p-3">
            <p class="text-xs text-info"><?php echo e(e($message)); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($type ?? '', ['workflow_not_found', 'workflow_expired', 'workflow_failure'], true)): ?>
        <div class="mt-2 bg-info-light border border-info/20 rounded-lg p-3">
            <p class="text-xs text-info"><?php echo e(e($message)); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($actions)): ?>
        <?php echo $__env->make('components.chatbot.quick-actions', ['actions' => $actions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($type ?? '') === 'workflow_success' && !empty($actions) && ($messageId ?? null) !== null): ?>
        <?php echo $__env->make('components.chatbot.feedback', ['messageId' => $messageId], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/chatbot/workflow-card.blade.php ENDPATH**/ ?>