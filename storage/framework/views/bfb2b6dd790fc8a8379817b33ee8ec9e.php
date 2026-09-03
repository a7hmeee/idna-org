<div>
     <?php $__env->slot('title', null, []); ?> المنصات الخارجية <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">المنصات الخارجية</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة البوابات والأنظمة والمنصات الخارجية</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createPlatform', App\Domains\Municipality\Models\Municipality::class)): ?>
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة منصة
        </button>
        <?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platforms->isEmpty()): ?>
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="globe" class="w-7 h-7 text-text-muted"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد منصات خارجية</p>
                <p class="text-xs text-text-tertiary mt-1">أضف منصة خارجية جديدة للبدء.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border bg-background/50">
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">المنصة</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">التصنيف</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الرابط</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الحالة</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">مميز</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'ext-platform-'.e($platform->id).''; ?>wire:key="ext-platform-<?php echo e($platform->id); ?>" class="border-b border-border last:border-0 hover:bg-background/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: <?php echo e($platform->color ?? '#E8F5E9'); ?>">
                                        <i data-lucide="<?php echo e($platform->icon); ?>" class="w-4 h-4" style="color: <?php echo e($platform->color ? '#fff' : '#2E7D32'); ?>"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-text"><?php echo e($platform->name); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platform->description): ?>
                                        <p class="text-[11px] text-text-tertiary"><?php echo e(Str::limit($platform->description, 50)); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platform->category): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-secondary text-[11px] font-semibold text-text-secondary"><?php echo e($categoryOptions[$platform->category] ?? $platform->category); ?></span>
                                <?php else: ?>
                                <span class="text-sm text-text-muted">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-secondary max-w-[200px] truncate">
                                <a href="<?php echo e($platform->url); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors inline-flex items-center gap-1">
                                    <?php echo e($platform->url); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platform->open_in_new_tab): ?><i data-lucide="external-link" class="w-3 h-3"></i><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platform->is_active): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-success-light text-[11px] font-semibold text-success">نشط</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-secondary text-[11px] font-semibold text-text-muted">غير نشط</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platform->is_featured): ?>
                                    <i data-lucide="star" class="w-4 h-4 text-warning"></i>
                                <?php else: ?>
                                    <span class="text-sm text-text-muted">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('updatePlatform', App\Domains\Municipality\Models\Municipality::class)): ?>
                                    <button wire:click="openEditModal(<?php echo e($platform->id); ?>)" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deletePlatform', App\Domains\Municipality\Models\Municipality::class)): ?>
                                    <button wire:click="confirmDelete(<?php echo e($platform->id); ?>)" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeForm"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text"><?php echo e($editingId ? 'تعديل المنصة' : 'إضافة منصة'); ?></h3>
                <button wire:click="closeForm" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="save" class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الاسم <span class="text-danger">*</span></label>
                        <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">التصنيف</label>
                        <select wire:model="category" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">— بدون تصنيف —</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categoryOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الأيقونة <span class="text-danger">*</span></label>
                        <input type="text" wire:model="icon" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">اللون</label>
                        <input type="text" wire:model="color" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الرابط <span class="text-danger">*</span></label>
                    <input type="url" wire:model="url" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                        <input type="number" wire:model="displayOrder" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all <?php $__errorArgs = ['displayOrder'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['displayOrder'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="space-y-3">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="openInNewTab" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                            <span class="text-sm text-text-secondary">فتح في نافذة جديدة</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isFeatured" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                            <span class="text-sm text-text-secondary">مميزة</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isActive" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                            <span class="text-sm text-text-secondary">نشط</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeForm" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove><?php echo e($editingId ? 'حفظ التعديلات' : 'إضافة'); ?></span>
                        <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف المنصة</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف المنصة الخارجية هذه؟</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="delete" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">نعم، حذف</button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/municipality/platforms.blade.php ENDPATH**/ ?>