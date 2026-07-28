<div>
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-text mb-2">متابعة شكوى</h1>
            <p class="text-text-tertiary">أدخل رقم التتبع للاستعلام عن حالة شكواك</p>
        </div>

        
        <div class="bg-surface rounded-2xl border border-border p-6 mb-6">
            <form wire:submit="track" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" wire:model="trackingNumber" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-3 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="أدخل رقم التتبع (مثال: CMP-XXXXXXXXXX)" />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['trackingNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors shrink-0" wire:loading.attr="disabled">
                    <span wire:loading.remove>بحث</span>
                    <span wire:loading>جاري البحث...</span>
                </button>
            </form>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($searched): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint): ?>
                <div class="bg-surface rounded-2xl border border-border overflow-hidden">
                    
                    <div class="p-6 border-b border-border">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-text">تفاصيل الشكوى</h2>
                            <span class="font-mono text-xs text-primary bg-primary/5 px-3 py-1.5 rounded-lg"><?php echo e($complaint->tracking_number); ?></span>
                        </div>

                        
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm text-text-tertiary">الحالة:</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                <?php if($complaint->status->value === 'resolved' || $complaint->status->value === 'closed'): ?> bg-success/10 text-success
                                <?php elseif($complaint->status->value === 'rejected'): ?> bg-danger/10 text-danger
                                <?php elseif($complaint->status->value === 'submitted'): ?> bg-info/10 text-info
                                <?php else: ?> bg-warning/10 text-warning <?php endif; ?>">
                                <?php echo e($complaint->status->label()); ?>

                            </span>
                        </div>
                        <span class="text-xs text-text-tertiary">آخر تحديث: <?php echo e($complaint->updated_at?->format('Y-m-d H:i')); ?></span>
                    </div>

                    
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-xs font-semibold text-text-tertiary block mb-1">التصنيف</span>
                            <span class="text-sm text-text"><?php echo e($complaint->category->label()); ?></span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-text-tertiary block mb-1">الموضوع</span>
                            <span class="text-sm text-text"><?php echo e($complaint->subject); ?></span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-text-tertiary block mb-1">الوصف</span>
                            <p class="text-sm text-text-secondary"><?php echo e($complaint->description); ?></p>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->location): ?>
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-1">الموقع</span>
                                <span class="text-sm text-text"><?php echo e($complaint->location); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->submitted_at): ?>
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-1">تاريخ التقديم</span>
                                <span class="text-sm text-text"><?php echo e($complaint->submitted_at->format('Y-m-d H:i')); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->resolution_at): ?>
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-1">تاريخ الحل</span>
                                <span class="text-sm text-text"><?php echo e($complaint->resolution_at->format('Y-m-d H:i')); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->public_response): ?>
                            <div class="bg-success-light border border-success/20 rounded-xl p-4">
                                <span class="text-xs font-semibold text-success block mb-1">الرد</span>
                                <p class="text-sm text-text"><?php echo e($complaint->public_response); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->attachments && count($complaint->attachments) > 0): ?>
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-2">المرفقات</span>
                                <div class="flex flex-wrap gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $complaint->attachments_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <a href="<?php echo e($url); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-secondary text-xs text-primary font-semibold hover:bg-border transition-colors">
                                            <i data-lucide="file" class="w-3.5 h-3.5"></i>
                                            <span>مرفق</span>
                                        </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->attachments && count($complaint->attachments) > 0): ?>
                            <div class="pt-4 border-t border-border">
                                <span class="text-xs font-semibold text-text-tertiary block mb-2">المرفقات</span>
                                <div class="flex flex-wrap gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $complaint->attachments_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <a href="<?php echo e($url); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-secondary text-xs text-primary font-semibold hover:bg-border transition-colors">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            <span>عرض المرفق</span>
                                        </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-surface rounded-2xl border border-border p-8 text-center">
                    <div class="w-14 h-14 rounded-full bg-warning/10 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="search-x" class="w-6 h-6 text-warning"></i>
                    </div>
                    <h3 class="font-semibold text-text mb-1">لم يتم العثور على الشكوى</h3>
                    <p class="text-sm text-text-tertiary">تأكد من صحة رقم التتبع وحاول مرة أخرى</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/complaints/public-complaint-tracking.blade.php ENDPATH**/ ?>