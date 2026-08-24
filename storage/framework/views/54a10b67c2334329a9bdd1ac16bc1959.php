<div class="min-h-screen bg-background flex flex-col" dir="rtl">
    <div class="w-full max-w-[960px] mx-auto flex flex-col h-[calc(100vh-64px)] md:h-screen">
        
        <header class="shrink-0 bg-surface border-b border-border px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-border">
                    <img src="<?php echo e(asset('robot.png')); ?>" alt="المساعد الذكي" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-sm font-bold text-text">المساعد الذكي لبلدية إذنا</h1>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        <span class="text-[11px] text-text-tertiary">متاح الآن</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button type="button"
                        wire:click="resetContext"
                        class="p-2 rounded-lg text-text-secondary hover:text-primary hover:bg-primary-light transition-all cursor-pointer border-none"
                        title="تصفية المحادثة">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </button>
                <a href="<?php echo e(route('home')); ?>"
                   class="p-2 rounded-lg text-text-secondary hover:text-primary hover:bg-primary-light transition-all no-underline">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </header>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($disclaimer): ?>
            <div class="shrink-0 bg-warning-light/50 border-b border-warning/20 px-4 py-2">
                <p class="text-xs text-text-secondary text-center"><?php echo e($disclaimer); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex-1 overflow-y-auto px-4 py-4 scroll-smooth" x-ref="msgList">
            <div class="max-w-[760px] mx-auto space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chatEnabled): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($msg['role'] === 'user'): ?>
                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'chatbot-message-'.e($msg['id'] ?? $loop->index).''; ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'chatbot-message-'.e($msg['id'] ?? $loop->index).''; ?>wire:key="chatbot-message-<?php echo e($msg['id'] ?? $loop->index); ?>">
                            <?php echo $__env->make('components.chatbot.citizen-message', [
                                'content' => $msg['content'],
                                'time' => $msg['time'] ?? '',
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php else: ?>
                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'chatbot-message-'.e($msg['id'] ?? $loop->index).''; ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'chatbot-message-'.e($msg['id'] ?? $loop->index).''; ?>wire:key="chatbot-message-<?php echo e($msg['id'] ?? $loop->index); ?>">
                            <?php echo $__env->make('components.chatbot.bot-message', [
                                'content' => $msg['content'],
                                'type' => $msg['type'] ?? 'text',
                                'items' => $msg['items'] ?? [],
                                'actions' => $msg['actions'] ?? [],
                                'needs_clarification' => $msg['needs_clarification'] ?? false,
                                'clarification_type' => $msg['clarification_type'] ?? null,
                                'metadata' => $msg['metadata'] ?? [],
                                'workflow' => $msg['workflow'] ?? null,
                                'feedback_eligible' => $msg['feedback_eligible'] ?? false,
                                'time' => $msg['time'] ?? '',
                                'messageId' => $msg['id'] ?? null,
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php echo $__env->make('components.chatbot.welcome-state', [
                        'actions' => [
                            ['label' => 'الخدمات الإلكترونية', 'value' => 'الخدمات الإلكترونية'],
                            ['label' => 'تقديم شكوى', 'value' => 'تقديم شكوى'],
                            ['label' => 'طلب اتصال', 'value' => 'طلب اتصال'],
                            ['label' => 'متابعة طلب', 'value' => 'تتبع طلب'],
                            ['label' => 'جدول توزيع المياه', 'value' => 'جدول توزيع المياه'],
                            ['label' => 'المرافق العامة', 'value' => 'المرافق العامة'],
                            ['label' => 'الوظائف', 'value' => 'الوظائف'],
                            ['label' => 'أعضاء المجلس البلدي', 'value' => 'أعضاء المجلس البلدي'],
                            ['label' => 'قرارات المجلس', 'value' => 'قرارات المجلس'],
                            ['label' => 'تواصل مع البلدية', 'value' => 'تواصل مع البلدية'],
                        ],
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loading): ?>
                        <?php echo $__env->make('components.chatbot.typing-indicator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <?php echo $__env->make('components.chatbot.error-state', [
                        'message' => 'المساعد الذكي غير متاح حاليًا.',
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($validationError): ?>
            <div class="shrink-0 bg-danger-light border-t border-danger/30 px-4 py-2.5">
                <div class="max-w-[760px] mx-auto">
                    <p class="text-xs text-danger text-center"><?php echo e($validationError); ?></p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chatEnabled): ?>
            <div class="shrink-0 bg-surface border-t border-border px-4 py-3">
                <div class="max-w-[760px] mx-auto">
                    <?php echo $__env->make('components.chatbot.composer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('livewire:messagesent', () => {
        const el = document.querySelector('[x-ref="msgList"]');
        if (el) el.scrollTop = el.scrollHeight;
    });
    window.addEventListener('chatbot-message-added', () => {
        const el = document.querySelector('[x-ref="msgList"]');
        if (el) el.scrollTop = el.scrollHeight;
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/chatbot/chatbot-page.blade.php ENDPATH**/ ?>