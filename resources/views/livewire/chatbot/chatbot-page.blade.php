<div class="min-h-screen bg-background flex flex-col" dir="rtl">
    <div class="w-full max-w-[960px] mx-auto flex flex-col h-[calc(100vh-64px)] md:h-screen">
        {{-- Header --}}
        <header class="shrink-0 bg-surface border-b border-border px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-border">
                    <img src="{{ asset('robot.png') }}" alt="المساعد الذكي" class="w-full h-full object-cover">
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
                        title="تصفية المحادثة" aria-label="تصفية المحادثة">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </button>
                <a href="{{ route('home') }}" aria-label="العودة إلى الرئيسية"
                   class="p-2 rounded-lg text-text-secondary hover:text-primary hover:bg-primary-light transition-all no-underline">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </header>

        {{-- Disclaimer --}}
        @if ($disclaimer)
            <div class="shrink-0 bg-warning-light/50 border-b border-warning/20 px-4 py-2">
                <p class="text-xs text-text-secondary text-center">{{ $disclaimer }}</p>
            </div>
        @endif

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 scroll-smooth" x-ref="msgList">
            <div class="max-w-[760px] mx-auto space-y-3">
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
                        @include('components.chatbot.typing-indicator')
                    @endif
                @else
                    @include('components.chatbot.error-state', [
                        'message' => 'المساعد الذكي غير متاح حاليًا.',
                    ])
                @endif
            </div>
        </div>

        {{-- Validation Error --}}
        @if ($validationError)
            <div class="shrink-0 bg-danger-light border-t border-danger/30 px-4 py-2.5">
                <div class="max-w-[760px] mx-auto">
                    <p class="text-xs text-danger text-center">{{ $validationError }}</p>
                </div>
            </div>
        @endif

        {{-- Composer --}}
        @if ($chatEnabled)
            <div class="shrink-0 bg-surface border-t border-border px-4 py-3">
                <div class="max-w-[760px] mx-auto">
                    @include('components.chatbot.composer')
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
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
@endpush
