<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotFeedbackRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotMessageRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotModelVersionRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatIntentRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatTrainingExampleRepositoryInterface;
use App\Domains\Chatbot\Contracts\DirectServiceResolverInterface;
use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\Contracts\IntentClassifierInterface;
use App\Domains\Chatbot\Contracts\IntentModelTrainerInterface;
use App\Domains\Chatbot\Contracts\SmartServiceSearchInterface;
use App\Domains\Chatbot\DTOs\ConversationData;
use App\Domains\Chatbot\DTOs\FeedbackData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\IntentPredictionData;
use App\Domains\Chatbot\DTOs\MessageData;
use App\Domains\Chatbot\DTOs\ModelEvaluationResultData;
use App\Domains\Chatbot\DTOs\ModelTrainingResultData;
use App\Domains\Chatbot\DTOs\ModelVersionData;
use App\Domains\Chatbot\DTOs\ServiceAliasData;
use App\Domains\Chatbot\DTOs\TrainingDatasetData;
use App\Domains\Chatbot\DTOs\TrainingExampleData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\Chatbot\Models\ChatbotFeedback;
use App\Domains\Chatbot\Models\ChatbotMessage;
use App\Domains\Chatbot\Models\ChatbotModelVersion;
use App\Domains\Chatbot\Models\ChatbotServiceAlias;
use App\Domains\Chatbot\Models\ChatTrainingExample;
use App\Domains\Chatbot\Repositories\EloquentChatbotConversationRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotFeedbackRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotMessageRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotModelVersionRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotServiceAliasRepository;
use App\Domains\Chatbot\Repositories\EloquentChatIntentRepository;
use App\Domains\Chatbot\Repositories\EloquentChatTrainingExampleRepository;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\HybridIntentPredictor;
use App\Domains\Chatbot\Services\MunicipalityTokenizer;
use App\Domains\Chatbot\Services\PhpMlIntentClassifier;
use App\Domains\Chatbot\Services\PhpMlIntentModelTrainer;
use App\Domains\Chatbot\Services\SmartServiceSearch;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\ElectronicServices\Models\ServiceSearchTerm;
use App\Livewire\Chatbot\ChatbotPage;
use Database\Seeders\ChatbotSearchTermSeeder;
use Database\Seeders\ChatbotTrainingSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ElectronicServicesSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// =============================================
// Migration Tests
// =============================================

it('chatbot_conversations table exists', function (): void {
    expect(Schema::hasTable('chatbot_conversations'))->toBeTrue();
});

it('chatbot_messages table exists', function (): void {
    expect(Schema::hasTable('chatbot_messages'))->toBeTrue();
});

it('chatbot_feedback table exists', function (): void {
    expect(Schema::hasTable('chatbot_feedback'))->toBeTrue();
});

it('chatbot_model_versions table exists', function (): void {
    expect(Schema::hasTable('chatbot_model_versions'))->toBeTrue();
});

it('chatbot_service_aliases table exists', function (): void {
    expect(Schema::hasTable('chatbot_service_aliases'))->toBeTrue();
});

// =============================================
// Repository Binding Tests
// =============================================

it('chatbot conversation repository binding resolves', function (): void {
    $repo = app(ChatbotConversationRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatbotConversationRepository::class);
});

it('chatbot message repository binding resolves', function (): void {
    $repo = app(ChatbotMessageRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatbotMessageRepository::class);
});

it('chatbot feedback repository binding resolves', function (): void {
    $repo = app(ChatbotFeedbackRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatbotFeedbackRepository::class);
});

it('chatbot model version repository binding resolves', function (): void {
    $repo = app(ChatbotModelVersionRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatbotModelVersionRepository::class);
});

it('chatbot service alias repository binding resolves', function (): void {
    $repo = app(ChatbotServiceAliasRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatbotServiceAliasRepository::class);
});

// =============================================
// Conversation Repository Tests
// =============================================

it('can create a conversation', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'test-session-1',
        'status' => 'active',
    ]);

    expect($conversation)->toBeInstanceOf(ChatbotConversation::class);
    expect($conversation->session_id)->toBe('test-session-1');
    expect($conversation->status)->toBe('active');
});

it('can find a conversation by id', function (): void {
    $created = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'test-session-2',
        'status' => 'active',
    ]);

    $found = app(ChatbotConversationRepositoryInterface::class)->find($created->id);

    expect($found)->not->toBeNull();
    expect($found->id)->toBe($created->id);
});

it('returns null when finding non-existent conversation', function (): void {
    $found = app(ChatbotConversationRepositoryInterface::class)->find(999);
    expect($found)->toBeNull();
});

it('can update a conversation', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'test-session-3',
        'status' => 'active',
    ]);

    $updated = app(ChatbotConversationRepositoryInterface::class)->update($conversation->id, [
        'status' => 'closed',
    ]);

    expect($updated->status)->toBe('closed');
});

it('can delete a conversation', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'test-session-4',
        'status' => 'active',
    ]);

    $result = app(ChatbotConversationRepositoryInterface::class)->delete($conversation->id);

    expect($result)->toBeTrue();
    expect(ChatbotConversation::find($conversation->id))->toBeNull();
});

it('returns false when deleting non-existent conversation', function (): void {
    $result = app(ChatbotConversationRepositoryInterface::class)->delete(999);
    expect($result)->toBeFalse();
});

it('can find active conversation by session id', function (): void {
    app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'test-session-5',
        'status' => 'active',
    ]);

    $found = app(ChatbotConversationRepositoryInterface::class)->findActiveBySession('test-session-5');

    expect($found)->not->toBeNull();
    expect($found->session_id)->toBe('test-session-5');
});

it('returns null when no active session exists', function (): void {
    $found = app(ChatbotConversationRepositoryInterface::class)->findActiveBySession('non-existent');
    expect($found)->toBeNull();
});

it('can get all conversations', function (): void {
    app(ChatbotConversationRepositoryInterface::class)->create(['session_id' => 's-1', 'status' => 'active']);
    app(ChatbotConversationRepositoryInterface::class)->create(['session_id' => 's-2', 'status' => 'closed']);

    $all = app(ChatbotConversationRepositoryInterface::class)->all();

    expect($all)->toHaveCount(2);
});

// =============================================
// Message Repository Tests
// =============================================

it('can create a message', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'msg-test-session',
        'status' => 'active',
    ]);

    $message = app(ChatbotMessageRepositoryInterface::class)->create([
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'content' => 'Hello chatbot',
    ]);

    expect($message)->toBeInstanceOf(ChatbotMessage::class);
    expect($message->role)->toBe('user');
    expect($message->content)->toBe('Hello chatbot');
});

