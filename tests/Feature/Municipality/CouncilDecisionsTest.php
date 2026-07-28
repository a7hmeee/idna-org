<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

it('redirects unauthenticated user to login', function (): void {
    get(route('dashboard.municipality.council-decisions'))
        ->assertRedirect(route('login'));
});

it('returns 403 for user without council_decisions.view permission', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('dashboard.municipality.council-decisions'))
        ->assertForbidden();
});

it('admin can view decisions index', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('council_decisions.view');

    actingAs($user)
        ->get(route('dashboard.municipality.council-decisions'))
        ->assertSuccessful();
});

it('admin can view create decision form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('council_decisions.create');

    actingAs($user)
        ->get(route('dashboard.municipality.council-decisions.create'))
        ->assertSuccessful();
});

it('admin can view edit decision form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('council_decisions.update');

    $decision = CouncilDecision::factory()->create();

    actingAs($user)
        ->get(route('dashboard.municipality.council-decisions.edit', $decision))
        ->assertSuccessful();
});

it('admin can view decision show page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('council_decisions.view');

    $decision = CouncilDecision::factory()->create();

    actingAs($user)
        ->get(route('dashboard.municipality.council-decisions.show', $decision))
        ->assertSuccessful();
});

it('admin can create decision', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['council_decisions.create', 'council_decisions.view']);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionForm::class)
        ->set('decision_number', 'ق-2026-001')
        ->set('title', 'قرار اختبار')
        ->set('type', 'administrative')
        ->set('status', 'draft')
        ->call('save')
        ->assertRedirect(route('dashboard.municipality.council-decisions'));

    expect(CouncilDecision::where('decision_number', 'ق-2026-001')->exists())->toBeTrue();
});

it('admin can update decision', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['council_decisions.update', 'council_decisions.view']);

    $decision = CouncilDecision::factory()->create([
        'decision_number' => 'ق-2026-001',
        'title' => 'العنوان القديم',
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionForm::class, ['councilDecision' => $decision])
        ->set('title', 'العنوان الجديد')
        ->call('save')
        ->assertRedirect(route('dashboard.municipality.council-decisions'));

    expect($decision->fresh()->title)->toBe('العنوان الجديد');
});

it('admin can publish decision', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['council_decisions.publish', 'council_decisions.view']);

    $decision = CouncilDecision::factory()->create([
        'status' => 'draft',
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionsIndex::class)
        ->call('publish', $decision->id)
        ->assertSessionHas('success');

    expect($decision->fresh()->status)->toBe('published');
    expect($decision->fresh()->published_at)->not->toBeNull();
});

it('admin can archive decision', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['council_decisions.archive', 'council_decisions.view']);

    $decision = CouncilDecision::factory()->create([
        'status' => 'published',
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionsIndex::class)
        ->call('archive', $decision->id)
        ->assertSessionHas('success');

    expect($decision->fresh()->status)->toBe('archived');
    expect($decision->fresh()->archived_at)->not->toBeNull();
});

it('admin can cancel decision', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['council_decisions.cancel', 'council_decisions.view']);

    $decision = CouncilDecision::factory()->create([
        'status' => 'published',
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionsIndex::class)
        ->call('cancel', $decision->id)
        ->assertSessionHas('success');

    expect($decision->fresh()->status)->toBe('cancelled');
});

it('unauthorized user cannot manage decisions', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('council_decisions.view');

    $decision = CouncilDecision::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionsIndex::class)
        ->call('publish', $decision->id)
        ->assertForbidden();
});

it('admin can delete decision', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['council_decisions.delete', 'council_decisions.view']);

    $decision = CouncilDecision::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Municipality\CouncilDecisionsIndex::class)
        ->call('confirmDelete', $decision->id)
        ->assertSet('showDeleteModal', true)
        ->call('delete')
        ->assertSessionHas('success');

    expect(CouncilDecision::find($decision->id))->toBeNull();
});
