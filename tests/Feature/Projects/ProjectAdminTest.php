<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Projects\Actions\CreateProjectAction;
use App\Domains\Projects\Actions\PublishProjectAction;
use App\Domains\Projects\Actions\ToggleFeaturedProjectAction;
use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\DTOs\ProjectData;
use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use App\Domains\Projects\Models\Project;
use App\Livewire\Projects\ProjectForm;
use App\Livewire\Projects\ProjectsIndex;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// ============================================
// Authentication & Authorization
// ============================================

it('redirects unauthenticated user from admin projects index', function (): void {
    get(route('dashboard.projects'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin projects create', function (): void {
    get(route('dashboard.projects.create'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin projects edit', function (): void {
    $project = Project::factory()->create();

    get(route('dashboard.projects.edit', $project))->assertRedirect(route('login'));
});

it('returns 403 for user without projects.view permission', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Employee');

    actingAs($user);

    get(route('dashboard.projects'))->assertForbidden();
});

it('returns 403 for user without projects.create permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('projects.view');

    actingAs($user);

    get(route('dashboard.projects.create'))->assertForbidden();
});

it('returns 403 for user without projects.update permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('projects.view');
    $project = Project::factory()->create();

    actingAs($user);

    get(route('dashboard.projects.edit', $project))->assertForbidden();
});

// ============================================
// Admin View
// ============================================

it('admin can view projects dashboard', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ProjectsIndex::class)
        ->assertSuccessful();
});

it('admin can view create form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ProjectForm::class)
        ->assertSuccessful();
});

it('admin can view edit form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $project = Project::factory()->create();

    actingAs($user);

    Livewire::test(ProjectForm::class, ['project' => $project])
        ->assertSuccessful()
        ->assertSet('nameAr', $project->name_ar)
        ->assertSet('projectId', $project->id);
});

// ============================================
// Create Project
// ============================================

it('admin can create project via form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ProjectForm::class)
        ->set('nameAr', 'مشروع جديد')
        ->set('nameEn', 'New Project')
        ->set('category', 'infrastructure')
        ->set('summary', 'ملخص المشروع')
        ->set('description', 'وصف المشروع')
        ->set('location', 'إذنا')
        ->set('startDate', now()->toDateString())
        ->set('expectedCompletionDate', now()->addMonths(6)->toDateString())
        ->call('save')
        ->assertRedirect(route('dashboard.projects'));

    expect(Project::count())->toBe(1);
    expect(Project::first()->name_ar)->toBe('مشروع جديد');
});

it('can create project via action', function (): void {
    $dto = ProjectData::fromRequest([
        'nameAr' => 'مشروع تجريبي',
        'category' => ProjectCategory::Roads,
        'projectStatus' => ProjectStatus::Planned,
        'status' => ProjectStatus::Planned,
        'budgetCurrency' => 'ILS',
        'implementationPercentage' => 0,
    ]);

    $project = app(CreateProjectAction::class)->execute($dto);

    expect($project)->toBeInstanceOf(Project::class);
    expect($project->name_ar)->toBe('مشروع تجريبي');
});

// ============================================
// Validation
// ============================================

it('validates required fields on project create', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ProjectForm::class)
        ->set('nameAr', '')
        ->call('save')
        ->assertHasErrors(['nameAr' => 'required']);
});

// ============================================
// Publish Workflow
// ============================================

it('can publish a project', function (): void {
    $project = Project::factory()->create(['is_public' => false, 'status' => ProjectStatus::Planned]);

    app(PublishProjectAction::class)->execute($project->id);

    expect($project->fresh()->is_public)->toBeTrue();
});

it('can unpublish a project', function (): void {
    $project = Project::factory()->create(['is_public' => true]);

    app(ProjectRepositoryInterface::class)->unpublish($project->id);

    expect($project->fresh()->is_public)->toBeFalse();
});

// ============================================
// Toggle Featured
// ============================================

it('can toggle featured status on project', function (): void {
    $project = Project::factory()->create(['is_featured' => false]);

    app(ToggleFeaturedProjectAction::class)->execute($project->id);

    expect($project->fresh()->is_featured)->toBeTrue();
});

// ============================================
// Delete (Soft Delete)
// ============================================

it('can soft delete a project', function (): void {
    $project = Project::factory()->create();

    app(ProjectRepositoryInterface::class)->delete($project->id);

    expect(Project::count())->toBe(0);
    expect(Project::withTrashed()->count())->toBe(1);
});

// ============================================
// Slug Generation
// ============================================

it('auto-generates slug from name_ar on project create', function (): void {
    $project = Project::factory()->create(['name_ar' => 'مشروع الطريق', 'slug' => null]);

    expect($project->fresh()->slug)->not->toBeNull();
});

// ============================================
// Pagination
// ============================================

it('dashboard paginates projects', function (): void {
    Project::factory()->count(25)->create();

    $paginator = app(ProjectRepositoryInterface::class)->paginateDashboard();

    expect($paginator->total())->toBe(25);
});

// ============================================
// Project Status Scopes
// ============================================

it('can get projects by project status', function (): void {
    Project::factory()->inProgress()->count(2)->create();
    Project::factory()->completed()->create();

    $projects = app(ProjectRepositoryInterface::class)->getByProjectStatus('in_progress');

    expect($projects)->toHaveCount(2);
});

// ============================================
// View Count
// ============================================

it('can increment project views count', function (): void {
    $project = Project::factory()->create(['views_count' => 0]);

    app(ProjectRepositoryInterface::class)->incrementViews($project->id);

    expect($project->fresh()->views_count)->toBe(1);
});

// ============================================
// Admin Index Actions
// ============================================

it('admin can delete project from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $project = Project::factory()->create();

    actingAs($user);

    Livewire::test(ProjectsIndex::class)
        ->call('confirmDelete', $project->id)
        ->assertSet('showDeleteModal', true)
        ->call('delete')
        ->assertSessionHas('success');

    expect(Project::count())->toBe(0);
});

it('admin can toggle featured from projects index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $project = Project::factory()->create(['is_featured' => false]);

    actingAs($user);

    Livewire::test(ProjectsIndex::class)
        ->call('toggleFeatured', $project->id)
        ->assertSessionHas('success');

    expect($project->fresh()->is_featured)->toBeTrue();
});