it('can get messages by conversation', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'msg-test-session-2',
        'status' => 'active',
    ]);

    app(ChatbotMessageRepositoryInterface::class)->create([
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'content' => 'Message 1',
    ]);

    app(ChatbotMessageRepositoryInterface::class)->create([
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'content' => 'Message 2',
    ]);

    $messages = app(ChatbotMessageRepositoryInterface::class)->getByConversation($conversation->id);

    expect($messages)->toHaveCount(2);
});

// =============================================
// Feedback Repository Tests
// =============================================

it('can create feedback', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'feedback-test',
        'status' => 'active',
    ]);

    $message = app(ChatbotMessageRepositoryInterface::class)->create([
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'content' => 'Response',
    ]);

    $feedback = app(ChatbotFeedbackRepositoryInterface::class)->create([
        'message_id' => $message->id,
        'type' => 'helpful',
    ]);

    expect($feedback)->toBeInstanceOf(ChatbotFeedback::class);
    expect($feedback->type)->toBe('helpful');
});

it('can get feedback by message', function (): void {
    $conversation = app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => 'feedback-test-2',
        'status' => 'active',
    ]);

    $message = app(ChatbotMessageRepositoryInterface::class)->create([
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'content' => 'Response',
    ]);

    app(ChatbotFeedbackRepositoryInterface::class)->create([
        'message_id' => $message->id,
        'type' => 'not_helpful',
        'comment' => 'Wrong answer',
    ]);

    $found = app(ChatbotFeedbackRepositoryInterface::class)->getByMessage($message->id);

    expect($found)->not->toBeNull();
    expect($found->type)->toBe('not_helpful');
    expect($found->comment)->toBe('Wrong answer');
});

// =============================================
// Model Version Repository Tests
// =============================================

it('can create a model version', function (): void {
    $version = app(ChatbotModelVersionRepositoryInterface::class)->create([
        'version' => '1.0.0',
        'status' => 'inactive',
    ]);

    expect($version)->toBeInstanceOf(ChatbotModelVersion::class);
    expect($version->version)->toBe('1.0.0');
    expect($version->status)->toBe('inactive');
});

it('can find active model version', function (): void {
    app(ChatbotModelVersionRepositoryInterface::class)->create([
        'version' => '1.0.0',
        'status' => 'inactive',
    ]);

    app(ChatbotModelVersionRepositoryInterface::class)->create([
        'version' => '2.0.0',
        'status' => 'active',
    ]);

    $active = app(ChatbotModelVersionRepositoryInterface::class)->getActive();

    expect($active)->not->toBeNull();
    expect($active->version)->toBe('2.0.0');
});

it('returns null when no active model version exists', function (): void {
    $active = app(ChatbotModelVersionRepositoryInterface::class)->getActive();
    expect($active)->toBeNull();
});

// =============================================
// Service Alias Repository Tests
// =============================================

it('can create a service alias', function (): void {
    $alias = app(ChatbotServiceAliasRepositoryInterface::class)->create([
        'alias' => 'water-schedule',
        'service_key' => 'water-schedule',
        'description' => 'جدول توزيع المياه',
        'is_active' => true,
    ]);

    expect($alias)->toBeInstanceOf(ChatbotServiceAlias::class);
    expect($alias->alias)->toBe('water-schedule');
    expect($alias->is_active)->toBeTrue();
});

it('can find alias by normalized name', function (): void {
    app(ChatbotServiceAliasRepositoryInterface::class)->create([
        'alias' => 'test-alias',
        'service_key' => 'test-service',
        'is_active' => true,
    ]);

    $found = app(ChatbotServiceAliasRepositoryInterface::class)->findByAlias('test-alias');

    expect($found)->not->toBeNull();
    expect($found->service_key)->toBe('test-service');
});

it('returns null when alias does not exist', function (): void {
    $found = app(ChatbotServiceAliasRepositoryInterface::class)->findByAlias('non-existent');
    expect($found)->toBeNull();
});

// =============================================
// Model Casts and Relationships Tests
// =============================================

it('chatbot conversation model uses soft deletes', function (): void {
    expect(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(ChatbotConversation::class)))->toBeTrue();
});

it('chatbot message model uses soft deletes', function (): void {
    expect(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(ChatbotMessage::class)))->toBeTrue();
});

it('chatbot feedback model uses soft deletes', function (): void {
    expect(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(ChatbotFeedback::class)))->toBeTrue();
});

it('chatbot model version model uses soft deletes', function (): void {
    expect(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(ChatbotModelVersion::class)))->toBeTrue();
});

it('chatbot service alias model uses soft deletes', function (): void {
    expect(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(ChatbotServiceAlias::class)))->toBeTrue();
});

it('conversation model has messages relationship', function (): void {
    $conversation = new ChatbotConversation;
    expect(method_exists($conversation, 'messages'))->toBeTrue();
});

it('message model has conversation relationship', function (): void {
    $message = new ChatbotMessage;
    expect(method_exists($message, 'conversation'))->toBeTrue();
});

it('message model has feedback relationship', function (): void {
    $message = new ChatbotMessage;
    expect(method_exists($message, 'feedback'))->toBeTrue();
});

it('feedback model has message relationship', function (): void {
    $feedback = new ChatbotFeedback;
    expect(method_exists($feedback, 'message'))->toBeTrue();
});

// =============================================
// Conversation-Message Relationship Test
// =============================================

it('conversation can have multiple messages', function (): void {
    $conversation = ChatbotConversation::create([
        'session_id' => 'rel-test',
        'status' => 'active',
    ]);

    ChatbotMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'content' => 'Q1',
    ]);

    ChatbotMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'content' => 'A1',
    ]);

    expect($conversation->messages)->toHaveCount(2);
});

// =============================================
// Route Tests
// =============================================

it('public chatbot page route exists and returns 200', function (): void {
    $this->get(route('public.chatbot'))->assertOk();
});

it('chatbot page component renders correctly', function (): void {
    Livewire::test(ChatbotPage::class)
        ->assertOk()
        ->assertSee('المساعد الذكي')
        ->assertSee('بلدية إذنا');
});

it('admin chatbot dashboard requires authentication', function (): void {
    $this->get(route('dashboard.chatbot'))->assertRedirect(route('login'));
});

it('admin chatbot dashboard loads with permission', function (): void {
    $user = User::factory()->create();

    $permission = Permission::where('name', 'chatbot.view')->first();
    DB::table('model_has_permissions')->insert([
        'permission_id' => $permission->id,
        'model_type' => $user->getMorphClass(),
        'model_id' => $user->id,
    ]);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->actingAs($user);

    $this->get(route('dashboard.chatbot'))->assertOk();
});

it('unauthorized user cannot access admin chatbot dashboard', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('dashboard.chatbot'))->assertForbidden();
});

// =============================================
// Permission Tests
// =============================================

it('chatbot permissions exist in config', function (): void {
    $permissions = config('permissions');
    $chatbotModule = collect($permissions)->firstWhere('module', 'chatbot');
    expect($chatbotModule)->not->toBeNull();
    expect($chatbotModule['permissions'])->toHaveCount(6);
});

