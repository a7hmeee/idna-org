<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'services',
        'fallbackTitle' => "كل الخدمات البلدية",
        'fallbackDescription' => "استعرض جميع الخدمات الإلكترونية التي تقدمها البلدية، واختر الخدمة المناسبة لتقديم طلبك إلكترونياً بكل سهولة ويسر.",
        'fallbackBadge' => 'بوابة الخدمات الإلكترونية',
        'fallbackIcon' => 'laptop',
        'fallbackImage' => $heroImage,
        'compact' => false,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2913672159-0', $__key);

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

    
    
    
    <section id="services" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    
                    <div style="display:flex;align-items:center;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;overflow:hidden;">
                        <button style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:#F3F4F6;color:#6B7280;border:none;cursor:pointer;transition:all 0.2s;">
                            <i data-lucide="list" style="width:16px;height:16px;"></i>
                        </button>
                        <button style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:#0F6A3D;color:white;border:none;cursor:pointer;">
                            <i data-lucide="grid-3x2" style="width:16px;height:16px;"></i>
                        </button>
                    </div>

                    
                    <button style="display:flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;background:#0F6A3D;color:white;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.2s;"
                            wire:click="toggleFilters"
                            onmouseover="this.style.background='#0D5C34'"
                            onmouseout="this.style.background='#0F6A3D'">
                        <i data-lucide="filter" style="width:14px;height:14px;"></i>
                        <span>تصفية</span>
                        <i data-lucide="chevron-up" style="width:12px;height:12px;" x-show="$wire.showFilters" x-cloak></i>
                        <i data-lucide="chevron-down" style="width:12px;height:12px;" x-show="!$wire.showFilters" x-cloak></i>
                    </button>
                </div>

                
                <div style="flex:1;max-width:400px;position:relative;">
                    <i data-lucide="search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#9CA3AF;pointer-events:none;"></i>
                    <input type="text" wire:model.live.debounce.400ms="search"
                           placeholder="ابحث عن خدمة..."
                           style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:12px 44px 12px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                           onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                           onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                </div>
            </div>

            
            <div x-data x-show="$wire.showFilters" x-transition x-cloak style="margin-bottom:24px;">
                <p style="font-size:13px;font-weight:600;color:#4B5563;margin:0 0 10px;">القائمة</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    <button wire:click="$set('filterCategoryId', '')"
                            style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid <?php echo e($filterCategoryId == '' ? '#0F6A3D' : '#E5E7EB'); ?>;background:<?php echo e($filterCategoryId == '' ? '#0F6A3D' : 'white'); ?>;color:<?php echo e($filterCategoryId == '' ? 'white' : '#6B7280'); ?>;">
                        الكل
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <button wire:click="$set('filterCategoryId', '<?php echo e($cat->id); ?>')"
                                style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid <?php echo e($filterCategoryId == $cat->id ? '#0F6A3D' : '#E5E7EB'); ?>;background:<?php echo e($filterCategoryId == $cat->id ? '#0F6A3D' : 'white'); ?>;color:<?php echo e($filterCategoryId == $cat->id ? 'white' : '#6B7280'); ?>;">
                            <?php echo e($cat->name); ?>

                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;"><?php echo e($services->total() ?? 0); ?></span> خدمة
                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterDepartmentSlug): ?>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:12px;color:#6B7280;">تصفية حسب القسم:</span>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;background:#0F6A3D;color:white;font-size:12px;font-weight:600;">
                            <?php echo e($filterDepartmentName ?? $filterDepartmentSlug); ?>

                            <button wire:click="$set('filterDepartmentSlug', null)" style="background:none;border:none;color:white;cursor:pointer;padding:0;display:flex;">
                                <i data-lucide="x" style="width:12px;height:12px;"></i>
                            </button>
                        </span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($services->isEmpty()): ?>
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="search-x" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h3>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('public.services.show', ['category' => $service->category?->slug ?? 'general', 'service' => $service->slug])); ?>" wire:navigate
                           class="svc-card block bg-white rounded-2xl border border-gray-100 p-5 transition-all duration-200"
                           style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                            <div style="display:flex;align-items:flex-start;gap:14px;">
                                <div class="svc-icon" style="width:52px;height:52px;border-radius:14px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s;">
                                    <i data-lucide="<?php echo e($service->category?->icon ?? 'file-text'); ?>" class="svc-i" style="width:22px;height:22px;color:#0F6A3D;transition:color 0.2s;"></i>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <h3 class="svc-title" style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 4px;transition:color 0.2s;line-height:1.4;"><?php echo e($service->name); ?></h3>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->description): ?>
                                        <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;"><?php echo e($service->description); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div style="margin-top:10px;">
                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;">
                                            اعرف المزيد
                                            <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($services->hasPages()): ?>
                <div class="mt-10">
                    <?php if (isset($component)) { $__componentOriginal4d04f29578652eb91560cfbf2ab48c57 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.pagination','data' => ['paginator' => $services]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d04f29578652eb91560cfbf2ab48c57)): ?>
<?php $attributes = $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57; ?>
<?php unset($__attributesOriginal4d04f29578652eb91560cfbf2ab48c57); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d04f29578652eb91560cfbf2ab48c57)): ?>
<?php $component = $__componentOriginal4d04f29578652eb91560cfbf2ab48c57; ?>
<?php unset($__componentOriginal4d04f29578652eb91560cfbf2ab48c57); ?>
<?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    
    
    
    <section class="py-16 sm:py-20 lg:py-28" style="background:#F9FAFB;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 16px;border-radius:9999px;font-size:12px;font-weight:700;background:rgba(15,106,61,0.06);color:#0F6A3D;margin-bottom:16px;">
                    <i data-lucide="list-checks" style="width:14px;height:14px;"></i>
                    خطوات مبسطة
                </span>
                <h2 style="font-size:clamp(22px,3vw,40px);font-weight:900;color:#1F2937;margin:0 0 8px;">كيف تحصل على الخدمة؟</h2>
                <p style="font-size:clamp(13px,1.2vw,15px);color:#6B7280;max-width:500px;margin:0 auto;">اتبع هذه الخطوات البسيطة لتقديم طلبك إلكترونياً</p>
            </div>

            <?php
                $steps = [
                    ['title' => 'اختر تصنيف الخدمة', 'description' => 'تصفح التصنيفات المتاحة واختر التصنيف المناسب لطلبك', 'icon' => 'folder-tree', 'number' => '01'],
                    ['title' => 'اطلع على المتطلبات', 'description' => 'تعرف على الوثائق والمتطلبات اللازمة لتقديم الطلب', 'icon' => 'clipboard-list', 'number' => '02'],
                    ['title' => 'انتقل إلى البوابة', 'description' => 'اضغط على زر التقديم لتنتقل إلى بوابة الخدمات الرسمية', 'icon' => 'external-link', 'number' => '03'],
                    ['title' => 'تابع معاملتك', 'description' => 'قدم طلبك وتابع حالة المعاملة عبر البوابة الإلكترونية', 'icon' => 'message-circle', 'number' => '04'],
                ];
            ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div style="position:relative;background:white;border-radius:20px;border:1px solid #F3F4F6;padding:32px 24px;text-align:center;transition:all 0.3s;"
                         onmouseover="this.style.borderColor='rgba(15,106,61,0.2)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'"
                         onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='none'">
                        <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);width:32px;height:32px;border-radius:50%;background:#0F6A3D;color:white;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(15,106,61,0.3);">
                            <?php echo e($step['number']); ?>

                        </div>
                        <div style="width:56px;height:56px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <i data-lucide="<?php echo e($step['icon']); ?>" style="width:24px;height:24px;color:#0F6A3D;"></i>
                        </div>
                        <h3 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 8px;"><?php echo e($step['title']); ?></h3>
                        <p style="font-size:12px;color:#6B7280;line-height:1.7;margin:0;"><?php echo e($step['description']); ?></p>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
        <section class="py-16 sm:py-20 lg:py-28 relative overflow-hidden" style="background:linear-gradient(135deg,#0F6A3D,#2E7D32,#1B5E20);">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ctaBackground): ?>
                <div style="position:absolute;inset:0;">
                    <img src="<?php echo e($ctaBackground); ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(15,106,61,0.85),rgba(46,125,50,0.80),rgba(27,94,32,0.85));"></div>
            <?php else: ?>
                <div style="position:absolute;inset:0;opacity:0.10;">
                    <div style="position:absolute;top:20px;right:20px;width:200px;height:200px;background:white;border-radius:50%;filter:blur(60px);"></div>
                    <div style="position:absolute;bottom:20px;left:20px;width:300px;height:300px;background:white;border-radius:50%;filter:blur(80px);"></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" style="position:relative;z-index:10;">
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);">
                    <i data-lucide="external-link" style="width:32px;height:32px;color:white;"></i>
                </div>
                <h2 style="font-size:clamp(22px,3vw,40px);font-weight:900;color:white;margin:0 0 12px;">هل تريد تقديم طلبك الآن؟</h2>
                <p style="font-size:clamp(14px,1.3vw,17px);color:rgba(255,255,255,0.85);max-width:500px;margin:0 auto 28px;">انتقل إلى بوابة الخدمات الرسمية لتقديم طلبك إلكترونياً</p>
                <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                   style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;border-radius:14px;background:white;color:#0F6A3D;font-size:14px;font-weight:700;text-decoration:none;transition:all 0.3s;box-shadow:0 4px 20px rgba(0,0,0,0.2);"
                   onmouseover="this.style.boxShadow='0 8px 30px rgba(0,0,0,0.3)';this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.2)';this.style.transform='translateY(0)'">
                    <span>الدخول إلى بوابة الخدمات</span>
                    <i data-lucide="external-link" style="width:16px;height:16px;"></i>
                </a>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    
    <style>
        .svc-card { cursor: pointer; }
        .svc-card:hover .svc-icon { background: #0F6A3D !important; }
        .svc-card:hover .svc-i { color: white !important; }
        .svc-card:hover .svc-title { color: #0F6A3D !important; }
    </style>

</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/electronic-services/public-services-portal.blade.php ENDPATH**/ ?>