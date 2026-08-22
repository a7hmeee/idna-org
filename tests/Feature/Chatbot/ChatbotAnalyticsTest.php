<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\ChatbotAnalytics\Contracts\DatasetVersionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\IntentAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\PerformanceLogRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\SearchAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\UnknownQuestionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\WorkflowAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Events\UnknownQuestionDetectedEvent;
use App\Domains\ChatbotAnalytics\Repositories\EloquentDatasetVersionRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentIntentAnalyticsRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentPerformanceLogRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentSearchAnalyticsRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentUnknownQuestionRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentWorkflowAnalyticsRepository;
use App\Domains\ChatbotAnalytics\Services\PerformanceMonitorService;
use App\Livewire\Admin\Chatbot\ChatbotDashboard;
use App\Livewire\Admin\Chatbot\UnknownQuestionsManager;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    seed(RolePermissionSeeder::class);
});

// =============================================
// Migration & Model Tests
// =============================================

it('has chatbot_intent_analytics table and indexes', function (): void {
    expect(Schema::hasTable('chatbot_intent_analytics'))->toBeTrue();
    expect(Schema::hasColumns('chatbot_intent_analytics', [
        'id', 'conversation_id', 'message_id', 'predicted_intent', 'final_intent',
        'confidence', 'prediction_source', 'handler_used', 'execution_time_ms',
        'clarification_happened', 'is_unknown', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('has chatbot_unknown_questions table', function (): void {
    expect(Schema::hasTable('chatbot_unknown_questions'))->toBeTrue();
    expect(Schema::hasColumns('chatbot_unknown_questions', [
        'id', 'question', 'normalized_question', 'conversation_id', 'detected_intent',
        'prediction_confidence', 'suggested_domain', 'occurrence_count', 'last_seen_at',
        'admin_status', 'admin_notes', 'created_at', 'updated_at', 'deleted_at',
    ]))->toBeTrue();
});

it('has chatbot_search_analytics table', function (): void {
    expect(Schema::hasTable('chatbot_search_analytics'))->toBeTrue();
});

it('has chatbot_workflow_analytics table', function (): void {
    expect(Schema::hasTable('chatbot_workflow_analytics'))->toBeTrue();
});

it('has chatbot_performance_logs table', function (): void {
    expect(Schema::hasTable('chatbot_performance_logs'))->toBeTrue();
});

it('has chatbot_dataset_versions table', function (): void {
    expect(Schema::hasTable('chatbot_dataset_versions'))->toBeTrue();
});

// =============================================
// Repository and Bindings Tests
// =============================================

it('resolves all analytics repositories correctly', function (): void {
    expect(app(IntentAnalyticsRepositoryInterface::class))->toBeInstanceOf(EloquentIntentAnalyticsRepository::class);
    expect(app(UnknownQuestionRepositoryInterface::class))->toBeInstanceOf(EloquentUnknownQuestionRepository::class);
    expect(app(SearchAnalyticsRepositoryInterface::class))->toBeInstanceOf(EloquentSearchAnalyticsRepository::class);
    expect(app(WorkflowAnalyticsRepositoryInterface::class))->toBeInstanceOf(EloquentWorkflowAnalyticsRepository::class);
    expect(app(PerformanceLogRepositoryInterface::class))->toBeInstanceOf(EloquentPerformanceLogRepository::class);
    expect(app(DatasetVersionRepositoryInterface::class))->toBeInstanceOf(EloquentDatasetVersionRepository::class);
});

// =============================================
// Services Tests
// =============================================

it('calculates metrics in analytics services correctly', function (): void {
    // Write test data for performance
    $perfRepo = app(PerformanceLogRepositoryInterface::class);
    $perfRepo->create([
        'context' => 'test_context',
        'duration_ms' => 150,
        'slow_flag' => false,
    ]);
    $perfRepo->create([
        'context' => 'test_context',
        'duration_ms' => 600,
        'slow_flag' => true,
    ]);

    $perfService = app(PerformanceMonitorService::class);
    $report = $perfService->generateReport(Carbon::now()->subDay(), Carbon::now()->addDay());

    expect($report->totalRequests)->toBe(2);
    expect($report->slowRequests)->toBe(1);
    expect($report->slowRate)->toBe(50.0);
    expect($report->avgResponseTimeMs)->toBe(375.0);
});

// =============================================
// Livewire Components Tests
// =============================================

it('can render ChatbotDashboard', function (): void {
    $user = User::factory()->create();
    $role = Role::where('name', 'Admin')->first();
    $user->roles()->save($role);
    actingAs($user);

    Livewire::test(ChatbotDashboard::class)
        ->assertStatus(200)
        ->assertViewHas('period', '7')
        ->assertViewHas('conversationStats')
        ->assertViewHas('intentDistribution')
        ->assertViewHas('knowledgeGaps')
        ->assertViewHas('performanceStats');
});

it('can filter and update status in UnknownQuestionsManager', function (): void {
    $user = User::factory()->create();
    $role = Role::where('name', 'Admin')->first();
    $user->roles()->save($role);
    actingAs($user);

    $repo = app(UnknownQuestionRepositoryInterface::class);
    $question = $repo->createOrIncrement('مرحبا بلدية', 'مرحبا بلديه');

    Livewire::test(UnknownQuestionsManager::class)
        ->assertStatus(200)
        ->set('statusFilter', 'new')
        ->call('openUpdate', $question->id)
        ->set('newStatus', 'reviewed')
        ->set('adminNotes', 'Reviewed notes')
        ->call('updateStatus')
        ->assertDispatched('status-updated');

    $updated = $question->fresh();
    expect($updated->admin_status)->toBe('reviewed');
    expect($updated->admin_notes)->toBe('Reviewed notes');
});

// =============================================
// Event Dispatching Tests
// =============================================

it('dispatches UnknownQuestionDetectedEvent on unknown question path', function (): void {
    Event::fake([UnknownQuestionDetectedEvent::class]);

    $action = app(ProcessRuleBasedChatMessageAction::class);

    // Send a message that returns unknown
    $incoming = new IncomingChatMessageData(
        message: 'كلمات عشوائية غير مفهومة إطلاقاً',
        sessionId: 'session-test-xyz',
        userId: null,
    );

    $action->execute($incoming);

    Event::assertDispatched(UnknownQuestionDetectedEvent::class);
});