it('chatbot permissions are seeded in database', function (): void {
    expect(Permission::where('name', 'chatbot.view')->exists())->toBeTrue();
    expect(Permission::where('name', 'chatbot.manage')->exists())->toBeTrue();
    expect(Permission::where('name', 'chatbot.conversations.view')->exists())->toBeTrue();
    expect(Permission::where('name', 'chatbot.feedback.view')->exists())->toBeTrue();
    expect(Permission::where('name', 'chatbot.models.view')->exists())->toBeTrue();
    expect(Permission::where('name', 'chatbot.aliases.view')->exists())->toBeTrue();
});

it('does not create duplicate chatbot permissions', function (): void {
    $count = Permission::where('name', 'like', 'chatbot.%')->count();
    expect($count)->toBe(6);
});

// =============================================
// DTO Tests
// =============================================

it('conversation data dto converts to array', function (): void {
    $dto = new ConversationData(
        sessionId: 'test-session',
        status: 'active',
    );
    $array = $dto->toArray();
    expect($array['session_id'])->toBe('test-session');
    expect($array['status'])->toBe('active');
});

it('message data dto converts to array', function (): void {
    $dto = new MessageData(
        conversationId: 1,
        role: 'user',
        content: 'Hello',
    );
    $array = $dto->toArray();
    expect($array['conversation_id'])->toBe(1);
    expect($array['role'])->toBe('user');
    expect($array['content'])->toBe('Hello');
});

it('feedback data dto converts to array', function (): void {
    $dto = new FeedbackData(
        messageId: 1,
        type: 'helpful',
    );
    $array = $dto->toArray();
    expect($array['message_id'])->toBe(1);
    expect($array['type'])->toBe('helpful');
});

it('model version data dto converts to array', function (): void {
    $dto = new ModelVersionData(
        version: '1.0.0',
    );
    $array = $dto->toArray();
    expect($array['version'])->toBe('1.0.0');
    expect($array['status'])->toBe('inactive');
});

it('service alias data dto converts to array', function (): void {
    $dto = new ServiceAliasData(
        alias: 'test',
        serviceKey: 'test-key',
    );
    $array = $dto->toArray();
    expect($array['alias'])->toBe('test');
    expect($array['service_key'])->toBe('test-key');
    expect($array['is_active'])->toBeTrue();
});

// =============================================
// Config Tests
// =============================================

it('chatbot config file exists and has required keys', function (): void {
    $config = config('chatbot');
    expect($config)->toHaveKey('enabled');
    expect($config)->toHaveKey('model');
    expect($config)->toHaveKey('max_conversation_length');
    expect($config)->toHaveKey('session');
    expect($config)->toHaveKey('response');
});

// =============================================
// Phase 2 — Config Tests
// =============================================

it('chatbot config has phase 2 keys', function (): void {
    $config = config('chatbot');
    expect($config)->toHaveKey('rule_based_enabled');
    expect($config)->toHaveKey('max_message_length');
    expect($config)->toHaveKey('search_limit');
    expect($config)->toHaveKey('session_key');
    expect($config)->toHaveKey('store_messages');
    expect($config)->toHaveKey('public_disclaimer');
    expect($config)->toHaveKey('rate_limit');
});

// =============================================
// Phase 2 — Chatbot Flow Tests
// =============================================

beforeEach(function (): void {
    // Create a service category and electronic service for flow tests
    $this->electronicCategory = ServiceCategory::create([
        'name' => 'الخدمات الإلكترونية',
        'slug' => 'alkhdmat-alalktrony',
        'status' => 'active',
        'is_public' => true,
    ]);
    $this->electronicService = ElectronicService::create([
        'service_category_id' => $this->electronicCategory->id,
        'name' => 'إصدار رخصة بناء',
        'status' => 'active',
        'is_public' => true,
        'steps' => [
            'تعبئة طلب التقديم',
            'إرفاق المستندات المطلوبة',
            'دفع الرسوم',
            'انتظار المراجعة والموافقة',
        ],
        'requirements' => [
            'صورة عن الهوية الشخصية',
            'سند ملكية الأرض',
            'مخططات معمارية معتمدة',
            'تعهد خطي من صاحب العلاقة',
        ],
        'fees' => [
            ['item' => 'رسوم الترخيص', 'amount' => '500 شيكل'],
            ['item' => 'رسوم المعاينة', 'amount' => '200 شيكل'],
        ],
        'processing_time' => '15-30 يوم عمل',
        'portal_url' => 'https://portal.idhna.ps/apply/building-permit',
    ]);
});

it('processes greeting message end-to-end', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: 'test-flow-session',
    );

    $response = $action->execute($incoming);

    expect($response->message)->toContain('مرحباً بك في المساعد الذكي لبلدية إذنا');
    expect($response->type)->toBe('text');
    expect($response->actions)->toHaveCount(10);
});

it('processes thanks message end-to-end', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'شكرا',
        sessionId: 'test-thanks-session',
    );

    $response = $action->execute($incoming);

    expect($response->message)->toContain('العفو');
    expect($response->type)->toBe('text');
});

it('processes service fees from database', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'الرسوم',
        sessionId: 'test-fees-session',
    );

    $response = $action->execute($incoming);

    // Should ask for service name since no service is detected in "الرسوم" alone
    expect($response->needsClarification)->toBeTrue();
    expect($response->clarificationType)->toBe('service');
});

it('processes service duration from database', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'المده',
        sessionId: 'test-duration-session',
    );

    $response = $action->execute($incoming);

    expect($response->needsClarification)->toBeTrue();
    expect($response->clarificationType)->toBe('service');
});

it('processes unknown intent without crashing', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'xyz not understood at all',
        sessionId: 'test-unknown-session',
    );

    $response = $action->execute($incoming);

    expect($response->needsClarification)->toBeTrue();
    expect($response->type)->toBe('clarification');
    expect($response->actions)->toHaveCount(10);
});

it('creates conversation and stores messages in transaction', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: 'test-store-session',
    );

    $response = $action->execute($incoming);

    $conversation = app(ChatbotConversationRepositoryInterface::class)->findActiveBySession('test-store-session');
    expect($conversation)->not->toBeNull();
    expect($conversation->status)->toBe('active');

    $messages = app(ChatbotMessageRepositoryInterface::class)->getByConversation($conversation->id);
    expect($messages)->toHaveCount(2);

    $citizenMsg = $messages->firstWhere('role', 'citizen');
    expect($citizenMsg)->not->toBeNull();
    expect($citizenMsg->content)->toBe('مرحبا');
    expect($citizenMsg->metadata)->toHaveKey('predicted_intent');
    expect($citizenMsg->metadata['predicted_intent'])->toBe('greeting');

    $botMsg = $messages->firstWhere('role', 'bot');
    expect($botMsg)->not->toBeNull();
    expect($botMsg->content)->toContain('مرحباً بك في المساعد الذكي لبلدية إذنا');
    expect($botMsg->metadata)->toHaveKey('response_payload');
    expect($botMsg->metadata)->toHaveKey('phase');
    expect($botMsg->metadata['phase'])->toBe('intent_classification');
});

