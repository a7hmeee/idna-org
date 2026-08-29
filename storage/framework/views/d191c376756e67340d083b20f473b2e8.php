
<section class="bg-white border-y border-border/60" aria-labelledby="quick-actions-title">
    <div class="container-home py-10 sm:py-12">
        <h2 id="quick-actions-title" class="sr-only">إجراءات سريعة</h2>
        <nav aria-label="إجراءات سريعة للمواطنين">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.complaints.submit')): ?>
                    <a href="<?php echo e(route('public.complaints.submit')); ?>" wire:navigate
                       class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-surface px-4 py-6 text-center no-underline transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none">
                        <span class="w-14 h-14 rounded-xl bg-primary-light text-primary flex items-center justify-center transition-colors group-hover:bg-primary group-hover:text-white">
                            <i data-lucide="message-square-warning" class="w-7 h-7"></i>
                        </span>
                        <span class="text-[14px] font-bold text-text group-hover:text-primary transition-colors">شكوى جديدة</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.complaints.track')): ?>
                    <a href="<?php echo e(route('public.complaints.track')); ?>" wire:navigate
                       class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-surface px-4 py-6 text-center no-underline transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none">
                        <span class="w-14 h-14 rounded-xl bg-primary-light text-primary flex items-center justify-center transition-colors group-hover:bg-primary group-hover:text-white">
                            <i data-lucide="search" class="w-7 h-7"></i>
                        </span>
                        <span class="text-[14px] font-bold text-text group-hover:text-primary transition-colors">تتبع شكوى</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.water-schedule')): ?>
                    <a href="<?php echo e(route('public.water-schedule')); ?>" wire:navigate
                       class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-surface px-4 py-6 text-center no-underline transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none">
                        <span class="w-14 h-14 rounded-xl bg-primary-light text-primary flex items-center justify-center transition-colors group-hover:bg-primary group-hover:text-white">
                            <i data-lucide="droplets" class="w-7 h-7"></i>
                        </span>
                        <span class="text-[14px] font-bold text-text group-hover:text-primary transition-colors">جدول المياه</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.services.index')): ?>
                    <a href="<?php echo e(route('public.services.index')); ?>" wire:navigate
                       class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-surface px-4 py-6 text-center no-underline transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none">
                        <span class="w-14 h-14 rounded-xl bg-primary-light text-primary flex items-center justify-center transition-colors group-hover:bg-primary group-hover:text-white">
                            <i data-lucide="laptop" class="w-7 h-7"></i>
                        </span>
                        <span class="text-[14px] font-bold text-text group-hover:text-primary transition-colors">الخدمات الإلكترونية</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.jobs.index')): ?>
                    <a href="<?php echo e(route('public.jobs.index')); ?>" wire:navigate
                       class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-surface px-4 py-6 text-center no-underline transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none">
                        <span class="w-14 h-14 rounded-xl bg-primary-light text-primary flex items-center justify-center transition-colors group-hover:bg-primary group-hover:text-white">
                            <i data-lucide="briefcase" class="w-7 h-7"></i>
                        </span>
                        <span class="text-[14px] font-bold text-text group-hover:text-primary transition-colors">الوظائف</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('public.tenders.index')): ?>
                    <a href="<?php echo e(route('public.tenders.index')); ?>" wire:navigate
                       class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-surface px-4 py-6 text-center no-underline transition-all duration-200 hover:border-primary/30 hover:shadow-md hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none">
                        <span class="w-14 h-14 rounded-xl bg-accent-light/40 text-accent-dark flex items-center justify-center transition-colors group-hover:bg-accent group-hover:text-white">
                            <i data-lucide="scroll-text" class="w-7 h-7"></i>
                        </span>
                        <span class="text-[14px] font-bold text-text group-hover:text-primary transition-colors">المناقصات</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </nav>
    </div>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/quick-actions.blade.php ENDPATH**/ ?>