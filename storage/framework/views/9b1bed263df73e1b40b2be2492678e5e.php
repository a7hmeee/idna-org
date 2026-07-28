<div>
     <?php $__env->slot('title', null, []); ?> أقسام الصفحة الرئيسية <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">أقسام الصفحة الرئيسية</h1>
            <p class="text-sm text-text-tertiary mt-1">إظهار، إخفاء، وترتيب أقسام الصفحة الرئيسية</p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">القسم</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">حد العناصر</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text"><?php echo e($section['title']); ?></p>
                                <p class="text-xs text-text-tertiary mt-0.5"><?php echo e($section['key']); ?></p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary"><?php echo e($section['sort_order']); ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($section['key'], ['services', 'departments', 'council_members', 'council_decisions', 'engineering_offices', 'latest_news', 'projects', 'announcements'])): ?>
                                    <input type="number" wire:change="updateLimit('<?php echo e($section['key']); ?>', $event.target.value)" value="<?php echo e($section['items_limit'] ?? 6); ?>" min="1" max="50" class="w-20 bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                <?php else: ?>
                                    <span class="text-sm text-text-tertiary">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggle('<?php echo e($section['key']); ?>')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors <?php if($section['is_enabled']): ?> bg-success/10 text-success hover:bg-success/20 <?php else: ?> bg-danger/10 text-danger hover:bg-danger/20 <?php endif; ?>">
                                    <i data-lucide="<?php echo e($section['is_enabled'] ? 'eye' : 'eye-off'); ?>" class="w-3 h-3"></i>
                                    <?php echo e($section['is_enabled'] ? 'مفعل' : 'معطل'); ?>

                                </button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections-manager.blade.php ENDPATH**/ ?>