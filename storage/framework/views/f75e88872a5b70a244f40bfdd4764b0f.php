<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'facilities',
        'pageTitle' => $facility->name,
        'pageSubtitle' => $facility->summary ?? null,
        'pageBadge' => 'مرفق عام',
        'pageBadgeIcon' => 'building-2',
        'compact' => true,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3811477392-0', $__key);

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

    
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->category): ?>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="<?php echo e($facility->category->icon ?? 'building-2'); ?>" style="width:12px;height:12px;"></i>
                    <span><?php echo e($facility->category->name); ?></span>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->phone): ?>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="phone" style="width:12px;height:12px;"></i>
                    <span dir="ltr"><?php echo e($facility->phone); ?></span>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->is_featured): ?>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(217,119,6,0.2);color:#FCD34D;">
                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                    <span>مرفق مميز</span>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    
    
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                
                <div class="lg:col-span-2 space-y-8">

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->cover_image_url): ?>
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;">
                            <img src="<?php echo e($facility->cover_image_url); ?>" alt="<?php echo e($facility->name); ?>" class="w-full aspect-video object-cover" />
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->gallery_urls): ?>
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="images" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>معرض الصور</span>
                            </h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $facility->gallery_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <img src="<?php echo e($image); ?>" alt="<?php echo e($facility->name); ?>" class="w-full aspect-video object-cover rounded-lg border border-gray-100" loading="lazy" />
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->description): ?>
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="info" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>نبذة عن المرفق</span>
                            </h2>
                            <div style="font-size:14px;color:#4B5563;line-height:1.8;white-space:pre-line;">
                                <?php echo e($facility->description); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->services): ?>
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="list-checks" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>الخدمات</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $facility->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#4B5563;">
                                        <i data-lucide="check-circle" style="width:16px;height:16px;color:#0F6A3D;flex-shrink:0;"></i>
                                        <span><?php echo e($service); ?></span>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->features): ?>
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="star" style="width:18px;height:18px;color:#D97706;"></i>
                                <span>المميزات</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $facility->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#4B5563;">
                                        <i data-lucide="star" style="width:16px;height:16px;color:#D97706;flex-shrink:0;"></i>
                                        <span><?php echo e($feature); ?></span>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->rules): ?>
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="alert-circle" style="width:18px;height:18px;color:#D97706;"></i>
                                <span>التعليمات</span>
                            </h2>
                            <ul style="margin:0;padding:0;list-style:none;display:flex;flex-direction:column;gap:8px;">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $facility->rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <li style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#4B5563;">
                                        <i data-lucide="alert-circle" style="width:16px;height:16px;color:#D97706;flex-shrink:0;margin-top:1px;"></i>
                                        <span><?php echo e($rule); ?></span>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="space-y-6">

                    
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;">
                        <div style="padding:24px;text-align:center;background:linear-gradient(135deg,rgba(15,106,61,0.04),rgba(15,106,61,0.08));">
                            <div style="width:64px;height:64px;border-radius:16px;background:#0F6A3D;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i data-lucide="building-2" style="width:28px;height:28px;color:white;"></i>
                            </div>
                            <h3 style="font-size:15px;font-weight:800;color:#1F2937;margin:0;"><?php echo e($facility->name); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->category): ?>
                                <p style="font-size:12px;color:#6B7280;margin:4px 0 0;"><?php echo e($facility->category->name); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="map-pin" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                </div>
                                <div>
                                    <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">العنوان</p>
                                    <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;"><?php echo e($facility->address); ?></p>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->phone): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="phone" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">الهاتف</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;" dir="ltr"><?php echo e($facility->phone); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->email): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="mail" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">البريد الإلكتروني</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;"><?php echo e($facility->email); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facility->working_hours): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="clock" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">ساعات العمل</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;"><?php echo e($facility->working_hours); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    
                    <div style="text-align:center;padding:12px 20px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:12px;color:#9CA3AF;">
                            <i data-lucide="eye" style="width:14px;height:14px;"></i>
                            <span><?php echo e(number_format($facility->views_count)); ?> مشاهدة</span>
                        </div>
                    </div>

                    
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px 24px;">
                        <h3 style="font-size:14px;font-weight:800;color:#1F2937;margin:0 0 14px;">روابط سريعة</h3>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <a href="<?php echo e(route('public.facilities.index')); ?>" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;background:transparent;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6'"
                               onmouseout="this.style.background='transparent'">
                                <i data-lucide="building-2" style="width:14px;height:14px;"></i>
                                <span>جميع المرافق</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div><?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/public-facilities/public-facility-show.blade.php ENDPATH**/ ?>