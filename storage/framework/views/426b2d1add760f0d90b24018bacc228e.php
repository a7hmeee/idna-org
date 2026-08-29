<?php
    $decisionTypes = [
        'financial' => ['label' => 'مالي', 'color' => 'bg-[#EEF8F0] text-[#1F7A36]'],
        'regulatory' => ['label' => 'تنظيمي', 'color' => 'bg-[#FEF3C7] text-[#92400E]'],
        'infrastructure' => ['label' => 'بنية تحتية', 'color' => 'bg-[#DBEAFE] text-[#1E40AF]'],
        'administrative' => ['label' => 'إداري', 'color' => 'bg-[#F3E8FF] text-[#6B21A8]'],
        'service' => ['label' => 'خدمي', 'color' => 'bg-[#DCFCE7] text-[#166534]'],
    ];

    $featured = $latestCouncilDecisions[0] ?? null;
    $recent = collect($latestCouncilDecisions)->skip(1)->take(3);

    $showRouteExists = Route::has('public.council.decisions.show');
    $indexRouteExists = Route::has('public.council.decisions.index');

    $featuredTypeInfo = $featured
        ? ($decisionTypes[$featured['type'] ?? ''] ?? ['label' => $featured['type'] ?? 'عام', 'color' => 'bg-[#EEF8F0] text-[#1F7A36]'])
        : null;

    $featuredUrl = '#';
    if ($featured && $showRouteExists && !empty($featured['id'])) {
        $featuredUrl = route('public.council.decisions.show', $featured['id']);
    }

    $carouselConfig = \App\Domains\Homepage\Services\CarouselRegistry::getConfigArray('homepage-council-decisions');
    $resolvedTitle = $sectionTitle ?? $carouselConfig['title'] ?? 'قرارات المجلس البلدي';
    $resolvedSubtitle = $sectionSubtitle ?? $carouselConfig['subtitle'] ?? 'اطلع على أحدث قرارات المجلس البلدي';
?>

