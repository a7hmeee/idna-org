<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\PublicFacilities\Actions\CreateFacilityAction;
use App\Domains\PublicFacilities\Actions\CreateFacilityCategoryAction;
use App\Domains\PublicFacilities\Actions\PublishFacilityAction;
use App\Domains\PublicFacilities\Actions\RecordFacilityViewAction;
use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\DTOs\FacilityCategoryData;
use App\Domains\PublicFacilities\DTOs\FacilityData;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use App\Livewire\PublicFacilities\PublicFacilitiesIndex;
use App\Livewire\PublicFacilities\PublicFacilityShow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

// ============================================
// Create Category
// ============================================

it('can create a facility category via action', function (): void {
    $dto = FacilityCategoryData::fromRequest([
        'name' => 'حدائق',
        'icon' => 'tree-pine',
        'description' => 'الحدائق العامة',
    ]);

    $category = app(CreateFacilityCategoryAction::class)->execute($dto);

    expect($category)->toBeInstanceOf(FacilityCategory::class);
    expect($category->name)->toBe('حدائق');
});

// ============================================
// Create Facility
// ============================================

it('can create a facility via action', function (): void {
    $category = FacilityCategory::factory()->create();

    $dto = FacilityData::fromRequest([
        'name' => 'حديقة البلدية',
        'summary' => 'حديقة عامة',
        'description' => 'وصف الحديقة',
        'address' => 'شارع القدس',
        'facilityCategoryId' => $category->id,
        'services' => ['ألعاب أطفال', 'مقاعد'],
        'features' => ['مساحات خضراء'],
        'rules' => ['يمنع التدخين'],
    ]);

    $facility = app(CreateFacilityAction::class)->execute($dto);

    expect($facility)->toBeInstanceOf(Facility::class);
    expect($facility->name)->toBe('حديقة البلدية');
});

// ============================================
// Publish Facility
// ============================================

it('can publish a facility', function (): void {
    $facility = Facility::factory()->draft()->create();

    app(PublishFacilityAction::class)->execute($facility->id);

    expect($facility->fresh()->status->value)->toBe('published');
    expect($facility->fresh()->is_public)->toBeTrue();
});

// ============================================
// Archive Facility
// ============================================

it('can archive a facility', function (): void {
    $facility = Facility::factory()->create();

    app(FacilityRepositoryInterface::class)->archive($facility->id);

    expect($facility->fresh()->status->value)->toBe('archived');
});

// ============================================
// View Published Facilities
// ============================================

it('published facilities are visible on public page', function (): void {
    Facility::factory()->count(3)->create();
    Facility::factory()->draft()->create();

    Livewire::test(PublicFacilitiesIndex::class)
        ->assertCount('facilities.items', 3);
});

// ============================================
// Draft Facilities Hidden
// ============================================

it('draft facilities are not shown on public page', function (): void {
    Facility::factory()->draft()->create();

    Livewire::test(PublicFacilitiesIndex::class)
        ->assertCount('facilities.items', 0);
});

// ============================================
// Views Counter
// ============================================

it('increments view count when viewing facility', function (): void {
    $facility = Facility::factory()->create(['views_count' => 0]);

    app(RecordFacilityViewAction::class)->execute($facility->id);

    expect($facility->fresh()->views_count)->toBe(1);
});

// ============================================
// Facility Detail Page
// ============================================

it('facility detail page loads successfully', function (): void {
    $facility = Facility::factory()->create();

    Livewire::test(PublicFacilityShow::class, ['facility' => $facility])
        ->assertOk()
        ->assertSee($facility->name);
});

it('draft facility detail page returns 404', function (): void {
    $facility = Facility::factory()->draft()->create();

    Livewire::test(PublicFacilityShow::class, ['facility' => $facility])
        ->assertStatus(404);
});

// ============================================
// Featured Facilities
// ============================================

it('featured facilities are displayed', function (): void {
    Facility::factory()->featured()->create();

    Livewire::test(PublicFacilitiesIndex::class)
        ->assertOk();
});

// ============================================
// Permission Tests
// ============================================

it('unauthorized user cannot view dashboard', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.facilities'))
        ->assertStatus(403);
});

it('authorized user can view dashboard', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('facilities.view');

    actingAs($user);

    get(route('dashboard.facilities'))->assertOk();
});

it('unauthorized user cannot create facility', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.facilities.create'))
        ->assertStatus(403);
});
