<?php
    $allNews = collect($latestNews)->take(4);

    $emergencyItems = collect($municipality['emergency_contacts'] ?? [])->take(4);
    $fallbackEmergency = collect([
        ['name' => 'الطوارئ العامة', 'phone' => '911'],
        ['name' => 'الدفاع المدني', 'phone' => '102'],
        ['name' => 'الشرطة', 'phone' => '100'],
        ['name' => 'قسم المياه', 'phone' => '06-5810012'],
    ]);
    $emergencyItems = $emergencyItems->isNotEmpty() ? $emergencyItems : $fallbackEmergency;

    $formatDay = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->format('d'); } catch (\Throwable) { return ''; }
    };
    $formatMonth = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('F'); } catch (\Throwable) { return ''; }
    };
?>

<section id="news" class="bg-white overflow-hidden" style="padding-top:clamp(70px,7vw,110px);padding-bottom:clamp(70px,7vw,110px);">
    <div class="container-home">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-10 sm:mb-12">
            <h2 class="text-3xl sm:text-4xl xl:text-[42px] font-black text-text leading-tight m-0">آخر الأخبار</h2>
            <a href="<?php echo e(Route::has('public.news.index') ? route('public.news.index') : '#'); ?>" wire:navigate
               class="inline-flex items-center gap-1.5 text-sm font-bold no-underline transition-colors hover:opacity-80"
               style="color:#2d6b3f;">
                <span>عرض جميع الأخبار</span>
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid xl:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <div class="xl:col-span-9">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($allNews->isNotEmpty()): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $allNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newsItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $day = $formatDay($newsItem['date'] ?? '');
                                $month = $formatMonth($newsItem['date'] ?? '');
                            ?>
                            <a href="<?php echo e(!empty($newsItem['url']) ? $newsItem['url'] : '#'); ?>"
                               <?php if(!empty($newsItem['url'])): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?>
                               class="group block bg-white rounded-2xl overflow-hidden hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline shadow-card">
                                <div class="relative aspect-[3/2] overflow-hidden">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['image'])): ?>
                                        <img src="<?php echo e($newsItem['image']); ?>" alt="<?php echo e($newsItem['title'] ?? ''); ?>"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-br from-primary-light to-surface-secondary flex items-center justify-center">
                                            <i data-lucide="image" class="w-10 h-10 text-primary/20"></i>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($day && $month): ?>
                                        <span class="absolute top-3 right-3 flex flex-col items-center justify-center rounded-lg text-white leading-none shadow-lg"
                                              style="background:#173f27;min-width:52px;padding:8px 10px;">
                                            <span class="text-xl font-black m-0"><?php echo e($day); ?></span>
                                            <span class="text-[10px] font-bold mt-1 m-0" style="color:rgba(255,255,255,0.9);"><?php echo e($month); ?></span>
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="p-4 sm:p-5">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['type'])): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold inline-block mb-2" style="background:#eef6ef;color:#173f27;"><?php echo e($newsItem['type']); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <h3 class="text-sm font-extrabold text-text group-hover:text-primary transition-colors leading-snug line-clamp-1"><?php echo e($newsItem['title'] ?? ''); ?></h3>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['summary'])): ?>
                                        <p class="text-xs text-text-secondary mt-2 leading-relaxed line-clamp-1"><?php echo e($newsItem['summary']); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

            
            <aside class="xl:col-span-3" aria-label="أرقام الطوارئ">
                <div class="rounded-3xl p-6" style="background:#f0f7f0;box-shadow:0 16px 40px rgba(23,63,39,0.10);">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background:#173f27;">
                            <i data-lucide="phone-call" class="w-5 h-5 text-white"></i>
                        </span>
                        <h3 class="text-base sm:text-lg font-black text-text leading-tight m-0">أرقام الطوارئ</h3>
                    </div>

                    <div class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $emergencyItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emergency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $name = $emergency['name'] ?? $emergency['department'] ?? '';
                                $phone = $emergency['phone'] ?? '';
                            ?>
                            <a href="<?php echo e($phone ? 'tel:' . preg_replace('/\s+/', '', $phone) : '#'); ?>"
                               class="flex items-center gap-3 rounded-xl bg-white px-3.5 py-3 no-underline transition-all hover:shadow-md hover:-translate-y-0.5 group/row">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:#eef6ef;">
                                    <i data-lucide="phone" class="w-3.5 h-3.5" style="color:#173f27;"></i>
                                </span>
                                <span class="flex-1 min-w-0">
                                    <span class="block text-[13px] font-bold text-text leading-tight truncate"><?php echo e($name); ?></span>
                                </span>
                                <span class="text-sm font-black flex-shrink-0" style="color:#173f27;" dir="ltr"><?php echo e($phone); ?></span>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section><?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/news.blade.php ENDPATH**/ ?>