<div class="shrink-0 bg-surface border-t border-border p-3" dir="rtl">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="bg-danger-light border border-danger/30 rounded-lg px-3 py-2 mb-2">
            <p class="text-xs text-danger"><?php echo e($message); ?></p>
        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form wire:submit.prevent="sendMessage" class="flex gap-2 items-end">
        <div class="flex-1 relative">
            <textarea wire:model="message"
                      placeholder="اكتب سؤالك هنا..."
                      maxlength="<?php echo e(config('chatbot.max_message_length', 500)); ?>"
                      rows="1"
                      x-data
                      x-init="
                          $el.style.height = 'auto';
                          $el.style.height = Math.min($el.scrollHeight, 120) + 'px';
                          $watch('message', value => {
                              $nextTick(() => {
                                  $el.style.height = 'auto';
                                  $el.style.height = Math.min($el.scrollHeight, 120) + 'px';
                              });
                          })
                      "
                      <?php if($loading || !$chatEnabled): ?> disabled <?php endif; ?>
                      @keydown.enter.prevent="if(!event.shiftKey){$wire.sendMessage()}"
                      class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm text-text placeholder-text-tertiary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none disabled:opacity-50 disabled:cursor-not-allowed"
                      style="min-height:44px;max-height:120px;overflow-y:auto;"
            ></textarea>
        </div>
        <button type="submit"
                <?php if($loading || empty(trim($message ?? '')) || !$chatEnabled): ?> disabled <?php endif; ?>
                class="w-11 h-11 flex items-center justify-center bg-primary hover:bg-primary-dark text-white rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer border-none shadow-sm shrink-0"
                aria-label="إرسال">
            <i data-lucide="send" class="w-4 h-4 <?php echo e($loading ? 'opacity-50' : ''); ?>"></i>
        </button>
    </form>
    <p class="text-[10px] text-text-tertiary mt-1.5 text-center">
        المساعد الذكي يقدم معلومات عامة ولا يعتبر وثيقة رسمية
    </p>
</div>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/components/chatbot/composer.blade.php ENDPATH**/ ?>