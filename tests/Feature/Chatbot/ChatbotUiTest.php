<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\ChatbotAnalytics\Events\FeedbackSubmittedEvent;
use App\Livewire\Chatbot\ChatbotPage;
use App\Livewire\Chatbot\ChatbotWidget;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// =============================================
// Component Mount Tests
// =============================================

it('chatbot page component loads with default state', function (): void {
    Livewire::test(ChatbotPage::class)
        ->assertOk()
        ->assertSet('chatEnabled', true)
        ->assertSee('المساعد الذكي');
});

it('chatbot page loads in home layout via route', function (): void {
    $this->get(route('public.chatbot'))->assertOk()->assertSee('المساعد الذكي');
});

it('chatbot widget loads with default state', function (): void {
    Livewire::test(ChatbotWidget::class)
        ->assertSet('widgetOpen', false)
        ->assertSet('chatEnabled', true)
        ->assertSee('المساعد الذكي');
});

// =============================================
// Message Sending Tests
// =============================================

it('rejects empty message via Livewire page', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', '')
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'required']);
});

it('rejects excessive message length via Livewire page', function (): void {
    $long = str_repeat('x', config('chatbot.max_message_length', 500) + 1);
    Livewire::test(ChatbotPage::class)
        ->set('message', $long)
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'max']);
});

it('sends greeting and receives response via Livewire page', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSet('message', '')
        ->assertSet('loading', false)
        ->assertHasNoErrors()
        ->assertSee('مرحباً بك في المساعد الذكي لبلدية إذنا');
});

it('sends greeting and receives response via Livewire widget', function (): void {
    Livewire::test(ChatbotWidget::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSet('message', '')
        ->assertSet('loading', false)
        ->assertHasNoErrors()
        ->assertSee('مرحباً بك في المساعد الذكي لبلدية إذنا');
});

it('prevents duplicate submission while loading', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->set('loading', true)
        ->call('sendMessage')
        ->assertSet('loading', true);
});

// =============================================
// Quick Actions Tests
// =============================================

it('quick action triggers backend flow', function (): void {
    Livewire::test(ChatbotPage::class)
        ->call('quickAction', 'الخدمات الإلكترونية')
        ->assertSet('message', '')
        ->assertSee('خدمات');
});

// =============================================
// History Loading Tests
// =============================================

it('new session shows welcome message', function (): void {
    $unique = (string) Str::uuid();
    $component = Livewire::test(ChatbotPage::class)
        ->set('sessionId', $unique);

    $component->assertSee('مرحباً بك');
});

it('shows disclaimer when configured', function (): void {
    config()->set('chatbot.public_disclaimer', 'اختبار الإخلاء');
    Livewire::test(ChatbotPage::class)
        ->assertSee('اختبار الإخلاء');
});

// =============================================
// Workflow UI Tests (container-bound fake action)
// =============================================

it('renders workflow question UI for complaint workflow', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function () {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(
                    message: 'ما تصنيف الشكوى؟',
                    type: 'workflow_question',
                    actions: [
                        ['label' => 'المياه', 'value' => 'المياه'],
                    ],
                    metadata: [
                        'workflow_type' => 'complaint',
                        'current_step' => 'category',
                        'total_steps' => 5,
                        'completed_steps' => 0,
                        'progress_percent' => 0.0,
                    ],
                );
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', 'تقديم شكوى')
        ->call('sendMessage')
        ->assertSee('ما تصنيف الشكوى؟')
        ->assertSee('الخطوة category من 5')
        ->assertSee('المياه');
});

it('renders workflow success UI after completion', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function () {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(
                    message: 'تم إرسال الشكوى بنجاح',
                    type: 'workflow_success',
                    actions: [],
                    metadata: [
                        'workflow_type' => 'complaint',
                        'tracking_number' => 'CMP-2026-001',
                        'progress_percent' => 100.0,
                    ],
                );
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', 'تأكيد')
        ->call('sendMessage')
        ->assertSee('تم إرسال الشكوى بنجاح')
        ->assertSee('CMP-2026-001');
});

it('renders tracking result UI for tracked request', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function () {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(
                    message: 'نتيجة المتابعة',
                    type: 'workflow_tracking',
                    items: [
                        'tracking_number' => 'CMP-2026-001',
                        'type' => 'complaint',
                        'status' => 'in_progress',
                        'status_label' => 'قيد المعالجة',
                        'submitted_date' => '2026-07-25',
                    ],
                );
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', 'تتبع CMP-2026-001')
        ->call('sendMessage')
        ->assertSee('نتيجة المتابعة')
        ->assertSee('CMP-2026-001')
        ->assertSee('قيد المعالجة');
});

