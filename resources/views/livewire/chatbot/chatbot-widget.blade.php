<div class="fixed bottom-5 left-4 z-[70] flex flex-col items-end gap-3 animate-chatbot-rise"
     x-data="{ open: false, showWelcome: true, toggleChat() { this.open = !this.open; if (this.open) { this.showWelcome = false; } else { this.showWelcome = true; } }, openChat() { this.open = true; this.showWelcome = false; }, closeChat() { this.open = false; this.showWelcome = true; } }"
     @keydown.escape.window="if(open) closeChat()">

    {{-- Chat Panel --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-8 opacity-0 scale-95"
         x-transition:enter-end="translate-y-0 opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100 scale-100"
         x-transition:leave-end="translate-y-8 opacity-0 scale-95"
         class="flex flex-col bg-surface rounded-3xl shadow-floating border border-border overflow-hidden
                max-md:fixed max-md:inset-x-3 max-md:inset-y-3 max-md:rounded-3xl
                md:w-[380px] md:h-[580px] md:max-h-[calc(100vh-120px)]"
         dir="rtl">

        {{-- Header --}}
        <header class="shrink-0 text-white px-4 py-3 flex items-center justify-between" style="background:linear-gradient(120deg,#0E4A2E 0%,#0B3A24 100%);">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 shrink-0">
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-7 h-7 rounded-full" style="background:rgba(0,0,0,0.35);" aria-hidden="true"></span>
                    <img src="{{ App\Domains\SharedKernel\Services\MediaResolver::chatbotAvatarUrl() }}"
                         alt="مساعد بلدية إذنا"
                         class="relative w-9 h-9 object-contain drop-shadow-[0_2px_6px_rgba(0,0,0,0.35)]">
                </div>
                <div>
                    <h2 class="text-sm font-bold text-white">المساعد الذكي</h2>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#6BAA3B;"></span>
                        <span class="text-[11px] text-white/70">متاح الآن</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button type="button"
                        wire:click="resetContext"
                        class="p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all cursor-pointer border-none"
                        title="تصفية المحادثة">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </button>
                <button type="button"
                        @click="closeChat()"
                        class="p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all cursor-pointer border-none"
                        title="إغلاق">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </header>

        {{-- Messages --}}
        <div role="log" aria-live="polite" class="flex-1 overflow-y-auto px-3 py-3 space-y-2.5 scroll-smooth" x-ref="msgList">
            @if ($chatEnabled)
                @forelse ($messages as $msg)
                    @if ($msg['role'] === 'user')
                        <div wire:key="chatbot-message-{{ $msg['id'] ?? $loop->index }}">
                            @include('components.chatbot.citizen-message', [
                                'content' => $msg['content'],
                                'time' => $msg['time'] ?? '',
                            ])
                        </div>
                    @else
                        <div wire:key="chatbot-message-{{ $msg['id'] ?? $loop->index }}">
                            @include('components.chatbot.bot-message', [
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
                            ])
                        </div>
                    @endif
                @empty
                    @include('components.chatbot.welcome-state', [
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
                    ])
                @endforelse

                {{-- Loading --}}
                @if ($loading)
                    <div class="flex justify-start gap-2">
                        <div class="relative w-8 h-8 shrink-0">
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full" style="background:rgba(0,0,0,0.35);" aria-hidden="true"></span>
                            <img src="{{ App\Domains\SharedKernel\Services\MediaResolver::chatbotAvatarUrl() }}" alt="" class="relative w-8 h-8 object-contain drop-shadow-[0_2px_6px_rgba(0,0,0,0.35)]">
                        </div>
                        <div class="bg-white/10 text-white/80 rounded-2xl rounded-br-sm px-4 py-2.5 text-sm">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-white/60 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                                <span class="w-1.5 h-1.5 bg-white/60 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                                <span class="w-1.5 h-1.5 bg-white/60 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                            </span>
                        </div>
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <p class="text-sm text-text-secondary">المساعد الذكي غير متاح حاليًا</p>
                </div>
            @endif
        </div>

        {{-- Validation Error --}}
        @if ($validationError)
            <div class="shrink-0 bg-red-500/10 border-t border-red-400/30 px-4 py-2">
                <p class="text-xs text-red-300 text-center">{{ $validationError }}</p>
            </div>
        @endif

        {{-- Composer --}}
        @if ($chatEnabled)
            <div class="shrink-0 bg-white border-t border-gray-200 p-2.5" dir="rtl">
                <form wire:submit.prevent="sendMessage" class="flex gap-2 items-end">
                    <div class="flex-1">
                        <textarea wire:model="message"
                                  placeholder="اكتب سؤالك هنا..."
                                  aria-label="اكتب رسالتك"
                                  maxlength="{{ config('chatbot.max_message_length', 500) }}"
                                  rows="1"
                                  @if($loading) disabled @endif
                                  @keydown.enter.prevent="if(!event.shiftKey){$wire.sendMessage()}"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-text placeholder-text-tertiary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all resize-none disabled:opacity-50 disabled:cursor-not-allowed"
                                  style="min-height:40px;max-height:100px;"
                        ></textarea>
                    </div>
                    <button type="submit"
                            @if($loading || empty(trim($message ?? ''))) disabled @endif
                            class="w-10 h-10 flex items-center justify-center bg-primary hover:bg-primary-dark text-white rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer border-none shadow-sm shrink-0"
                            aria-label="إرسال">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Welcome Bubble --}}
    <div x-show="!open && showWelcome"
         x-cloak
         @click="openChat()"
         class="relative bg-white border border-border rounded-[20px] shadow-floating px-4 py-3 w-[300px] max-w-[calc(100vw-2.5rem)] cursor-pointer select-none animate-chatbot-bubble transition-shadow duration-300 hover:shadow-[0_18px_50px_rgba(12,42,24,0.22)]"
         dir="rtl"
         role="button"
         tabindex="0"
         @keydown.enter.prevent="openChat()"
         @keydown.space.prevent="openChat()">
        <p class="text-sm font-bold text-text leading-snug">أنا إذناوي 👋</p>
        <p class="text-xs font-bold text-primary mt-0.5">مساعدك الذكي من بلدية إذنا</p>
        <p class="text-[11px] text-text-secondary mt-1.5 leading-snug">شو حاب تعرف؟ أنا جاهز أساعدك!</p>
        <span class="absolute -bottom-[7px] left-10 w-3.5 h-3.5 rotate-45 bg-white border-b border-l border-border rounded-[2px]"
              aria-hidden="true"></span>
    </div>

    {{-- Floating Trigger Button --}}
    <button type="button"
            @click="toggleChat()"
            :aria-expanded="open.toString()"
            class="relative w-[clamp(96px,12vw,140px)] h-[clamp(104px,13vw,150px)] shrink-0 cursor-pointer border-none bg-transparent p-0 transition-transform duration-300 hover:scale-105 active:scale-95"
            aria-label="إذناوي — مساعد بلدية إذنا الرقمي">
        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[clamp(64px,8vw,94px)] h-[clamp(64px,8vw,94px)] rounded-full"
              style="background:radial-gradient(circle at 35% 30%, #0E4A2E, #031F10);box-shadow:0 0 0 6px rgba(200,168,90,0.18),0 14px 32px rgba(3,31,16,0.5);"
              aria-hidden="true"></span>
        <img src="{{ App\Domains\SharedKernel\Services\MediaResolver::chatbotAvatarUrl() }}"
             alt="إذناوي - مساعد بلدية إذنا الرقمي"
             class="relative w-full h-full object-contain drop-shadow-[0_12px_20px_rgba(0,0,0,0.35)] animate-chatbot-float"
             width="140"
             height="150">
        <span class="absolute top-1.5 right-1.5 w-[clamp(12px,1.6vw,16px)] h-[clamp(12px,1.6vw,16px)] rounded-full border-[3px] border-background animate-chatbot-status"
              style="background:#C8A85A;"
              aria-hidden="true"></span>
    </button>
</div>
