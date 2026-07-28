<?php
    $chairman = $mayor ?? collect($featuredCouncilMembers)->first(function ($m) {
        $pos = $m['position'] ?? '';
        return str_contains($pos, 'رئيس') || $pos === 'mayor';
    });
    $membersList = collect($featuredCouncilMembers)->filter(function ($m) use ($chairman) {
        return $chairman ? ($m['id'] ?? null) !== ($chairman['id'] ?? null) : true;
    })->values();
    $hasMembers = $chairman || $membersList->isNotEmpty();

    $chairmanPhoto = null;
    $chairmanName = '';
    $chairmanRole = 'رئيس المجلس البلدي';
    $chairmanBio = '';
    $chairmanUrl = '#';
    $hasChairmanBio = false;

    if ($chairman) {
        $chairmanPhoto = $chairman['photo_url'] ?? null;
        $chairmanName = $chairman['full_name'] ?? '';
        $chairmanRole = $chairman['position_label'] ?? $chairman['position'] ?? 'رئيس المجلس البلدي';
        $chairmanBio = $chairman['bio'] ?? '';
        $chairmanSlug = $chairman['slug'] ?? ($chairman['id'] ?? null);
        $chairmanUrl = $chairmanSlug && Route::has('public.council.show') ? route('public.council.show', ['councilMember' => $chairmanSlug]) : '#';
        $hasChairmanBio = !empty($chairmanBio);
    }

    $isMayor = $chairman && (($chairman['position'] ?? '') === 'mayor' || !empty($mayor));
?>

