<?php
    $allNews = collect($latestNews)->take(4);
    $featuredNews = $allNews->first();
    $otherNews = $allNews->skip(1)->take(3);
?>

<section id="news" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary-light text-primary mb-3">
                    <i data-lucide="newspaper" class="w-3.5 h-3.5"></i>
                    آخر المستجدات
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight"><?php echo e($sectionTitle ?? 'أخبار وإعلانات البلدية'); ?></h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sectionSubtitle): ?>
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed"><?php echo e($sectionSubtitle); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($allNews->isNotEmpty()): ?>
            <div class="grid lg:grid-cols-12 gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredNews): ?>
                    <div class="lg:col-span-7">
                        <a href="<?php echo e(!empty($featuredNews['url']) ? $featuredNews['url'] : '#'); ?>" <?php if(!empty($featuredNews['url'])): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?>
                           class="block group bg-white rounded-2xl border border-border/60 overflow-hidden hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline h-full shadow-card">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['image'])): ?>
                                <div class="aspect-[16/9] overflow-hidden">
                                    <img src="<?php echo e($featuredNews['image']); ?>" alt="<?php echo e($featuredNews['title'] ?? ''); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                                </div>
                            <?php else: ?>
                                <div class="aspect-[16/9] bg-gradient-to-br from-primary-light to-surface-secondary flex items-center justify-center">
                                    <i data-lucide="image" class="w-12 h-12 text-primary/20"></i>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="p-5 sm:p-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['type'])): ?>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-primary-light text-primary inline-block mb-3"><?php echo e($featuredNews['type']); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <h3 class="text-base sm:text-lg font-black text-text group-hover:text-primary transition-colors leading-snug"><?php echo e($featuredNews['title'] ?? ''); ?></h3>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['summary'])): ?>
                                    <p class="text-sm text-text-secondary mt-2 line-clamp-2 leading-relaxed"><?php echo e($featuredNews['summary']); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-border/50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['date'])): ?>
                                        <span class="text-xs text-text-muted flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-primary"></i>
                                            <?php echo e($featuredNews['date']); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="text-xs font-bold text-primary group-hover:gap-2 transition-all inline-flex items-center gap-1 mr-auto">
                                        <span>قراءة المزيد</span>
                                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="lg:col-span-5">
                    <div class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $otherNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newsItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(!empty($newsItem['url']) ? $newsItem['url'] : '#'); ?>" <?php if(!empty($newsItem['url'])): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?>
                               class="flex gap-4 bg-surface-secondary rounded-xl border border-border/40 p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 no-underline group">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['image'])): ?>
                                    <div class="w-20 h-20 sm:w-20 sm:h-20 rounded-xl overflow-hidden flex-shrink-0">
                                        <img src="<?php echo e($newsItem['image']); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                <?php else: ?>
                                    <div class="w-20 h-20 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="file-text" class="w-8 h-8 text-primary/40"></i>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="min-w-0 flex-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['type'])): ?>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-primary-light text-primary"><?php echo e($newsItem['type']); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <h4 class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-2 mt-1"><?php echo e($newsItem['title'] ?? ''); ?></h4>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['date'])): ?>
                                        <p class="text-[10px] text-text-muted mt-1.5 flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            <?php echo e($newsItem['date']); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal2d676a58b00d13d9951e5ef6afb5b5b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d676a58b00d13d9951e5ef6afb5b5b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state-section','data' => ['icon' => 'newspaper','title' => 'لا توجد أخبار منشورة حالياً','description' => 'سيتم إضافة الأخبار فور نشرها']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'newspaper','title' => 'لا توجد أخبار منشورة حالياً','description' => 'سيتم إضافة الأخبار فور نشرها']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d676a58b00d13d9951e5ef6afb5b5b2)): ?>
<?php $attributes = $__attributesOriginal2d676a58b00d13d9951e5ef6afb5b5b2; ?>
<?php unset($__attributesOriginal2d676a58b00d13d9951e5ef6afb5b5b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d676a58b00d13d9951e5ef6afb5b5b2)): ?>
<?php $component = $__componentOriginal2d676a58b00d13d9951e5ef6afb5b5b2; ?>
<?php unset($__componentOriginal2d676a58b00d13d9951e5ef6afb5b5b2); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/news.blade.php ENDPATH**/ ?>