<?php
    $statusConfig = [
        'available' => ['label' => 'متوفرة', 'color' => 'bg-success-light text-success'],
        'low_pressure' => ['label' => 'ضغط منخفض', 'color' => 'bg-accent-light/30 text-accent-dark'],
        'maintenance' => ['label' => 'صيانة', 'color' => 'bg-warning-light text-warning'],
        'emergency' => ['label' => 'طارئ', 'color' => 'bg-danger-light text-danger'],
        'no_water' => ['label' => 'مقطوعة', 'color' => 'bg-surface-hover text-text-secondary'],
    ];
    $todaySchedules = collect($waterSchedule)->take(6);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($todaySchedules->isNotEmpty()): ?>
<section id="water-schedule" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary-light text-primary mb-3">
                    <i data-lucide="droplets" class="w-3.5 h-3.5"></i>
                    جدول توزيع المياه
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight"><?php echo e($sectionTitle ?? 'جدول توزيع المياه'); ?></h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sectionSubtitle): ?>
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed"><?php echo e($sectionSubtitle); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.water-schedule')): ?>
                <a href="<?php echo e(route('public.water-schedule')); ?>" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-dark transition-all shadow-sm whitespace-nowrap">
                    <span>عرض الجدول الكامل</span>
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="bg-gradient-to-br from-[#F4F5F1] to-white rounded-3xl border border-border/60 p-8 sm:p-10 shadow-lg">
            <div class="grid lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center">
                            <i data-lucide="calendar-clock" class="w-7 h-7 text-white"></i>
                        </div>
                        <div>
                            <p class="font-black text-text text-lg">جدول توزيع المياه</p>
                            <p class="text-xs text-text-muted"><?php echo e(now()->locale('ar')->translatedFormat('l d F Y')); ?></p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statusConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold <?php echo e($cfg['color']); ?>"><?php echo e($cfg['label']); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <div class="mt-6 p-4 rounded-2xl bg-accent-light/20 border border-accent/20">
                        <p class="text-xs text-accent-dark flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                            قد تتغير المواعيد وفق الظروف الفنية
                        </p>
                    </div>
                </div>

                
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl border border-border/50 overflow-hidden shadow-sm">
                        <div class="grid grid-cols-12 gap-0">
                            
                            <div class="col-span-12 grid grid-cols-12 gap-0 px-5 py-3 bg-[#F4F5F1] border-b border-border/50 text-[10px] font-bold text-text-muted">
                                <div class="col-span-5">المنطقة</div>
                                <div class="col-span-3 text-center">التوقيت</div>
                                <div class="col-span-2 text-center">الحالة</div>
                                <div class="col-span-2 text-left">ملاحظات</div>
                            </div>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $todaySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $areaName = $schedule['area']['name'] ?? 'منطقة غير محددة';
                                    $status = $schedule['status'] ?? '';
                                    $statusStr = is_object($status) ? ($status->value ?? $status) : (string) $status;
                                    $statusInfo = $statusConfig[$statusStr] ?? ['label' => $statusStr, 'color' => 'bg-surface-hover text-text-secondary'];
                                    $start = $schedule['start_time'] ?? '';
                                    $end = $schedule['end_time'] ?? '';
                                    $notes = $schedule['notes'] ?? '';
                                ?>
                                <div class="col-span-12 grid grid-cols-12 gap-0 px-5 py-4 border-b border-border/30 last:border-0 hover:bg-primary-light/20 transition-colors items-center">
                                    <div class="col-span-5 flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
                                        </div>
                                        <span class="text-sm font-bold text-text"><?php echo e($areaName); ?></span>
                                    </div>
                                    <div class="col-span-3 text-center">
                                        <span class="text-xs font-semibold text-text inline-flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3 text-primary"></i>
                                            <?php echo e($start); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($end): ?> - <?php echo e($end); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="col-span-2 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold whitespace-nowrap <?php echo e($statusInfo['color']); ?>"><?php echo e($statusInfo['label']); ?></span>
                                    </div>
                                    <div class="col-span-2 text-left">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notes): ?>
                                            <span class="text-[10px] text-text-muted"><?php echo e($notes); ?></span>
                                        <?php else: ?>
                                            <span class="text-[10px] text-text-muted">—</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/water-status.blade.php ENDPATH**/ ?>