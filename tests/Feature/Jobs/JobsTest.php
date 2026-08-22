<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Jobs\Actions\CreateJobAction;
use App\Domains\Jobs\Actions\PublishJobAction;
use App\Domains\Jobs\Actions\RecordJobViewAction;
use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\DTOs\JobData;
use App\Domains\Jobs\Models\Job;
use App\Livewire\Jobs\PublicJobShow;
use App\Livewire\Jobs\PublicJobsIndex;
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
// Create Job
// ============================================

it('can create a job via action', function (): void {
    $dto = JobData::fromRequest([
        'title' => 'مهندس بلدي',
        'employmentType' => 'full_time',
        'location' => 'إذنا',
        'summary' => 'مطلوب مهندس بلدي',
        'description' => 'وصف الوظيفة',
        'requirements' => ['بكالوريوس هندسة', 'خبرة 3 سنوات'],
        'responsibilities' => ['الإشراف على المشاريع'],
        'requiredDocuments' => ['السيرة الذاتية', 'الهوية'],
        'applicationMethod' => 'email',
        'publishAt' => now()->toDateString(),
        'closingAt' => now()->addMonth()->toDateString(),
    ]);

    $job = app(CreateJobAction::class)->execute($dto);

    expect($job)->toBeInstanceOf(Job::class);
    expect($job->title)->toBe('مهندس بلدي');
});

// ============================================
// Publish Job
// ============================================

it('can publish a job', function (): void {
    $job = Job::factory()->draft()->create();

    app(PublishJobAction::class)->execute($job->id);

    expect($job->fresh()->status->value)->toBe('published');
    expect($job->fresh()->is_public)->toBeTrue();
});

// ============================================
// Close Job
// ============================================

it('can close a job', function (): void {
    $job = Job::factory()->create();

    app(JobRepositoryInterface::class)->close($job->id);

    expect($job->fresh()->status->value)->toBe('closed');
});

// ============================================
// Archive Job
// ============================================

it('can archive a job', function (): void {
    $job = Job::factory()->create();

    app(JobRepositoryInterface::class)->archive($job->id);

    expect($job->fresh()->status->value)->toBe('archived');
});

// ============================================
// View Published Jobs
// ============================================

it('published jobs are visible on public page', function (): void {
    Job::factory()->count(3)->create();
    Job::factory()->draft()->create();

    Livewire::test(PublicJobsIndex::class)
        ->assertCount('jobs.items', 3);
});

// ============================================
// Expired Jobs Hidden
// ============================================

it('expired jobs are not shown on public page', function (): void {
    Job::factory()->create(['closing_at' => now()->subDay()->toDateString(), 'status' => 'published']);

    Livewire::test(PublicJobsIndex::class)
        ->assertCount('jobs.items', 0);
});

// ============================================
// Closed Jobs Hidden
// ============================================

it('closed jobs are not shown on public page', function (): void {
    Job::factory()->closed()->create();

    Livewire::test(PublicJobsIndex::class)
        ->assertCount('jobs.items', 0);
});

// ============================================
// Views Counter
// ============================================

it('increments view count when viewing job', function (): void {
    $job = Job::factory()->create(['views_count' => 0]);

    app(RecordJobViewAction::class)->execute($job->id);

    expect($job->fresh()->views_count)->toBe(1);
});

// ============================================
// Job Detail Page
// ============================================

it('job detail page loads successfully', function (): void {
    $job = Job::factory()->create();

    Livewire::test(PublicJobShow::class, ['job' => $job])
        ->assertOk()
        ->assertSee($job->title);
});

it('draft job detail page returns 404', function (): void {
    $job = Job::factory()->draft()->create();

    Livewire::test(PublicJobShow::class, ['job' => $job])
        ->assertStatus(404);
});

// ============================================
// Featured Jobs
// ============================================

it('featured jobs are displayed', function (): void {
    Job::factory()->featured()->create();

    Livewire::test(PublicJobsIndex::class)
        ->assertOk();
});

// ============================================
// Permission Tests
// ============================================

it('unauthorized user cannot view dashboard', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.jobs'))
        ->assertStatus(403);
});

it('authorized user can view dashboard', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('jobs.view');

    actingAs($user);

    get(route('dashboard.jobs'))->assertOk();
});

it('unauthorized user cannot create job', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.jobs.create'))
        ->assertStatus(403);
});
