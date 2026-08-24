<?php
    $departments = collect($featuredDepartments)->take(5);
    $highlight = $departments->first();
    $otherDepartments = $departments->slice(1)->take(4);

    $iconMap = [
        'هندسة' => 'DraftingCompass', 'تخطيط' => 'DraftingCompass',
        'خدمات' => 'Building2', 'صحة' => 'Leaf', 'بيئة' => 'Leaf',
        'مالية' => 'WalletCards', 'إداري' => 'WalletCards',
        'اجتماعي' => 'Users', 'شؤون' => 'Users',
        'مياه' => 'Droplets', 'ثقافة' => 'BookOpen',
        'رياضة' => 'Trophy', 'تعليم' => 'GraduationCap',
        'زراعة' => 'Sprout', 'استثمار' => 'TrendingUp',
        'قانوني' => 'Scale', 'كهرباء' => 'Zap',
        'نظافة' => 'Trash2', 'طرق' => 'Route',
    ];

    $resolveIcon = function ($name, $configured) use ($iconMap) {
        if (!empty($configured)) return $configured;
        foreach ($iconMap as $keyword => $icon) {
            if (mb_strpos($name, $keyword) !== false) return $icon;
        }
        return 'Building2';
    };
?>

<section id="departments" class="departments-section" style="background:#FFFFFF;padding-top:clamp(54px,5.8vw,78px);padding-bottom:clamp(52px,5.8vw,78px);">

    <div style="width:100%;max-width:1280px;margin:0 auto;padding:0 clamp(16px,2.5vw,36px);">

        
        
        
        <div class="departments-header">
            <div style="text-align:center;">
                <div style="display:flex;align-items:center;justify-content:center;gap:5px;margin-bottom:10px;">
                    <span style="display:block;width:26px;height:2px;border-radius:9999px;background:#176B32;"></span>
                    <span style="display:block;width:4px;height:4px;border-radius:50%;background:#176B32;"></span>
                    <span style="display:block;width:26px;height:2px;border-radius:9999px;background:#176B32;"></span>
                </div>
                <h2 style="text-align:center;color:#17243A;font-size:clamp(23px,2.8vw,32px);font-weight:700;line-height:1.3;margin:0;">
                    <?php echo e($sectionTitle ?? 'أقسام البلدية'); ?>

                </h2>
                <p style="text-align:center;max-width:640px;margin:8px auto 0;font-size:13px;line-height:1.8;color:#66756D;">
                    <?php echo e($sectionSubtitle ?? 'نضع بين يديك أهم أقسام البلدية والخدمات التي يقدمها كل قسم للمواطنين.'); ?>

                </p>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.departments.index')): ?>
                <div class="departments-header-action">
                    <a href="<?php echo e(route('public.departments.index')); ?>" wire:navigate
                       class="departments-view-all-btn"
                       style="display:inline-flex;align-items:center;gap:6px;height:42px;padding:0 20px;border-radius:9px;background:#176B32;color:white;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(23,107,50,0.2);transition:background 200ms,box-shadow 200ms;">
                        <span>عرض جميع الأقسام</span>
                        <i data-lucide="arrow-left" style="width:15px;height:15px;transition:transform 200ms;"></i>
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($departments->isNotEmpty()): ?>
            <div class="departments-grid" style="display:grid;grid-template-columns:1.25fr 1fr 1fr 1fr 1fr;gap:16px;margin-top:44px;min-width:0;">

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($highlight): ?>
                    <?php
                        $fgUrl = !empty($highlight['slug']) && Route::has('public.departments.show')
                            ? route('public.departments.show', ['department' => $highlight['slug']])
                            : '#';
                        $fgIcon = $resolveIcon($highlight['name'] ?? '', $highlight['icon'] ?? '');
                    ?>
                    <article class="dept-featured" style="display:flex;flex-direction:column;border-radius:16px;overflow:hidden;position:relative;background:linear-gradient(145deg,#1C7736,#145D2B);box-shadow:0 14px 32px rgba(18,75,36,0.18);transition:all 240ms ease-out;min-width:0;">
                        <div style="position:absolute;inset:0;background:radial-gradient(circle at top left,rgba(255,255,255,0.12),transparent 55%);pointer-events:none;"></div>
                        <div style="position:relative;z-index:1;display:flex;flex-direction:column;height:100%;padding:24px;">
                            
                            <span style="display:inline-flex;align-items:center;gap:1px;height:26px;padding:0 11px;border-radius:9999px;background:rgba(255,255,255,0.15);color:white;font-size:10px;font-weight:600;align-self:flex-start;backdrop-filter:blur(4px);">
                                <i data-lucide="star" style="width:11px;height:11px;"></i>
                                <span>القسم المميز</span>
                            </span>

                            
                            <div style="margin-top:16px;">
                                <i data-lucide="<?php echo e($fgIcon); ?>" style="width:32px;height:32px;stroke-width:1.6;color:white;"></i>
                            </div>

                            
                            <h3 style="margin:12px 0 0;font-size:18px;font-weight:700;color:white;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                <?php echo e($highlight['name'] ?? ''); ?>

                            </h3>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($highlight['short_description'])): ?>
                                <p style="margin:8px 0 0;font-size:12px;line-height:1.75;color:rgba(255,255,255,0.85);display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                    <?php echo e($highlight['short_description']); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <div style="flex:1;min-height:8px;"></div>

                            
                            <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px;">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($highlight['manager_name'])): ?>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <span style="font-size:10px;font-weight:500;color:rgba(255,255,255,0.6);">مدير القسم</span>
                                        <span style="font-size:12px;font-weight:600;color:white;"><?php echo e($highlight['manager_name']); ?></span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($highlight['services_count'] ?? 0) > 0): ?>
                                    <div style="display:flex;align-items:center;gap:5px;">
                                        <i data-lucide="list-checks" style="width:13px;height:13px;color:rgba(255,255,255,0.7);"></i>
                                        <span style="font-size:12px;font-weight:600;color:white;"><?php echo e($highlight['services_count']); ?> خدمة</span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <a href="<?php echo e($fgUrl); ?>" <?php if($fgUrl !== '#'): ?> wire:navigate <?php endif; ?>
                               class="dept-featured-action"
                               style="display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:0;color:rgba(255,255,255,0.9);font-size:12px;font-weight:600;text-decoration:none;transition:color 200ms;align-self:flex-start;">
                                <span>استكشف القسم</span>
                                <i data-lucide="chevron-left" style="width:14px;height:14px;transition:transform 200ms;"></i>
                            </a>
                        </div>
                    </article>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $otherDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $regUrl = !empty($department['slug']) && Route::has('public.departments.show')
                            ? route('public.departments.show', ['department' => $department['slug']])
                            : '#';
                        $regIcon = $resolveIcon($department['name'] ?? '', $department['icon'] ?? '');
                    ?>
                    <a href="<?php echo e($regUrl); ?>" <?php if($regUrl !== '#'): ?> wire:navigate <?php endif; ?>
                       class="dept-regular"
                       style="display:flex;flex-direction:column;border-radius:14px;border:1px solid #E4E9E5;background:white;padding:20px;text-decoration:none;box-shadow:0 5px 18px rgba(20,50,30,0.05);transition:all 240ms ease-out;min-width:0;">
                        
                        <div style="width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:#EAF5EE;">
                            <i data-lucide="<?php echo e($regIcon); ?>" style="width:30px;height:30px;stroke-width:1.7;color:#176B32;transition:transform 240ms;"></i>
                        </div>

                        
                        <h4 style="margin:14px 0 0;font-size:15px;font-weight:700;color:#17243A;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?php echo e($department['name'] ?? ''); ?>

                        </h4>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($department['short_description'])): ?>
                            <p style="margin:8px 0 0;font-size:12px;line-height:1.7;color:#66756D;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                <?php echo e($department['short_description']); ?>

                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div style="flex:1;min-height:8px;"></div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($department['manager_name'])): ?>
                            <div style="margin-top:12px;display:flex;align-items:center;gap:6px;">
                                <span style="font-size:10px;font-weight:500;color:#94A3B8;">مدير القسم</span>
                                <span style="font-size:12px;font-weight:600;color:#17243A;"><?php echo e($department['manager_name']); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($department['services_count'] ?? 0) > 0): ?>
                            <div style="margin-top:8px;display:flex;align-items:center;gap:5px;">
                                <i data-lucide="list-checks" style="width:12px;height:12px;color:#7BBC9D;"></i>
                                <span style="font-size:11px;font-weight:600;color:#7BBC9D;"><?php echo e($department['services_count']); ?> خدمة</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            </div>
        <?php else: ?>
            
            <div style="display:flex;align-items:center;justify-content:center;min-height:180px;margin-top:44px;">
                <div style="text-align:center;">
                    <i data-lucide="building-2" style="width:36px;height:36px;color:#A0CFB8;margin-bottom:10px;"></i>
                    <p style="font-size:15px;font-weight:600;color:#66756D;margin:0;">لا توجد أقسام متاحة حالياً</p>
                    <p style="font-size:12px;color:#94A3B8;margin:6px 0 0;">سيتم إضافة أقسام البلدية قريباً</p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if (! $__env->hasRenderedOnce('d6267d8f-c14d-4825-bbc9-5bd455940327')): $__env->markAsRenderedOnce('d6267d8f-c14d-4825-bbc9-5bd455940327'); ?>
        <?php $__env->startPush('styles'); ?>
            <style>
                /* View-all hover */
                .departments-view-all-btn:hover { background:#0F4F28 !important; box-shadow:0 6px 20px rgba(23,107,50,0.3) !important; }
                .departments-view-all-btn:hover i { transform:translateX(-2px); }

                /* Desktop: absolute left for view-all */
                @media (min-width:1025px) {
                    .departments-header { position:relative; }
                    .departments-header-action { position:absolute; left:0; top:50%; transform:translateY(-50%); }
                }

                /* Tablet: stack header, featured full-width, regulars 2 columns */
                @media (max-width:1024px) {
                    .departments-header-action { text-align:center; margin-top:20px; }
                    .departments-grid { grid-template-columns:repeat(2,1fr) !important; margin-top:36px !important; }
                    .dept-featured { grid-column:1 / -1 !important; }
                }

                /* Mobile: 1-column grid, smaller spacing */
                @media (max-width:640px) {
                    .departments-grid { grid-template-columns:1fr !important; gap:14px !important; margin-top:32px !important; }
                }

                /* Featured card hover */
                .dept-featured:hover { transform:translateY(-3px); box-shadow:0 18px 40px rgba(18,75,36,0.25) !important; }
                .dept-featured:hover .dept-featured-action i { transform:translateX(-3px); }
                .dept-featured:hover .dept-featured-action { color:white !important; }

                /* Regular card hover */
                .dept-regular:hover { transform:translateY(-3px); border-color:rgba(23,107,50,0.25) !important; box-shadow:0 10px 28px rgba(20,50,30,0.1) !important; }
                .dept-regular:hover i { transform:translateY(-1px); }

                /* Focus */
                .dept-featured a:focus-visible,
                .dept-regular:focus-visible,
                .departments-view-all-btn:focus-visible { outline:2px solid #176B32; outline-offset:2px; border-radius:8px; }

                /* Reduced motion */
                @media (prefers-reduced-motion:reduce) {
                    .dept-featured,.dept-regular,.dept-featured *,.dept-regular *,.departments-view-all-btn { transition-duration:0.01ms !important; transform:none !important; }
                }
            </style>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
</section>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/departments.blade.php ENDPATH**/ ?>