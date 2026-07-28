<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'municipality' => null,
    'municipalityName' => '',
    'municipalitySubtitle' => '',
    'logoUrl' => null,
    'contacts' => [],
    'socialPlatforms' => [],
    'portalUrl' => '',
    'sectionKeys' => [],
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
    'municipality' => null,
    'municipalityName' => '',
    'municipalitySubtitle' => '',
    'logoUrl' => null,
    'contacts' => [],
    'socialPlatforms' => [],
    'portalUrl' => '',
    'sectionKeys' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<footer id="footer" style="background:#0B1623;width:100%;max-width:100%;overflow:hidden;" role="contentinfo">
    <div class="container-home py-14 lg:py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center overflow-hidden flex-shrink-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($municipalityName); ?>" class="w-7 h-7 object-contain" loading="lazy">
                        <?php else: ?>
                            <img src="<?php echo e(asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" class="w-7 h-7 object-contain" style="filter:brightness(0) invert(1);">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <p class="font-black text-white text-sm leading-tight"><?php echo e($municipalityName); ?></p>
                        <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.4);"><?php echo e($municipalitySubtitle); ?></p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed mb-5" style="color:rgba(255,255,255,0.5);">
                    <?php echo e(!empty($municipality['short_description']) ? $municipality['short_description'] : ''); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($socialPlatforms)): ?>
                    <div class="flex items-center gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $socialPlatforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $url = $platform['url'] ?? $platform['platform_url'] ?? null; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($url): ?>
                                <a href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                                   style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.4);"
                                   onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.color='white'"
                                   onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.4)'"
                                   aria-label="<?php echo e($platform['name'] ?? 'تواصل اجتماعي'); ?>">
                                    <i data-lucide="<?php echo e($platform['icon'] ?? 'globe'); ?>" class="w-3.5 h-3.5"></i>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div>
                <h4 class="font-bold text-white text-sm mb-4">روابط سريعة</h4>
                <ul class="space-y-2.5">
                    <li><a href="<?php echo e(route('home')); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الرئيسية</a></li>
                    <li><a href="#municipality-intro" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">عن البلدية</a></li>
                    <li><a href="<?php echo e(Route::has('public.services.index') ? route('public.services.index') : '#services'); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الخدمات</a></li>
                    <li><a href="<?php echo e(Route::has('public.council.index') ? route('public.council.index') : '#council-members'); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">المجلس البلدي</a></li>
                    <li><a href="<?php echo e(Route::has('public.departments.index') ? route('public.departments.index') : '#departments'); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الأقسام</a></li>
                    <li><a href="#contact" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">اتصل بنا</a></li>
                </ul>
            </div>

            
            <div>
                <h4 class="font-bold text-white text-sm mb-4">خدمات إلكترونية</h4>
                <ul class="space-y-2.5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <li><a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">بوابة الخدمات</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.services.index')): ?>
                        <li><a href="<?php echo e(route('public.services.index')); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">جميع الخدمات</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.water-schedule')): ?>
                        <li><a href="<?php echo e(route('public.water-schedule')); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">جدول توزيع المياه</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.jobs.index')): ?>
                        <li><a href="<?php echo e(route('public.jobs.index')); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الوظائف</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.facilities.index')): ?>
                        <li><a href="<?php echo e(route('public.facilities.index')); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">المرافق العامة</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.announcements.index')): ?>
                        <li><a href="<?php echo e(route('public.announcements.index')); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الإعلانات</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>

            
            <div>
                <h4 class="font-bold text-white text-sm mb-4">تواصل معنا</h4>
                <ul class="space-y-2.5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="<?php echo e($contact['type'] === 'phone' ? 'phone' : ($contact['type'] === 'email' ? 'mail' : ($contact['type'] === 'mobile' ? 'smartphone' : 'map-pin'))); ?>" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" style="color:#C8A85A;"></i>
                            <div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($contact['label'])): ?>
                                    <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.3);"><?php echo e($contact['label']); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($contact['url'])): ?>
                                    <a href="<?php echo e($contact['url']); ?>" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.6);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'"><?php echo e($contact['value']); ?></a>
                                <?php else: ?>
                                    <span class="text-sm" style="color:rgba(255,255,255,0.6);"><?php echo e($contact['value']); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        </div>

        
        <div class="mt-10 pt-8" style="border-top:1px solid rgba(255,255,255,0.06);">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="font-bold text-white text-sm mb-1">اشترك في النشرة البريدية</h4>
                    <p class="text-xs" style="color:rgba(255,255,255,0.4);">احصل على آخر الأخبار والتحديثات</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <input type="email" placeholder="بريدك الإلكتروني" class="flex-1 sm:w-56 px-4 py-2.5 rounded-xl text-sm" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);color:white;" aria-label="البريد الإلكتروني">
                    <button class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all cursor-pointer" style="background:#C8A85A;color:#0B1623;" onmouseover="this.style.background='#D4B46A'" onmouseout="this.style.background='#C8A85A'">
                        اشترك
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div style="border-top:1px solid rgba(255,255,255,0.05);">
        <div class="container-home py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs" style="color:rgba(255,255,255,0.25);">جميع الحقوق محفوظة &copy; <?php echo e(date('Y')); ?> <?php echo e($municipalityName); ?></p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-xs transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.25);" onmouseover="this.style.color='rgba(255,255,255,0.5)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">سياسة الخصوصية</a>
                    <a href="#" class="text-xs transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.25);" onmouseover="this.style.color='rgba(255,255,255,0.5)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">شروط الاستخدام</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/footer.blade.php ENDPATH**/ ?>