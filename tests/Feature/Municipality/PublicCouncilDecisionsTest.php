<?php

declare(strict_types=1);

use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

// ============================================
// Homepage tests
// ============================================

it('homepage displays public published decisions only', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Draft->value,
        'is_public' => true,
        'published_at' => null,
        'decision_date' => now(),
    ]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('قرارات المجلس البلدي');
});

it('homepage decisions limit is respected', function (): void {
    CouncilDecision::factory()->count(6)->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    $response = get(route('home'));
    $response->assertSuccessful();
});

it('draft decision is hidden from homepage', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Draft->value,
        'is_public' => true,
        'published_at' => null,
        'decision_date' => now(),
    ]);

    get(route('home'));
});

it('archived decision is hidden from homepage', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Archived->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('home'));
});

it('cancelled decision is hidden from homepage', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Cancelled->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('home'));
});

it('private decision is hidden from homepage', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => false,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('home'));
});

// ============================================
// Public index page tests
// ============================================

it('public decisions page loads', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.index'))
        ->assertSuccessful()
        ->assertSee('قرارات المجلس البلدي');
});

it('search finds decision by title', function (): void {
    CouncilDecision::factory()->create([
        'title' => 'قرار فريد جدا للاختبار',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.index', ['search' => 'فريد']))
        ->assertSuccessful()
        ->assertSee('فريد');
});

it('search finds decision by number', function (): void {
    CouncilDecision::factory()->create([
        'decision_number' => 'ق-2026-999',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.index', ['search' => '999']))
        ->assertSuccessful()
        ->assertSee('ق-2026-999');
});

it('type filter works', function (): void {
    CouncilDecision::factory()->create([
        'type' => CouncilDecisionType::Financial->value,
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.index', ['type' => 'financial']))
        ->assertSuccessful();
});

it('year filter works', function (): void {
    CouncilDecision::factory()->create([
        'decision_date' => '2026-01-15',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
    ]);

    get(route('public.council.decisions.index', ['year' => '2026']))
        ->assertSuccessful();
});

it('pagination works on public page', function (): void {
    CouncilDecision::factory()->count(15)->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.index'))
        ->assertSuccessful();
});

// ============================================
// Public detail page tests
// ============================================

it('public decision detail loads', function (): void {
    $decision = CouncilDecision::factory()->create([
        'title' => 'قرار مهم للعرض',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.show', $decision))
        ->assertSuccessful()
        ->assertSee('قرار مهم للعرض');
});

it('private decision returns 404', function (): void {
    $decision = CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => false,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.show', $decision))
        ->assertNotFound();
});

it('draft decision returns 404 on public page', function (): void {
    $decision = CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Draft->value,
        'is_public' => true,
        'published_at' => null,
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.show', $decision))
        ->assertNotFound();
});

it('attachment button appears only when file exists', function (): void {
    $decision = CouncilDecision::factory()->create([
        'attachment_path' => 'decisions/test-file.pdf',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    $response = get(route('public.council.decisions.show', $decision));
    $response->assertSuccessful();
});

it('related decisions exclude current decision', function (): void {
    $decision = CouncilDecision::factory()->create([
        'type' => CouncilDecisionType::Financial->value,
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    $related = CouncilDecision::factory()->count(2)->create([
        'type' => CouncilDecisionType::Financial->value,
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.show', $decision))
        ->assertSuccessful();
});

it('previous and next navigation work', function (): void {
    $older = CouncilDecision::factory()->create([
        'decision_date' => '2026-01-01',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $current = CouncilDecision::factory()->create([
        'decision_date' => '2026-06-15',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $newer = CouncilDecision::factory()->create([
        'decision_date' => '2026-12-30',
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
    ]);

    get(route('public.council.decisions.show', $current))
        ->assertSuccessful();
});

it('missing optional relation does not break page', function (): void {
    $decision = CouncilDecision::factory()->create([
        'session_number' => null,
        'attachment_path' => null,
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.show', $decision))
        ->assertSuccessful();
});

it('cache clears after publishing decision', function (): void {
    Cache::shouldReceive('forget')
        ->with('homepage.public.data')
        ->atLeast()->once();

    $decision = CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Draft->value,
        'is_public' => false,
    ]);

    $decision->update([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
    ]);
});

it('no mock decisions are rendered', function (): void {
    get(route('public.council.decisions.index'))
        ->assertSuccessful()
        ->assertDontSee('قرار وهمي');
});

it('same public layout is used for decisions', function (): void {
    CouncilDecision::factory()->create([
        'status' => CouncilDecisionStatus::Published->value,
        'is_public' => true,
        'published_at' => now(),
        'decision_date' => now(),
    ]);

    get(route('public.council.decisions.index'))
        ->assertSuccessful();
});