it('resolves service overview from database through full flow', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'معلومات عن إصدار رخصة بناء',
        sessionId: 'test-overview-session',
    );

    $response = $action->execute($incoming);

    expect($response->type)->toBe('text');
    expect($response->message)->not->toBeEmpty();
});

it('resolves service steps from database', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'خطوات التقديم إصدار رخصة بناء',
        sessionId: 'test-steps-session',
    );

    $response = $action->execute($incoming);

    expect($response->type)->toBe('steps');
    expect($response->items)->toHaveCount(4);
});

it('resolves service requirements from database', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'المتطلبات إصدار رخصة بناء',
        sessionId: 'test-req-session',
    );

    $response = $action->execute($incoming);

    expect($response->type)->toBe('requirements');
    expect($response->items)->toHaveCount(4);
});

it('never invents fees when missing from database', function (): void {
    ElectronicService::create([
        'service_category_id' => $this->electronicCategory->id,
        'name' => 'خدمة بدون رسوم',
        'status' => 'active',
        'is_public' => true,
        'fees' => null,
    ]);

    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'الرسوم خدمة بدون رسوم',
        sessionId: 'test-nofees-session',
    );

    $response = $action->execute($incoming);

    expect($response->message)->toContain('غير منشورة');
    expect($response->type)->toBe('text');
});

it('supports Livewire message sending flow', function (): void {
    Livewire::test(ChatbotPage::class)
        ->assertOk()
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSet('message', '')
        ->assertSet('loading', false)
        ->assertHasNoErrors();
});

it('validation rejects empty message via Livewire', function (): void {
    Livewire::test(ChatbotPage::class)
        ->assertOk()
        ->set('message', '')
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'required']);
});

it('validation rejects excessive message length', function (): void {
    Livewire::test(ChatbotPage::class)
        ->assertOk()
        ->set('message', str_repeat('x', config('chatbot.max_message_length', 500) + 1))
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'max']);
});

it('requires no Phase 3 classes', function (): void {
    $phase3Classes = [
        'App\\Domains\\Chatbot\\Services\\NlpIntentClassifier',
        'App\\Domains\\Chatbot\\Services\\ConversationContext',
        'App\\Domains\\Chatbot\\Services\\ResponseBuilder',
        'App\\Domains\\Chatbot\\Handlers\\ComplaintHandler',
    ];

    foreach ($phase3Classes as $class) {
        expect(class_exists($class))->toBeFalse();
    }
});

it('does not import electronic service models in chatbot domain except in adapter', function (): void {
    $chatbotFiles = glob(str_replace('\\', '/', app_path('Domains/Chatbot/**/*.php')));
    expect($chatbotFiles)->not->toBeEmpty();

    $modelPattern = 'App\\Domains\\ElectronicServices\\Models\\';

    foreach ($chatbotFiles as $file) {
        if (str_contains($file, 'MunicipalityServiceQueryAdapter.php')) {
            continue;
        }
        $content = file_get_contents($file);
        expect($content)->not->toContain($modelPattern);
    }
});

it('unpublished service is never exposed', function (): void {
    $category = ServiceCategory::create([
        'name' => 'خدمات سرية',
    ]);
    $unpublished = ElectronicService::create([
        'service_category_id' => $category->id,
        'name' => 'خدمة سرية',
        'status' => 'draft',
        'is_public' => false,
    ]);

    $resolver = app(DirectServiceResolverInterface::class);
    $normalizer = app(ArabicTextNormalizer::class);

    $result = $resolver->resolve($normalizer->normalize('خدمة سرية'));

    expect($result)->toBeNull();
});

// =============================================
// Phase 3 — Migration Tests
// =============================================

it('chat_intents table exists', function (): void {
    expect(Schema::hasTable('chat_intents'))->toBeTrue();
});

it('chat_training_examples table exists', function (): void {
    expect(Schema::hasTable('chat_training_examples'))->toBeTrue();
});

// =============================================
// Phase 3 — Repository Binding Tests
// =============================================

it('chat intent repository binding resolves', function (): void {
    $repo = app(ChatIntentRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatIntentRepository::class);
});

it('chat training example repository binding resolves', function (): void {
    $repo = app(ChatTrainingExampleRepositoryInterface::class);
    expect($repo)->toBeInstanceOf(EloquentChatTrainingExampleRepository::class);
});

// =============================================
// Phase 3 — ML Service Binding Tests
// =============================================

it('intent model trainer binding resolves', function (): void {
    $trainer = app(IntentModelTrainerInterface::class);
    expect($trainer)->toBeInstanceOf(PhpMlIntentModelTrainer::class);
});

it('intent classifier binding resolves', function (): void {
    $classifier = app(IntentClassifierInterface::class);
    expect($classifier)->toBeInstanceOf(PhpMlIntentClassifier::class);
});

it('hybrid intent predictor binding resolves', function (): void {
    $predictor = app(HybridIntentPredictorInterface::class);
    expect($predictor)->toBeInstanceOf(HybridIntentPredictor::class);
});

// =============================================
// Phase 3 — Config Tests
// =============================================

it('chatbot config has phase 3 keys', function (): void {
    $config = config('chatbot');
    expect($config)->toHaveKey('ml_enabled');
    expect($config)->toHaveKey('ml_default_confidence_threshold');
    expect($config)->toHaveKey('ml_model_storage_path');
    expect($config)->toHaveKey('training');
});

it('chatbot config training section has required keys', function (): void {
    $training = config('chatbot.training');
    expect($training)->toHaveKey('minimum_examples_per_intent');
    expect($training)->toHaveKey('default_algorithm');
    expect($training)->toHaveKey('seed_on_migrate');
});

// =============================================
// Phase 3 — ChatIntent Repository Tests
// =============================================

it('chat intent repository can create and find intents', function (): void {
    $repo = app(ChatIntentRepositoryInterface::class);

    $count = $repo->synchronizeFromEnum();
    expect($count)->toBe(count(ChatbotIntent::cases()));

    $greeting = $repo->findByName('greeting');
    expect($greeting)->not->toBeNull();
    expect($greeting->label_ar)->toBe('تحية');
    expect($greeting->is_active)->toBeTrue();
});

it('chat intent repository returns all active intents ordered', function (): void {
    $repo = app(ChatIntentRepositoryInterface::class);
    $repo->synchronizeFromEnum();

    $active = $repo->allActive();
    expect($active)->not->toBeEmpty();
    expect($active->count())->toBe(count(ChatbotIntent::cases()));
});

