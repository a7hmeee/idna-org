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
            init() {
                if (this.total > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    this.startAutoplay();
                }
            },
            startAutoplay() {
                this.interval = setInterval(() => {
                    this.next();
                }, 8000);
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
        aria-label="عرض الشرائح"
    >
        
        <div class="relative" style="min-height: clamp(520px, 80vh, 720px);">
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
                     aria-label="<?php echo e('شريحة ' . ($index + 1) . ' من ' . count($slides)); ?>"
                    <?php if($index === 0): ?>
                     
                    <?php endif; ?>>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['image_url'])): ?>
                        <img src="<?php echo e($slide['image_url']); ?>"
                             alt="<?php echo e($slide['title'] ?? ''); ?>"
                             class="w-full h-full object-cover object-center"
                             style="min-height: clamp(520px, 80vh, 720px);"
                             <?php if($index === 0): ?>
                             fetchpriority="high" loading="eager"
                             <?php else: ?>
                             loading="lazy" decoding="async"
                             <?php endif; ?>
                             onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                    <?php else: ?>
                        <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);min-height: clamp(520px, 80vh, 720px);"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            
            <div class="absolute inset-0 z-10 bg-gradient-to-l from-[#031F10]/92 via-[#073A25]/70 via-35% to-[#073A25]/30 to-70%"></div>

            
            <div class="absolute inset-0 z-10 pointer-events-none opacity-[0.04]" aria-hidden="true">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="islamic-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                            <rect width="80" height="80" fill="none"/>
                            <path d="M40 0 L80 40 L40 80 L0 40 Z" fill="none" stroke="#C8A85A" stroke-width="0.5"/>
                            <path d="M40 10 L70 40 L40 70 L10 40 Z" fill="none" stroke="#C8A85A" stroke-width="0.5"/>
                            <path d="M40 20 L60 40 L40 60 L20 40 Z" fill="none" stroke="#C8A85A" stroke-width="0.4"/>
                            <circle cx="40" cy="40" r="3" fill="none" stroke="#C8A85A" stroke-width="0.4"/>
                            <circle cx="40" cy="40" r="1" fill="#C8A85A" opacity="0.5"/>
                            <line x1="0" y1="40" x2="80" y2="40" stroke="#C8A85A" stroke-width="0.2" opacity="0.5"/>
                            <line x1="40" y1="0" x2="40" y2="80" stroke="#C8A85A" stroke-width="0.2" opacity="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#islamic-pattern)"/>
                </svg>
            </div>

            
            <div class="absolute top-0 right-0 left-0 h-[2px] z-20 pointer-events-none" style="background: linear-gradient(90deg, transparent 0%, #C8A85A 20%, #C8A85A 80%, transparent 100%); opacity: 0.6;" aria-hidden="true"></div>

            
            <div class="absolute left-0 top-0 bottom-0 w-[35%] max-w-[500px] z-20 pointer-events-none overflow-hidden hidden lg:block" aria-hidden="true">
                <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #0F6A3D 0%, #0F6A3D 40%, transparent 100%); clip-path: polygon(100% 0, 0 0, 0 100%, 100% 100%, 85% 80%, 78% 60%, 82% 40%, 90% 20%); opacity: 0.85;"></div>
                <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #2B8A4B 0%, transparent 70%); clip-path: polygon(100% 0, 5% 0, 15% 100%, 100% 100%, 88% 82%, 82% 60%, 85% 35%); opacity: 0.30; transform: translateY(5%);"></div>
            </div>

            
            <div class="absolute top-4 left-4 z-20 pointer-events-none opacity-30 hidden md:block" aria-hidden="true">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 55 L5 5 L55 5" stroke="#C8A85A" stroke-width="1" fill="none"/>
                    <path d="M10 50 L10 10 L50 10" stroke="#C8A85A" stroke-width="0.5" fill="none" opacity="0.7"/>
                    <circle cx="5" cy="5" r="2" fill="#C8A85A" opacity="0.8"/>
                    <path d="M5 15 L15 5" stroke="#C8A85A" stroke-width="0.5" fill="none" opacity="0.6"/>
                </svg>
            </div>
            <div class="absolute top-4 right-4 z-20 pointer-events-none opacity-30 hidden md:block" aria-hidden="true">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M55 55 L55 5 L5 5" stroke="#C8A85A" stroke-width="1" fill="none"/>
                    <path d="M50 50 L50 10 L10 10" stroke="#C8A85A" stroke-width="0.5" fill="none" opacity="0.7"/>
                    <circle cx="55" cy="5" r="2" fill="#C8A85A" opacity="0.8"/>
                    <path d="M55 15 L45 5" stroke="#C8A85A" stroke-width="0.5" fill="none" opacity="0.6"/>
                </svg>
            </div>

            
            <div class="relative z-30 w-full h-full flex items-center" dir="rtl">
                <div class="w-full py-20 sm:py-24 lg:py-28" style="padding-top:max(80px, 6vh);padding-bottom:max(80px, 12vh);">
                    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-[580px] lg:max-w-[640px] mr-auto lg:mr-[5%] text-right">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div x-show="current === <?php echo e($index); ?>" x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['badge_text'])): ?>
                                        <span class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/[0.07] backdrop-blur-md text-white/95 text-xs sm:text-sm font-bold mb-5 sm:mb-6 border border-white/[0.15] shadow-lg relative z-10" style="box-shadow: 0 4px 16px rgba(0,0,0,0.2);">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#C8A85A] shadow-[0_0_6px_rgba(200,168,90,0.5)]"></span>
                                            <?php echo e($slide['badge_text']); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/[0.07] backdrop-blur-md text-white/95 text-xs sm:text-sm font-bold mb-5 sm:mb-6 border border-white/[0.15] shadow-lg relative z-10" style="box-shadow: 0 4px 16px rgba(0,0,0,0.2);">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#C8A85A] shadow-[0_0_6px_rgba(200,168,90,0.5)]"></span>
                                            الخدمات الإلكترونية
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    
                                    <h1 class="font-black text-white leading-[1.15] mb-2 relative z-10" style="font-size: clamp(32px, 6vw, 72px); text-shadow: 0 2px 24px rgba(0,0,0,0.35); letter-spacing: -0.01em;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['title'])): ?>
                                            <?php echo e($slide['title']); ?>

                                        <?php else: ?>
                                            مرحباً بكم في
                                            <br>
                                            <span class="relative inline-block" style="color: #7BBC9D;">
                                                <?php echo e($municipalityName); ?>

                                                
                                                <svg class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-[80%] h-3 text-[#C8A85A] opacity-60" viewBox="0 0 200 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path d="M0 6 Q 25 0, 50 6 T 100 6 T 150 6 T 200 6" stroke="currentColor" stroke-width="1.2" fill="none"/>
                                                    <circle cx="100" cy="6" r="2" fill="currentColor" opacity="0.8"/>
                                                    <circle cx="60" cy="6" r="1.2" fill="currentColor" opacity="0.5"/>
                                                    <circle cx="140" cy="6" r="1.2" fill="currentColor" opacity="0.5"/>
                                                </svg>
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </h1>

                                    
                                    <div class="flex items-center gap-3 mb-5 mt-3" aria-hidden="true">
                                        <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent via-[#C8A85A]/40 to-transparent max-w-[120px]"></div>
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-[#C8A85A]/60">
                                            <path d="M10 1 L12 8 L19 10 L12 12 L10 19 L8 12 L1 10 L8 8 Z" fill="currentColor" opacity="0.7"/>
                                        </svg>
                                        <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent via-[#C8A85A]/40 to-transparent max-w-[120px]"></div>
                                    </div>

                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['description'])): ?>
                                        <p class="text-white/85 leading-[1.8] mb-8 max-w-[500px] relative z-10" style="font-size: clamp(14px, 1.6vw, 17px); text-shadow: 0 1px 8px rgba(0,0,0,0.15);">
                                            <?php echo e($slide['description']); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 relative z-10">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                                            <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2.5 px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl bg-white text-[#073A25] font-bold text-sm sm:text-base hover:bg-gray-50 transition-all duration-300 relative overflow-hidden group"
                                               style="box-shadow: 0 4px 20px rgba(0,0,0,0.15), 0 0 0 1px rgba(200,168,90,0.15);">
                                                <span class="absolute inset-0 bg-gradient-to-r from-[#C8A85A]/0 via-[#C8A85A]/10 to-[#C8A85A]/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>
                                                <i data-lucide="external-link" class="w-4 h-4 sm:w-5 sm:h-5 relative z-10"></i>
                                                <span class="relative z-10"><?php echo e($primaryBtn); ?></span>
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e($secondaryBtnUrl); ?>"
                                           class="inline-flex items-center gap-2.5 px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl border border-white/20 text-white font-semibold text-sm sm:text-base hover:bg-white/10 hover:border-[#C8A85A]/40 transition-all duration-300 backdrop-blur-sm relative group"
                                           style="box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
                                            <span class="absolute inset-0 bg-gradient-to-r from-[#C8A85A]/0 via-[#C8A85A]/8 to-[#C8A85A]/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>
                                            <i data-lucide="info" class="w-4 h-4 sm:w-5 sm:h-5 relative z-10"></i>
                                            <span class="relative z-10"><?php echo e($secondaryBtn); ?></span>
                                        </a>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$singleSlide && count($slides) > 1): ?>
            <div class="absolute bottom-6 sm:bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2" role="tablist" aria-label="اختيار الشريحة">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button @click="goTo(<?php echo e($index); ?>)"
                            :class="current === <?php echo e($index); ?> ? 'bg-[#C8A85A] w-7' : 'bg-white/40 w-2.5 hover:bg-white/70'"
                            class="h-2.5 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#C8A85A]/60"
                            role="tab"
                            :aria-selected="current === <?php echo e($index); ?>"
                            :aria-label="'الانتقال إلى الشريحة ' + (<?php echo e($index); ?> + 1)">
                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="absolute bottom-0 right-0 left-0 h-[1px] z-20 pointer-events-none" style="background: linear-gradient(90deg, transparent 0%, #C8A85A/30 20%, #C8A85A/30 80%, transparent 100%);" aria-hidden="true"></div>
    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/hero.blade.php ENDPATH**/ ?>