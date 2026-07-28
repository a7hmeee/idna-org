<div>

    
    
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('public-page-carousel', [
        'pageKey' => 'engineering-offices',
        'pageTitle' => $office->office_name,
        'pageSubtitle' => $office->engineer_name ? 'المهندس: ' . $office->engineer_name : null,
        'pageBadge' => 'مكتب هندسي',
        'pageBadgeIcon' => 'hard-hat',
        'compact' => true,
    ]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3445834642-0', $__key);

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

    <section class="py-12 sm:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
                    <div class="p-6 space-y-4">
                        <h3 class="text-xs font-bold text-text-tertiary uppercase tracking-wider">معلومات المكتب</h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->license_number): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">رقم الترخيص</p>
                                <p class="text-sm font-bold text-text" dir="ltr"><?php echo e($office->license_number); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->engineer_name): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">المهندس المسؤول</p>
                                <p class="text-sm font-bold text-text"><?php echo e($office->engineer_name); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->address): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">العنوان</p>
                                <p class="text-sm text-text"><?php echo e($office->address); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="p-6 space-y-4">
                        <h3 class="text-xs font-bold text-text-tertiary uppercase tracking-wider">معلومات الاتصال</h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->phone): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">الهاتف</p>
                                <p class="text-sm font-bold text-text" dir="ltr"><?php echo e($office->phone); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->mobile): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">الجوال</p>
                                <p class="text-sm font-bold text-text" dir="ltr"><?php echo e($office->mobile); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->email): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">البريد الإلكتروني</p>
                                <p class="text-sm font-bold text-text dir-ltr"><?php echo e($office->email); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="p-6 space-y-4">
                        <h3 class="text-xs font-bold text-text-tertiary uppercase tracking-wider">حالة الاعتماد</h3>
                        <?php
                            $statusColors = match($office->approval_status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'suspended' => 'bg-red-100 text-red-700',
                                'expired' => 'bg-gray-100 text-gray-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                            $statusLabels = [
                                'approved' => 'معتمد',
                                'pending' => 'قيد الانتظار',
                                'suspended' => 'موقوف',
                                'expired' => 'منتهي الصلاحية',
                            ];
                        ?>
                        <div>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-lg <?php echo e($statusColors); ?>">
                                <?php echo e($statusLabels[$office->approval_status] ?? $office->approval_status); ?>

                            </span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->expires_at): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">تاريخ انتهاء الاعتماد</p>
                                <p class="text-sm font-bold text-text"><?php echo e($office->expires_at->format('Y-m-d')); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->approved_at): ?>
                            <div>
                                <p class="text-xs text-text-tertiary">تاريخ الاعتماد</p>
                                <p class="text-sm text-text"><?php echo e($office->approved_at->format('Y-m-d')); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->specializations && is_array($office->specializations) && count($office->specializations) > 0): ?>
                    <div class="border-t border-gray-100 px-6 py-5">
                        <h3 class="text-xs font-bold text-text-tertiary uppercase tracking-wider mb-3">التخصصات</h3>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $office->specializations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <span style="font-size:12px;font-weight:600;color:#0F6A3D;background:rgba(15,106,61,0.06);padding:4px 12px;border-radius:8px;"><?php echo e($spec); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->notes): ?>
                    <div class="border-t border-gray-100 px-6 py-5">
                        <h3 class="text-xs font-bold text-text-tertiary uppercase tracking-wider mb-2">ملاحظات</h3>
                        <p class="text-sm text-text-secondary"><?php echo e($office->notes); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

</div><?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/engineering-offices/public-engineering-office-show.blade.php ENDPATH**/ ?>