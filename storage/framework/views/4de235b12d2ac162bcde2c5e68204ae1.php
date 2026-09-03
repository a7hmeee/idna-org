<div>
    <form wire:submit="submit" class="space-y-5">
        <?php echo csrf_field(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
            <div class="rounded-xl bg-danger-light border border-danger/20 px-4 py-3 flex items-start gap-3" role="alert">
                <i data-lucide="alert-circle" class="w-5 h-5 text-danger mt-0.5 shrink-0"></i>
                <span class="text-sm text-danger font-medium"><?php echo e($errorMessage); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div>
            <label for="email" class="label">البريد الإلكتروني</label>
            <div class="relative">
                <input
                    wire:model="email"
                    id="email"
                    type="email"
                    autocomplete="email"
                    autofocus
                    class="input ps-11 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="admin@idhna.ps"
                />
                <i data-lucide="mail" class="absolute start-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-text-muted pointer-events-none"></i>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1.5 text-xs text-danger flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div>
            <label for="password" class="label">كلمة المرور</label>
            <div class="relative">
                <input
                    wire:model="password"
                    id="password"
                    type="password"
                    autocomplete="current-password"
                    class="input ps-11 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="••••••••"
                />
                <i data-lucide="lock" class="absolute start-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-text-muted pointer-events-none"></i>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1.5 text-xs text-danger flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2.5 cursor-pointer group">
                <input
                    wire:model="remember"
                    type="checkbox"
                    class="w-4 h-4 rounded border-border text-primary focus:ring-primary/30 cursor-pointer transition-colors"
                />
                <span class="text-sm text-text-secondary group-hover:text-text transition-colors">تذكرني</span>
            </label>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                <a
                    href="<?php echo e(route('password.request')); ?>"
                    class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors"
                    wire:navigate
                >
                    نسيت كلمة المرور؟
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <button
            type="submit"
            class="btn-primary w-full py-3 text-base"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove class="flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                تسجيل الدخول
            </span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <div class="loading-spinner"></div>
                جاري التحقق...
            </span>
        </button>
    </form>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/auth/login.blade.php ENDPATH**/ ?>