<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'announcements',
        'fallbackTitle' => 'الإعلانات',
        'fallbackDescription' => 'تصفح جميع الإعلانات الرسمية الصادرة عن بلدية إذنا، واطلع على آخر المستجدات والتنبيهات.',
        'fallbackBadge' => 'الإعلانات',
        'fallbackIcon' => 'megaphone',
        'compact' => false,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2242732665-0', $__key);

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

    
    
    
    <section id="announcements-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="flex flex-col gap-4 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button wire:click="$set('sort', 'latest')"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 border"
                                style="border-color:<?php echo e($sort == 'latest' ? '#0F6A3D' : '#E5E7EB'); ?>;background:<?php echo e($sort == 'latest' ? '#0F6A3D' : 'white'); ?>;color:<?php echo e($sort == 'latest' ? 'white' : '#6B7280'); ?>;">
                            الأحدث
                        </button>
                        <button wire:click="$set('sort', 'oldest')"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 border"
                                style="border-color:<?php echo e($sort == 'oldest' ? '#0F6A3D' : '#E5E7EB'); ?>;background:<?php echo e($sort == 'oldest' ? '#0F6A3D' : 'white'); ?>;color:<?php echo e($sort == 'oldest' ? 'white' : '#6B7280'); ?>;">
                            الأقدم
                        </button>
                    </div>
                    <div class="relative w-full max-w-xs">
                        <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary pointer-events-none"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               placeholder="ابحث في الإعلانات..."
                               class="w-full bg-surface-secondary border border-border rounded-lg py-2.5 pr-10 pl-4 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                </div>

                
                <div class="flex flex-wrap items-center gap-2">
                    <select wire:model.live="type" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-xs text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">جميع الأنواع</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($t->value); ?>"><?php echo e($t->label()); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <select wire:model.live="priority" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-xs text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">جميع الأولويات</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($p->value); ?>"><?php echo e($p->label()); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $type || $priority): ?>
                        <button wire:click="clearFilters" class="text-xs text-danger font-semibold hover:underline px-2">مسح الكل</button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-text-tertiary">
                    يوجد <span class="font-bold text-text"><?php echo e($announcements->total() ?? 0); ?></span> إعلان
                </p>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured->isNotEmpty()): ?>
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                        <h2 class="text-sm font-bold text-text">إعلانات مميزة</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('public.announcements.show', $announcement->slug)); ?>" wire:navigate
                               class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 no-underline">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->desktop_image_path): ?>
                                    <div class="aspect-video overflow-hidden">
                                        <img src="<?php echo e(asset('storage/' . $announcement->desktop_image_path)); ?>" alt="<?php echo e($announcement->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded
                                            <?php if($announcement->priority->value === 'urgent'): ?> bg-danger/10 text-danger
                                            <?php elseif($announcement->priority->value === 'important'): ?> bg-warning/10 text-warning
                                            <?php else: ?> bg-info/10 text-info <?php endif; ?>">
                                            <?php echo e($announcement->priority->label()); ?>

                                        </span>
                                        <span class="text-[10px] text-text-tertiary"><?php echo e($announcement->type->label()); ?></span>
                                    </div>
                                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors leading-snug"><?php echo e($announcement->title); ?></h3>
                                    <p class="text-xs text-text-tertiary mt-1 line-clamp-2"><?php echo e($announcement->short_description); ?></p>
                                    <p class="text-[10px] text-text-tertiary mt-2"><?php echo e($announcement->published_at?->format('Y/m/d')); ?></p>
                                </div>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcements->isNotEmpty()): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('public.announcements.show', $announcement->slug)); ?>" wire:navigate
                           class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 no-underline">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->desktop_image_path): ?>
                                <div class="aspect-video overflow-hidden">
                                    <img src="<?php echo e(asset('storage/' . $announcement->desktop_image_path)); ?>" alt="<?php echo e($announcement->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded
                                        <?php if($announcement->priority->value === 'urgent'): ?> bg-danger/10 text-danger
                                        <?php elseif($announcement->priority->value === 'important'): ?> bg-warning/10 text-warning
                                        <?php else: ?> bg-info/10 text-info <?php endif; ?>">
                                        <?php echo e($announcement->priority->label()); ?>

                                    </span>
                                    <span class="text-[10px] text-text-tertiary"><?php echo e($announcement->type->label()); ?></span>
                                </div>
                                <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors leading-snug"><?php echo e($announcement->title); ?></h3>
                                <p class="text-xs text-text-tertiary mt-1 line-clamp-2"><?php echo e($announcement->short_description); ?></p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-[10px] text-text-tertiary"><?php echo e($announcement->published_at?->format('Y/m/d')); ?></p>
                                    <span class="text-[10px] text-text-tertiary flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        <?php echo e(number_format($announcement->views)); ?>

                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcements->hasPages()): ?>
                    <div class="mt-8">
                        <?php echo e($announcements->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <div class="text-center py-16">
                    <i data-lucide="megaphone" class="w-12 h-12 text-text-tertiary mx-auto mb-4"></i>
                    <p class="text-text-tertiary">لا توجد إعلانات حالياً</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/announcements/public-announcements-index.blade.php ENDPATH**/ ?>