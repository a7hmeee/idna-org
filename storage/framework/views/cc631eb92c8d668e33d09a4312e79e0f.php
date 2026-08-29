<?php
    $foundationYear = !empty($municipality['foundation_date']) ? $formatDate($municipality['foundation_date'], 'Y') : null;
    $population = !empty($municipality['population']) ? number_format($municipality['population']) : null;
    $hasVision = !empty($municipality['vision']);
    $hasMission = !empty($municipality['mission']);
    $values = is_array($municipality['values']) ? $municipality['values'] : [];

    $mainImgUrl = $municipality['about_image_url'] ?? null;
    $mainImgAlt = !empty($municipality['about_image_alt']) ? $municipality['about_image_alt'] : $municipalityName;

    if (empty($mainImgUrl)) {
        foreach (($municipality['images'] ?? []) as $img) {
            if (empty($img['url'])) continue;
            $mainImgUrl = $img['url'];
            $mainImgAlt = !empty($img['alt']) ? $img['alt'] : $municipalityName;
            break;
        }
    }

    $isPng = $mainImgUrl && preg_match('/\.png$/i', $mainImgUrl);
?>

<section id="municipality-intro" class="bg-white">
    <div class="py-[48px] md:py-[60px] lg:py-[80px] container-home">

        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,46%)_minmax(0,54%)] lg:gap-12 lg:items-center">

            
            <div class="relative">
                <div class="rounded-[22px] overflow-hidden shadow-[0_16px_40px_rgba(25,60,35,0.12)] <?php echo e($isPng ? 'bg-[#EAF5EE]' : 'bg-[#F4F5F1]'); ?> h-[260px] sm:h-[320px] lg:h-[420px]">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mainImgUrl): ?>
                        <img src="<?php echo e($mainImgUrl); ?>"
                             alt="<?php echo e($mainImgAlt); ?>"
                             class="w-full h-full <?php echo e($isPng ? 'object-contain p-10' : 'object-cover'); ?> object-center"
                             loading="lazy" decoding="async"
                             width="560" height="420">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#EAF5EE] to-[#F4F5F1]">
                            <i data-lucide="building-2" class="w-20 h-20 text-primary/25"></i>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationYear || $population): ?>
                    <div class="absolute -bottom-6 left-4 sm:left-8 flex gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationYear): ?>
                            <div class="bg-white rounded-2xl border border-[#E1E8E2] shadow-[0_8px_24px_rgba(25,60,35,0.10)] px-5 py-4 min-w-[120px]">
                                <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e($foundationYear); ?></p>
                                <p class="text-[11px] text-[#66756D] mt-2">سنة التأسيس</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($population): ?>
                            <div class="bg-white rounded-2xl border border-[#E1E8E2] shadow-[0_8px_24px_rgba(25,60,35,0.10)] px-5 py-4 min-w-[120px]">
                                <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e($population); ?></p>
                                <p class="text-[11px] text-[#66756D] mt-2">نسمة</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="text-right mt-12 lg:mt-0">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF5EE] text-primary text-[12px] font-bold mb-4">
                    <i data-lucide="landmark" class="w-3.5 h-3.5" stroke-width="1.8"></i>
                    نبذة عن البلدية
                </span>

                <h2 class="text-[30px] md:text-[36px] font-extrabold text-[#13251C] leading-[1.25] mb-5">
                    <?php echo e($sectionTitle ?: $municipalityName); ?>

                </h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality['short_description'])): ?>
                    <p class="text-[16px] leading-[1.9] text-[#66756D] mb-7 line-clamp-3">
                        <?php echo e($municipality['short_description']); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVision || $hasMission): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVision): ?>
                            <div class="rounded-2xl border border-[#DCE8DE] bg-gradient-to-br from-white to-[#F8FBF8] p-5 shadow-[0_4px_14px_rgba(20,55,30,0.035)]">
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                        <i data-lucide="eye" class="w-5 h-5" stroke-width="1.7"></i>
                                    </div>
                                    <p class="text-[16px] font-bold text-[#13251C]">رؤيتنا</p>
                                </div>
                                <p class="text-[14px] text-[#66756D] leading-[1.8] line-clamp-3"><?php echo e($municipality['vision']); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasMission): ?>
                            <div class="rounded-2xl border border-[#DCE8DE] bg-gradient-to-br from-white to-[#F8FBF8] p-5 shadow-[0_4px_14px_rgba(20,55,30,0.035)]">
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                        <i data-lucide="crosshair" class="w-5 h-5" stroke-width="1.7"></i>
                                    </div>
                                    <p class="text-[16px] font-bold text-[#13251C]">رسالتنا</p>
                                </div>
                                <p class="text-[14px] text-[#66756D] leading-[1.8] line-clamp-3"><?php echo e($municipality['mission']); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($values)): ?>
                    <div class="flex flex-wrap gap-1.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <span class="inline-flex items-center h-7 px-2.5 rounded-full font-semibold text-[11px] <?php echo e($i === 0 ? 'bg-[#F5E6B8] text-[#7A6218]' : 'bg-[#EAF5EE] text-primary'); ?>"><?php echo e($value); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/municipality-story.blade.php ENDPATH**/ ?>