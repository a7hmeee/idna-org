<div>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'water-schedule',
        'fallbackTitle' => "جدول توزيع المياه",
        'fallbackDescription' => "تفقد جدول الضخ الأسبوعي للمياه في مختلف مناطق بلدية إذنا.",
        'fallbackBadge' => 'جدول المياه',
        'fallbackIcon' => 'droplets',
        'fallbackImage' => $slides->isNotEmpty() ? $slides->first()->image_url : null,
        'compact' => true,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3005630075-0', $__key);

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

    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary-light text-primary mb-3">
                        <i data-lucide="droplets" class="w-3.5 h-3.5"></i>
                        جدول توزيع المياه
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight">جدول ضخ المياه</h2>
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed">تابع مواعيد ضخ المياه في جميع مناطق بلدية إذنا</p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeMaintenance): ?>
                <div class="mb-8 rounded-2xl border-2 border-red-300 bg-red-50 p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-red-800 text-lg"><?php echo e($activeMaintenance->title); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeMaintenance->description): ?>
                                <p class="text-red-700 text-sm mt-1"><?php echo e($activeMaintenance->description); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeMaintenance->affected_areas): ?>
                                <div class="mt-2">
                                    <p class="text-xs font-semibold text-red-700">المناطق المتأثرة:</p>
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activeMaintenance->affected_areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <span class="inline-block bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-medium"><?php echo e($area); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($areaSchedules)): ?>
                <div class="bg-white rounded-3xl border border-border/50 p-12 text-center shadow-lg">
                    <div class="w-20 h-20 rounded-full bg-primary-light flex items-center justify-center mx-auto mb-5">
                        <i data-lucide="droplets" class="w-10 h-10 text-primary"></i>
                    </div>
                    <h3 class="font-bold text-text text-xl mb-2">لا توجد جداول مياه حالياً</h3>
                    <p class="text-sm text-text-secondary max-w-md mx-auto">لم يتم إنشاء جداول ضخ المياه بعد. يرجى المحاولة لاحقاً.</p>
                </div>
            <?php else: ?>
                <?php
                    $statusConfig = [
                        'available' => ['label' => 'متوفر', 'color' => 'bg-green-500', 'bg' => 'bg-green-50 border-green-200'],
                        'low_pressure' => ['label' => 'ضغط منخفض', 'color' => 'bg-yellow-500', 'bg' => 'bg-yellow-50 border-yellow-200'],
                        'maintenance' => ['label' => 'صيانة', 'color' => 'bg-orange-500', 'bg' => 'bg-orange-50 border-orange-200'],
                        'emergency' => ['label' => 'طارئ', 'color' => 'bg-red-500', 'bg' => 'bg-red-50 border-red-200'],
                        'no_water' => ['label' => 'لا يوجد ضخ', 'color' => 'bg-gray-500', 'bg' => 'bg-gray-50 border-gray-200'],
                    ];
                ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $areaSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $area = $data['area'];
                            $current = $data['current'];
                            $history = $data['history'];
                            $status = $current?->status?->value ?? 'available';
                            $statusInfo = $statusConfig[$status] ?? $statusConfig['available'];
                        ?>
                        <div class="bg-white rounded-3xl border border-border/50 overflow-hidden shadow-lg">
                            
                            <div class="bg-gradient-to-l from-[#0F4F28] to-[#0B3A24] px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                            <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-white text-lg"><?php echo e($area->name); ?></h3>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($current): ?>
                                                <p class="text-xs text-white/70"><?php echo e($todayDayName); ?> — <?php echo e(now()->format('d/m/Y')); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($current): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-white/20 text-white">
                                            <span class="w-2 h-2 rounded-full <?php echo e($statusInfo['color']); ?>"></span>
                                            <?php echo e($statusInfo['label']); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($current): ?>
                                
                                <div class="px-6 py-6">
                                    <div class="flex items-center justify-center gap-6">
                                        <div class="text-center">
                                            <div class="w-16 h-16 rounded-2xl bg-primary-light flex items-center justify-center mx-auto mb-2">
                                                <i data-lucide="sun" class="w-8 h-8 text-primary"></i>
                                            </div>
                                            <p class="text-2xl font-black text-text"><?php echo e($current->start_time ? \Carbon\Carbon::parse($current->start_time)->format('h:i') : '—'); ?></p>
                                            <p class="text-[11px] text-text-tertiary mt-0.5"><?php echo e($current->start_time ? (\Carbon\Carbon::parse($current->start_time)->format('A') === 'AM' ? 'صباحًا' : 'مساءً') : ''); ?></p>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-full bg-border/30 flex items-center justify-center">
                                                <i data-lucide="arrow-left" class="w-5 h-5 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-16 rounded-2xl bg-accent-light/30 flex items-center justify-center mx-auto mb-2">
                                                <i data-lucide="moon" class="w-8 h-8 text-accent-dark"></i>
                                            </div>
                                            <p class="text-2xl font-black text-text"><?php echo e($current->end_time ? \Carbon\Carbon::parse($current->end_time)->format('h:i') : '—'); ?></p>
                                            <p class="text-[11px] text-text-tertiary mt-0.5"><?php echo e($current->end_time ? (\Carbon\Carbon::parse($current->end_time)->format('A') === 'AM' ? 'صباحًا' : 'مساءً') : ''); ?></p>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($current->notes): ?>
                                        <div class="mt-4 p-3 rounded-xl bg-surface-secondary border border-border/50">
                                            <div class="flex items-start gap-2">
                                                <i data-lucide="info" class="w-3.5 h-3.5 text-text-muted mt-0.5 shrink-0"></i>
                                                <p class="text-xs text-text-secondary"><?php echo e($current->notes); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($history) > 0): ?>
                                    <div class="border-t border-border/30 px-6 py-4">
                                        <p class="text-[11px] font-bold text-text-muted mb-3">الجدول السابق</p>
                                        <div class="space-y-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <?php
                                                    $hDate = $h['schedule_date'] ?? '';
                                                    $hDay = '';
                                                    if ($hDate) {
                                                        try { $hDay = \Carbon\Carbon::parse($hDate)->locale('ar')->translatedFormat('l'); } catch (\Throwable $e) { $hDay = $hDate; }
                                                    }
                                                    $hSt = $h['status'] ?? 'available';
                                                    $hStInfo = $statusConfig[$hSt] ?? $statusConfig['available'];
                                                ?>
                                                <div class="flex items-center justify-between py-1.5">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-lg bg-surface-secondary flex flex-col items-center justify-center shrink-0">
                                                            <span class="text-[9px] font-bold text-text-muted leading-none"><?php echo e($hDay); ?></span>
                                                            <span class="text-sm font-black text-text leading-none mt-0.5"><?php echo e($hDate ? \Carbon\Carbon::parse($hDate)->format('d') : '—'); ?></span>
                                                        </div>
                                                        <div>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h['start_time'] ?? null): ?>
                                                                <span class="text-xs text-text-secondary">
                                                                    <?php echo e(\Carbon\Carbon::parse($h['start_time'])->format('h:i')); ?> — <?php echo e(\Carbon\Carbon::parse($h['end_time'])->format('h:i')); ?>

                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-xs text-text-muted">غير محدد</span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold <?php echo e($hStInfo['color']); ?>">
                                                        <?php echo e($hStInfo['label']); ?>

                                                    </span>
                                                </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <div class="px-6 py-8 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="calendar-x" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-text-secondary">لا يوجد جدول متاح لهذه المنطقة</p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="text-center pt-8 mt-12 border-t border-border/30">
                <p class="text-xs text-text-muted">جميع الحقوق محفوظة &copy; <?php echo e(date('Y')); ?> بلدية إذنا</p>
            </div>
        </div>
    </section>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/water-schedule/public-water-schedule.blade.php ENDPATH**/ ?>