it('chat intent repository returns null for unknown intent', function (): void {
    $repo = app(ChatIntentRepositoryInterface::class);
    $result = $repo->findByName('nonexistent');

    expect($result)->toBeNull();
});

it('chat intent repository minimum confidence defaults to null', function (): void {
    $repo = app(ChatIntentRepositoryInterface::class);
    $repo->synchronizeFromEnum();

    $confidence = $repo->getMinimumConfidence('greeting');
    expect($confidence)->toBeNull();
});

// =============================================
// Phase 3 — ChatTrainingExample Repository Tests
// =============================================

it('chat training example repository can create examples', function (): void {
    $intentRepo = app(ChatIntentRepositoryInterface::class);
    $intentRepo->synchronizeFromEnum();
    $greeting = $intentRepo->findByName('greeting');

    $exampleRepo = app(ChatTrainingExampleRepositoryInterface::class);
    $normalizer = app(ArabicTextNormalizer::class);

    $example = $exampleRepo->create([
        'chat_intent_id' => $greeting->id,
        'text' => 'مرحبا',
        'normalized_text' => $normalizer->normalize('مرحبا'),
        'source' => 'test',
    ]);

    expect($example)->toBeInstanceOf(ChatTrainingExample::class);
    expect($example->text)->toBe('مرحبا');
    expect($example->is_active)->toBeTrue();
    expect($example->is_verified)->toBeTrue();
});

it('chat training example repository returns verified active examples', function (): void {
    $intentRepo = app(ChatIntentRepositoryInterface::class);
    $intentRepo->synchronizeFromEnum();
    $greeting = $intentRepo->findByName('greeting');

    $exampleRepo = app(ChatTrainingExampleRepositoryInterface::class);
    $normalizer = app(ArabicTextNormalizer::class);

    $exampleRepo->create([
        'chat_intent_id' => $greeting->id,
        'text' => 'مرحبا',
        'normalized_text' => $normalizer->normalize('مرحبا'),
    ]);

    $examples = $exampleRepo->getVerifiedActiveExamples();
    expect($examples)->toHaveCount(1);
});

it('chat training example repository excludes inactive examples', function (): void {
    $intentRepo = app(ChatIntentRepositoryInterface::class);
    $intentRepo->synchronizeFromEnum();
    $greeting = $intentRepo->findByName('greeting');

    $exampleRepo = app(ChatTrainingExampleRepositoryInterface::class);
    $normalizer = app(ArabicTextNormalizer::class);

    $example = $exampleRepo->create([
        'chat_intent_id' => $greeting->id,
        'text' => 'مرحبا',
        'normalized_text' => $normalizer->normalize('مرحبا'),
    ]);

    $exampleRepo->deactivate($example->id);

    $examples = $exampleRepo->getVerifiedActiveExamples();
    expect($examples)->toHaveCount(0);
});

it('chat training example repository can count by intent', function (): void {
    $intentRepo = app(ChatIntentRepositoryInterface::class);
    $intentRepo->synchronizeFromEnum();
    $greeting = $intentRepo->findByName('greeting');
    $thanks = $intentRepo->findByName('thanks');

    $exampleRepo = app(ChatTrainingExampleRepositoryInterface::class);
    $normalizer = app(ArabicTextNormalizer::class);

    $exampleRepo->create(['chat_intent_id' => $greeting->id, 'text' => 'مرحبا', 'normalized_text' => $normalizer->normalize('مرحبا')]);
    $exampleRepo->create(['chat_intent_id' => $greeting->id, 'text' => 'سلام', 'normalized_text' => $normalizer->normalize('سلام')]);
    $exampleRepo->create(['chat_intent_id' => $thanks->id, 'text' => 'شكرا', 'normalized_text' => $normalizer->normalize('شكرا')]);

    $counts = $exampleRepo->countByIntent();
    expect($counts[$greeting->id])->toBe(2);
    expect($counts[$thanks->id])->toBe(1);
});

it('chat training example repository generates consistent fingerprint', function (): void {
    $intentRepo = app(ChatIntentRepositoryInterface::class);
    $intentRepo->synchronizeFromEnum();
    $greeting = $intentRepo->findByName('greeting');

    $exampleRepo = app(ChatTrainingExampleRepositoryInterface::class);
    $normalizer = app(ArabicTextNormalizer::class);

    $exampleRepo->create(['chat_intent_id' => $greeting->id, 'text' => 'مرحبا', 'normalized_text' => $normalizer->normalize('مرحبا')]);

    $fingerprint1 = $exampleRepo->datasetFingerprint();
    $fingerprint2 = $exampleRepo->datasetFingerprint();

    expect($fingerprint1)->toBe($fingerprint2);
});

// =============================================
// Phase 3 — DTO Tests
// =============================================

it('intent prediction data dto converts to array', function (): void {
    $dto = new IntentPredictionData(
        intent: ChatbotIntent::Greeting,
        confidence: 0.95,
        source: 'ml',
        modelVersionId: 1,
        modelVersion: '20260728120000',
    );

    $array = $dto->toArray();
    expect($array['intent'])->toBe('greeting');
    expect($array['confidence'])->toBe(0.95);
    expect($array['source'])->toBe('ml');
    expect($array['model_version_id'])->toBe(1);
});

it('model training result data dto converts to array', function (): void {
    $dto = new ModelTrainingResultData(
        success: true,
        version: '20260728120000',
        artifactPath: '/some/path.model',
        examplesCount: 100,
        intentsCount: 5,
        datasetFingerprint: 'abc123',
        trainingDurationMs: 1500,
        metrics: ['algorithm' => 'naive_bayes'],
    );

    $array = $dto->toArray();
    expect($array['success'])->toBeTrue();
    expect($array['version'])->toBe('20260728120000');
    expect($array['examples_count'])->toBe(100);
    expect($array['intents_count'])->toBe(5);
});

it('model evaluation result data dto converts to array', function (): void {
    $dto = new ModelEvaluationResultData(
        totalExamples: 50,
        correctPredictions: 40,
        accuracy: 0.80,
        perIntentPrecision: ['greeting' => 0.9],
        perIntentRecall: ['greeting' => 0.85],
    );

    $array = $dto->toArray();
    expect($array['total_examples'])->toBe(50);
    expect($array['accuracy'])->toBe(0.80);
});

it('training example data dto converts to array', function (): void {
    $dto = new TrainingExampleData(
        intentId: 1,
        text: 'مرحبا',
        normalizedText: 'مرحبا',
    );

    $array = $dto->toArray();
    expect($array['chat_intent_id'])->toBe(1);
    expect($array['text'])->toBe('مرحبا');
    expect($array['normalized_text'])->toBe('مرحبا');
    expect($array['source'])->toBe('seed');
});

