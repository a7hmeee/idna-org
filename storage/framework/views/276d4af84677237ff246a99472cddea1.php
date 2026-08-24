<?php
    $services = collect($featuredServices)->take(6);
?>

<section id="services" class="services-section" style="background:#FFFFFF;padding-top:clamp(16px,3vw,66px);padding-bottom:clamp(24px,4vw,78px);overflow:hidden;position:relative;">
    
    <div class="services-pattern" style="position:absolute;top:0;bottom:0;right:0;pointer-events:none;overflow:hidden;width:clamp(120px,15vw,200px);" aria-hidden="true">
        <svg style="position:absolute;top:50%;right:-10px;transform:translateY(-50%);width:100%;height:auto;opacity:0.035;" viewBox="0 0 160 280" fill="none" preserveAspectRatio="xMidYMid meet">
            <path d="M80 10C80 10 130 65 130 120C130 175 80 230 80 230C80 230 30 175 30 120C30 65 80 10 80 10Z" stroke="#176B32" stroke-width="2.5"/>
            <path d="M80 55C80 55 110 85 110 115C110 145 80 175 80 175C80 175 50 145 50 115C50 85 80 55 80 55Z" stroke="#176B32" stroke-width="1.8"/>
            <path d="M30 160C30 160 55 150 80 160C105 150 130 160 130 160" stroke="#176B32" stroke-width="1.5"/>
            <path d="M45 200C45 200 62 190 80 200C98 190 115 200 115 200" stroke="#176B32" stroke-width="1.5"/>
            <path d="M70 125L80 115L90 125" stroke="#176B32" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </div>

    <div style="width:100%;max-width:1280px;margin:0 auto;padding:0 clamp(16px,2.5vw,36px);">
        
        
        
        <div class="services-header">
            <div class="services-header-center">
                
                <div style="display:flex;align-items:center;justify-content:center;gap:5px;margin-bottom:10px;">
                    <span style="display:block;width:26px;height:2px;border-radius:9999px;background:#176B32;"></span>
                    <span style="display:block;width:4px;height:4px;border-radius:50%;background:#176B32;"></span>
                    <span style="display:block;width:26px;height:2px;border-radius:9999px;background:#176B32;"></span>
                </div>

                <h2 class="services-title" style="text-align:center;color:#17243A;font-size:clamp(23px,2.8vw,32px);font-weight:700;line-height:1.3;margin:0;">
                    <?php echo e($sectionTitle ?? 'الخدمات الإلكترونية'); ?>

                </h2>

                <p style="text-align:center;max-width:640px;margin:8px auto 0;font-size:13px;line-height:1.8;color:#66756D;">
                    <?php echo e($sectionSubtitle ?? 'نقدم مجموعة متكاملة من الخدمات الإلكترونية لتسهيل معاملات المواطنين بسرعة ووضوح.'); ?>

                </p>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($publicServicesIndexUrl): ?>
                <div class="services-header-action">
                    <a href="<?php echo e($publicServicesIndexUrl); ?>" wire:navigate
                       class="services-view-all-btn"
                       style="display:inline-flex;align-items:center;gap:6px;height:42px;padding:0 20px;border-radius:9px;background:#176B32;color:white;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(23,107,50,0.2);transition:background 200ms,box-shadow 200ms;">
                        <span>عرض جميع الخدمات</span>
                        <i data-lucide="arrow-left" style="width:15px;height:15px;transition:transform 200ms;"></i>
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($services->isNotEmpty()): ?>
            <div class="services-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:44px;min-width:0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $serviceName = $service['name'] ?? '';
                        $serviceSummary = $service['summary'] ?? '';
                        $categoryName = $service['category']['name'] ?? '';
                        $servicePortalUrl = $service['portal_url'] ?? null;
                        $serviceShowUrl = (!empty($service['category']['slug']) && !empty($service['slug']) && Route::has('public.services.show')) ? route('public.services.show', ['category' => $service['category']['slug'], 'service' => $service['slug']]) : '#';
                        $processingTime = $service['processing_time'] ?? null;
                        $requiresLogin = $service['requires_login'] ?? false;
                    ?>
                    <article class="service-card" style="display:flex;flex-direction:column;background:#FFFFFF;border-radius:14px;border:1px solid #E3E9E4;padding:20px 22px;box-shadow:0 5px 20px rgba(20,50,30,0.055);transition:all 240ms ease-out;min-height:225px;min-width:0;">
                        
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;min-height:26px;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categoryName): ?>
                                <span class="service-category-badge" style="display:inline-flex;align-items:center;padding:0 10px;height:26px;border-radius:9999px;font-size:11px;font-weight:600;background:#EAF5EE;color:#176B32;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                    <?php echo e($categoryName); ?>

                                </span>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requiresLogin): ?>
                                <span style="font-size:10px;font-weight:600;color:#94A3B8;white-space:nowrap;display:flex;align-items:center;gap:3px;">
                                    <i data-lucide="lock" style="width:11px;height:11px;"></i>
                                    يتطلب تسجيل دخول
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <h3 style="margin:18px 0 0;font-size:17px;font-weight:700;color:#17243A;line-height:1.55;text-align:right;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            <?php echo e($serviceName); ?>

                        </h3>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serviceSummary): ?>
                            <p style="margin:8px 0 0;font-size:13px;line-height:1.75;color:#66756D;text-align:right;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                <?php echo e($serviceSummary); ?>

                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div style="flex:1;min-height:6px;"></div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($processingTime): ?>
                            <div style="margin-top:6px;">
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:500;color:#7BBC9D;">
                                    <i data-lucide="clock" style="width:14px;height:14px;"></i>
                                    <?php echo e($processingTime); ?>

                                </span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div style="margin:12px 0 10px;height:1px;background:#EDF0ED;"></div>

                        
                        <div style="display:flex;gap:9px;">
                            <a href="<?php echo e($serviceShowUrl); ?>" <?php if($serviceShowUrl !== '#'): ?> wire:navigate <?php endif; ?>
                               class="service-btn-primary"
                               style="flex:0 0 62%;display:inline-flex;align-items:center;justify-content:center;gap:6px;height:40px;border-radius:8px;background:#176B32;color:white;font-size:13px;font-weight:600;text-decoration:none;transition:background 200ms;">
                                <span>ابدأ الخدمة</span>
                                <i data-lucide="external-link" style="width:14px;height:14px;flex-shrink:0;"></i>
                            </a>
                            <a href="<?php echo e($serviceShowUrl); ?>" <?php if($serviceShowUrl !== '#'): ?> wire:navigate <?php endif; ?>
                               class="service-btn-secondary"
                               style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:5px;height:40px;border-radius:8px;background:#FFFFFF;border:1px solid #DDE5DC;color:#17243A;font-size:13px;font-weight:600;text-decoration:none;transition:border-color 200ms,background 200ms;">
                                <span>التفاصيل</span>
                                <i data-lucide="chevron-left" style="width:13px;height:13px;flex-shrink:0;"></i>
                            </a>
                        </div>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php else: ?>
            
            <div style="display:flex;align-items:center;justify-content:center;min-height:180px;margin-top:44px;">
                <div style="text-align:center;">
                    <i data-lucide="monitor" style="width:36px;height:36px;color:#A0CFB8;margin-bottom:10px;"></i>
                    <p style="font-size:15px;font-weight:600;color:#66756D;margin:0;">لا توجد خدمات متاحة حالياً</p>
                    <p style="font-size:12px;color:#94A3B8;margin:6px 0 0;">سيتم إضافة الخدمات الإلكترونية قريباً</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;height:40px;padding:0 20px;border-radius:9px;background:#176B32;color:white;font-size:13px;font-weight:600;text-decoration:none;">
                            <i data-lucide="external-link" style="width:14px;height:14px;"></i>
                            <span>البوابة الإلكترونية</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if (! $__env->hasRenderedOnce('2f368f5a-49ca-4d82-b4f8-8ad5896abc6b')): $__env->markAsRenderedOnce('2f368f5a-49ca-4d82-b4f8-8ad5896abc6b'); ?>
        <?php $__env->startPush('styles'); ?>
            <style>
                /* Desktop header — view-all button absolute on the left */
                @media (min-width: 1025px) {
                    .services-header {
                        position: relative;
                    }
                    .services-header-action {
                        position: absolute;
                        left: 0;
                        top: 50%;
                        transform: translateY(-50%);
                    }
                    .services-view-all-btn:hover {
                        background: #0F4F28 !important;
                        box-shadow: 0 6px 20px rgba(23,107,50,0.3) !important;
                    }
                    .services-view-all-btn:hover i {
                        transform: translateX(-2px);
                    }
                }

                /* Tablet — stack header, switch to 2 columns */
                @media (max-width: 1024px) {
                    .services-header-action {
                        text-align: center;
                        margin-top: 20px;
                    }
                    .services-grid {
                        grid-template-columns: repeat(2, 1fr) !important;
                        margin-top: 36px !important;
                    }
                }

                /* Mobile — single column, tighter spacing */
                @media (max-width: 640px) {
                    .services-section {
                        padding-top: 12px !important;
                        padding-bottom: 20px !important;
                    }
                    .services-header-center p {
                        font-size: 12px !important;
                        margin-top: 4px !important;
                        line-height: 1.6 !important;
                    }
                    .services-grid {
                        grid-template-columns: 1fr !important;
                        margin-top: 16px !important;
                        gap: 12px !important;
                    }
                    .service-card {
                        padding: 12px 14px !important;
                        min-height: 180px !important;
                    }
                    .services-pattern {
                        display: none !important;
                    }
                    .services-header-action {
                        margin-top: 12px !important;
                    }
                    .services-view-all-btn {
                        height: 36px !important;
                        font-size: 12px !important;
                        padding: 0 16px !important;
                    }
                }

                /* Ultra-narrow: stack action buttons */
                @media (max-width: 360px) {
                    .service-card > div:last-child {
                        flex-direction: column !important;
                    }
                    .service-card > div:last-child a {
                        flex: 1 1 auto !important;
                        width: 100% !important;
                    }
                }

                /* Card hover interactions */
                .service-card:hover {
                    transform: translateY(-3px);
                    border-color: rgba(23, 107, 50, 0.25) !important;
                    box-shadow: 0 10px 28px rgba(20, 50, 30, 0.1) !important;
                }
                .service-card:hover .service-btn-primary {
                    background: #0F4F28 !important;
                }
                .service-card:hover .service-btn-secondary {
                    border-color: #176B32 !important;
                    background: #F5FBF6 !important;
                }

                /* Keyboard focus visibility */
                .service-card a:focus-visible,
                .service-card button:focus-visible {
                    outline: 2px solid #176B32;
                    outline-offset: 2px;
                    border-radius: 8px;
                }
                .services-view-all-btn:focus-visible {
                    outline: 2px solid #FFFFFF;
                    outline-offset: 3px;
                    border-radius: 9px;
                }

                /* Reduced motion */
                @media (prefers-reduced-motion: reduce) {
                    .service-card,
                    .service-card *,
                    .services-view-all-btn,
                    .services-view-all-btn i {
                        transition-duration: 0.01ms !important;
                        transform: none !important;
                    }
                }
            </style>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/services.blade.php ENDPATH**/ ?>