<?php
    $announcements = collect($latestAnnouncements ?? [])->take(6);
    $carouselConfig = \App\Domains\Homepage\Services\CarouselRegistry::getConfigArray('homepage-announcements');
    $resolvedTitle = $sectionTitle ?? $carouselConfig['title'] ?? 'الإعلانات';
    $resolvedSubtitle = $sectionSubtitle ?? $carouselConfig['subtitle'] ?? 'تابع آخر الإعلانات والتنبيهات البلدية';

    $formatFullDate = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('d F Y'); } catch (\Throwable) { return ''; }
    };
?>

<section id="announcements" class="overflow-hidden" style="background:white;padding-top:clamp(64px,6vw,100px);padding-bottom:clamp(64px,6vw,100px);">
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.announcements.index')): ?>
                <a href="<?php echo e(route('public.announcements.index')); ?>" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold no-underline transition-all duration-200"
                   style="background:#176B32;color:white;"
                   onmouseover="this.style.background='#0D5A28'"
                   onmouseout="this.style.background='#176B32'">
                    <span>عرض جميع الإعلانات</span>
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcements->isNotEmpty()): ?>
            <div x-data="{
                slides: <?php echo \Illuminate\Support\Js::from($announcements->values()->all())->toHtml() ?>,
                slider: null,
                currentPage: 0,
                canPrev: false,
                canNext: false,
                init() {
                    this.$nextTick(() => {
                        this.slider = this.$refs.track;
                        this.refresh();
                    });
                    window.addEventListener('resize', () => this.refresh(), { passive: true });
                },
                visible() {
                    return window.innerWidth >= 1280 ? 3 : window.innerWidth >= 768 ? 2 : 1;
                },
                step() {
                    const card = this.slider?.querySelector('.announcement-slide');
                    return card ? card.getBoundingClientRect().width + 24 : 0;
                },
                pages() {
                    return Math.max(1, Math.ceil(this.slides.length / this.visible()));
                },
                refresh() {
                    if (!this.slider) return;
                    const max = this.slider.scrollWidth - this.slider.clientWidth;
                    this.canPrev = this.slider.scrollLeft > 4;
                    this.canNext = this.slider.scrollLeft < max - 4;
                    this.currentPage = this.step() ? Math.round(this.slider.scrollLeft / (this.step() * this.visible())) : 0;
                },
                move(page) {
                    if (this.slider) this.slider.scrollTo({ left: page * this.step() * this.visible(), behavior: 'smooth' });
                }
            }" dir="rtl">
                <div class="relative">
                    <button x-show="canPrev" x-transition.opacity @click="move(Math.max(0, currentPage - 1))"
                            class="absolute -left-4 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#E9EFEA] bg-white text-[#176B32] shadow-lg transition hover:bg-[#F0F7F2]"
                            aria-label="السابق">
                        <i data-lucide="chevron-left" class="h-5 w-5"></i>
                    </button>

                    <div x-ref="track" @scroll.throttle.100ms="refresh()" tabindex="0" role="region"
                         aria-label="الإعلانات"
                         class="flex items-start gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#176B32]/30"
                         style="-ms-overflow-style:none;scrollbar-width:none;"
                         ::style="'-ms-overflow-style:none;scrollbar-width:none;'">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $fullDate = $formatFullDate($announcement['date'] ?? '');
                                $url = !empty($announcement['url']) ? $announcement['url'] : (Route::has('public.announcements.index') ? route('public.announcements.index') : '#');
                            ?>
                            <div class="announcement-slide flex shrink-0 snap-start" style="flex:0 0 calc((100% - 48px) / 3);min-width:280px;">
                                <a href="<?php echo e($url); ?>"
                                   <?php if(!empty($announcement['url'])): ?> wire:navigate <?php endif; ?>
                                   class="group flex flex-col w-full bg-white rounded-2xl overflow-hidden no-underline transition-all duration-300"
                                   style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                                   onmouseover="this.style.boxShadow='0 20px 50px rgba(23,107,50,0.10)';this.style.transform='translateY(-3px)'"
                                   onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                                    
                                    <div class="relative overflow-hidden" style="aspect-ratio:16/10;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($announcement['image'])): ?>
                                            <img src="<?php echo e($announcement['image']); ?>" alt="<?php echo e($announcement['title'] ?? ''); ?>"
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                 loading="lazy">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);">
                                                <i data-lucide="megaphone" class="w-12 h-12" style="color:#176B32;opacity:0.15;"></i>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($announcement['type'])): ?>
                                            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold" style="background:#176B32;color:white;">
                                                <?php echo e($announcement['type']); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div class="flex flex-col flex-1 p-5">
                                        <h3 class="text-base font-black leading-snug line-clamp-2 m-0" style="color:#0F1A14;">
                                            <?php echo e($announcement['title'] ?? ''); ?>

                                        </h3>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($announcement['summary'])): ?>
                                            <p class="text-sm mt-2 leading-relaxed line-clamp-2 m-0" style="color:#6B7B6E;">
                                                <?php echo e($announcement['summary']); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="flex items-center gap-3 mt-auto pt-3" style="border-top:1px solid #F0F4F0;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fullDate): ?>
                                                <span class="flex items-center gap-1.5 text-xs font-medium" style="color:#8A9A8D;">
                                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                                    <?php echo e($fullDate); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <span class="flex items-center gap-1 text-xs font-bold" style="color:#176B32;">
                                                اقرأ المزيد
                                                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    <button x-show="canNext" x-transition.opacity @click="move(currentPage + 1)"
                            class="absolute -right-4 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#E9EFEA] bg-white text-[#176B32] shadow-lg transition hover:bg-[#F0F7F2]"
                            aria-label="التالي">
                        <i data-lucide="chevron-right" class="h-5 w-5"></i>
                    </button>
                </div>

                
                <div class="mt-6 flex items-center justify-center gap-2" x-show="pages() > 1">
                    <template x-for="index in pages()" :key="index">
                        <button @click="move(index - 1)"
                                :class="currentPage === index - 1 ? 'h-2 w-7 rounded bg-[#176B32]' : 'h-2 w-2 rounded-full bg-[#DDE5DC]'"
                                class="transition-all border-none cursor-pointer"
                                :aria-label="'الانتقال إلى مجموعة ' + index"></button>
                    </template>
                </div>
            </div>

            <style>
                .announcement-slide::-webkit-scrollbar { display: none; }
                @media (max-width: 1279px) {
                    .announcement-slide { flex: 0 0 calc((100% - 24px) / 2) !important; min-width: 260px !important; }
                }
                @media (max-width: 639px) {
                    .announcement-slide { flex: 0 0 88% !important; min-width: 0 !important; }
                }
                @media (prefers-reduced-motion: reduce) {
                    .announcement-slide { scroll-behavior: auto; }
                }
            </style>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-[#DDE5DC] bg-[#F8FAF9] py-16 text-center">
                <i data-lucide="megaphone" class="mx-auto h-10 w-10" style="color:#176B32;opacity:0.3;"></i>
                <p class="mt-3 text-sm font-semibold" style="color:#6B7B6E;">لا توجد إعلانات حالياً</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/announcements.blade.php ENDPATH**/ ?>