<section id="council-decisions" class="bg-white relative overflow-hidden" style="padding-top:56px;padding-bottom:80px;background:linear-gradient(180deg, #F7FAF7 0%, #FFFFFF 140px);">

    
    <div class="absolute pointer-events-none" style="top:-30%;right:-10%;width:55%;height:100%;background:radial-gradient(ellipse 60% 70% at 70% 40%,rgba(31,122,54,0.03) 0%,transparent 70%);"></div>

    <div class="relative z-10" style="width:100%;max-width:1280px;margin-left:auto;margin-right:auto;padding-left:clamp(16px,2.5vw,36px);padding-right:clamp(16px,2.5vw,36px);">

        
        <div class="text-center mb-[52px]">
            <h2 class="text-[clamp(32px,3.8vw,46px)] font-extrabold text-[#0F172A] leading-[1.2]">
                <?php echo e($resolvedTitle); ?>

            </h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resolvedSubtitle): ?>
                <p class="text-[clamp(14px,1.15vw,18px)] text-[#64748B] leading-[1.8] mt-[14px] max-w-[600px] mx-auto"><?php echo e($resolvedSubtitle); ?></p>
            <?php else: ?>
                <p class="text-[clamp(14px,1.15vw,18px)] text-[#64748B] leading-[1.8] mt-[14px] max-w-[600px] mx-auto">اطلع على أحدث القرارات والمصادقات الرسمية الصادرة عن المجلس البلدي</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured): ?>

        <div class="decisions-grid" style="display:grid;grid-template-columns:70% 30%;gap:32px;">

            
            <div class="fade-in-up" style="animation-delay:0s">
                <div class="bg-white rounded-[24px] relative overflow-hidden group transition-all duration-500 hover:scale-[1.008]"
                     style="box-shadow:0 20px 60px rgba(15,23,42,0.06);height:100%;">

                    
                    <div class="absolute top-0 bottom-0 pointer-events-none flex items-center justify-center group-hover:opacity-[0.10] transition-opacity duration-500"
                         style="right:0;width:35%;opacity:0.07;">
                        <svg viewBox="0 0 160 240" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:90%;height:auto;">
                            <path d="M20,8 H105 L140,43 V218 Q140,228 130,228 H20 Q10,228 10,218 V18 Q10,8 20,8Z" fill="rgba(31,122,54,0.035)" stroke="#1F7A36" stroke-width="1" stroke-opacity="0.10"/>
                            <path d="M105,8 V43 H140" fill="rgba(31,122,54,0.015)" stroke="#1F7A36" stroke-width="1" stroke-opacity="0.10"/>
                            <rect x="32" y="60" width="75" height="3" rx="1.5" fill="#1F7A36" opacity="0.08"/>
                            <rect x="32" y="78" width="60" height="3" rx="1.5" fill="#1F7A36" opacity="0.06"/>
                            <rect x="32" y="96" width="68" height="3" rx="1.5" fill="#1F7A36" opacity="0.05"/>
                            <rect x="32" y="114" width="45" height="3" rx="1.5" fill="#1F7A36" opacity="0.04"/>
                            <rect x="32" y="138" width="55" height="3" rx="1.5" fill="#1F7A36" opacity="0.06"/>
                            <rect x="32" y="156" width="40" height="3" rx="1.5" fill="#1F7A36" opacity="0.04"/>
                            <rect x="32" y="180" width="75" height="18" rx="4" fill="#1F7A36" opacity="0.08"/>
                            <rect x="32" y="180" width="35" height="18" rx="4" fill="#1F7A36" opacity="0.15"/>
                            <circle cx="118" cy="170" r="14" fill="none" stroke="#1F7A36" stroke-width="0.7" opacity="0.07"/>
                            <path d="M109,170 L114,175 L126,164" stroke="#1F7A36" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" opacity="0.12"/>
                        </svg>
                    </div>

                    
                    <div class="relative z-10 flex flex-col" style="padding:clamp(24px,2.8vw,36px);padding-inline-end:38%;min-height:100%;">

                        
                        <div class="flex items-center justify-between mb-[18px]">
                            <span class="inline-flex items-center h-[28px] px-[12px] rounded-full text-[12px] font-bold leading-none <?php echo e($featuredTypeInfo['color']); ?>">
                                <?php echo e($featuredTypeInfo['label']); ?>

                            </span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featured['decision_number'])): ?>
                            <span class="inline-flex items-center h-[28px] px-[12px] rounded-full bg-[#F8FAFC] border border-[#E9EFEA] text-[#64748B] text-[12px] font-bold leading-none gap-[5px]">
                                <i data-lucide="hash" class="w-[12px] h-[12px]"></i>
                                <?php echo e($featured['decision_number']); ?>

                            </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <h3 class="text-[clamp(22px,2.4vw,30px)] font-extrabold text-[#0F172A] leading-[1.35]">
                            <?php echo e($featured['title'] ?? ''); ?>

                        </h3>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featured['summary'])): ?>
                        <p class="text-[15px] text-[#64748B] leading-[1.8] mt-[14px] line-clamp-3">
                            <?php echo e($featured['summary']); ?>

                        </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div class="flex-1 min-h-0"></div>

                        
                        <div class="flex items-center flex-wrap gap-x-[20px] gap-y-[8px] mt-[20px]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featured['decision_date'])): ?>
                            <span class="text-[13px] text-[#64748B] flex items-center gap-[6px]">
                                <i data-lucide="calendar" class="w-[14px] h-[14px] text-[#1F7A36]" stroke-width="1.8"></i>
                                <?php echo e($formatDate($featured['decision_date'], 'Y-m-d')); ?>

                            </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($featured['session_number'])): ?>
                            <span class="text-[13px] text-[#64748B] flex items-center gap-[6px]">
                                <i data-lucide="layers" class="w-[14px] h-[14px] text-[#1F7A36]" stroke-width="1.8"></i>
                                <?php echo e($featured['session_number']); ?>

                            </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="text-[13px] text-[#64748B] flex items-center gap-[6px]">
                                <i data-lucide="folder" class="w-[14px] h-[14px] text-[#1F7A36]" stroke-width="1.8"></i>
                                <?php echo e($featuredTypeInfo['label']); ?>

                            </span>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredUrl !== '#'): ?>
                        <div class="mt-[24px]">
                            <a href="<?php echo e($featuredUrl); ?>" wire:navigate
                               class="inline-flex items-center justify-center h-[48px] px-[24px] rounded-[14px] bg-[#1F7A36] text-white text-[14px] font-bold transition-all duration-300 hover:bg-[#16632B] hover:-translate-y-[1px] hover:shadow-[0_6px_20px_rgba(31,122,54,0.25)] no-underline gap-[8px]">
                                <span>عرض القرار</span>
                                <i data-lucide="chevron-left" class="w-[17px] h-[17px]" stroke-width="2.5"></i>
                            </a>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recent->isNotEmpty()): ?>
            <div class="flex flex-col gap-[16px] fade-in-up" style="animation-delay:0.1s">
                <?php $stagger = 0.16; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $decision): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $dUrl = '#';
                        if ($showRouteExists && !empty($decision['id'])) {
                            $dUrl = route('public.council.decisions.show', $decision['id']);
                        }
                        $isLink = $dUrl !== '#';
                    ?>
                    <div class="fade-in-up" style="animation-delay:<?php echo e($stagger); ?>s">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLink): ?>
                        <a href="<?php echo e($dUrl); ?>" wire:navigate class="block no-underline group">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="bg-white rounded-[16px] flex items-center gap-[14px] transition-all duration-300 group-hover:-translate-y-[2px] group-hover:border-[#1F7A36]/25 group-hover:shadow-[0_8px_24px_rgba(15,23,42,0.07)]"
                                 style="height:120px;padding:20px;box-shadow:0 4px 16px rgba(15,23,42,0.04);border:1px solid #E9EFEA;">
                                <div class="w-[42px] h-[42px] rounded-[12px] bg-[#EEF8F0] flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#1F7A36]">
                                    <i data-lucide="file-text" class="w-[19px] h-[19px] text-[#1F7A36] transition-all duration-300 group-hover:text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[15px] font-extrabold text-[#0F172A] leading-[1.4] line-clamp-1 transition-colors duration-300 group-hover:text-[#1F7A36]">
                                        <?php echo e($decision['title'] ?? ''); ?>

                                    </h4>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($decision['decision_date'])): ?>
                                    <span class="text-[12px] text-[#94A3B8] mt-[6px] block"><?php echo e($formatDate($decision['decision_date'], 'Y-m-d')); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLink): ?>
                                <i data-lucide="chevron-left" class="w-[18px] h-[18px] text-[#1F7A36] opacity-0 -translate-x-1 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-0 shrink-0"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLink): ?>
                        </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php $stagger += 0.06; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php else: ?>
        
        <div class="flex flex-col items-center justify-center text-center" style="padding:100px 0;">
            <div style="width:72px;height:72px;border-radius:50%;background:#F0F7F2;display:flex;align-items:center;justify-content:center;margin-bottom:18px;">
                <svg style="width:32px;height:32px;color:rgba(31,122,54,0.35);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <h3 class="text-[20px] font-bold text-[#0F172A]">لا توجد قرارات منشورة</h3>
            <p class="text-[14px] text-[#64748B] mt-[8px]">سيتم نشر القرارات الرسمية للمجلس البلدي قريباً</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($indexRouteExists && $featured): ?>
        <div class="flex justify-center mt-[44px] fade-in-up" style="animation-delay:0.3s">
            <a href="<?php echo e(route('public.council.decisions.index')); ?>" wire:navigate
               class="inline-flex items-center gap-[10px] h-[50px] px-[32px] rounded-full border-2 border-[#1F7A36] bg-white text-[#1F7A36] text-[14px] font-bold transition-all duration-300 hover:bg-[#1F7A36] hover:text-white hover:-translate-y-[1px] hover:shadow-[0_6px_20px_rgba(31,122,54,0.18)] no-underline group">
                <span>عرض جميع القرارات</span>
                <i data-lucide="arrow-left" class="w-[17px] h-[17px] transition-transform duration-300 group-hover:-translate-x-1"></i>
            </a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <style>
        @keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .fade-in-up{animation:fadeInUp 0.6s ease forwards;opacity:0}
        @media(prefers-reduced-motion:reduce){.fade-in-up{animation:none;opacity:1}}
        @media (max-width: 768px) {
            .decisions-grid { grid-template-columns: 1fr !important; gap: 24px !important; }
        }
    </style>

</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/council-decisions.blade.php ENDPATH**/ ?>