<section id="jobs-offices" class="section-py bg-surface-secondary overflow-hidden">
    <div class="container-home">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary-light text-primary mb-3">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    الوظائف والمكاتب الهندسية
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight"><?php echo e($sectionTitle ?? 'فرص عمل ومكاتب معتمدة'); ?></h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sectionSubtitle): ?>
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed"><?php echo e($sectionSubtitle); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            
            <div class="rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-card">
                <div class="flex items-center justify-between gap-4 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-light flex items-center justify-center">
                            <i data-lucide="briefcase" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-text">أحدث الوظائف</h3>
                            <p class="text-[11px] text-text-secondary">فرص عمل متاحة في البلدية</p>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.jobs.index')): ?>
                        <a href="<?php echo e(route('public.jobs.index')); ?>" wire:navigate class="text-xs font-bold text-primary hover:text-primary-dark transition-colors flex items-center gap-1 no-underline">
                            <span>عرض الكل</span>
                            <i data-lucide="arrow-left" class="w-3 h-3"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php $jobSlice = collect($latestJobs)->take(3); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jobSlice->isNotEmpty()): ?>
                    <div class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jobSlice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $closingAt = $job['closing_at'] ?? null;
                                $isOpen = $closingAt && \Carbon\Carbon::parse($closingAt)->isFuture();
                                $employmentType = $job['employment_type'] ?? '';
                                $typeLabels = ['full_time' => 'دوام كامل', 'part_time' => 'دوام جزئي', 'contract' => 'عقد', 'temporary' => 'مؤقت', 'freelance' => 'حر'];
                                $typeLabel = $typeLabels[$employmentType] ?? $employmentType;
                                $jobSlug = $job['slug'] ?? $job['id'] ?? null;
                                $jobUrl = $jobSlug && Route::has('public.jobs.show') ? route('public.jobs.show', ['job' => $jobSlug]) : '#';
                            ?>
                            <a href="<?php echo e($jobUrl); ?>" <?php if($jobUrl !== '#'): ?> wire:navigate <?php endif; ?>
                               class="flex items-center gap-3 bg-surface-secondary rounded-xl p-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 no-underline group">
                                <div class="w-9 h-9 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0 group-hover:bg-primary group-hover:text-white transition-all">
                                    <i data-lucide="briefcase" class="w-4 h-4 text-primary group-hover:text-white transition-colors"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="text-sm font-bold text-text group-hover:text-primary transition-colors"><?php echo e($job['title'] ?? ''); ?></h4>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold whitespace-nowrap <?php echo e($isOpen ? 'bg-success-light text-success' : 'bg-danger-light text-danger'); ?>">
                                            <?php echo e($isOpen ? 'متاحة' : 'مغلقة'); ?>

                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($typeLabel): ?>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-surface-secondary text-text-muted"><?php echo e($typeLabel); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($closingAt): ?>
                                            <span class="text-[10px] text-text-muted flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                <?php echo e(\Carbon\Carbon::parse($closingAt)->format('Y-m-d')); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <i data-lucide="chevron-left" class="w-4 h-4 text-text-muted group-hover:text-primary flex-shrink-0"></i>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-xl bg-surface-secondary p-6 text-center">
                        <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center mx-auto mb-2">
                            <i data-lucide="briefcase" class="w-5 h-5 text-primary/50"></i>
                        </div>
                        <p class="text-sm font-bold text-text-secondary">لا توجد وظائف شاغرة حالياً</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-card">
                <?php $officeSlice = collect($engineeringOffices)->take(3); ?>
                <div class="flex items-center justify-between gap-4 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-light flex items-center justify-center">
                            <i data-lucide="hard-hat" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-text">مكاتب هندسية</h3>
                            <p class="text-[11px] text-text-secondary">مكاتب معتمدة لدى البلدية</p>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.departments.index')): ?>
                        <a href="<?php echo e(route('public.departments.index')); ?>" wire:navigate class="text-xs font-bold text-primary hover:text-primary-dark transition-colors flex items-center gap-1 no-underline">
                            <span>عرض الكل</span>
                            <i data-lucide="arrow-left" class="w-3 h-3"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($officeSlice->isNotEmpty()): ?>
                    <div class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $officeSlice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex items-center gap-3 bg-surface-secondary rounded-xl p-3 hover:shadow-md transition-all duration-200">
                                <div class="w-9 h-9 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="helmet" class="w-4 h-4 text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-text"><?php echo e($office['office_name'] ?? ''); ?></h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($office['engineer_name'])): ?>
                                            <span class="text-[11px] text-text-secondary"><?php echo e($office['engineer_name']); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($office['license_number'])): ?>
                                            <span class="text-[10px] text-text-muted">#<?php echo e($office['license_number']); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($office['phone']) || !empty($office['mobile'])): ?>
                                    <a href="tel:<?php echo e($office['phone'] ?? $office['mobile']); ?>" class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0 hover:bg-primary hover:text-white transition-all">
                                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-xl bg-surface-secondary p-6 text-center">
                        <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center mx-auto mb-2">
                            <i data-lucide="hard-hat" class="w-5 h-5 text-primary/50"></i>
                        </div>
                        <p class="text-sm font-bold text-text-secondary">لا توجد مكاتب هندسية بعد</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/jobs.blade.php ENDPATH**/ ?>