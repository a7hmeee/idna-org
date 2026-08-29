<div>
    <?php
        $hasSlides = $slides->isNotEmpty();
        $slideCount = $slides->count();
        $heroHeight = $compact ? 'clamp(300px, 44vh, 460px)' : 'clamp(380px, 58vh, 620px)';
        $bgImage = null;
        if ($hasSlides) {
            $bgImage = $slides->first()->image_url;
        } elseif ($fallbackImage) {
            $bgImage = $fallbackImage;
        }
        $configTitle = $carouselConfig['title'] ?? null;
        $configSubtitle = $carouselConfig['subtitle'] ?? null;
        $displayTitle = $pageTitle ?? ($hasSlides ? $slides->first()->title ?? null : null) ?? $configTitle ?? $fallbackTitle ?? '';
        $displaySubtitle = $pageSubtitle ?? ($hasSlides ? $slides->first()->description ?? null : null) ?? $configSubtitle ?? $fallbackDescription ?? '';
        $displayBadge = $pageBadge ?? ($hasSlides ? $slides->first()->badge_text ?? null : null) ?? $fallbackBadge ?? '';
        $isExternal = fn ($url) => parse_url($url, PHP_URL_HOST) !== null && parse_url($url, PHP_URL_HOST) !== request()->getHost();
    ?>

    <section class="relative overflow-hidden bg-[#073A25]" dir="ltr" aria-label="الشريط الرئيسي">
        <div
            x-data="{
                current: 0,
                total: <?php echo e(max($slideCount, 1)); ?>,
                interval: null,
                autoplay: <?php echo e(($hasSlides && $slideCount > 1) ? 'true' : 'false'); ?>,
                init() {
                    if (this.total > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.startAutoplay();
                    }
                },
                startAutoplay() {
                    this.interval = setInterval(() => { this.next(); }, 8000);
                },
                stopAutoplay() {
                    if (this.interval) { clearInterval(this.interval); this.interval = null; }
                },
                next() { this.current = (this.current + 1) % this.total; },
                prev() { this.current = (this.current - 1 + this.total) % this.total; },
                goTo(i) { this.current = i; }
            }"
            @mouseenter="stopAutoplay()"
            @mouseleave="autoplay && startAutoplay()"
            @focusin="stopAutoplay()"
            @focusout="autoplay && startAutoplay()"
            class="relative"
            role="region"
            aria-roledescription="carousel"
            aria-label="عرض الشرائح">

            
            <div class="relative overflow-hidden" style="height:<?php echo e($heroHeight); ?>;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSlides): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div x-show="current === <?php echo e($index); ?>"
                             x-transition:enter="transition-opacity duration-700"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-500"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0"
                             role="group"
                             aria-roledescription="slide"
                             aria-label="<?php echo e('شريحة ' . ($index + 1) . ' من ' . $slideCount); ?>">
                            <img src="<?php echo e($slide->image_url); ?>"
                                 alt="<?php echo e($slide->title ?? ''); ?>"
                                 class="hidden md:block absolute inset-0 w-full h-full object-cover object-center page-hero-zoom"
                                 style="animation:pageHeroZoom 9s ease-out forwards;"
                                 <?php if($index === 0): ?> fetchpriority="high" loading="eager" <?php else: ?> loading="lazy" decoding="async" <?php endif; ?>
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                            <img src="<?php echo e($slide->mobile_image_url ?? $slide->image_url); ?>"
                                 alt="<?php echo e($slide->title ?? ''); ?>"
                                 class="md:hidden absolute inset-0 w-full h-full object-cover object-center page-hero-zoom"
                                 style="animation:pageHeroZoom 9s ease-out forwards;"
                                 loading="lazy" decoding="async"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php elseif($bgImage): ?>
                    <img src="<?php echo e($bgImage); ?>" alt="" class="absolute inset-0 w-full h-full object-cover object-center page-hero-zoom" style="animation:pageHeroZoom 9s ease-out forwards;" fetchpriority="high" loading="eager">
                <?php else: ?>
                    <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);min-height:<?php echo e($heroHeight); ?>;"></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="absolute inset-0 z-10 bg-gradient-to-l from-black/50 via-black/15 via-30% to-transparent to-60%"></div>

                
                <div class="absolute left-0 top-0 bottom-0 w-[35%] max-w-[460px] z-20 pointer-events-none overflow-hidden hidden lg:block" aria-hidden="true">
                    <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #0F6A3D 0%, #0F6A3D 40%, transparent 100%); clip-path: polygon(100% 0, 0 0, 0 100%, 100% 100%, 85% 80%, 78% 60%, 82% 40%, 90% 20%); opacity: 0.85;"></div>
                    <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #2B8A4B 0%, transparent 70%); clip-path: polygon(100% 0, 5% 0, 15% 100%, 100% 100%, 88% 82%, 82% 60%, 85% 35%); opacity: 0.30; transform: translateY(5%);"></div>
                </div>

                
                <div class="relative z-30 w-full h-full flex items-center" dir="rtl">
                    <div class="w-full py-16 sm:py-20 lg:py-24" style="padding-top:max(44px,5vh);padding-bottom:max(60px,9vh);">
                        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="max-w-[580px] lg:max-w-[640px] mr-auto lg:mr-[5%] text-right">

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($breadcrumb)): ?>
                                    <nav aria-label="مسار التنقل" class="mb-4 sm:mb-5">
                                        <ol class="flex flex-wrap items-center gap-2 list-none m-0 p-0 text-xs sm:text-[13px]">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <li class="flex items-center gap-2">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last && !empty($item['url'])): ?>
                                                        <a href="<?php echo e($item['url']); ?>" wire:navigate class="text-white/65 hover:text-white no-underline font-medium whitespace-nowrap transition-colors"><?php echo e($item['label']); ?></a>
                                                    <?php else: ?>
                                                        <span class="font-bold text-white"><?php echo e($item['label']); ?></span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($loop->last)): ?>
                                                        <span aria-hidden="true" class="text-white/30 text-sm leading-none select-none">‹</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </li>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </ol>
                                    </nav>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSlides): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <div x-show="current === <?php echo e($index); ?>"
                                             x-transition:enter="transition-all duration-500"
                                             x-transition:enter-start="opacity-0 translate-y-4"
                                             x-transition:enter-end="opacity-100 translate-y-0">
                                            
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/90 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-4 sm:mb-5 shadow-lg" style="box-shadow: 0 4px 16px rgba(15,106,61,0.35);">
                                                <i data-lucide="<?php echo e($pageBadgeIcon ?? 'zap'); ?>" class="w-4 h-4"></i>
                                                <span><?php echo e($slide->badge_text ?? $displayBadge); ?></span>
                                            </span>

                                            
                                            <h1 class="font-black text-white leading-[1.15] mb-4 sm:mb-5" style="font-size:clamp(26px,4vw,52px);max-width:560px;text-shadow:0 2px 20px rgba(0,0,0,0.3);">
                                                <?php echo e($slide->title ?? $displayTitle); ?>

                                            </h1>

                                            
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slide->description ?? $displaySubtitle): ?>
                                                <p class="text-white/85 leading-relaxed mb-6 sm:mb-7 max-w-[520px]" style="font-size:clamp(13px,1.4vw,16px);text-shadow:0 1px 8px rgba(0,0,0,0.15);">
                                                    <?php echo e($slide->description ?? $displaySubtitle); ?>

                                                </p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            
                                            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slide->button_text && $slide->button_url): ?>
                                                    <a href="<?php echo e($slide->button_url); ?>"
                                                       <?php if($isExternal($slide->button_url)): ?> target="_blank" rel="noopener noreferrer" <?php else: ?> wire:navigate <?php endif; ?>
                                                       class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 rounded-xl bg-white text-primary font-bold text-sm sm:text-base hover:bg-gray-50 hover:-translate-y-0.5 transition-all shadow-xl no-underline"
                                                       style="box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                                                        <span><?php echo e($slide->button_text); ?></span>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isExternal($slide->button_url)): ?>
                                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                                        <?php else: ?>
                                                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <a href="<?php echo e(route('home')); ?>#contact" wire:navigate
                                                   class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 rounded-xl border-2 border-white/25 text-white font-semibold text-sm sm:text-base hover:bg-white/10 hover:border-white/40 transition-all backdrop-blur-sm no-underline">
                                                    <i data-lucide="message-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                                    <span>تواصل معنا</span>
                                                </a>
                                            </div>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <?php else: ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayBadge): ?>
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/90 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-4 sm:mb-5 shadow-lg" style="box-shadow: 0 4px 16px rgba(15,106,61,0.35);">
                                            <i data-lucide="<?php echo e($pageBadgeIcon ?? 'zap'); ?>" class="w-4 h-4"></i>
                                            <span><?php echo e($displayBadge); ?></span>
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayTitle): ?>
                                        <h1 class="font-black text-white leading-[1.15] mb-4 sm:mb-5" style="font-size:clamp(26px,4vw,52px);max-width:560px;text-shadow:0 2px 20px rgba(0,0,0,0.3);">
                                            <?php echo e($displayTitle); ?>

                                        </h1>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displaySubtitle): ?>
                                        <p class="text-white/85 leading-relaxed mb-6 sm:mb-7 max-w-[520px]" style="font-size:clamp(13px,1.4vw,16px);text-shadow:0 1px 8px rgba(0,0,0,0.15);">
                                            <?php echo e($displaySubtitle); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                        <a href="<?php echo e(route('home')); ?>#contact" wire:navigate
                                           class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 rounded-xl border-2 border-white/25 text-white font-semibold text-sm sm:text-base hover:bg-white/10 hover:border-white/40 transition-all backdrop-blur-sm no-underline">
                                            <i data-lucide="message-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                            <span>تواصل معنا</span>
                                        </a>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSlides && $slideCount > 1): ?>
                    <div class="absolute bottom-6 sm:bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2" role="tablist" aria-label="اختيار الشريحة">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <button type="button" @click="goTo(<?php echo e($index); ?>)"
                                    :class="current === <?php echo e($index); ?> ? 'bg-primary w-7' : 'bg-white/40 w-2.5 hover:bg-white/70'"
                                    class="h-2.5 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50 cursor-pointer border-none"
                                    role="tab"
                                    :aria-selected="current === <?php echo e($index); ?>"
                                    :aria-label="'الانتقال إلى الشريحة ' + (<?php echo e($index); ?> + 1)">
                            </button>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <?php $__env->startPush('styles'); ?>
        <style>
            @keyframes pageHeroZoom {
                0% { transform: scale(1); }
                100% { transform: scale(1.08); }
            }
            @media (prefers-reduced-motion: reduce) {
                .page-hero-zoom { animation: none !important; }
            }
        </style>
    <?php $__env->stopPush(); ?>
</div><?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/public-page-carousel.blade.php ENDPATH**/ ?>