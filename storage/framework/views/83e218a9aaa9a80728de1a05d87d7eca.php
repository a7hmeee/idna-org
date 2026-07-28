<div>
     <?php $__env->slot('title', null, []); ?> إدارة كاروسيل الصفحات <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إدارة كاروسيل الصفحات</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة جميع الشرائح المعروضة في صفحات الموقع الداخلية</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createSlide', \App\Domains\Homepage\Models\HomepageSetting::class)): ?>
            <a href="<?php echo e(route('dashboard.page-carousels.create')); ?>"
               class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>إضافة شريحة</span>
            </a>
        <?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('debug')): ?>
        <div class="mb-4 rounded-xl bg-warning-light border border-warning/20 px-4 py-3">
            <span class="text-sm text-warning font-medium"><?php echo e(session('debug')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                <div class="flex-1 w-full">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث..." class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="status" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">الكل</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
                <span class="text-xs text-text-tertiary bg-surface-secondary px-3 py-1.5 rounded-lg whitespace-nowrap">
                    <span class="font-semibold"><?php echo e($slides->total()); ?></span> شريحة
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الصورة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">العنوان</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الصفحة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الجدولة</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors">
                            <td class="px-4 py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slide->image_url): ?>
                                    <img src="<?php echo e($slide->image_url); ?>" alt="<?php echo e($slide->title); ?>" class="w-16 h-10 rounded-lg object-cover border border-border">
                                <?php else: ?>
                                    <div class="w-16 h-10 rounded-lg bg-surface-secondary border border-border flex items-center justify-center">
                                        <i data-lucide="image-off" class="w-4 h-4 text-text-tertiary"></i>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text"><?php echo e($slide->title); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slide->description): ?>
                                    <p class="text-xs text-text-tertiary mt-0.5 line-clamp-1"><?php echo e($slide->description); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slide->page_key): ?>
                                    <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium whitespace-nowrap"><?php echo e($slide->page_label); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary"><?php echo e($slide->sort_order); ?></span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('updateSlide', \App\Domains\Homepage\Models\HomepageSetting::class)): ?>
                                    <button wire:click="toggle(<?php echo e($slide->id); ?>)" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors <?php if($slide->is_active): ?> bg-success/10 text-success hover:bg-success/20 <?php else: ?> bg-danger/10 text-danger hover:bg-danger/20 <?php endif; ?>">
                                        <i data-lucide="<?php echo e($slide->is_active ? 'eye' : 'eye-off'); ?>" class="w-3 h-3"></i>
                                        <?php echo e($slide->is_active ? 'نشط' : 'غير نشط'); ?>

                                    </button>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold <?php if($slide->is_active): ?> bg-success/10 text-success <?php else: ?> bg-danger/10 text-danger <?php endif; ?>">
                                        <?php echo e($slide->is_active ? 'نشط' : 'غير نشط'); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php
                                    $now = now();
                                    $starts = $slide->starts_at;
                                    $ends = $slide->ends_at;
                                    $isScheduled = $starts && $starts->isFuture();
                                    $isExpired = $ends && $ends->isPast();
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isScheduled): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-warning/10 text-warning">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        مجدول
                                    </span>
                                <?php elseif($isExpired): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-danger/10 text-danger">
                                        <i data-lucide="calendar-x" class="w-3 h-3"></i>
                                        منتهي
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-text-tertiary">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('updateSlide', \App\Domains\Homepage\Models\HomepageSetting::class)): ?>
                                        <a href="<?php echo e(route('dashboard.page-carousels.edit', $slide->id)); ?>" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" wire:navigate>
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deleteSlide', \App\Domains\Homepage\Models\HomepageSetting::class)): ?>
                                        <button wire:click="confirmDelete(<?php echo e($slide->id); ?>)" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="images" class="w-8 h-8 text-text-tertiary"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد شرائح بعد</p>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createSlide', \App\Domains\Homepage\Models\HomepageSetting::class)): ?>
                                        <a href="<?php echo e(route('dashboard.page-carousels.create')); ?>"
                                           class="text-sm text-primary font-semibold hover:underline" wire:navigate>إضافة شريحة جديدة</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slides->hasPages()): ?>
            <div class="p-4 border-t border-border">
                <?php echo e($slides->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-sm mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-danger/10 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-danger"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تأكيد الحذف</h3>
                        <p class="text-xs text-text-tertiary">هذا الإجراء لا يمكن التراجع عنه</p>
                    </div>
                </div>
                <p class="text-sm text-text-secondary mb-6">هل أنت متأكد من حذف هذه الشريحة؟</p>
                <div class="flex items-center justify-end gap-3">
                    <button wire:click="closeDeleteModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="delete" class="px-4 py-2 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>تأكيد الحذف</span>
                        <span wire:loading>جاري الحذف...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/page-carousels/page-carousels-index.blade.php ENDPATH**/ ?>