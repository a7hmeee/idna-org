<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'tenders',
        'pageTitle' => $tender->title_ar,
        'pageSubtitle' => $tender->summary ?? null,
        'pageBadge' => 'مناقصة',
        'pageBadgeIcon' => 'scroll-text',
        'compact' => true,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1570515212-0', $__key);

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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->issuing_department): ?>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="building-2" style="width:12px;height:12px;"></i>
                    <span><?php echo e($tender->issuing_department); ?></span>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                <i data-lucide="clock" style="width:12px;height:12px;"></i>
                <span>آخر موعد: <?php echo e($tender->submission_deadline?->format('Y/m/d') ?? '—'); ?></span>
            </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->is_featured): ?>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(217,119,6,0.2);color:#FCD34D;">
                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                    <span>مميزة</span>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;
                <?php if($tender->status->value === 'open'): ?> background:rgba(46,125,50,0.15);color:#2E7D32;
                <?php elseif($tender->status->value === 'closed'): ?> background:rgba(211,47,47,0.15);color:#D32F2F;
                <?php elseif($tender->status->value === 'awarded'): ?> background:rgba(21,101,192,0.15);color:#1565C0;
                <?php else: ?> background:rgba(158,158,158,0.15);color:#757575; <?php endif; ?>">
                <i data-lucide="circle" style="width:12px;height:12px;"></i>
                <span><?php echo e($tender->status->label()); ?></span>
            </span>
        </div>
    </div>

    
    
    
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                
                <div class="lg:col-span-2 space-y-6">

                    
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-text mb-3">نبذة عن المناقصة</h2>
                        <p class="text-text-secondary leading-relaxed"><?php echo e($tender->summary); ?></p>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->description): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">الوصف</h2>
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line"><?php echo e($tender->description); ?></div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->eligibility_requirements): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">شروط التأهيل</h2>
                            <ul class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tender->eligibility_requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="check-circle" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span><?php echo e($req); ?></span>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->application_instructions): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">تعليمات التقديم</h2>
                            <ul class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tender->application_instructions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="info" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span><?php echo e($inst); ?></span>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->tender_documents): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">مستندات المناقصة</h2>
                            <ul class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tender->tender_documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="file-text" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span><?php echo e($doc); ?></span>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->result_documents): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">نتائج المناقصة</h2>
                            <ul class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tender->result_documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="award" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span><?php echo e($doc); ?></span>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedTenders->isNotEmpty()): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">مناقصات أخرى</h2>
                            <div class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $relatedTenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <a href="<?php echo e(route('public.tenders.show', $related->slug)); ?>" wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors no-underline group">
                                        <div class="w-10 h-10 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="scroll-text" class="w-5 h-5 text-primary"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors"><?php echo e($related->title_ar); ?></h3>
                                            <p class="text-xs text-text-tertiary"><?php echo e($related->issuing_department); ?> · <?php echo e($related->status->label()); ?></p>
                                        </div>
                                        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-300 group-hover:text-primary transition-colors"></i>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="space-y-4">

                    
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3">معلومات المناقصة</h3>
                        <div class="space-y-3 text-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->tender_number): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">رقم المناقصة</span>
                                    <span class="text-text font-semibold"><?php echo e($tender->tender_number); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">الجهة المصدرة</span>
                                <span class="text-text font-semibold"><?php echo e($tender->issuing_department ?? '—'); ?></span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->category): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">التصنيف</span>
                                    <span class="text-text font-semibold"><?php echo e($tender->category); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">تاريخ النشر</span>
                                <span class="text-text font-semibold"><?php echo e($tender->publication_date?->format('Y/m/d') ?? '—'); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">آخر موعد</span>
                                <span class="text-text font-semibold"><?php echo e($tender->submission_deadline?->format('Y/m/d') ?? '—'); ?></span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->opening_date): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">تاريخ الفتح</span>
                                    <span class="text-text font-semibold"><?php echo e($tender->opening_date->format('Y/m/d')); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->budget): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">الميزانية</span>
                                    <span class="text-text font-semibold"><?php echo e(number_format((float) $tender->budget)); ?> <?php echo e($tender->budget_currency); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->submission_deadline): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="font-bold text-text mb-3">الوقت المتبقي</h3>
                            <?php
                                $daysLeft = now()->diffInDays($tender->submission_deadline, false);
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daysLeft > 0): ?>
                                <div class="text-center">
                                    <span class="text-3xl font-bold text-primary"><?php echo e($daysLeft); ?></span>
                                    <span class="text-sm text-text-tertiary block mt-1">يوماً متبقياً</span>
                                    <div class="mt-3 w-full bg-gray-100 rounded-full h-2">
                                        <?php
                                            $totalDays = max(1, now()->diffInDays($tender->publication_date ?? $tender->submission_deadline->copy()->subMonth()));
                                            $progress = max(0, min(100, ($totalDays - $daysLeft) / $totalDays * 100));
                                        ?>
                                        <div class="bg-primary h-2 rounded-full" style="width: <?php echo e($progress); ?>%"></div>
                                    </div>
                                </div>
                            <?php elseif($daysLeft === 0): ?>
                                <div class="text-center">
                                    <span class="text-xl font-bold text-warning">آخر يوم اليوم</span>
                                </div>
                            <?php else: ?>
                                <div class="text-center">
                                    <span class="text-xl font-bold text-danger">انتهى التقديم</span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->contact_info || $tender->contact_phone || $tender->contact_email): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="font-bold text-text mb-3">معلومات الاتصال</h3>
                            <div class="space-y-2 text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->contact_info): ?>
                                    <p class="text-text-secondary"><?php echo e($tender->contact_info); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->contact_phone): ?>
                                    <a href="tel:<?php echo e($tender->contact_phone); ?>" class="flex items-center gap-2 text-primary hover:underline no-underline">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                        <span><?php echo e($tender->contact_phone); ?></span>
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tender->contact_email): ?>
                                    <a href="mailto:<?php echo e($tender->contact_email); ?>" class="flex items-center gap-2 text-primary hover:underline no-underline">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                        <span><?php echo e($tender->contact_email); ?></span>
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="text-center text-xs text-text-tertiary">
                        <i data-lucide="eye" class="w-3 h-3 inline"></i>
                        <?php echo e(number_format($tender->views_count)); ?> مشاهدة
                    </div>

                    
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3 text-sm">روابط سريعة</h3>
                        <div class="flex flex-col gap-2">
                            <a href="<?php echo e(route('public.tenders.index')); ?>" wire:navigate
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-gray-50 hover:text-text transition-colors no-underline">
                                <i data-lucide="scroll-text" class="w-4 h-4"></i>
                                <span>جميع المناقصات</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/tenders/public-tender-show.blade.php ENDPATH**/ ?>