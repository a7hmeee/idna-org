<?php
    $stats = [
        ['label' => 'شرائح البنر النشطة', 'value' => $slidesCount, 'total' => $totalSlides, 'icon' => 'images', 'color' => 'primary'],
        ['label' => 'الروابط السريعة النشطة', 'value' => $quickLinksCount, 'total' => $totalQuickLinks, 'icon' => 'link', 'color' => 'success'],
        ['label' => 'الإحصائيات النشطة', 'value' => $statisticsCount, 'total' => $totalStatistics, 'icon' => 'bar-chart-3', 'color' => 'warning'],
        ['label' => 'الأقسام الممكّنة', 'value' => $enabledSectionsCount, 'total' => $totalSections, 'icon' => 'layers', 'color' => 'info'],
    ];
?>

<div>
     <?php $__env->slot('title', null, []); ?> لوحة الصفحة الرئيسية <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">لوحة الصفحة الرئيسية</h1>
            <p class="text-sm text-text-tertiary mt-1">نظرة عامة على إعدادات الصفحة الرئيسية</p>
        </div>
        <a href="<?php echo e(route('home')); ?>" target="_blank" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2">
            <i data-lucide="external-link" class="w-4 h-4"></i>
            <span>عرض الصفحة</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="bg-surface rounded-xl border border-border p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-<?php echo e($stat['color']); ?>/10 flex items-center justify-center">
                        <i data-lucide="<?php echo e($stat['icon']); ?>" class="w-5 h-5 text-<?php echo e($stat['color']); ?>"></i>
                    </div>
                    <span class="text-xs text-text-tertiary">من أصل <?php echo e($stat['total']); ?></span>
                </div>
                <p class="text-2xl font-bold text-text"><?php echo e($stat['value']); ?></p>
                <p class="text-sm text-text-tertiary mt-1"><?php echo e($stat['label']); ?></p>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="font-semibold text-text mb-4">روابط سريعة</h3>
            <div class="space-y-3">
                <a href="<?php echo e(route('dashboard.homepage.settings')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">إعدادات الصفحة</p>
                        <p class="text-xs text-text-tertiary">تعديل عنوان الموقع، رابط البورتال، رسالة رئيس البلدية</p>
                    </div>
                </a>
                <a href="<?php echo e(route('dashboard.homepage.slides')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="images" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">شرائح البنر</p>
                        <p class="text-xs text-text-tertiary">إدارة الشرائح المعروضة في الواجهة الرئيسية</p>
                    </div>
                </a>
                <a href="<?php echo e(route('dashboard.homepage.sections')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="layers" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">أقسام الصفحة</p>
                        <p class="text-xs text-text-tertiary">إظهار، إخفاء، وترتيب أقسام الصفحة الرئيسية</p>
                    </div>
                </a>
                <a href="<?php echo e(route('dashboard.homepage.quick-links')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="link" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">الروابط السريعة</p>
                        <p class="text-xs text-text-tertiary">إدارة روابط الوصول السريع في الصفحة الرئيسية</p>
                    </div>
                </a>
                <a href="<?php echo e(route('dashboard.homepage.statistics')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-municipal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">الإحصائيات</p>
                        <p class="text-xs text-text-tertiary">إدارة الأرقام والإحصائيات المعروضة</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-border p-6">
            <h3 class="font-semibold text-text mb-4">معاينة سريعة</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-text-tertiary mb-1">عنوان الموقع</p>
                    <p class="text-sm font-semibold text-text"><?php echo e($settings->site_title ?? '—'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-text-tertiary mb-1">رابط البورتال</p>
                    <p class="text-sm text-primary truncate"><?php echo e($settings->portal_url ?? '—'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-text-tertiary mb-1">عنوان الترحيب</p>
                    <p class="text-sm text-text"><?php echo e($settings->welcome_title ?? '—'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-text-tertiary mb-1">آخر تحديث</p>
                    <p class="text-sm text-text"><?php echo e($settings->updated_at?->diffForHumans() ?? '—'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/dashboard.blade.php ENDPATH**/ ?>