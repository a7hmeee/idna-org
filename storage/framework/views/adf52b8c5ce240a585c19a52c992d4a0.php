<?php
    $ctaTitle = $settings['contact_cta_title'] ?? 'تواصل معنا';
    $ctaDesc = $settings['contact_cta_description'] ?? 'نحن هنا لمساعدتك. يمكنكم التواصل معنا عبر البوابة الإلكترونية أو من خلال قنوات الاتصال التالية';
    $ctaBtnText = $settings['contact_cta_button_text'] ?? $primaryBtn ?? 'تواصل معنا';
    $ctaBtnUrl = $settings['contact_cta_button_url'] ?? $portalUrl ?? '#';
    $contactPhone = collect($contacts)->firstWhere('type', 'phone');
    $contactEmail = collect($contacts)->firstWhere('type', 'email');
    $contactAddress = collect($contacts)->firstWhere('type', 'address');
    $workingHours = collect($businessHours)->first();
?>

<section id="contact" class="relative overflow-hidden" style="background:#176B32;">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl" style="background:rgba(255,255,255,0.04);" aria-hidden="true"></div>
    </div>

    <div class="relative container-home section-py">
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold mb-3" style="background:rgba(255,255,255,0.1);color:#A5D6A7;">
                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                تواصل معنا
            </span>
            <h2 class="text-3xl lg:text-[34px] font-black text-white"><?php echo e($ctaTitle); ?></h2>
        </div>

        <div class="grid lg:grid-cols-[1.3fr_1fr] gap-10 lg:gap-16 items-center">
            <div>
                <p class="text-sm sm:text-base leading-relaxed mb-8" style="color:rgba(255,255,255,0.7);"><?php echo e($ctaDesc); ?></p>

                <div class="grid sm:grid-cols-3 gap-3 mb-8">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactPhone): ?>
                        <a href="tel:<?php echo e($contactPhone['value']); ?>" class="flex flex-col items-center text-center p-4 rounded-xl transition-all hover:bg-white/10 no-underline" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);min-height:110px;">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2" style="background:rgba(200,168,90,0.15);">
                                <i data-lucide="phone" class="w-4 h-4" style="color:#C8A85A;"></i>
                            </div>
                            <p class="text-[10px] font-medium mb-0.5" style="color:rgba(255,255,255,0.4);">الهاتف</p>
                            <p class="text-sm font-bold text-white"><?php echo e($contactPhone['value']); ?></p>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactEmail): ?>
                        <a href="mailto:<?php echo e($contactEmail['value']); ?>" class="flex flex-col items-center text-center p-4 rounded-xl transition-all hover:bg-white/10 no-underline" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);min-height:110px;">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2" style="background:rgba(200,168,90,0.15);">
                                <i data-lucide="mail" class="w-4 h-4" style="color:#C8A85A;"></i>
                            </div>
                            <p class="text-[10px] font-medium mb-0.5" style="color:rgba(255,255,255,0.4);">البريد الإلكتروني</p>
                            <p class="text-sm font-bold text-white"><?php echo e($contactEmail['value']); ?></p>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactAddress): ?>
                        <div class="flex flex-col items-center text-center p-4 rounded-xl" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);min-height:110px;">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2" style="background:rgba(200,168,90,0.15);">
                                <i data-lucide="map-pin" class="w-4 h-4" style="color:#C8A85A;"></i>
                            </div>
                            <p class="text-[10px] font-medium mb-0.5" style="color:rgba(255,255,255,0.4);">العنوان</p>
                            <p class="text-sm font-bold text-white"><?php echo e($contactAddress['value']); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($workingHours && !$contactAddress): ?>
                        <div class="flex flex-col items-center text-center p-4 rounded-xl" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);min-height:110px;">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2" style="background:rgba(200,168,90,0.15);">
                                <i data-lucide="clock" class="w-4 h-4" style="color:#C8A85A;"></i>
                            </div>
                            <p class="text-[10px] font-medium mb-0.5" style="color:rgba(255,255,255,0.4);">ساعات الدوام</p>
                            <p class="text-sm font-bold text-white"><?php echo e($workingHours['opening_time'] ?? ''); ?> - <?php echo e($workingHours['closing_time'] ?? ''); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="<?php echo e($ctaBtnUrl); ?>" <?php if(filter_var($ctaBtnUrl, FILTER_VALIDATE_URL) && !str_contains($ctaBtnUrl, request()->getHost())): ?> target="_blank" rel="noopener noreferrer" <?php else: ?> wire:navigate <?php endif; ?>
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-primary text-sm font-bold hover:bg-primary-light transition-all shadow-lg">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span><?php echo e($ctaBtnText); ?></span>
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all border" style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span>بوابة الخدمات الإلكترونية</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="rounded-2xl overflow-hidden shadow-lg relative" style="height:240px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality['images'][0]['url'])): ?>
                        <img src="<?php echo e($municipality['images'][0]['url']); ?>" alt="بلدية إذنا" class="w-full h-full object-cover" loading="lazy">
                        <div class="absolute inset-0" style="background:linear-gradient(135deg, rgba(23,107,50,0.25), rgba(23,107,50,0.05));"></div>
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center" style="background:rgba(255,255,255,0.06);">
                            <i data-lucide="building-2" class="w-16 h-16" style="color:rgba(255,255,255,0.15);"></i>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="absolute bottom-3 right-3 left-3">
                        <div class="rounded-xl p-3" style="background:rgba(0,0,0,0.35);backdrop-filter:blur(8px);">
                            <p class="text-white text-xs font-bold">بلدية إذنا</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactAddress): ?>
                                <p class="text-white/60 text-[10px]"><?php echo e($contactAddress['value']); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/contact-cta.blade.php ENDPATH**/ ?>