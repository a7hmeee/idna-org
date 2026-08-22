<div class="flex justify-end" dir="rtl">
    <div class="max-w-[85%] flex flex-col items-end gap-1">
        <div class="bg-primary text-white rounded-2xl rounded-bl-sm px-4 py-2.5 text-sm leading-relaxed shadow-sm">
            <p class="whitespace-pre-line"><?php echo e(e($content)); ?></p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($time)): ?>
            <span class="text-[10px] text-text-tertiary px-1"><?php echo e($time); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/chatbot/citizen-message.blade.php ENDPATH**/ ?>