it('training dataset data dto converts to array', function (): void {
    $dto = new TrainingDatasetData(
        examples: ['مرحبا', 'شكرا'],
        labels: ['greeting', 'thanks'],
        intentCounts: ['greeting' => 1, 'thanks' => 1],
        totalCount: 2,
        intentCount: 2,
        fingerprint: 'abc123',
    );

    $array = $dto->toArray();
    expect($array['examples'])->toBe(2);
    expect($array['total_count'])->toBe(2);
    expect($array['fingerprint'])->toBe('abc123');
});

// =============================================
// Phase 3 — ChatbotTrainingSeeder Test
// =============================================

it('chatbot training seeder seeds examples without duplicates', function (): void {
    $intentRepo = app(ChatIntentRepositoryInterface::class);
    $intentRepo->synchronizeFromEnum();

    $this->seed(ChatbotTrainingSeeder::class);

    $exampleRepo = app(ChatTrainingExampleRepositoryInterface::class);
    $examples = $exampleRepo->getVerifiedActiveExamples();

    expect($examples)->not->toBeEmpty();
    expect($examples->count())->toBeGreaterThan(200);

    $this->seed(ChatbotTrainingSeeder::class);

    $examplesAfter = $exampleRepo->getVerifiedActiveExamples();
    expect($examplesAfter->count())->toBe($examples->count());
});

// =============================================
// Phase 3 — Artisan Command Tests
// =============================================

it('chatbot:train command registers', function (): void {
    expect(Artisan::all())->toHaveKey('chatbot:train');
});

it('chatbot:predict command registers', function (): void {
    expect(Artisan::all())->toHaveKey('chatbot:predict');
});

it('chatbot:evaluate command registers', function (): void {
    expect(Artisan::all())->toHaveKey('chatbot:evaluate');
});

it('chatbot:activate-model command registers', function (): void {
    expect(Artisan::all())->toHaveKey('chatbot:activate-model');
});

it('chatbot:train dry-run succeeds', function (): void {
    $this->artisan('chatbot:train', ['--dry-run' => true])
        ->expectsOutputToContain('Dry run mode')
        ->assertSuccessful();
});

it('chatbot:activate-model shows error for non-existent version', function (): void {
    $this->artisan('chatbot:activate-model', ['version' => 'nonexistent'])
        ->expectsOutputToContain('not found')
        ->assertFailed();
});

// =============================================
// Phase 3 — MunicipalityTokenizer Test
// =============================================

it('municipality tokenizer service binding resolves', function (): void {
    $tokenizer = app(MunicipalityTokenizer::class);
    expect($tokenizer)->toBeInstanceOf(MunicipalityTokenizer::class);
});

// =============================================
// Phase 3 — ArabicTextNormalizer remains functional
// =============================================

it('normalizer still works after Phase 3 changes', function (): void {
    $normalizer = app(ArabicTextNormalizer::class);

    expect($normalizer->normalize('السلام عليكم'))->toBe('السلام عليكم');
    expect($normalizer->normalize('أهلاً'))->toBe('اهلا');
    expect($normalizer->normalize('شكراً'))->toBe('شكرا');
});

// =============================================
// Phase 3 — Hybrid Intent Predictor uses provider config
// =============================================

it('hybrid predictor is configured with ML disabled by default', function (): void {
    Config::set('chatbot.ml_enabled', false);

    $predictor = app(HybridIntentPredictorInterface::class);

    $reflection = new ReflectionClass($predictor);
    $mlEnabled = $reflection->getProperty('mlEnabled');
    $mlEnabled->setAccessible(true);

    expect($mlEnabled->getValue($predictor))->toBeFalse();
});

// =============================================
// Phase 5 — Smart Service Search Feature Tests
// =============================================

