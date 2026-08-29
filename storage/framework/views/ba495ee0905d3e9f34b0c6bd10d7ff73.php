<div class="bg-surface border border-primary/30 rounded-xl p-4" dir="rtl">
    <div class="flex items-center gap-2 mb-3">
        <div class="w-8 h-8 rounded-lg bg-info-light flex items-center justify-center">
            <i data-lucide="search" class="w-4 h-4 text-info"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text">نتيجة المتابعة</p>
            <p class="text-[11px] text-text-tertiary">رقم المتابعة: <?php echo e(e($item['tracking_number'] ?? '')); ?></p>
        </div>
    </div>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-text-tertiary">النوع</span>
            <span class="text-text font-medium"><?php echo e(e($item['type'] ?? '')); ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-text-tertiary">الحالة</span>
            <?php
                $status = $item['status'] ?? '';
                $statusClass = match(true) {
                    str_contains($status, 'مكتمل') || str_contains($status, 'closed') => 'text-success',
                    str_contains($status, 'قيد') || str_contains($status, 'progress') => 'text-warning',
                    default => 'text-text-secondary',
                };
            ?>
            <span class="font-medium <?php echo e($statusClass); ?>"><?php echo e(e($item['status_label'] ?? $item['status'] ?? '')); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['submitted_date'])): ?>
            <div class="flex justify-between">
                <span class="text-text-tertiary">تاريخ التقديم</span>
                <span class="text-text font-medium"><?php echo e(e($item['submitted_date'])); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['subject'])): ?>
            <div class="flex justify-between">
                <span class="text-text-tertiary">الموضوع</span>
                <span class="text-text font-medium text-left"><?php echo e(e($item['subject'])); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['department'])): ?>
            <div class="flex justify-between">
                <span class="text-text-tertiary">القسم</span>
                <span class="text-text font-medium"><?php echo e(e($item['department'])); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/chatbot/tracking-card.blade.php ENDPATH**/ ?>