<?php
    $allNews = collect($latestNews)->take(4);
    $featuredNews = $allNews->first();
    $secondaryNews = $allNews->skip(1)->take(3);
    $carouselConfig = \App\Domains\Homepage\Services\CarouselRegistry::getConfigArray('homepage-news');
    $resolvedTitle = $sectionTitle ?? $carouselConfig['title'] ?? 'آخر الأخبار';
    $resolvedSubtitle = $sectionSubtitle ?? $carouselConfig['subtitle'] ?? 'تابع آخر أخبار وفعاليات بلدية إذنا';

    $emergencyItems = collect($municipality['emergency_contacts'] ?? [])->take(4);
    $fallbackEmergency = collect([
        ['name' => 'الشرطة', 'phone' => '100'],
        ['name' => 'الدفاع المدني', 'phone' => '101'],
        ['name' => 'الإسعاف الطبي', 'phone' => '102'],
        ['name' => 'طوارئ البلدية', 'phone' => '106'],
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
    $formatFullDate = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('d F Y'); } catch (\Throwable) { return ''; }
    };
?>

<section id="news" class="overflow-hidden" style="background:#F8FAF8;padding-top:clamp(64px,6vw,100px);padding-bottom:clamp(64px,6vw,100px);">
    <div class="container-home">

        
        
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10 sm:mb-14">
            <div class="flex items-center gap-4">
                <span class="hidden sm:flex w-1.5 h-12 rounded-full" style="background:#176B32;"></span>
                <div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-tight m-0" style="color:#0F1A14;">
                        <?php echo e($resolvedTitle); ?>

                    </h2>
                    <p class="text-sm mt-1.5 m-0" style="color:#6B7B6E;"><?php echo e($resolvedSubtitle); ?></p>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.news.index')): ?>
                <a href="<?php echo e(route('public.news.index')); ?>" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold no-underline transition-all duration-200"
                   style="background:#176B32;color:white;"
                   onmouseover="this.style.background='#0D5A28'"
                   onmouseout="this.style.background='#176B32'">
                    <span>عرض جميع الأخبار</span>
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <div class="grid xl:grid-cols-12 gap-8 lg:gap-10 items-start">

            
            <div class="xl:col-span-9">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($allNews->isNotEmpty()): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredNews): ?>
                            <?php
                                $day = $formatDay($featuredNews['date'] ?? '');
                                $month = $formatMonth($featuredNews['date'] ?? '');
                                $fullDate = $formatFullDate($featuredNews['date'] ?? '');
                            ?>
                            <a href="<?php echo e(!empty($featuredNews['url']) ? $featuredNews['url'] : '#'); ?>"
                               <?php if(!empty($featuredNews['url'])): ?> wire:navigate <?php endif; ?>
                               class="group block lg:col-span-7 bg-white rounded-2xl overflow-hidden no-underline transition-all duration-300"
                               style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                               onmouseover="this.style.boxShadow='0 20px 50px rgba(23,107,50,0.10)';this.style.transform='translateY(-3px)'"
                               onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                                <div class="relative overflow-hidden" style="aspect-ratio:16/9;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['image'])): ?>
                                        <img src="<?php echo e($featuredNews['image']); ?>" alt="<?php echo e($featuredNews['title'] ?? ''); ?>"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                             loading="eager">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);">
                                            <i data-lucide="newspaper" class="w-16 h-16" style="color:#176B32;opacity:0.15;"></i>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.55) 0%,transparent 50%;"></div>

                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['category'])): ?>
                                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold" style="background:#176B32;color:white;">
                                            <?php echo e($featuredNews['category']); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    
                                    <div class="absolute bottom-0 right-0 left-0 p-5 sm:p-6">
                                        <h3 class="text-lg sm:text-xl lg:text-2xl font-black text-white leading-snug m-0 line-clamp-2">
                                            <?php echo e($featuredNews['title'] ?? ''); ?>

                                        </h3>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featuredNews['summary'])): ?>
                                            <p class="text-sm mt-2 leading-relaxed m-0 line-clamp-2" style="color:rgba(255,255,255,0.85);">
                                                <?php echo e($featuredNews['summary']); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="flex items-center gap-3 mt-3">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fullDate): ?>
                                                <span class="flex items-center gap-1.5 text-xs font-medium" style="color:rgba(255,255,255,0.75);">
                                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                                    <?php echo e($fullDate); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <span class="flex items-center gap-1 text-xs font-bold" style="color:#A5D6A7;">
                                                اقرأ المزيد
                                                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($secondaryNews->isNotEmpty()): ?>
                            <div class="lg:col-span-5 flex flex-col gap-5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $secondaryNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newsItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php
                                        $sDay = $formatDay($newsItem['date'] ?? '');
                                        $sMonth = $formatMonth($newsItem['date'] ?? '');
                                    ?>
                                    <a href="<?php echo e(!empty($newsItem['url']) ? $newsItem['url'] : '#'); ?>"
                                       <?php if(!empty($newsItem['url'])): ?> wire:navigate <?php endif; ?>
                                       class="group flex gap-4 bg-white rounded-xl p-4 no-underline transition-all duration-200"
                                       style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                                       onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                                       onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                                        
                                        <div class="flex-shrink-0 rounded-lg overflow-hidden" style="width:110px;height:90px;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['image'])): ?>
                                                <img src="<?php echo e($newsItem['image']); ?>" alt="<?php echo e($newsItem['title'] ?? ''); ?>"
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                     loading="lazy">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center" style="background:#E8F5E9;">
                                                    <i data-lucide="image" class="w-6 h-6" style="color:#176B32;opacity:0.2;"></i>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                            <div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($newsItem['type'])): ?>
                                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mb-1.5" style="background:#E8F5E9;color:#176B32;">
                                                        <?php echo e($newsItem['type']); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <h4 class="text-sm font-bold leading-snug line-clamp-2 m-0" style="color:#0F1A14;">
                                                    <?php echo e($newsItem['title'] ?? ''); ?>

                                                </h4>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sDay && $sMonth): ?>
                                                    <span class="flex items-center gap-1 text-[11px] font-medium" style="color:#8A9A8D;">
                                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                                        <?php echo e($sDay); ?> <?php echo e($sMonth); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

            
            <aside class="xl:col-span-3 flex flex-col gap-6" aria-label="الإعلانات وأرقام الطوارئ">

                
                <?php $announcementsList = collect($latestAnnouncements ?? [])->take(3); ?>
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                    
                    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid #F0F4F0;">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#E8F5E9;">
                                <i data-lucide="megaphone" class="w-4 h-4" style="color:#176B32;"></i>
                            </span>
                            <h3 class="text-sm font-black m-0" style="color:#0F1A14;">الإعلانات</h3>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.announcements.index')): ?>
                            <a href="<?php echo e(route('public.announcements.index')); ?>" wire:navigate
                               class="text-xs font-bold no-underline transition-colors" style="color:#176B32;"
                               onmouseover="this.style.color='#0D5A28'"
                               onmouseout="this.style.color='#176B32'">
                                عرض الكل
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcementsList->isNotEmpty()): ?>
                        <div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $announcementsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <a href="<?php echo e(!empty($announcement['url']) ? $announcement['url'] : (Route::has('public.announcements.index') ? route('public.announcements.index') : '#')); ?>"
                                   <?php if(!empty($announcement['url'])): ?> wire:navigate <?php endif; ?>
                                   class="flex items-start gap-3 px-5 py-3.5 no-underline transition-colors duration-150"
                                   style="<?php echo e($index < $announcementsList->count() - 1 ? 'border-bottom:1px solid #F0F4F0;' : ''); ?>"
                                   onmouseover="this.style.background='#F8FAF8'"
                                   onmouseout="this.style.background='transparent'">
                                    <span class="w-6 h-6 rounded flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#E8F5E9;">
                                        <i data-lucide="megaphone" class="w-3 h-3" style="color:#176B32;"></i>
                                    </span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block text-[13px] font-bold leading-snug line-clamp-2" style="color:#0F1A14;">
                                            <?php echo e($announcement['title'] ?? ''); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($announcement['date'])): ?>
                                            <span class="block text-[11px] mt-1" style="color:#8A9A8D;">
                                                <?php echo e($announcement['date']); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="px-5 py-6 text-center">
                            <p class="text-xs m-0" style="color:#8A9A8D;">لا توجد إعلانات حالياً</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="rounded-2xl overflow-hidden" style="background:#176B32;">
                    
                    <div class="flex items-center gap-2.5 px-5 py-4">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.15);">
                            <i data-lucide="phone-call" class="w-4 h-4 text-white"></i>
                        </span>
                        <h3 class="text-sm font-black text-white m-0">أرقام الطوارئ</h3>
                    </div>
                    
                    <div class="px-3 pb-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $emergencyItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $emergency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $name = $emergency['name'] ?? $emergency['department'] ?? '';
                                $phone = $emergency['phone'] ?? '';
                            ?>
                            <a href="<?php echo e($phone ? 'tel:' . preg_replace('/\s+/', '', $phone) : '#'); ?>"
                               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 no-underline transition-all duration-150"
                               style="<?php echo e($index < $emergencyItems->count() - 1 ? 'border-bottom:1px solid rgba(255,255,255,0.12);' : ''); ?>"
                               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                               onmouseout="this.style.background='transparent'">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.12);">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-white"></i>
                                </span>
                                <span class="flex-1 min-w-0 text-[13px] font-bold text-white truncate"><?php echo e($name); ?></span>
                                <span class="text-sm font-black text-white flex-shrink-0" dir="ltr"><?php echo e($phone); ?></span>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

            </aside>
        </div>

    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/news.blade.php ENDPATH**/ ?>