beforeEach(function (): void {
    // Create additional services for Phase 5 tests
    $cat = ServiceCategory::firstOrCreate(
        ['name' => 'الخدمات الإلكترونية'],
        ['slug' => 'alkhdmat-alalktrony', 'description' => 'الخدمات الإلكترونية', 'status' => 'active', 'is_public' => true],
    );

    $this->buildingPermit = ElectronicService::create([
        'service_category_id' => $cat->id,
        'name' => 'رخصة بناء',
        'status' => 'active',
        'is_public' => true,
        'summary' => 'التقدم للحصول على رخصة بناء جديدة',
        'steps' => ['تقديم الطلب', 'مراجعة المخططات', 'إصدار الرخصة'],
        'requirements' => ['هوية', 'ملكية', 'مخططات'],
        'fees' => [['item' => 'رسوم', 'amount' => '500 شيكل']],
        'processing_time' => '15 يوم',
    ]);

    $this->shopLicence = ElectronicService::create([
        'service_category_id' => $cat->id,
        'name' => 'ترخيص محل تجاري',
        'status' => 'active',
        'is_public' => true,
        'summary' => 'ترخيص لمحل تجاري',
        'steps' => ['تقديم الطلب', 'الفحص', 'إصدار الترخيص'],
        'requirements' => ['هوية', 'عقد إيجار'],
        'fees' => [['item' => 'رسوم', 'amount' => '300 شيكل']],
        'processing_time' => '7 أيام',
    ]);

    $this->completionCert = ElectronicService::create([
        'service_category_id' => $cat->id,
        'name' => 'شهادة إتمام بناء',
        'status' => 'active',
        'is_public' => true,
        'summary' => 'شهادة إتمام البناء',
        'steps' => ['تقديم الطلب', 'الفحص', 'إصدار الشهادة'],
        'requirements' => ['هوية', 'رخصة بناء'],
        'fees' => [['item' => 'رسوم', 'amount' => '200 شيكل']],
        'processing_time' => '10 أيام',
    ]);

    // Unpublished service
    $this->unpublishedService = ElectronicService::create([
        'service_category_id' => $cat->id,
        'name' => 'خدمة غير منشورة',
        'status' => 'active',
        'is_public' => false,
        'summary' => 'غير منشورة',
    ]);

    // Archival service
    $this->archivedService = ElectronicService::create([
        'service_category_id' => $cat->id,
        'name' => 'خدمة مؤرشفة',
        'status' => 'archived',
        'is_public' => false,
        'summary' => 'مؤرشفة',
    ]);

    // Seed search terms
    $normalizer = app(ArabicTextNormalizer::class);

    $searchTerms = [
        $this->buildingPermit->id => [
            ['term' => 'تصريح بناء', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
            ['term' => 'رخصة مباني', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
            ['term' => 'بناء', 'type' => 'keyword', 'weight' => 20, 'priority' => 10],
            ['term' => 'بيت', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
            ['term' => 'دار', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
            ['term' => 'طابق', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
            ['term' => 'بدي أبني بيت', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
            ['term' => 'بدي ابني بيت', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
            ['term' => 'بدي أرخص داري', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
            ['term' => 'بدي ارخص داري', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
            ['term' => 'بدي أضيف طابق', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
            ['term' => 'بدي اضيف طابق', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
        ],
        $this->shopLicence->id => [
            ['term' => 'رخصة محل', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
            ['term' => 'رخصة مهن', 'type' => 'alias', 'weight' => 28, 'priority' => 9],
            ['term' => 'محل', 'type' => 'keyword', 'weight' => 20, 'priority' => 10],
            ['term' => 'تجاري', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
            ['term' => 'بدي أفتح محل', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
            ['term' => 'بدي افتح محل', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
            ['term' => 'بدي أفتح محل ملابس', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 9],
            ['term' => 'بدي افتح محل ملابس', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 9],
        ],
        $this->completionCert->id => [
            ['term' => 'شهادة إتمام', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
            ['term' => 'إتمام بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
            ['term' => 'بدي شهادة إتمام', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
            ['term' => 'بدي شهادة اتمام', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
            ['term' => 'خلصت بناء', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
        ],
    ];

    foreach ($searchTerms as $serviceId => $terms) {
        foreach ($terms as $termData) {
            ServiceSearchTerm::create([
                'electronic_service_id' => $serviceId,
                'term' => $termData['term'],
                'normalized_term' => $normalizer->normalize($termData['term']),
                'type' => $termData['type'],
                'weight' => $termData['weight'],
                'priority' => $termData['priority'],
                'is_active' => true,
            ]);
        }
    }

    // Clear search cache
    app(SmartServiceSearch::class)->clearCache();
});

it('Phase 5: finds building permit from natural phrase', function (): void {
    $search = app(SmartServiceSearchInterface::class);
    $result = $search->search('بدي أبني بيت');

    expect($result->noMatch)->toBeFalse();
    expect($result->bestMatch)->not->toBeNull();
    expect($result->isConfident)->toBeTrue();
    expect($result->bestMatch->serviceName)->toContain('رخصة بناء');
});

it('Phase 5: finds business licence from shop phrase', function (): void {
    $search = app(SmartServiceSearchInterface::class);
    $result = $search->search('بدي أفتح محل ملابس');

    expect($result->noMatch)->toBeFalse();
    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toContain('ترخيص محل');
});

it('Phase 5: matches dialect spelling ارخص داري', function (): void {
    $search = app(SmartServiceSearchInterface::class);
    $result = $search->search('بدي ارخص داري');

    expect($result->noMatch)->toBeFalse();
    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toContain('رخصة بناء');
});

it('Phase 5: returns no match for gibberish', function (): void {
    $search = app(SmartServiceSearchInterface::class);
    $result = $search->search('بدي اطلع ورقة ابصر شو اسمها');

    expect($result->noMatch)->toBeTrue();
    expect($result->bestMatch)->toBeNull();
});

it('Phase 5: unpublished service never appears in search', function (): void {
    $search = app(SmartServiceSearchInterface::class);
    $result = $search->search('خدمة غير منشورة');

    expect($result->noMatch)->toBeTrue();
    expect($result->bestMatch)->toBeNull();
    $matchNames = array_map(fn ($m) => $m->serviceName, $result->matches);
    expect($matchNames)->not->toContain('خدمة غير منشورة');
});

it('Phase 5: explicit new service overrides old context', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(ChatbotSearchTermSeeder::class);

    $action = app(ProcessRuleBasedChatMessageAction::class);

    // First, set context to building permit
    $incoming1 = new IncomingChatMessageData(
        message: 'بدي رخصة بناء',
        sessionId: 'test-phase5-override',
    );
    $response1 = $action->execute($incoming1);

    // Now send a query about shop licence (should override context)
    $incoming2 = new IncomingChatMessageData(
        message: 'بدي أفتح محل ملابس',
        sessionId: 'test-phase5-override',
    );
    $response2 = $action->execute($incoming2);

    // The response should reference the shop, not building permit
    expect($response2->message)->toContain('ترخيص محل');
});

it('Phase 5: search result has deterministic ranking', function (): void {
    $search = app(SmartServiceSearchInterface::class);

    $result1 = $search->search('بدي أبني بيت');
    $result2 = $search->search('بدي أبني بيت');

    expect($result1->bestMatch->serviceId)->toBe($result2->bestMatch->serviceId);
    expect($result1->bestMatch->score)->toBe($result2->bestMatch->score);
});

// =============================================
// Context Override Tests
// =============================================

it('Phase 6: water context is overridden by explicit mayor question', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-context-override-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'متى المي؟',
        sessionId: $sessionId,
    );
    $response1 = $action->execute($incoming1);
    expect($response1->message)->toContain('المياه');

    $incoming2 = new IncomingChatMessageData(
        message: 'مين رئيس البلديه',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);
    expect($response2->message)->toContain('رئيس البلدية');
    expect($response2->message)->not->toContain('منطقة');
});

it('Phase 6: pending water clarification is cancelled by explicit mayor question', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-pending-clarification-cancel-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'متى المي؟',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'مين رئيس البلديه',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);
    expect($response2->message)->toContain('رئيس البلدية');
});

it('Phase 6: previous jobs context is overridden by explicit water question', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-jobs-to-water-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'في وظائف؟',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'متى المي؟',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);
    expect($response2->message)->toContain('المياه');
});

it('Phase 6: previous service context is overridden by explicit council question', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-service-to-council-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي رخصة بناء',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'مين أعضاء المجلس',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);
    expect($response2->message)->toContain('أعضاء المجلس');
});

it('Phase 6: previous council context does not misuse service follow-up', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-council-to-service-followup-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'مين أعضاء المجلس',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'قديش رسومها',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);
    expect($response2->message)->toContain('توضح اسم الخدمة');
});

it('Phase 6: same-domain follow-up after mayor question uses mayor context', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-mayor-followup-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'مين رئيس البلديه',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'مين أعضاء المجلس',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);
    expect($response2->message)->toContain('أعضاء المجلس');
});

it('Phase 6: unpublished mayor does not appear', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-unpublished-mayor-'.time();

    $incoming = new IncomingChatMessageData(
        message: 'مين رئيس البلديه',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming);
    expect($response->message)->toContain('غير منشورة');
});

it('Phase 6: session isolation remains valid', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);

    $sessionA = 'test-isolation-a-'.time();
    $sessionB = 'test-isolation-b-'.time();

    $incomingA = new IncomingChatMessageData(
        message: 'متى المي؟',
        sessionId: $sessionA,
    );
    $action->execute($incomingA);

    $incomingB = new IncomingChatMessageData(
        message: 'مين رئيس البلديه',
        sessionId: $sessionB,
    );
    $responseB = $action->execute($incomingB);

    expect($responseB->message)->toContain('رئيس البلدية');
});

// =============================================
// Guided Service Discovery Tests
// =============================================

beforeEach(function (): void {
    $this->electronicCategory = ServiceCategory::where('slug', 'alkhdmat-alalktrony')->firstOrFail();

    $this->electronicService = ElectronicService::create([
        'service_category_id' => $this->electronicCategory->id,
        'name' => 'طلب خدمة رقمية',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 1,
        'steps' => ['تعبئة الطلب', 'إرفاق المستندات', 'دفع الرسوم'],
        'requirements' => ['هوية', 'سند ملكية'],
        'fees' => [['item' => 'رسوم الترخيص', 'amount' => '500 شيكل']],
        'processing_time' => '15 يوم',
        'portal_url' => 'https://portal.idhna.ps/apply/building-permit',
    ]);

    $this->electronicService2 = ElectronicService::create([
        'service_category_id' => $this->electronicCategory->id,
        'name' => 'طلب دعم فني',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 2,
        'steps' => ['تعبئة الطلب', 'الفحص'],
        'requirements' => ['هوية', 'فواتير مياه سابقة'],
        'fees' => [['item' => 'رسوم الاشتراك', 'amount' => '100 شيكل']],
        'processing_time' => '5 أيام',
        'portal_url' => 'https://portal.idhna.ps/apply/water-subscription',
    ]);

    $this->unpublishedElectronicService = ElectronicService::create([
        'service_category_id' => $this->electronicCategory->id,
        'name' => 'خدمة سرية',
        'status' => 'active',
        'is_public' => false,
    ]);
});

it('guided discovery: broad service request enters WaitingForServiceSelection', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: 'test-guided-broad-'.time(),
    );

    $response = $action->execute($incoming);

    expect($response->needsClarification)->toBeFalse();
    expect($response->type)->toBe('text');
    expect($response->message)->toContain('اختر التصنيف');
});

it('guided discovery: electronic services come from database', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: 'test-guided-cats-'.time(),
    );

    $response = $action->execute($incoming);

    expect($response->actions)->not->toBeEmpty();
    $labels = array_column($response->actions, 'label');
    expect($labels)->toContain('الخدمات الإلكترونية');
    expect($labels)->not->toContain('خدمة سرية');

    $incoming2 = new IncomingChatMessageData(
        message: 'الخدمات الإلكترونية',
        sessionId: 'test-guided-cats-'.time(),
    );
    $response2 = $action->execute($incoming2);

    expect($response2->actions)->not->toBeEmpty();
    $serviceLabels = array_column($response2->actions, 'label');
    expect($serviceLabels)->toContain('طلب خدمة رقمية');
    expect($serviceLabels)->toContain('طلب دعم فني');
    expect($serviceLabels)->not->toContain('خدمة سرية');
});

it('guided discovery: electronic list state persists in context', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-cat-persist-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $conversation = app(ChatbotConversationRepositoryInterface::class)
        ->findActiveBySession($sessionId);

    expect($conversation)->not->toBeNull();
    expect($conversation->metadata['state'])->toBe('waiting_for_service_selection');
    expect($conversation->metadata['current_category_id'])->toBeNull();
});

