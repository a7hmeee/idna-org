<div class="shrink-0 bg-surface border-t border-border p-3" dir="rtl">
    @error('message')
        <div class="bg-danger-light border border-danger/30 rounded-lg px-3 py-2 mb-2">
            <p class="text-xs text-danger">{{ $message }}</p>
        </div>
    @enderror

    <form wire:submit.prevent="sendMessage" class="flex gap-2 items-end">
        <div class="flex-1 relative">
            <textarea wire:model="message"
                      placeholder="اكتب سؤالك هنا..."
                      maxlength="{{ config('chatbot.max_message_length', 500) }}"
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
                      @if($loading || !$chatEnabled) disabled @endif
                      @keydown.enter.prevent="if(!event.shiftKey){$wire.sendMessage()}"
                      class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm text-text placeholder-text-tertiary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none disabled:opacity-50 disabled:cursor-not-allowed"
                      style="min-height:44px;max-height:120px;overflow-y:auto;"
            ></textarea>
        </div>
        <button type="submit"
                @if($loading || empty(trim($message ?? '')) || !$chatEnabled) disabled @endif
                class="w-11 h-11 flex items-center justify-center bg-primary hover:bg-primary-dark text-white rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer border-none shadow-sm shrink-0"
                aria-label="إرسال">
            <i data-lucide="send" class="w-4 h-4 {{ $loading ? 'opacity-50' : '' }}"></i>
        </button>
    </form>
    <p class="text-[10px] text-text-tertiary mt-1.5 text-center">
        المساعد الذكي يقدم معلومات عامة ولا يعتبر وثيقة رسمية
    </p>
</div>
