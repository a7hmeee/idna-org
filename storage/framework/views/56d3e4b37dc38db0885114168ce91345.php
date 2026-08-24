<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'services',
        'breadcrumbExtra' => $service->category
            ? [['label' => $service->category->name, 'url' => route('public.services.category', $service->category->slug)]]
            : null,
        'pageTitle' => $service->name,
        'pageSubtitle' => $service->summary ?? null,
        'pageBadge' => $service->category?->name ?? 'خدمة',
        'pageBadgeIcon' => $service->category?->icon ?? 'file-text',
        'compact' => true,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-890726581-0', $__key);

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

    
    
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-30px;position:relative;z-index:20;">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 lg:gap-8">

            
            <div x-data="{ tab: 'overview' }">
                <div style="background:white;border-radius:14px;border:1px solid #F3F4F6;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.04);">

                    
                    <div style="display:flex;gap:0;border-bottom:1px solid #F3F4F6;overflow-x:auto;">
                        <button @click="tab='overview'" :class="tab==='overview' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="info" style="width:14px;height:14px;"></i>
                                <span>نبذة عن الخدمة</span>
                            </span>
                        </button>
                        <button @click="tab='requirements'" :class="tab==='requirements' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="clipboard-list" style="width:14px;height:14px;"></i>
                                <span>المتطلبات والوثائق</span>
                            </span>
                        </button>
                        <button @click="tab='steps'" :class="tab==='steps' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="list-ordered" style="width:14px;height:14px;"></i>
                                <span>خطوات التقديم</span>
                            </span>
                        </button>
                        <button @click="tab='fees'" :class="tab==='fees' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="wallet" style="width:14px;height:14px;"></i>
                                <span>الرسوم</span>
                            </span>
                        </button>
                    </div>

                    
                    <div x-show="tab==='overview'" style="padding:28px 24px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->description): ?>
                            <div style="margin-bottom:24px;">
                                <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 10px;">وصف الخدمة</h3>
                                <p style="font-size:13px;color:#6B7280;line-height:1.8;margin:0;"><?php echo e($service->description); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->eligibility): ?>
                            <div>
                                <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 10px;">من يستطيع التقديم؟</h3>
                                <p style="font-size:13px;color:#6B7280;line-height:1.8;margin:0;"><?php echo e($service->eligibility); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$service->description && !$service->eligibility): ?>
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="file-text" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">لا توجد تفاصيل إضافية لهذه الخدمة</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->requires_login): ?>
                            <div style="margin-top:24px;padding:16px;border-radius:10px;background:#FFFBEB;border:1px solid rgba(251,191,36,0.3);">
                                <div style="display:flex;align-items:flex-start;gap:10px;">
                                    <i data-lucide="alert-triangle" style="width:16px;height:16px;color:#D97706;flex-shrink:0;margin-top:1px;"></i>
                                    <div>
                                        <p style="font-size:12px;font-weight:700;color:#92400E;margin:0 0 4px;">ملاحظة مهمة</p>
                                        <p style="font-size:11px;color:rgba(146,64,14,0.8);line-height:1.6;margin:0;">هذه الخدمة تتطلب تسجيل الدخول إلى البوابة الإلكترونية لتقديم الطلب. يرجى التأكد من امتلاك حساب نشط على البوابة.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div x-show="tab==='requirements'" style="padding:28px 24px;">
                        <?php $hasReqs = !empty($service->requirements); $hasDocs = !empty($service->documents); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasReqs || $hasDocs): ?>
                            <?php if (isset($component)) { $__componentOriginal39ba3d6217960c7288936705069ea4e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal39ba3d6217960c7288936705069ea4e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.services.service-requirements','data' => ['requirements' => $service->requirements ?? []]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('services.service-requirements'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['requirements' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->requirements ?? [])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal39ba3d6217960c7288936705069ea4e9)): ?>
<?php $attributes = $__attributesOriginal39ba3d6217960c7288936705069ea4e9; ?>
<?php unset($__attributesOriginal39ba3d6217960c7288936705069ea4e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal39ba3d6217960c7288936705069ea4e9)): ?>
<?php $component = $__componentOriginal39ba3d6217960c7288936705069ea4e9; ?>
<?php unset($__componentOriginal39ba3d6217960c7288936705069ea4e9); ?>
<?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasReqs && $hasDocs): ?>
                                <hr style="border:none;border-top:1px solid #F3F4F6;margin:20px 0;">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal0191198c4b8fc7ffdb5bcadfab52cd11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0191198c4b8fc7ffdb5bcadfab52cd11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.services.service-documents','data' => ['documents' => $service->documents ?? []]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('services.service-documents'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['documents' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->documents ?? [])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0191198c4b8fc7ffdb5bcadfab52cd11)): ?>