<section id="council-members" class="py-[90px] bg-white relative overflow-hidden">
    
    <div class="absolute pointer-events-none" style="top:-30%;right:-10%;width:50%;height:80%;background:radial-gradient(ellipse 60% 70% at 70% 40%,rgba(31,122,54,0.035) 0%,transparent 70%);"></div>
    <div class="absolute pointer-events-none" style="top:10%;left:-8%;width:45%;height:70%;background:radial-gradient(ellipse 55% 65% at 30% 50%,rgba(31,122,54,0.025) 0%,transparent 70%);"></div>

    <div style="width:100%;max-width:1280px;margin-left:auto;margin-right:auto;padding-left:clamp(16px,2.5vw,36px);padding-right:clamp(16px,2.5vw,36px);" class="relative z-10">

        
        
        
        <div class="flex flex-col items-center text-center mb-[clamp(52px,5vw,64px)]">
            <span class="w-[60px] h-[2px] bg-[#1F7A36] rounded-full relative flex items-center justify-center">
                <span class="w-[6px] h-[6px] bg-[#1F7A36] rounded-full"></span>
            </span>
            <h2 class="text-[clamp(30px,3.6vw,42px)] font-extrabold text-[#0F172A] leading-[1.25] tracking-[-0.01em] mt-[14px]">
                <?php echo e($sectionTitle ?? 'أعضاء المجلس البلدي'); ?>

            </h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sectionSubtitle): ?>
                <p class="text-[clamp(14px,1.15vw,18px)] text-[#64748B] leading-[1.8] mt-[14px] max-w-[760px]">
                    <?php echo e($sectionSubtitle); ?>

                </p>
            <?php else: ?>
                <p class="text-[clamp(14px,1.15vw,18px)] text-[#64748B] leading-[1.8] mt-[14px] max-w-[760px]">
                    تعرف على رئيس وأعضاء المجلس البلدي الذين يمثلون المجتمع ويقودون العمل البلدي
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasMembers): ?>

        
        
        

        <?php
            $sliderData = $membersList->values()->toArray();
        ?>

        <div class="flex flex-col lg:flex-row gap-[clamp(24px,3vw,40px)] <?php if($chairman && $membersList->isNotEmpty()): ?> <?php else: ?> items-center <?php endif; ?>">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chairman): ?>
            <div class="w-full <?php if($chairman && $membersList->isNotEmpty()): ?> lg:w-[380px] shrink-0 <?php else: ?> max-w-[420px] mx-auto <?php endif; ?>">
                <div class="bg-white rounded-[30px] border border-[#DDEDE0] flex flex-col items-center text-center px-[clamp(24px,2.5vw,32px)] pt-[clamp(32px,3.5vw,40px)] pb-[clamp(24px,2.5vw,30px)] min-h-[500px] lg:min-h-[520px] transition-all duration-300 hover:-translate-y-[4px] hover:shadow-[0_24px_60px_rgba(15,23,42,0.12)] group relative overflow-hidden"
                    style="background:linear-gradient(180deg,#F3FBF6 0%,#FFFFFF 35%);box-shadow:0 20px 50px rgba(15,23,42,0.10);">

                    <div class="absolute right-0 top-0 bottom-0 w-[70px] opacity-[0.035] pointer-events-none"
                        style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 60 600%27%3E%3Cpath d=%27M52,30 Q32,55 42,95 Q52,75 60,85 Q62,50 52,30%27 fill=%27%231F7A36%27/%3E%3Cpath d=%27M48,130 Q28,155 38,195 Q48,175 56,185 Q58,150 48,130%27 fill=%27%231F7A36%27/%3E%3Cpath d=%27M54,240 Q34,265 44,305 Q54,285 62,295 Q64,260 54,240%27 fill=%27%231F7A36%27/%3E%3Cpath d=%27M50,350 Q30,375 40,415 Q50,395 58,405 Q60,370 50,350%27 fill=%27%231F7A36%27/%3E%3Cpath d=%27M55,460 Q35,485 45,525 Q55,505 63,515 Q65,480 55,460%27 fill=%27%231F7A36%27/%3E%3C/svg%3E');
                        background-repeat:repeat-y;
                        background-position:right center;">
                    </div>

                    <div class="w-[150px] h-[150px] rounded-full ring-[4px] ring-white shadow-[0_0_0_3px_rgba(31,122,54,0.15),0_6px_20px_rgba(0,0,0,0.06)] overflow-hidden shrink-0 bg-[#F0F7F2] transition-all duration-300 group-hover:shadow-[0_0_0_3px_rgba(31,122,54,0.25),0_8px_28px_rgba(0,0,0,0.08)]">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chairmanPhoto): ?>
                            <img src="<?php echo e($chairmanPhoto); ?>" alt="<?php echo e($chairmanName); ?>" class="w-full h-full object-cover object-[center_30%]" loading="lazy" decoding="async" width="150" height="150">
                        <?php else: ?>
                            <div class="w-full h-full bg-[#F0F7F2] flex items-center justify-center">
                                <i data-lucide="user" class="w-[60px] h-[60px] text-[#1F7A36]/35" stroke-width="1.3"></i>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <span class="inline-flex items-center h-[28px] px-[12px] rounded-full bg-[#1F7A36] text-white text-[12px] font-bold leading-none mt-[16px]">
                        <?php echo e($isMayor ? 'رئيس البلدية' : 'رئيس المجلس'); ?>

                    </span>

                    <h3 class="text-[clamp(24px,2.5vw,30px)] font-extrabold text-[#0F172A] leading-[1.35] mt-[10px]">
                        <?php echo e($chairmanName); ?>

                    </h3>

                    <p class="text-[16px] text-[#64748B] mt-[6px]">
                        <?php echo e($chairmanRole); ?>

                    </p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasChairmanBio): ?>
                        <p class="text-[14px] text-[#64748B] leading-[1.8] mt-[18px] max-w-[290px] line-clamp-3">
                            <?php echo e($chairmanBio); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="flex-1 min-h-0"></div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chairmanUrl !== '#'): ?>
                        <a href="<?php echo e($chairmanUrl); ?>" wire:navigate
                            class="inline-flex items-center justify-center h-[54px] w-full rounded-[16px] bg-[#1F7A36] text-white text-[16px] font-bold transition-all duration-200 hover:bg-[#16632B] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(31,122,54,0.30)] no-underline gap-2 mt-[20px]">
                            <span>عرض ملف الرئيس</span>
                            <i data-lucide="chevron-left" class="w-[18px] h-[18px]" stroke-width="2.5"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($membersList->isNotEmpty()): ?>
            <div class="min-w-0 relative <?php if($chairman): ?> flex-1 <?php else: ?> w-full <?php endif; ?>">

                <div x-data="{
                    slides: <?php echo \Illuminate\Support\Js::from($sliderData)->toHtml() ?>,
                    currentIndex: 0,
                    canPrev: false,
                    canNext: false,
                    sliderEl: null,
                    init() {
                        this.$nextTick(() => {
                            this.sliderEl = this.$refs.track;
                            if (this.sliderEl) {
                                this.checkArrows();
                                this.sliderEl.addEventListener('scroll', () => this.checkArrows(), { passive: true });
                            }
                        });
                    },
                    slidePrev() {
                        if (!this.sliderEl) return;
                        const cardWidth = this.sliderEl.querySelector('.member-slide')?.offsetWidth || 250;
                        this.sliderEl.scrollBy({ left: -(cardWidth + 24), behavior: 'smooth' });
                    },
                    slideNext() {
                        if (!this.sliderEl) return;
                        const cardWidth = this.sliderEl.querySelector('.member-slide')?.offsetWidth || 250;
                        this.sliderEl.scrollBy({ left: cardWidth + 24, behavior: 'smooth' });
                    },
                    checkArrows() {
                        if (!this.sliderEl) return;
                        const t = this.sliderEl;
                        this.canPrev = t.scrollLeft > 10;
                        this.canNext = t.scrollLeft < t.scrollWidth - t.clientWidth - 10;
                        const children = t.querySelectorAll('.member-slide');
                        let acc = 0;
                        for (let i = 0; i < children.length; i++) {
                            const w = (children[i]?.offsetWidth || 250) + 24;
                            if (t.scrollLeft < acc + w / 2) { this.currentIndex = i; break; }
                            acc += w;
                        }
                    },
                    scrollToSlide(index) {
                        if (!this.sliderEl) return;
                        const children = this.sliderEl.querySelectorAll('.member-slide');
                        if (!children[index]) return;
                        let target = 0;
                        for (let i = 0; i < index; i++) {
                            target += (children[i]?.offsetWidth || 250) + 24;
                        }
                        this.sliderEl.scrollTo({ left: target, behavior: 'smooth' });
                        this.currentIndex = index;
                    }
                }" class="relative">

                    
                    <button x-show="canPrev" x-transition.opacity.duration.200ms
                        @click="slidePrev()"
                        class="absolute -right-[18px] top-[calc(50%-24px)] -translate-y-1/2 z-20 w-[48px] h-[48px] rounded-full bg-white border border-[#E9EFEA] shadow-[0_4px_16px_rgba(15,23,42,0.06)] hover:bg-[#F0F7F2] hover:border-[#1F7A36]/30 transition-all flex items-center justify-center cursor-pointer"
                        aria-label="السابق">
                        <i data-lucide="chevron-right" class="w-[20px] h-[20px] text-[#1F7A36]" stroke-width="2.5"></i>
                    </button>

                    
                    <div x-ref="track"
                        @scroll.throttle.100ms="checkArrows()"
                        class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-none pb-2">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $membersList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $mPhoto = $member['photo_url'] ?? null;
                                $mSlug = $member['slug'] ?? $member['id'] ?? null;
                                $mUrl = $mSlug && Route::has('public.council.show') ? route('public.council.show', ['councilMember' => $mSlug]) : '#';
                                $mName = $member['full_name'] ?? '';
                                $mRole = $member['position_label'] ?? $member['position'] ?? 'عضو المجلس البلدي';
                                $mPhone = $member['phone'] ?? $member['mobile'] ?? null;
                                $mEmail = $member['email'] ?? null;
                                $mCommittee = $member['committee'] ?? null;
                            ?>
                            <div class="member-slide snap-start shrink-0 w-[calc((100%-72px)/4)] min-w-[220px] lg:min-w-[230px]">
                                <div class="bg-white rounded-[24px] border border-[#E9EFEA] flex flex-col items-center text-center px-[24px] pt-[32px] pb-[24px] min-h-[430px] transition-all duration-300 hover:-translate-y-[6px] hover:shadow-[0_16px_40px_rgba(15,23,42,0.07)] hover:border-[#1F7A36]/18 group"
                                    style="box-shadow:0 8px 25px rgba(15,23,42,0.06);">

                                    <div class="w-[120px] h-[120px] rounded-full ring-[4px] ring-white shadow-[0_0_0_3px_rgba(31,122,54,0.12),0_4px_14px_rgba(0,0,0,0.04)] overflow-hidden shrink-0 bg-[#F0F7F2] transition-all duration-300 group-hover:shadow-[0_0_0_3px_rgba(31,122,54,0.22),0_6px_20px_rgba(0,0,0,0.06)]">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mPhoto): ?>
                                            <img src="<?php echo e($mPhoto); ?>" alt="<?php echo e($mName); ?>" class="w-full h-full object-cover object-[center_30%]" loading="lazy" decoding="async" width="120" height="120">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-[#F0F7F2] flex items-center justify-center">
                                                <i data-lucide="user" class="w-[48px] h-[48px] text-[#1F7A36]/35" stroke-width="1.3"></i>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <h3 class="text-[18px] font-extrabold text-[#0F172A] leading-[1.4] mt-[20px] line-clamp-2">
                                        <?php echo e($mName); ?>

                                    </h3>

                                    <p class="text-[14px] text-[#64748B] mt-[8px]">
                                        <?php echo e($mRole); ?>

                                    </p>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mCommittee): ?>
                                        <span class="inline-flex items-center text-[14px] text-[#1F7A36] font-semibold mt-[10px] leading-[1.4] max-w-full line-clamp-2">
                                            <?php echo e($mCommittee); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div class="flex-1 min-h-0"></div>

                                    <div class="flex items-center justify-center gap-[12px] mt-auto pt-[18px]">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mPhone): ?>
                                            <a href="tel:<?php echo e($mPhone); ?>"
                                                class="w-[42px] h-[42px] rounded-full bg-[#EEF8F0] flex items-center justify-center transition-all duration-200 hover:bg-[#1F7A36] text-[#1F7A36] hover:text-white no-underline"
                                                aria-label="اتصال بـ <?php echo e($mName); ?>">
                                                <i data-lucide="phone" class="w-[18px] h-[18px]" stroke-width="2"></i>
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mEmail): ?>
                                            <a href="mailto:<?php echo e($mEmail); ?>"
                                                class="w-[42px] h-[42px] rounded-full bg-[#EEF8F0] flex items-center justify-center transition-all duration-200 hover:bg-[#1F7A36] text-[#1F7A36] hover:text-white no-underline"
                                                aria-label="بريد <?php echo e($mName); ?>">
                                                <i data-lucide="envelope" class="w-[18px] h-[18px]" stroke-width="2"></i>
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e($mUrl); ?>" wire:navigate
                                            class="w-[42px] h-[42px] rounded-full bg-[#EEF8F0] flex items-center justify-center transition-all duration-200 hover:bg-[#1F7A36] text-[#1F7A36] hover:text-white no-underline"
                                            aria-label="الملف الشخصي لـ <?php echo e($mName); ?>">
                                            <i data-lucide="user" class="w-[18px] h-[18px]" stroke-width="2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                    </div>

                    
                    <button x-show="canNext" x-transition.opacity.duration.200ms
                        @click="slideNext()"
                        class="absolute -left-[18px] top-[calc(50%-24px)] -translate-y-1/2 z-20 w-[48px] h-[48px] rounded-full bg-white border border-[#E9EFEA] shadow-[0_4px_16px_rgba(15,23,42,0.06)] hover:bg-[#F0F7F2] hover:border-[#1F7A36]/30 transition-all flex items-center justify-center cursor-pointer"
                        aria-label="التالي">
                        <i data-lucide="chevron-left" class="w-[20px] h-[20px] text-[#1F7A36]" stroke-width="2.5"></i>
                    </button>

                    
                    <div class="flex items-center justify-center gap-[8px] mt-[28px]">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="scrollToSlide(index)"
                                :class="{
                                    'bg-[#1F7A36] w-[24px] rounded-[4px]': index === currentIndex,
                                    'bg-[#DDE5DC] w-[8px] rounded-full': index !== currentIndex
                                }"
                                class="h-[8px] transition-all duration-300 cursor-pointer"
                                :aria-label="'الانتقال إلى العضو ' + (index + 1)">
                            </button>
                        </template>
                    </div>

                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        
        <div class="flex justify-center mt-[28px]">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.council.index')): ?>
                <a href="<?php echo e(route('public.council.index')); ?>" wire:navigate
                    class="inline-flex items-center gap-[10px] h-[48px] px-[28px] rounded-[16px] border border-[#1F7A36] bg-white text-[#1F7A36] text-[16px] font-bold transition-all duration-200 hover:bg-[#1F7A36] hover:text-white no-underline">
                    <i data-lucide="users" class="w-[20px] h-[20px]" stroke-width="2"></i>
                    <span>عرض جميع الأعضاء</span>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php else: ?>
        <div class="flex flex-col items-center justify-center text-center py-[60px]">
            <div class="w-[56px] h-[56px] rounded-full bg-[#F0F7F2] flex items-center justify-center mb-[16px]">
                <i data-lucide="users" class="w-[28px] h-[28px] text-[#1F7A36]/50" stroke-width="1.5"></i>
            </div>
            <h3 class="text-[18px] font-bold text-[#0F172A]">لا يوجد أعضاء مجلس حالياً</h3>
            <p class="text-[14px] text-[#64748B] mt-[6px]">سيتم إضافة أعضاء المجلس البلدي قريباً</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <style>
        .scrollbar-none::-webkit-scrollbar { display: none; width: 0; height: 0; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
        @media (prefers-reduced-motion: reduce) {
            .flex { scroll-behavior: auto; }
        }
        .member-slide {
            scroll-snap-align: start;
        }
    </style>

</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/council-members.blade.php ENDPATH**/ ?>