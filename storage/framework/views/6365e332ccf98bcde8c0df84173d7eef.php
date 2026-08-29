<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'slides' => [],
    'settings' => [],
    'municipalityName' => '',
    'portalUrl' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'slides' => [],
    'settings' => [],
    'municipalityName' => '',
    'portalUrl' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $singleSlide = count($slides) === 1;
    $primaryBtn = $settings['primary_button_text'] ?? 'الدخول إلى البوابة';
    $secondaryBtn = $settings['secondary_button_text'] ?? 'تعرف على البلدية';
    $secondaryBtnUrl = $settings['secondary_button_url'] ?? '#municipality-intro';
?>
<section id="hero" class="relative overflow-hidden bg-[#073A25]" dir="ltr" aria-label="الشريط الرئيسي">
    
    <div
        x-data="{
            current: 0,
            total: <?php echo e(count($slides)); ?>,
            interval: null,
            autoplay: <?php echo e($singleSlide ? 'false' : 'true'); ?>,
            paused: false,
            init() {
                if (this.total > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    this.startAutoplay();
                } else {
                    this.autoplay = false;
                }
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) { this.stopAutoplay(); }
                    else if (this.autoplay && !this.paused && this.total > 1) { this.startAutoplay(); }
                });
            },
            startAutoplay() {
                this.stopAutoplay();
                this.interval = setInterval(() => { if (!this.paused) { this.next(); } }, 8000);
            },
            stopAutoplay() {
                if (this.interval) { clearInterval(this.interval); this.interval = null; }
            },
            togglePause() {
                this.paused = !this.paused;
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
        aria-label="عرض الشرائح"
    >
        
        <div class="relative ih-hero-stage">
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
                     aria-label="<?php echo e('شريحة ' . ($index + 1) . ' من ' . count($slides)); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['image_url'])): ?>
                        <img src="<?php echo e($slide['image_url']); ?>"
                             alt="<?php echo e($slide['title'] ?? ''); ?>"
                             class="w-full h-full object-cover object-center"
                             <?php if($index === 0): ?> fetchpriority="high" loading="eager" <?php else: ?> loading="lazy" decoding="async" <?php endif; ?>
                             onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                    <?php else: ?>
                        <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            
            <div class="absolute inset-0 z-10" style="background: linear-gradient(135deg, rgba(3,31,16,0.85) 0%, rgba(7,58,37,0.65) 45%, rgba(7,58,37,0.35) 75%, rgba(7,58,37,0.08) 100%);"></div>

            
            <div class="relative z-20 w-full h-full ih-hero-content" dir="rtl">
                <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-[600px] lg:max-w-[680px] mx-auto lg:ml-0 lg:mr-[5%] text-center lg:text-right">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($slides) === 0): ?>
                            
                            <div class="ih-hero-badge">بلدية إذنا</div>
                            <h1 class="ih-hero-title">
                                <span class="ih-hero-title-white">مرحباً بكم في بلدية إذنا</span>
                            </h1>
                            <div class="ih-hero-buttons">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.services.index')): ?>
                                    <a href="<?php echo e(route('public.services.index')); ?>" wire:navigate class="ih-hero-btn ih-hero-btn-primary">
                                        <i data-lucide="laptop" style="width:14px;height:14px;"></i>
                                        <span>الخدمات الإلكترونية</span>
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.complaints.submit')): ?>
                                    <a href="<?php echo e(route('public.complaints.submit')); ?>" wire:navigate class="ih-hero-btn ih-hero-btn-secondary">
                                        <i data-lucide="message-square-warning" style="width:14px;height:14px;"></i>
                                        <span>تقديم شكوى</span>
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div x-show="current === <?php echo e($index); ?>" x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['badge_text'])): ?>
                                    <div class="ih-hero-badge"><?php echo e($slide['badge_text']); ?></div>
                                <?php else: ?>
                                    <div class="ih-hero-badge">الخدمات الإلكترونية</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                
                                <h1 class="ih-hero-title">
                                    <?php
                                        $rawTitle = !empty($slide['title']) ? $slide['title'] : 'مرحباً بكم في بلدية إذنا';
                                        $greenWord = 'إذنا';
                                        $titleHtml = preg_replace(
                                            '/' . preg_quote($greenWord, '/') . '/',
                                            '<span class="ih-hero-title-green" style="color:#3BAF56 !important;">' . $greenWord . '</span>',
                                            e($rawTitle),
                                            1
                                        );
                                    ?>
                                    <span class="ih-hero-title-white"><?php echo $titleHtml; ?></span>
                                </h1>

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['description'])): ?>
                                    <p class="ih-hero-desc"><?php echo e($slide['description']); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                
                                <div class="ih-hero-buttons">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.services.index')): ?>
                                        <a href="<?php echo e(route('public.services.index')); ?>" wire:navigate class="ih-hero-btn ih-hero-btn-primary">
                                            <i data-lucide="laptop" style="width:14px;height:14px;"></i>
                                            <span>الخدمات الإلكترونية</span>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.complaints.submit')): ?>
                                        <a href="<?php echo e(route('public.complaints.submit')); ?>" wire:navigate class="ih-hero-btn ih-hero-btn-secondary">
                                            <i data-lucide="message-square-warning" style="width:14px;height:14px;"></i>
                                            <span>تقديم شكوى</span>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" class="ih-hero-btn ih-hero-btn-secondary !bg-transparent !border-white/30 hover:!bg-white/10">
                                            <i data-lucide="arrow-up-left" style="width:14px;height:14px;"></i>
                                            <span><?php echo e($primaryBtn); ?></span>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$singleSlide && count($slides) > 1): ?>
            <div class="absolute left-1/2 -translate-x-1/2 z-30 flex items-center gap-3" style="bottom:12px;" role="group" aria-label="أدوات العرض">
                <button x-show="autoplay" @click="togglePause()"
                        :aria-label="paused ? 'تشغيل العرض التلقائي' : 'إيقاف العرض التلقائي مؤقتاً'"
                        :aria-pressed="paused ? 'true' : 'false'"
                        class="w-7 h-7 rounded-full bg-white/15 hover:bg-white/30 border border-white/25 backdrop-blur-sm flex items-center justify-center transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#3BAF56]/60"
                        style="color:white;">
                    <i data-lucide="pause" class="w-3.5 h-3.5" x-show="!paused"></i>
                    <i data-lucide="play" class="w-3.5 h-3.5" x-show="paused" x-cloak></i>
                </button>
                <div class="flex items-center gap-1.5" role="tablist" aria-label="اختيار الشريحة">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button @click="goTo(<?php echo e($index); ?>)"
                            :class="current === <?php echo e($index); ?> ? 'bg-[#3BAF56] w-6' : 'bg-white/40 w-2 hover:bg-white/70'"
                            class="relative h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#3BAF56]/60 [&::after]:content-[''] [&::after]:absolute [&::after]:-inset-2.5"
                            role="tab"
                            :aria-selected="current === <?php echo e($index); ?>"
                            :aria-label="'الانتقال إلى الشريحة ' + (<?php echo e($index); ?> + 1)">
                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>