<?php $attributes = $__attributesOriginal0191198c4b8fc7ffdb5bcadfab52cd11; ?>
<?php unset($__attributesOriginal0191198c4b8fc7ffdb5bcadfab52cd11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0191198c4b8fc7ffdb5bcadfab52cd11)): ?>
<?php $component = $__componentOriginal0191198c4b8fc7ffdb5bcadfab52cd11; ?>
<?php unset($__componentOriginal0191198c4b8fc7ffdb5bcadfab52cd11); ?>
<?php endif; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="clipboard-list" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">لا توجد متطلبات أو وثائق لهذه الخدمة</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div x-show="tab==='steps'" style="padding:28px 24px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($service->steps)): ?>
                            <?php if (isset($component)) { $__componentOriginalce3578a2ca0068cc4f07041555b798ef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce3578a2ca0068cc4f07041555b798ef = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.services.service-steps','data' => ['steps' => $service->steps ?? []]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('services.service-steps'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['steps' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->steps ?? [])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce3578a2ca0068cc4f07041555b798ef)): ?>
<?php $attributes = $__attributesOriginalce3578a2ca0068cc4f07041555b798ef; ?>
<?php unset($__attributesOriginalce3578a2ca0068cc4f07041555b798ef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce3578a2ca0068cc4f07041555b798ef)): ?>
<?php $component = $__componentOriginalce3578a2ca0068cc4f07041555b798ef; ?>
<?php unset($__componentOriginalce3578a2ca0068cc4f07041555b798ef); ?>
<?php endif; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="list-ordered" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">لا توجد خطوات متاحة لهذه الخدمة</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div x-show="tab==='fees'" style="padding:28px 24px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($service->fees)): ?>
                            <?php if (isset($component)) { $__componentOriginale9ea3657810d1b746c2b2b3592547b77 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale9ea3657810d1b746c2b2b3592547b77 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.services.service-fees','data' => ['fees' => $service->fees ?? []]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('services.service-fees'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['fees' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->fees ?? [])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale9ea3657810d1b746c2b2b3592547b77)): ?>
<?php $attributes = $__attributesOriginale9ea3657810d1b746c2b2b3592547b77; ?>
<?php unset($__attributesOriginale9ea3657810d1b746c2b2b3592547b77); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale9ea3657810d1b746c2b2b3592547b77)): ?>
<?php $component = $__componentOriginale9ea3657810d1b746c2b2b3592547b77; ?>
<?php unset($__componentOriginale9ea3657810d1b746c2b2b3592547b77); ?>
<?php endif; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="wallet" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">هذه الخدمة مجانية بالكامل</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="min-w-0">
                <div class="lg:sticky lg:top-28" style="display:flex;flex-direction:column;gap:12px;">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->portal_url): ?>
                        <button wire:click="goToPortal" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:14px 20px;border-radius:12px;background:linear-gradient(135deg,#0F6A3D,#2E7D32);color:white;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.3s;box-shadow:0 4px 16px rgba(15,106,61,0.3);" onmouseover="this.style.boxShadow='0 6px 24px rgba(15,106,61,0.4)'" onmouseout="this.style.boxShadow='0 4px 16px rgba(15,106,61,0.3)'">
                            <i data-lucide="external-link" style="width:15px;height:15px;"></i>
                            <span>التقديم عبر البوابة</span>
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                        <p style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 14px;">معلومات الخدمة</p>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="folder" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">التصنيف</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($service->category?->name ?? '—'); ?></p>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->department): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="building-2" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">الدائرة المسؤولة</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($service->department->name); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->processing_time): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="clock" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">مدة الإنجاز</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($service->processing_time); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="shield" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">حالة الخدمة</p>
                                    <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;margin-top:2px;<?php echo e($statusLabel === 'متاحة' ? 'background:#ECFDF5;color:#059669;' : 'background:#FEF2F2;color:#DC2626;'); ?>"><?php echo e($statusLabel); ?></span>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="<?php echo e($service->requires_login ? 'lock' : 'unlock'); ?>" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">تسجيل دخول</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;"><?php echo e($service->requires_login ? 'مطلوب' : 'غير مطلوب'); ?></p>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->views_count > 0): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="eye" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">المشاهدات</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;"><?php echo e(number_format($service->views_count)); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedServices->isNotEmpty()): ?>
        <section style="padding:48px 0 32px;margin-top:40px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div style="text-align:center;margin-bottom:28px;">
                    <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:9999px;font-size:11px;font-weight:700;background:rgba(15,106,61,0.06);color:#0F6A3D;margin-bottom:10px;">خدمات ذات صلة</span>
                    <h2 style="font-size:22px;font-weight:900;color:#1F2937;margin:0;">خدمات قد تهمك</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $relatedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6c5db73971dcb0bff4371680f1cd202d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6c5db73971dcb0bff4371680f1cd202d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.services.service-card','data' => ['service' => $related,'route' => route('public.services.show', ['category' => $service->category?->slug ?? 'general', 'service' => $related->slug])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('services.service-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['service' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($related),'route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.services.show', ['category' => $service->category?->slug ?? 'general', 'service' => $related->slug]))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6c5db73971dcb0bff4371680f1cd202d)): ?>
<?php $attributes = $__attributesOriginal6c5db73971dcb0bff4371680f1cd202d; ?>
<?php unset($__attributesOriginal6c5db73971dcb0bff4371680f1cd202d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6c5db73971dcb0bff4371680f1cd202d)): ?>
<?php $component = $__componentOriginal6c5db73971dcb0bff4371680f1cd202d; ?>
<?php unset($__componentOriginal6c5db73971dcb0bff4371680f1cd202d); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    
    <?php if (isset($component)) { $__componentOriginal546dff65808e27fed6d25e32c023b47f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal546dff65808e27fed6d25e32c023b47f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.services.portal-cta','data' => ['portalUrl' => $portalUrl ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('services.portal-cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['portalUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portalUrl ?? null)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal546dff65808e27fed6d25e32c023b47f)): ?>
<?php $attributes = $__attributesOriginal546dff65808e27fed6d25e32c023b47f; ?>
<?php unset($__attributesOriginal546dff65808e27fed6d25e32c023b47f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal546dff65808e27fed6d25e32c023b47f)): ?>
<?php $component = $__componentOriginal546dff65808e27fed6d25e32c023b47f; ?>
<?php unset($__componentOriginal546dff65808e27fed6d25e32c023b47f); ?>
<?php endif; ?>

</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/electronic-services/public-service-detail.blade.php ENDPATH**/ ?>