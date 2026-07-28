<?php
    $foundationYear = !empty($municipality['foundation_date']) ? $formatDate($municipality['foundation_date'], 'Y') : null;
    $population = !empty($municipality['population']) ? number_format($municipality['population']) : null;
    $hasVision = !empty($municipality['vision']);
    $hasMission = !empty($municipality['mission']);
    $values = is_array($municipality['values']) ? $municipality['values'] : [];

    $mainImgUrl = null;
    $mainImgAlt = $municipalityName;
    $isPng = false;
    foreach (($municipality['images'] ?? []) as $img) {
        $url = $img['url'] ?? '';
        if (empty($url)) continue;
        $mainImgUrl = $url;
        $mainImgAlt = !empty($img['alt']) ? $img['alt'] : $municipalityName;
        if (preg_match('/\.jpe?g$/i', $url)) {
            $isPng = false;
            break;
        }
        $isPng = preg_match('/\.png$/i', $url);
    }
?>

<section id="municipality-intro" class="bg-white">
    <div class="py-[48px] md:py-[60px] lg:py-[80px] container-home">

        
        
        
        <div class="hidden lg:grid lg:grid-cols-[minmax(150px,15%)_minmax(0,43%)_minmax(0,42%)] lg:gap-9">

            
            <div class="flex flex-col gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationYear): ?>
                    <div class="rounded-xl border border-[#E1E8E2] bg-white p-5 shadow-[0_4px_16px_rgba(20,55,30,0.04)] min-h-[135px] flex flex-col justify-center">
                        <i data-lucide="calendar-days" class="w-6 h-6 text-primary mb-3 block" stroke-width="1.6"></i>
                        <p class="text-[28px] font-extrabold text-[#13251C] leading-none"><?php echo e($foundationYear); ?></p>
                        <p class="text-[11px] text-[#66756D] mt-2.5">سنة التأسيس</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($population): ?>
                    <div class="rounded-xl border border-[#E1E8E2] bg-white p-5 shadow-[0_4px_16px_rgba(20,55,30,0.04)] min-h-[135px] flex flex-col justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-primary mb-3 block" stroke-width="1.6"></i>
                        <p class="text-[28px] font-extrabold text-[#13251C] leading-none"><?php echo e($population); ?></p>
                        <p class="text-[11px] text-[#66756D] mt-2.5">عدد السكان</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="text-right">
                <h2 class="text-[32px] lg:text-[36px] font-extrabold text-[#13251C] leading-[1.25] mb-3">
                    <?php echo e($municipalityName); ?>

                </h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality['short_description'])): ?>
                    <p class="text-[14px] leading-[1.85] text-[#66756D] line-clamp-3 mb-5">
                        <?php echo e($municipality['short_description']); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVision || $hasMission): ?>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVision): ?>
                            <div class="rounded-xl border border-[#DCE8DE] bg-white p-4 min-h-[120px] shadow-[0_4px_14px_rgba(20,55,30,0.035)]">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-7 h-7 rounded-lg bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                        <i data-lucide="eye" class="w-[19px] h-[19px]" stroke-width="1.7"></i>
                                    </div>
                                    <p class="text-[14px] font-bold text-[#13251C]">رؤيتنا</p>
                                </div>
                                <p class="text-[12px] text-[#66756D] leading-[1.75] line-clamp-3"><?php echo e($municipality['vision']); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasMission): ?>
                            <div class="rounded-xl border border-[#DCE8DE] bg-white p-4 min-h-[120px] shadow-[0_4px_14px_rgba(20,55,30,0.035)]">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-7 h-7 rounded-lg bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                        <i data-lucide="crosshair" class="w-[19px] h-[19px]" stroke-width="1.7"></i>
                                    </div>
                                    <p class="text-[14px] font-bold text-[#13251C]">رسالتنا</p>
                                </div>
                                <p class="text-[12px] text-[#66756D] leading-[1.75] line-clamp-3"><?php echo e($municipality['mission']); ?></p>
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

            
            <div class="rounded-[20px] overflow-hidden shadow-[0_12px_30px_rgba(25,60,35,0.10)] <?php echo e($isPng ? 'bg-[#EAF5EE]' : 'bg-[#F4F5F1]'); ?> h-[320px]">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mainImgUrl): ?>
                    <img src="<?php echo e($mainImgUrl); ?>"
                         alt="<?php echo e($mainImgAlt); ?>"
                         class="w-full h-full <?php echo e($isPng ? 'object-contain p-10' : 'object-cover'); ?> object-center"
                         loading="lazy" decoding="async"
                         width="520" height="320">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-[#EAF5EE]">
                        <i data-lucide="building-2" class="w-16 h-16 text-primary/30"></i>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

        
        
        
        <div class="lg:hidden">
            
            <div class="md:grid md:grid-cols-[45%_55%] md:gap-8 md:items-start">
                
                <div class="hidden md:block rounded-[20px] overflow-hidden shadow-[0_12px_30px_rgba(25,60,35,0.10)] <?php echo e($isPng ? 'bg-[#EAF5EE]' : 'bg-[#F4F5F1]'); ?> h-[280px]">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mainImgUrl): ?>
                        <img src="<?php echo e($mainImgUrl); ?>"
                             alt="<?php echo e($mainImgAlt); ?>"
                             class="w-full h-full <?php echo e($isPng ? 'object-contain p-8' : 'object-cover'); ?> object-center"
                             loading="lazy" decoding="async"
                             width="400" height="280">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-[#EAF5EE]">
                            <i data-lucide="building-2" class="w-14 h-14 text-primary/30"></i>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <h2 class="text-[28px] md:text-[30px] font-extrabold text-[#13251C] leading-[1.25] text-right mb-3">
                        <?php echo e($municipalityName); ?>

                    </h2>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality['short_description'])): ?>
                        <p class="text-[14px] leading-[1.85] text-[#66756D] text-right line-clamp-3">
                            <?php echo e($municipality['short_description']); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="md:hidden mt-6 rounded-[20px] overflow-hidden shadow-[0_12px_30px_rgba(25,60,35,0.10)] <?php echo e($isPng ? 'bg-[#EAF5EE]' : 'bg-[#F4F5F1]'); ?> h-[250px]">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mainImgUrl): ?>
                            <img src="<?php echo e($mainImgUrl); ?>"
                                 alt="<?php echo e($mainImgAlt); ?>"
                                 class="w-full h-full <?php echo e($isPng ? 'object-contain p-8' : 'object-cover'); ?> object-center"
                                 loading="lazy" decoding="async"
                                 width="400" height="250">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-[#EAF5EE]">
                                <i data-lucide="building-2" class="w-14 h-14 text-primary/30"></i>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationYear || $population): ?>
                <div class="flex flex-wrap gap-4 mt-6 justify-end">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationYear): ?>
                        <div class="w-[155px] rounded-xl border border-[#E1E8E2] bg-white p-3.5">
                            <i data-lucide="calendar-days" class="w-5 h-5 text-primary mb-2 block" stroke-width="1.6"></i>
                            <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e($foundationYear); ?></p>
                            <p class="text-[11px] text-[#66756D] mt-1.5">سنة التأسيس</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($population): ?>
                        <div class="w-[155px] rounded-xl border border-[#E1E8E2] bg-white p-3.5">
                            <i data-lucide="users" class="w-5 h-5 text-primary mb-2 block" stroke-width="1.6"></i>
                            <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e($population); ?></p>
                            <p class="text-[11px] text-[#66756D] mt-1.5">عدد السكان</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVision || $hasMission): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVision): ?>
                        <div class="rounded-xl border border-[#DCE8DE] bg-white p-4 shadow-[0_4px_14px_rgba(20,55,30,0.035)]">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                    <i data-lucide="eye" class="w-[19px] h-[19px]" stroke-width="1.7"></i>
                                </div>
                                <p class="text-[14px] font-bold text-[#13251C]">رؤيتنا</p>
                            </div>
                            <p class="text-[12px] text-[#66756D] leading-[1.75] line-clamp-3"><?php echo e($municipality['vision']); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasMission): ?>
                        <div class="rounded-xl border border-[#DCE8DE] bg-white p-4 shadow-[0_4px_14px_rgba(20,55,30,0.035)]">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                    <i data-lucide="crosshair" class="w-[19px] h-[19px]" stroke-width="1.7"></i>
                                </div>
                                <p class="text-[14px] font-bold text-[#13251C]">رسالتنا</p>
                            </div>
                            <p class="text-[12px] text-[#66756D] leading-[1.75] line-clamp-3"><?php echo e($municipality['mission']); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($values)): ?>
                <div class="flex flex-wrap gap-1.5 mt-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <span class="inline-flex items-center h-7 px-2.5 rounded-full font-semibold text-[11px] <?php echo e($i === 0 ? 'bg-[#F5E6B8] text-[#7A6218]' : 'bg-[#EAF5EE] text-primary'); ?>"><?php echo e($value); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/municipality-story.blade.php ENDPATH**/ ?>