<?php $__env->startPush('styles'); ?>
    <?php if (! $__env->hasRenderedOnce('46003533-0818-4686-b337-0a844e00b66f')): $__env->markAsRenderedOnce('46003533-0818-4686-b337-0a844e00b66f'); ?>
        <style>
            /* ===== HERO STAGE HEIGHT ===== */
            .ih-hero-stage { min-height: clamp(560px, 85vh, 780px); height: auto; overflow: hidden; position: relative; }
            .ih-hero-content {
                width: 100%;
                min-height: clamp(560px, 85vh, 780px);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: clamp(60px, 8vw, 120px) 0 clamp(60px, 8vw, 120px);
            }

            /* ===== BADGE ===== */
            .ih-hero-badge {
                display: inline-flex;
                align-items: center;
                padding: 5px 14px;
                border-radius: 9999px;
                background: rgba(59,175,86,0.12);
                backdrop-filter: blur(8px);
                color: #5EC97A;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 18px;
                border: 1px solid rgba(59,175,86,0.2);
                letter-spacing: 0.03em;
            }

            /* ===== TITLE ===== */
            .ih-hero-title {
                font-weight: 800;
                line-height: 1.15;
                margin: 0 0 14px;
                font-size: clamp(30px, 5.5vw, 68px);
                text-shadow: 0 2px 24px rgba(0,0,0,0.35);
                letter-spacing: -0.01em;
            }
            .ih-hero-title-white {
                color: #FFFFFF;
            }
            .ih-hero-title-green {
                color: #3BAF56;
                position: relative;
                display: inline;
                text-shadow: 0 0 40px rgba(59,175,86,0.25), 0 2px 24px rgba(0,0,0,0.35);
            }
            .ih-hero-title-green::after {
                content: '';
                position: absolute;
                bottom: -2px;
                right: 0;
                width: 100%;
                height: 3px;
                background: linear-gradient(90deg, transparent 0%, #3BAF56 20%, #3BAF56 80%, transparent 100%);
                border-radius: 2px;
                opacity: 0.45;
            }

            /* ===== DESCRIPTION ===== */
            .ih-hero-desc {
                color: rgba(255,255,255,0.9);
                line-height: 1.9;
                margin: 0 0 36px;
                max-width: 560px;
                margin-left: auto;
                margin-right: auto;
                font-size: 17px;
                text-shadow: 0 1px 8px rgba(0,0,0,0.12);
                font-weight: 400;
                letter-spacing: 0.01em;
            }

            /* ===== BUTTONS ===== */
            .ih-hero-buttons {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }
            .ih-hero-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 14px 32px;
                border-radius: 14px;
                font-size: 15px;
                font-weight: 700;
                text-decoration: none;
                white-space: nowrap;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
            }
            .ih-hero-btn-primary {
                background: linear-gradient(135deg, #176B32 0%, #0F4F28 100%);
                color: white;
                box-shadow: 0 4px 20px rgba(23,107,50,0.35);
            }
            .ih-hero-btn-primary:hover {
                background: linear-gradient(135deg, #1a7a38 0%, #126030 100%);
                box-shadow: 0 6px 28px rgba(23,107,50,0.5);
                transform: translateY(-1px);
            }
            .ih-hero-btn-secondary {
                background: rgba(255,255,255,0.12);
                color: white;
                border: 1.5px solid rgba(212,183,106,0.4);
                backdrop-filter: blur(8px);
            }
            .ih-hero-btn-secondary:hover {
                background: rgba(255,255,255,0.2);
                border-color: rgba(212,183,106,0.7);
                transform: translateY(-1px);
            }

            /* ===== DESKTOP (>= 1024px) ===== */
            @media (min-width: 1024px) {
                .ih-hero-stage { min-height: clamp(600px, 85vh, 780px); }
                .ih-hero-content { padding: 120px 0; min-height: clamp(600px, 85vh, 780px); }
                .ih-hero-badge { font-size: 14px; padding: 7px 20px; margin-bottom: 24px; }
                .ih-hero-title { font-size: clamp(44px, 5vw, 72px); margin-bottom: 20px; }
                .ih-hero-desc { font-size: 18px; margin-bottom: 40px; }
                .ih-hero-btn { padding: 18px 40px; font-size: 17px; border-radius: 14px; gap: 10px; }
            }

            /* ===== TABLET (768px – 1023px) ===== */
            @media (min-width: 768px) and (max-width: 1023.98px) {
                .ih-hero-stage { min-height: 520px; }
                .ih-hero-content { padding: 72px 0; min-height: 520px; }
                .ih-hero-title { font-size: clamp(34px, 5vw, 50px); }
                .ih-hero-desc { font-size: 16px; margin-bottom: 30px; }
                .ih-hero-btn { padding: 15px 32px; font-size: 15px; }
            }

            /* ===== MOBILE (< 768px) ===== */
            @media (max-width: 767.98px) {
                .ih-hero-stage {
                    min-height: 440px;
                }
                .ih-hero-content {
                    padding: 48px 20px 32px;
                    align-items: center;
                    min-height: 440px;
                }
                .ih-hero-badge {
                    font-size: 12px;
                    padding: 5px 14px;
                    margin-bottom: 14px;
                }
                .ih-hero-title {
                    font-size: clamp(28px, 8vw, 40px);
                    margin-bottom: 12px;
                    line-height: 1.15;
                }
                .ih-hero-desc {
                    font-size: 15px;
                    line-height: 1.75;
                    margin-bottom: 28px;
                }
                .ih-hero-buttons {
                    gap: 12px;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                .ih-hero-btn {
                    padding: 14px 24px;
                    font-size: 14px;
                    border-radius: 12px;
                    gap: 7px;
                    flex-shrink: 0;
                }
                .ih-hero-btn i, .ih-hero-btn svg {
                    width: 15px !important;
                    height: 15px !important;
                }
            }

            /* ===== SMALL MOBILE (< 380px) ===== */
            @media (max-width: 379.98px) {
                .ih-hero-content { padding: 28px 14px 24px; }
                .ih-hero-title { font-size: 24px; }
                .ih-hero-desc { font-size: 13px; margin-bottom: 20px; }
                .ih-hero-buttons { gap: 10px; flex-wrap: wrap; }
                .ih-hero-btn {
                    padding: 11px 18px;
                    font-size: 12px;
                    flex: 1 1 auto;
                    min-width: 0;
                }
            }

            /* ===== REDUCED MOTION ===== */
            @media (prefers-reduced-motion: reduce) {
                .ih-hero-btn { transition: none; }
            }
        </style>
    <?php endif; ?>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/hero.blade.php ENDPATH**/ ?>