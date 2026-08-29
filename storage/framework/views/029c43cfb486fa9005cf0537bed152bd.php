<?php
    $members = collect($featuredCouncilMembers);
    $president = $members->firstWhere('position', 'mayor');
    $regularMembers = $members->reject(fn (array $member): bool =>
        $president !== null && ($member['id'] ?? null) === ($president['id'] ?? null)
    )->values();

    $photoUrl = static fn (array $member): ?string => ! empty($member['photo_url'])
        ? $member['photo_url']
        : null;

    $profileUrl = static function (array $member): string {
        $slug = $member['slug'] ?? null;

        return $slug && Route::has('public.council.show')
            ? route('public.council.show', ['councilMember' => $slug])
            : '#';
    };

    $initials = static function (string $name): string {
        $parts = array_values(array_filter(preg_split('/\s+/u', trim($name)) ?: []));

        return count($parts) > 1
            ? mb_substr($parts[0], 0, 1).' '.mb_substr($parts[1], 0, 1)
            : mb_substr($parts[0] ?? '', 0, 2);
    };
?>

<section id="council-members" class="relative overflow-hidden bg-white py-[clamp(56px,6vw,88px)]">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true" style="background:radial-gradient(ellipse 55% 70% at 80% 20%,rgba(23,107,50,.035),transparent 70%),radial-gradient(ellipse 45% 60% at 15% 80%,rgba(23,107,50,.025),transparent 70%);"></div>

    <div class="relative z-10 mx-auto w-full max-w-[1400px] px-5 sm:px-8">
        <header class="relative mb-10 flex flex-col items-center text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-[#D4E8DA] bg-[#F0F7F2]">
                <i data-lucide="users" class="h-7 w-7 text-[#176B32]" stroke-width="1.8"></i>
            </div>
            <div class="mb-3 flex items-center gap-4" aria-hidden="true">
                <span class="h-0.5 w-14 rounded-full" style="background:linear-gradient(90deg,transparent,#C8A85A);"></span>
                <span class="h-2 w-2 rounded-full bg-[#C8A85A]"></span>
                <span class="h-0.5 w-14 rounded-full" style="background:linear-gradient(270deg,transparent,#C8A85A);"></span>
            </div>
            <h2 class="text-[clamp(28px,3.4vw,42px)] font-extrabold leading-tight tracking-tight text-[#17243A]">
                <?php echo e($sectionTitle ?? 'أعضاء المجلس البلدي'); ?>

            </h2>
            <p class="mt-3 max-w-xl text-[clamp(14px,1.1vw,17px)] leading-relaxed text-[#64748B]">
                <?php echo e($sectionSubtitle ?? 'نعمل معاً من أجل تطوير مدينتنا وخدمة المواطنين'); ?>

            </p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.council.index')): ?>
                <a href="<?php echo e(route('public.council.index')); ?>" wire:navigate
                   class="absolute left-0 top-0 hidden h-10 items-center gap-2 rounded-xl border border-[#D4E8DA] bg-white px-4 text-sm font-semibold text-[#176B32] no-underline transition hover:border-[#176B32]/30 hover:bg-[#F0F7F2] lg:inline-flex">
                    <i data-lucide="arrow-left" class="h-4 w-4" stroke-width="2"></i>
                    عرض جميع الأعضاء
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </header>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($members->isNotEmpty()): ?>
            <div class="homepage-council-composition flex flex-col items-start gap-6 lg:flex-row" dir="ltr">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($president): ?>
                    <?php
                        $presidentName = $president['full_name'] ?? '';
                        $presidentPhoto = $photoUrl($president);
                        $presidentProfile = $profileUrl($president);
                    ?>
                    <article class="homepage-council-president relative flex w-full shrink-0 flex-col overflow-hidden rounded-[28px] border border-[#0B4D28] bg-[#07552D] text-center text-white shadow-[0_18px_50px_rgba(8,70,34,.22)]" dir="rtl">
                        <div class="pointer-events-none absolute inset-0 opacity-[.08]" aria-hidden="true" style="background-image:radial-gradient(circle at 15% 20%,#C8A85A 0 1px,transparent 2px),radial-gradient(circle at 80% 80%,#fff 0 1px,transparent 2px);background-size:28px 28px;"></div>
                        <div class="pointer-events-none absolute -bottom-20 -left-12 h-64 w-64 rounded-full border border-[#C8A85A]/20" aria-hidden="true"></div>
                        <div class="absolute right-5 top-0 z-10 flex h-14 w-11 items-center justify-center rounded-b-lg bg-[#C8A85A] text-white shadow-md" aria-label="رئيس المجلس البلدي">
                            <i data-lucide="star" class="h-5 w-5" fill="currentColor"></i>
                        </div>

                        <div class="relative z-10 flex min-h-0 flex-1 flex-col items-center p-5 sm:p-6">
                            <div class="flex h-[280px] w-full shrink-0 items-center justify-center overflow-hidden rounded-[20px] bg-[#0B4D28] ring-1 ring-white/15">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($presidentPhoto): ?>
                                    <img src="<?php echo e($presidentPhoto); ?>" alt="<?php echo e($presidentName); ?>" class="h-full w-full object-cover object-[center_22%]" loading="lazy" decoding="async">
                                <?php else: ?>
                                    <span class="text-5xl font-black text-white/35" aria-hidden="true"><?php echo e($initials($presidentName)); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="mt-4 inline-flex items-center rounded-full border border-[#C8A85A]/45 bg-[#C8A85A]/15 px-4 py-1.5 text-xs font-bold text-[#F5E6B8]">رئيس المجلس البلدي</span>
                            <h3 class="mt-3 text-[clamp(20px,2vw,28px)] font-extrabold leading-tight"><?php echo e($presidentName); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($president['position_label'] ?? null)): ?>
                                <p class="mt-2 text-sm font-semibold text-white/75"><?php echo e($president['position_label']); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($president['bio'] ?? null)): ?>
                                <p class="mt-2 text-[13px] leading-[1.8] text-white/60"><?php echo e($president['bio']); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="mt-auto flex items-center justify-center gap-3 pt-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($president['phone'] ?? $president['mobile'] ?? null)): ?>
                                    <a href="tel:<?php echo e($president['phone'] ?? $president['mobile']); ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:border-[#C8A85A] hover:bg-[#C8A85A]" aria-label="اتصال بـ <?php echo e($presidentName); ?>"><i data-lucide="phone" class="h-4 w-4"></i></a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($president['email'] ?? null)): ?>
                                    <a href="mailto:<?php echo e($president['email']); ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:border-[#C8A85A] hover:bg-[#C8A85A]" aria-label="بريد <?php echo e($presidentName); ?>"><i data-lucide="mail" class="h-4 w-4"></i></a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($presidentProfile !== '#'): ?>
                                    <a href="<?php echo e($presidentProfile); ?>" wire:navigate class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:border-[#C8A85A] hover:bg-[#C8A85A]" aria-label="الملف الشخصي لـ <?php echo e($presidentName); ?>"><i data-lucide="user" class="h-4 w-4"></i></a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($regularMembers->isNotEmpty()): ?>
                    <?php $sliderData = $regularMembers->all(); ?>
                    <div class="homepage-council-slider min-w-0 flex-1" x-data="{
                        slides: <?php echo \Illuminate\Support\Js::from($sliderData)->toHtml() ?>,
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
                            const card = this.slider?.querySelector('.homepage-council-slide');
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
                    }" dir="ltr">
                        <div class="relative">
                            <button x-show="canPrev" x-transition.opacity @click="move(Math.max(0, currentPage - 1))" class="homepage-council-prev absolute -left-4 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#E9EFEA] bg-white text-[#176B32] shadow-lg transition hover:bg-[#F0F7F2]" aria-label="السابق">
                                <i data-lucide="chevron-left" class="h-5 w-5"></i>
                            </button>

                            <div x-ref="track" @scroll.throttle.100ms="refresh()" tabindex="0" role="region" aria-label="أعضاء المجلس البلدي" class="homepage-council-track flex items-start gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#176B32]/30">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $regularMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php
                                        $name = $member['full_name'] ?? '';
                                        $image = $photoUrl($member);
                                        $url = $profileUrl($member);
                                    ?>
                                    <div class="homepage-council-slide flex shrink-0 snap-start" dir="rtl">
                                        <article class="homepage-council-card flex w-full flex-col overflow-hidden rounded-[22px] border border-[#E9EFEA] bg-white text-center shadow-[0_6px_20px_rgba(15,23,42,.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_14px_36px_rgba(15,23,42,.1)]">
                                            <div class="flex h-[230px] w-full shrink-0 items-center justify-center overflow-hidden rounded-t-[18px] bg-[#F0F7F2] p-4">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
                                                    <img src="<?php echo e($image); ?>" alt="<?php echo e($name); ?>" class="h-full w-full rounded-[14px] object-cover object-[center_25%]" loading="lazy" decoding="async">
                                                <?php else: ?>
                                                    <span class="text-4xl font-black text-[#176B32]/25" aria-hidden="true"><?php echo e($initials($name)); ?></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="flex flex-1 flex-col px-5 py-4">
                                                <h3 class="text-[17px] font-extrabold leading-snug text-[#17243A]"><?php echo e($name); ?></h3>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($member['position_label'] ?? null)): ?>
                                                    <p class="mt-1.5 text-[13px] font-semibold text-[#176B32]"><?php echo e($member['position_label']); ?></p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($member['committee'] ?? null)): ?>
                                                    <span class="mt-1 text-[12px] leading-relaxed text-[#64748B]"><?php echo e($member['committee']); ?></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($member['bio'] ?? null)): ?>
                                                    <p class="mt-2 text-[12px] leading-[1.8] text-[#64748B]"><?php echo e($member['bio']); ?></p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div class="mt-auto flex items-center justify-center gap-3 pt-4">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($member['phone'] ?? $member['mobile'] ?? null)): ?>
                                                        <a href="tel:<?php echo e($member['phone'] ?? $member['mobile']); ?>" class="flex h-9 w-9 items-center justify-center rounded-full bg-[#EEF8F0] text-[#176B32] transition hover:bg-[#176B32] hover:text-white" aria-label="اتصال بـ <?php echo e($name); ?>"><i data-lucide="phone" class="h-4 w-4"></i></a>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($member['email'] ?? null)): ?>
                                                        <a href="mailto:<?php echo e($member['email']); ?>" class="flex h-9 w-9 items-center justify-center rounded-full bg-[#EEF8F0] text-[#176B32] transition hover:bg-[#176B32] hover:text-white" aria-label="بريد <?php echo e($name); ?>"><i data-lucide="mail" class="h-4 w-4"></i></a>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($url !== '#'): ?>
                                                        <a href="<?php echo e($url); ?>" wire:navigate class="flex h-9 w-9 items-center justify-center rounded-full bg-[#EEF8F0] text-[#176B32] transition hover:bg-[#176B32] hover:text-white" aria-label="الملف الشخصي لـ <?php echo e($name); ?>"><i data-lucide="user" class="h-4 w-4"></i></a>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            <button x-show="canNext" x-transition.opacity @click="move(currentPage + 1)" class="homepage-council-next absolute -right-4 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#E9EFEA] bg-white text-[#176B32] shadow-lg transition hover:bg-[#F0F7F2]" aria-label="التالي">
                                <i data-lucide="chevron-right" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div class="mt-6 flex items-center justify-center gap-2" x-show="pages() > 1">
                            <template x-for="index in pages()" :key="index">
                                <button @click="move(index - 1)" :class="currentPage === index - 1 ? 'h-2 w-7 rounded bg-[#176B32]' : 'h-2 w-2 rounded-full bg-[#DDE5DC]'" class="transition-all" :aria-label="'الانتقال إلى مجموعة الأعضاء ' + index"></button>
                            </template>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-[#DDE5DC] bg-[#F8FAF9] py-16 text-center">
                <i data-lucide="users" class="mx-auto h-10 w-10 text-[#176B32]/30"></i>
                <p class="mt-3 text-sm font-semibold text-[#64748B]">لا يوجد أعضاء مجلس حالياً</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="mt-8 rounded-2xl border border-[#E8EDF2] bg-[#F8FAF9] px-6 py-5" dir="rtl">
            <div class="flex flex-col items-center justify-center gap-5 divide-y divide-[#E2E8F0] sm:flex-row sm:divide-x sm:divide-y-0">
                <div class="flex items-center gap-3 px-5 py-2">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#F0F7F2]"><i data-lucide="users" class="h-5 w-5 text-[#176B32]"></i></div>
                    <div><strong class="block text-2xl font-extrabold text-[#17243A]"><?php echo e($members->count()); ?></strong><span class="text-xs text-[#64748B]">أعضاء المجلس</span></div>
                </div>
                <div class="flex items-center gap-3 px-5 py-2">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#F0F7F2]"><i data-lucide="shield-check" class="h-5 w-5 text-[#176B32]"></i></div>
                    <div><strong class="block text-base font-bold text-[#17243A]">نعمل معاً</strong><span class="text-xs text-[#64748B]">من أجل خدمة المجتمع</span></div>
                </div>
                <div class="flex items-center gap-3 px-5 py-2">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#F0F7F2]"><i data-lucide="building-2" class="h-5 w-5 text-[#176B32]"></i></div>
                    <div><strong class="block text-base font-bold text-[#17243A]"><?php echo e($municipalityName ?? ''); ?></strong><span class="text-xs text-[#64748B]">شفافية · كفاءة · تطوير</span></div>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.council.index')): ?>
            <div class="mt-5 flex justify-center lg:hidden">
                <a href="<?php echo e(route('public.council.index')); ?>" wire:navigate class="inline-flex h-11 items-center gap-2 rounded-xl border border-[#176B32] px-5 text-sm font-bold text-[#176B32] no-underline transition hover:bg-[#176B32] hover:text-white">
                    <i data-lucide="users" class="h-4 w-4"></i> عرض جميع الأعضاء
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <style>
        .homepage-council-track::-webkit-scrollbar { display: none; }
        .homepage-council-track { -ms-overflow-style: none; scrollbar-width: none; }

        .homepage-council-slide {
            flex: 0 0 calc((100% - 48px) / 3);
            min-width: 280px;
        }

        .homepage-council-card {
            height: auto;
            min-height: 420px;
        }

        .homepage-council-president {
            width: 380px;
        }

        @media (max-width: 1279px) {
            .homepage-council-composition { flex-direction: column; align-items: stretch; }
            .homepage-council-president { width: min(100%, 380px); margin-inline: auto; }
            .homepage-council-slider { width: 100%; }
            .homepage-council-slide { flex: 0 0 calc((100% - 24px) / 2); min-width: 260px; }
        }
        @media (max-width: 639px) {
            .homepage-council-slide { flex: 0 0 88%; min-width: 0; }
            .homepage-council-president { width: 100%; }
            .homepage-council-prev { left: -2px !important; }
            .homepage-council-next { right: -2px !important; }
        }
        @media (prefers-reduced-motion: reduce) {
            .homepage-council-track { scroll-behavior: auto; }
            .homepage-council-composition * { transition-duration: .01ms !important; }
        }
    </style>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/council-members.blade.php ENDPATH**/ ?>