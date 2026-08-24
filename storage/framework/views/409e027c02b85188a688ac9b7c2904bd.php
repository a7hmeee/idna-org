<div style="min-height:100vh;background:white;width:100%;max-width:100%;">

    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'about',
        'fallbackTitle' => $municipality->name_ar ?? 'بلدية إذنا',
        'fallbackDescription' => $municipality->short_description ?? null,
        'fallbackBadge' => 'نبذة عن البلدية',
        'fallbackIcon' => 'landmark',
        'fallbackImage' => (collect($images)->first()['path'] ?? null) ? asset('storage/' . collect($images)->first()['path']) : null,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2430665489-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipality && ($municipality->foundation_date || $municipality->population || $municipality->area)): ?>
        <section style="background:#F8FAF9;border-bottom:1px solid #E8EDEA;">
            <div style="width:100%;max-width:1280px;margin:0 auto;padding:24px clamp(16px,2.5vw,36px);">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipality->foundation_date): ?>
                        <div class="text-center p-4 rounded-xl bg-white border border-[#E1E8E2] shadow-sm">
                            <i data-lucide="calendar-days" class="w-6 h-6 text-primary mb-2 inline-block" stroke-width="1.6"></i>
                            <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e($municipality->foundation_date->format('Y')); ?></p>
                            <p class="text-[11px] text-[#66756D] mt-1.5">سنة التأسيس</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipality->population): ?>
                        <div class="text-center p-4 rounded-xl bg-white border border-[#E1E8E2] shadow-sm">
                            <i data-lucide="users" class="w-6 h-6 text-primary mb-2 inline-block" stroke-width="1.6"></i>
                            <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e(number_format($municipality->population)); ?></p>
                            <p class="text-[11px] text-[#66756D] mt-1.5">عدد السكان</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipality->area): ?>
                        <div class="text-center p-4 rounded-xl bg-white border border-[#E1E8E2] shadow-sm">
                            <i data-lucide="map-pin" class="w-6 h-6 text-primary mb-2 inline-block" stroke-width="1.6"></i>
                            <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e(number_format($municipality->area, 2)); ?></p>
                            <p class="text-[11px] text-[#66756D] mt-1.5">المساحة (كم²)</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipality->municipality_code): ?>
                        <div class="text-center p-4 rounded-xl bg-white border border-[#E1E8E2] shadow-sm">
                            <i data-lucide="hash" class="w-6 h-6 text-primary mb-2 inline-block" stroke-width="1.6"></i>
                            <p class="text-[26px] font-extrabold text-[#13251C] leading-none"><?php echo e($municipality->municipality_code); ?></p>
                            <p class="text-[11px] text-[#66756D] mt-1.5">رمز البلدية</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <section class="bg-white">
        <div style="width:100%;max-width:1280px;margin:0 auto;padding:60px clamp(16px,2.5vw,36px);">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality->full_description)): ?>
                <div class="mb-12">
                    <h2 class="text-[28px] font-extrabold text-[#13251C] mb-4 text-right">عن البلدية</h2>
                    <div class="prose prose-lg max-w-none text-right text-[#66756D] leading-[1.9] text-[14px]">
                        <?php echo e($municipality->full_description); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality->vision)): ?>
                    <div class="rounded-2xl border border-[#DCE8DE] bg-white p-6 shadow-[0_4px_20px_rgba(20,55,30,0.04)]">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                <i data-lucide="eye" class="w-[22px] h-[22px]" stroke-width="1.7"></i>
                            </div>
                            <h3 class="text-[18px] font-bold text-[#13251C]">رؤيتنا</h3>
                        </div>
                        <p class="text-[14px] text-[#66756D] leading-[1.85]"><?php echo e($municipality->vision); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality->mission)): ?>
                    <div class="rounded-2xl border border-[#DCE8DE] bg-white p-6 shadow-[0_4px_20px_rgba(20,55,30,0.04)]">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-[#EAF5EE] flex items-center justify-center text-primary shrink-0">
                                <i data-lucide="crosshair" class="w-[22px] h-[22px]" stroke-width="1.7"></i>
                            </div>
                            <h3 class="text-[18px] font-bold text-[#13251C]">رسالتنا</h3>
                        </div>
                        <p class="text-[14px] text-[#66756D] leading-[1.85]"><?php echo e($municipality->mission); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($municipality->objectives) && is_array($municipality->objectives) && count($municipality->objectives) > 0): ?>
                <div class="mb-12">
                    <h2 class="text-[28px] font-extrabold text-[#13251C] mb-6 text-right">أهدافنا</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $municipality->objectives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $objective): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex items-start gap-3 p-4 rounded-xl bg-[#F8FAF9] border border-[#E8EDEA]">
                                <span class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5"><?php echo e($index + 1); ?></span>
                                <p class="text-[14px] text-[#4A5A52] leading-[1.8]"><?php echo e($objective); ?></p>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($images) > 0): ?>
                <div class="mb-12">
                    <h2 class="text-[28px] font-extrabold text-[#13251C] mb-6 text-right">معرض الصور</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $imgUrl = !empty($img['path']) ? asset('storage/' . $img['path']) : ($img['url'] ?? null); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($imgUrl): ?>
                                <div class="rounded-2xl overflow-hidden shadow-[0_4px_16px_rgba(20,55,30,0.06)] h-[220px]">
                                    <img src="<?php echo e($imgUrl); ?>" alt="<?php echo e($img['alt'] ?? ''); ?>" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($contacts) > 0 || count($socialPlatforms) > 0): ?>
                <div class="rounded-2xl border border-[#DCE8DE] bg-[#F8FAF9] p-6 md:p-8">
                    <h2 class="text-[22px] font-extrabold text-[#13251C] mb-6 text-right">معلومات الاتصال</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($contacts) > 0): ?>
                            <div>
                                <h3 class="text-[14px] font-bold text-[#13251C] mb-3">بيانات الاتصال</h3>
                                <div class="space-y-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-[#E1E8E2] flex items-center justify-center text-primary shrink-0">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($contact['type'] ?? '') === 'phone'): ?>
                                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                                <?php elseif(($contact['type'] ?? '') === 'email'): ?>
                                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                                <?php elseif(($contact['type'] ?? '') === 'fax'): ?>
                                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                                <?php else: ?>
                                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="text-[12px] text-[#66756D]"><?php echo e($contact['label'] ?? $contact['type']); ?></p>
                                                <p class="text-[13px] font-semibold text-[#13251C]"><?php echo e($contact['value']); ?></p>
                                            </div>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($socialPlatforms) > 0): ?>
                            <div>
                                <h3 class="text-[14px] font-bold text-[#13251C] mb-3">وسائل التواصل الاجتماعي</h3>
                                <div class="flex flex-wrap gap-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $socialPlatforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <a href="<?php echo e($platform['url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-[#E1E8E2] text-[#13251C] text-sm font-semibold no-underline hover:border-primary hover:text-primary transition-all shadow-sm">
                                            <i data-lucide="<?php echo e($platform['icon'] ?? 'external-link'); ?>" class="w-4 h-4"></i>
                                            <span><?php echo e($platform['name'] ?? 'منصة'); ?></span>
                                        </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>
    </section>

</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/municipality/public-municipality-about.blade.php ENDPATH**/ ?>