<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'announcements',
        'pageTitle' => $announcement->title,
        'pageSubtitle' => $announcement->short_description ?? null,
        'pageBadge' => 'إعلان',
        'pageBadgeIcon' => 'megaphone',
        'compact' => true,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1243507478-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

    
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div class="flex flex-wrap items-center gap-2 py-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                <?php if($announcement->priority->value === 'urgent'): ?> bg-danger/10 text-danger
                <?php elseif($announcement->priority->value === 'important'): ?> bg-warning/10 text-warning
                <?php else: ?> bg-info/10 text-info <?php endif; ?>">
                <i data-lucide="<?php echo e($announcement->priority->value === 'urgent' ? 'alert-triangle' : ($announcement->priority->value === 'important' ? 'alert-circle' : 'info')); ?>" class="w-3 h-3"></i>
                <?php echo e($announcement->priority->label()); ?>

            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-municipal-50 text-text-tertiary">
                <?php echo e($announcement->type->label()); ?>

            </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->published_at): ?>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-municipal-50 text-text-tertiary">
                    <i data-lucide="calendar" class="w-3 h-3"></i>
                    <?php echo e($announcement->published_at->format('Y/m/d')); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->is_featured): ?>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-yellow-50 text-yellow-600">
                    <i data-lucide="star" class="w-3 h-3"></i>
                    مميز
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-municipal-50 text-text-tertiary">
                <i data-lucide="eye" class="w-3 h-3"></i>
                <?php echo e(number_format($announcement->views)); ?> مشاهدة
            </span>
        </div>
    </div>

    
    
    
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                
                <div class="lg:col-span-2 space-y-6">

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->desktop_image_path): ?>
                        <div class="rounded-xl overflow-hidden border border-gray-200">
                            <img src="<?php echo e(asset('storage/' . $announcement->desktop_image_path)); ?>" alt="<?php echo e($announcement->title); ?>" class="w-full object-cover" />
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-text mb-3">نبذة عن الإعلان</h2>
                        <p class="text-text-secondary leading-relaxed"><?php echo e($announcement->short_description); ?></p>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->content): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">التفاصيل</h2>
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line"><?php echo e($announcement->content); ?></div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>

                
                <div class="space-y-4">

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedAnnouncements->isNotEmpty()): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="text-sm font-bold text-text mb-3">إعلانات مشابهة</h3>
                            <div class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $relatedAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <a href="<?php echo e(route('public.announcements.show', $related->slug)); ?>" wire:navigate
                                       class="flex items-start gap-3 no-underline group">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($related->desktop_image_path): ?>
                                            <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
                                                <img src="<?php echo e(asset('storage/' . $related->desktop_image_path)); ?>" alt="<?php echo e($related->title); ?>" class="w-full h-full object-cover" loading="lazy" />
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-text group-hover:text-primary transition-colors line-clamp-2"><?php echo e($related->title); ?></p>
                                            <p class="text-[10px] text-text-tertiary mt-0.5"><?php echo e($related->published_at?->format('Y/m/d')); ?></p>
                                        </div>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </div>
        </div>
    </section>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/announcements/public-announcement-show.blade.php ENDPATH**/ ?>