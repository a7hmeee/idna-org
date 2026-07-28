<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'projects',
        'fallbackTitle' => "مشاريع البلدية",
        'fallbackDescription' => "استعرض جميع مشاريع بلدية إذنا، وتابع نسب الإنجاز والتفاصيل الكاملة لكل مشروع.",
        'fallbackBadge' => 'المشاريع',
        'fallbackIcon' => 'hard-hat',
        'compact' => false,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1497933070-0', $__key);

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

    
    
    
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="flex flex-col gap-4 mb-7">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <select wire:model.live="category" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">جميع التصنيفات</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($c->value); ?>"><?php echo e($c->label()); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <select wire:model.live="projectStatus" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">جميع الحالات</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $projectStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($ps->value); ?>"><?php echo e($ps->label()); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="relative w-full max-w-xs">
                        <i data-lucide="search" class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary pointer-events-none"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               placeholder="ابحث عن مشروع..."
                               class="w-full bg-white border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-text-tertiary">
                    يوجد <span class="font-bold text-text"><?php echo e($projects->total() ?? 0); ?></span> مشروع
                </p>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projects->isEmpty()): ?>
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="hard-hat" class="w-8 h-8 text-text-tertiary"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text mb-2">لا توجد مشاريع حالياً</h3>
                    <p class="text-sm text-text-tertiary">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('public.projects.show', $project->slug)); ?>" wire:navigate
                           class="group block bg-white rounded-2xl border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-lg hover:border-primary/20"
                           style="text-decoration:none;">
                            
                            <div class="relative h-44 bg-surface-secondary overflow-hidden">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->cover_image_url): ?>
                                    <img src="<?php echo e($project->cover_image_url); ?>" alt="<?php echo e($project->name_ar); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="image" class="w-10 h-10 text-text-tertiary/40"></i>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-white/90 text-xs font-semibold text-text shadow-sm">
                                    <?php echo e($project->category->label()); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->is_featured): ?>
                                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-yellow-100/90 text-xs font-semibold text-yellow-700 shadow-sm inline-flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3"></i>
                                        مميز
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div class="p-4">
                                <h3 class="font-bold text-text group-hover:text-primary transition-colors mb-2"><?php echo e($project->name_ar); ?></h3>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->summary): ?>
                                    <p class="text-sm text-text-secondary line-clamp-2 mb-3"><?php echo e($project->summary); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->project_status->value === 'in_progress' || $project->implementation_percentage > 0): ?>
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-text-tertiary">نسبة الإنجاز</span>
                                            <span class="font-semibold" dir="ltr"><?php echo e($project->implementation_percentage); ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-700
                                                <?php if($project->implementation_percentage >= 100): ?> bg-success
                                                <?php elseif($project->implementation_percentage >= 50): ?> bg-primary
                                                <?php else: ?> bg-warning <?php endif; ?>"
                                                style="width: <?php echo e($project->implementation_percentage); ?>%">
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                
                                <div class="flex items-center gap-3 text-xs text-text-tertiary flex-wrap">
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="folder" class="w-3 h-3"></i>
                                        <?php echo e($project->category->label()); ?>

                                    </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->location): ?>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            <?php echo e($project->location); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        <?php echo e(number_format($project->views_count)); ?>

                                    </span>
                                </div>

                                
                                <div class="mt-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                        <?php if($project->project_status->value === 'completed'): ?> bg-success/10 text-success
                                        <?php elseif($project->project_status->value === 'in_progress'): ?> bg-warning/10 text-warning
                                        <?php elseif($project->project_status->value === 'suspended'): ?> bg-danger/10 text-danger
                                        <?php else: ?> bg-info/10 text-info <?php endif; ?>">
                                        <?php echo e($project->project_status->label()); ?>

                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projects->hasPages()): ?>
                <div class="mt-10">
                    <?php echo e($projects->links()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/projects/public-projects-index.blade.php ENDPATH**/ ?>