it('guided discovery: service selection by name resolves from the database', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-svc-list-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'طلب دعم فني',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming2);

    expect($response->message)->toContain('دعم فني للمواطنين');
    $labels = array_column($response->actions, 'label');
    expect($labels)->toContain('الرسوم');
    expect($labels)->toContain('المتطلبات');
});

it('guided discovery: service selection persists in context', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-svc-persist-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'طلب دعم فني',
        sessionId: $sessionId,
    );
    $action->execute($incoming2);

    $conversation = app(ChatbotConversationRepositoryInterface::class)
        ->findActiveBySession($sessionId);

    expect($conversation->metadata['state'])->toBe('normal');
    expect($conversation->metadata['current_service_id'])->toBe($this->electronicService2->id);
    expect($conversation->metadata['current_service_name'])->toBe('طلب دعم فني');
});

it('guided discovery: action choice uses selected service', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-action-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'طلب خدمة رقمية',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming2);

    expect($response->message)->toContain('ممكن تسأل عن أي تفصيل');
    expect($response->actions)->not->toBeEmpty();
    $labels = array_column($response->actions, 'label');
    expect($labels)->toContain('الرسوم');
    expect($labels)->toContain('المتطلبات');
    expect($labels)->toContain('خطوات التقديم');
    expect($labels)->toContain('مدة الخدمة');
    expect($labels)->toContain('مكان التقديم');
    expect($labels)->toContain('رابط التقديم');
});

it('guided discovery: action result uses selected service id', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-action-result-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'طلب خدمة رقمية',
        sessionId: $sessionId,
    );
    $action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'الرسوم',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming3);

    expect($response->message)->toContain('رسوم خدمة');
    expect(json_encode($response->items, JSON_UNESCAPED_UNICODE))->toContain('500');
});

it('guided discovery: UnknownHandler is never called for broad service discovery', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: 'test-guided-unknown-'.time(),
    );

    $response = $action->execute($incoming);

    expect($response->type)->not->toBe('clarification');
    expect($response->message)->not->toContain('ما فهمت');
    expect($response->message)->not->toContain('للأسف ما لقيت');
});

it('guided discovery: explicit new domain exits guided flow', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-domain-switch-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'متى المي؟',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming2);

    expect($response->message)->toContain('المياه');
});

it('guided discovery: back from electronic list re-lists the electronic services', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-back-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'رجوع',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming2);

    expect($response->message)->toContain('اختر التصنيف');
});

it('guided discovery: cancel clears guided context', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-cancel-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'إلغاء',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming2);

    expect($response->message)->toContain('كيف أقدر أساعدك');
});

it('guided discovery: no duplicate messages', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-nodup-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $response1 = $action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: 'رخص وبناء',
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);

    expect($response1->message)->not->toBe($response2->message);
});

it('guided discovery: quick action sends real payload', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: 'test-guided-quick-'.time(),
    );

    $response = $action->execute($incoming);

    expect($response->actions)->not->toBeEmpty();
    foreach ($response->actions as $action) {
        expect($action['value'])->not->toBe('1');
        expect($action['value'])->not->toBe('3');
    }
});

it('guided discovery: unpublished service never appears', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $sessionId = 'test-guided-unpub-svc-'.time();

    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );
    $response = $action->execute($incoming1);

    expect($response->message)->not->toContain('خدمة سرية');

    $labels = array_column($response->actions, 'label');
    expect($labels)->not->toContain('خدمة سرية');
});

it('guided discovery: session isolation remains valid', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);

    $sessionA = 'test-guided-iso-a-'.time();
    $sessionB = 'test-guided-iso-b-'.time();

    $incomingA = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionA,
    );
    $action->execute($incomingA);

    $incomingB = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionB,
    );
    $responseB = $action->execute($incomingB);

    expect($responseB->message)->toContain('اختر التصنيف');
});