// =============================================
// Feedback Tests
// =============================================

it('submits positive feedback with conversation context', function (): void {
    Event::fake(FeedbackSubmittedEvent::class);

    $conversation = ChatbotConversation::create([
        'session_id' => 'feedback-test',
        'language' => 'ar',
        'is_active' => true,
    ]);

    Livewire::test(ChatbotPage::class)
        ->set('sessionId', 'feedback-test')
        ->call('submitFeedback', 5, null, null, $conversation->id)
        ->assertSee('شكراً لك');

    Event::assertDispatched(FeedbackSubmittedEvent::class);
});

it('ignores invalid feedback rating', function (): void {
    Livewire::test(ChatbotPage::class)
        ->call('submitFeedback', 0)
        ->assertDontSee('شكراً لك');
});

it('ignores feedback with invalid conversation id', function (): void {
    Event::fake(FeedbackSubmittedEvent::class);

    Livewire::test(ChatbotPage::class)
        ->call('submitFeedback', 5, 'great', null, 99999);

    Event::assertNotDispatched(FeedbackSubmittedEvent::class);
});

// =============================================
// Reset Context Tests
// =============================================

it('resets context when no workflow active', function (): void {
    Livewire::test(ChatbotPage::class)
        ->call('resetContext')
        ->assertSee('تم تصفية المحادثة');
});

it('warns before resetting when workflow is active', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function () {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(
                    message: 'ما تصنيف الشكوى؟',
                    type: 'workflow_question',
                    actions: [],
                    metadata: [
                        'workflow_type' => 'complaint',
                        'current_step' => 'category',
                        'total_steps' => 5,
                        'progress_percent' => 0.0,
                        'next_conversation_state' => ConversationState::WorkflowCollectingData->value,
                    ],
                );
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', 'تقديم شكوى')
        ->call('sendMessage')
        ->call('resetContext')
        ->assertSee('طلب قيد الإكمال');
});

// =============================================
// Security / XSS Tests
// =============================================

it('does not expose raw HTML from bot response', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function () {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(
                    message: '<script>alert("xss")</script>مرحبا',
                    type: 'text',
                );
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;مرحبا')
        ->assertDontSee('<script>');
});

it('does not expose raw HTML from user message', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function () {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(message: 'رد', type: 'text');
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', '<img src=x onerror=alert(1)>')
        ->call('sendMessage')
        ->assertDontSee('<img src=x onerror=alert(1)>');
});

// =============================================
// Config Tests
// =============================================

it('chatbot config has public widget toggle', function (): void {
    $config = config('chatbot');
    expect($config)->toHaveKey('public_widget');
    expect($config['public_widget'])->toHaveKey('enabled');
});

it('public widget toggle is boolean', function (): void {
    expect(config('chatbot.public_widget.enabled'))->toBeBool();
});

// =============================================
// Conversation Isolation Tests
// =============================================

it('separate sessions do not share messages', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);

    $a = new IncomingChatMessageData(message: 'رسالة A', sessionId: 'iso-a');
    $action->execute($a);

    $b = new IncomingChatMessageData(message: 'رسالة B', sessionId: 'iso-b');
    $action->execute($b);

    $pageA = Livewire::test(ChatbotPage::class)->set('sessionId', 'iso-a');
    $pageA->call('loadConversationHistory');
    $pageA->assertSee('رسالة A')->assertDontSee('رسالة B');

    $pageB = Livewire::test(ChatbotPage::class)->set('sessionId', 'iso-b');
    $pageB->call('loadConversationHistory');
    $pageB->assertSee('رسالة B')->assertDontSee('رسالة A');
});

// =============================================
// Widget State Tests
// =============================================

it('widget toggles open and closed', function (): void {
    Livewire::test(ChatbotWidget::class)
        ->set('widgetOpen', false)
        ->call('toggle')
        ->assertSet('widgetOpen', true)
        ->call('toggle')
        ->assertSet('widgetOpen', false);
});

// =============================================
// Route Existence Tests
// =============================================

it('public chatbot route exists', function (): void {
    $this->get(route('public.chatbot'))->assertOk();
});
