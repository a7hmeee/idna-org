<?php

declare(strict_types=1);

use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use App\Domains\Projects\Models\Project;
use App\Livewire\Projects\PublicProjectShow;
use App\Livewire\Projects\PublicProjectsIndex;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// ============================================
// Public Index
// ============================================

it('public projects index page returns 200', function (): void {
    get(route('public.projects.index'))->assertOk();
});

it('public projects index renders livewire component', function (): void {
    Livewire::test(PublicProjectsIndex::class)
        ->assertSuccessful();
});

it('public projects index shows completed projects', function (): void {
    Project::factory()->completed()->count(3)->create();

    Livewire::test(PublicProjectsIndex::class)
        ->assertCount('projects.items', 3);
});

it('public projects index hides non-completed projects', function (): void {
    Project::factory()->completed()->create();
    Project::factory()->create(['project_status' => ProjectStatus::Planned, 'is_public' => false]);

    Livewire::test(PublicProjectsIndex::class)
        ->assertCount('projects.items', 1);
});

it('public projects index hides non-public projects', function (): void {
    Project::factory()->completed()->create(['is_public' => false]);

    Livewire::test(PublicProjectsIndex::class)
        ->assertCount('projects.items', 0);
});

// ============================================
// Search Filter
// ============================================

it('public projects index can search by name', function (): void {
    Project::factory()->completed()->create(['name_ar' => 'مشروع الطريق']);
    Project::factory()->completed()->create(['name_ar' => 'مشروع الماء']);

    Livewire::test(PublicProjectsIndex::class)
        ->set('search', 'الطريق')
        ->assertCount('projects.items', 1);
});

// ============================================
// Category Filter
// ============================================

it('public projects index can filter by category', function (): void {
    Project::factory()->completed()->create(['category' => ProjectCategory::Roads]);
    Project::factory()->completed()->create(['category' => ProjectCategory::Water]);

    Livewire::test(PublicProjectsIndex::class)
        ->set('category', 'roads')
        ->assertCount('projects.items', 1);
});

// ============================================
// Project Status Filter
// ============================================

it('public projects index can filter by project status', function (): void {
    Project::factory()->completed()->create(['project_status' => ProjectStatus::Completed]);
    Project::factory()->inProgress()->create(['status' => ProjectStatus::Completed, 'is_public' => true]);

    Livewire::test(PublicProjectsIndex::class)
        ->set('projectStatus', 'completed')
        ->assertCount('projects.items', 2);
});

// ============================================
// Public Show
// ============================================

it('public project show page returns 200 for completed project', function (): void {
    $project = Project::factory()->completed()->create();

    Livewire::test(PublicProjectShow::class, ['project' => $project])
        ->assertSuccessful()
        ->assertSee($project->name_ar);
});

it('public project show returns 404 for non-completed project', function (): void {
    $project = Project::factory()->create(['project_status' => ProjectStatus::Planned, 'is_public' => false]);

    Livewire::test(PublicProjectShow::class, ['project' => $project])
        ->assertStatus(404);
});

it('public project show returns 404 for non-public project', function (): void {
    $project = Project::factory()->completed()->create(['is_public' => false]);

    Livewire::test(PublicProjectShow::class, ['project' => $project])
        ->assertStatus(404);
});

it('public project show increments view count', function (): void {
    $project = Project::factory()->completed()->create(['views_count' => 3]);

    Livewire::test(PublicProjectShow::class, ['project' => $project]);

    expect($project->fresh()->views_count)->toBe(4);
});

// ============================================
// Slug-based routing
// ============================================

it('public project show by slug works', function (): void {
    $project = Project::factory()->completed()->create();

    get(route('public.projects.show', $project->slug))->assertOk();
});

it('public project show by invalid slug returns 404', function (): void {
    get(route('public.projects.show', 'this-slug-does-not-exist'))
        ->assertStatus(404);
});

// ============================================
// Featured
// ============================================

it('featured projects are displayed on public index', function (): void {
    Project::factory()->completed()->featured()->create();
    Project::factory()->completed()->create();

    Livewire::test(PublicProjectsIndex::class)
        ->assertSuccessful();
});

// ============================================
// Model Scopes
// ============================================

it('published scope only returns completed and public projects', function (): void {
    Project::factory()->inProgress()->create(['status' => ProjectStatus::Completed, 'is_public' => true]);
    Project::factory()->create(['is_public' => false]);

    $count = Project::published()->count();

    expect($count)->toBe(1);
});

it('isVisible returns correct status', function (): void {
    $completed = Project::factory()->completed()->create();
    $nonPublic = Project::factory()->completed()->create(['is_public' => false]);

    expect($completed->isVisible())->toBeTrue();
    expect($nonPublic->isVisible())->toBeFalse();
});
