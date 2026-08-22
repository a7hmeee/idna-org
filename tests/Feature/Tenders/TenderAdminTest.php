<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Tenders\Actions\ArchiveTenderAction;
use App\Domains\Tenders\Actions\CancelTenderAction;
use App\Domains\Tenders\Actions\CreateTenderAction;
use App\Domains\Tenders\Actions\PublishTenderAction;
use App\Domains\Tenders\Actions\ToggleFeaturedTenderAction;
use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\DTOs\TenderData;
use App\Domains\Tenders\Models\Tender;
use App\Livewire\Tenders\TenderForm;
use App\Livewire\Tenders\TendersIndex;
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

it('redirects unauthenticated user from admin tenders index', function (): void {
    get(route('dashboard.tenders'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin tenders create', function (): void {
    get(route('dashboard.tenders.create'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin tenders edit', function (): void {
    $tender = Tender::factory()->create();

    get(route('dashboard.tenders.edit', $tender))->assertRedirect(route('login'));
});

it('returns 403 for user without tenders.view permission', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Employee');

    actingAs($user);

    get(route('dashboard.tenders'))->assertForbidden();
});

it('returns 403 for user without tenders.create permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('tenders.view');

    actingAs($user);

    get(route('dashboard.tenders.create'))->assertForbidden();
});

it('returns 403 for user without tenders.update permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('tenders.view');
    $tender = Tender::factory()->create();

    actingAs($user);

    get(route('dashboard.tenders.edit', $tender))->assertForbidden();
});

// ============================================
// Admin View
// ============================================

it('admin can view tenders dashboard', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(TendersIndex::class)
        ->assertSuccessful();
});

it('admin can view create tender form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(TenderForm::class)
        ->assertSuccessful()
        ->assertSet('status', 'draft');
});

it('admin can view edit tender form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tender = Tender::factory()->create();

    actingAs($user);

    Livewire::test(TenderForm::class, ['tender' => $tender])
        ->assertSuccessful()
        ->assertSet('titleAr', $tender->title_ar)
        ->assertSet('tenderId', $tender->id);
});

// ============================================
// Create Tender
// ============================================

it('admin can create tender via form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(TenderForm::class)
        ->set('titleAr', 'مناقصة جديدة')
        ->set('summary', 'ملخص المناقصة الجديدة')
        ->set('description', 'وصف المناقصة الجديدة')
        ->set('issuingDepartment', 'دائرة اللوازم')
        ->set('publicationDate', now()->toDateString())
        ->set('submissionDeadline', now()->addMonth()->toDateString())
        ->set('status', 'draft')
        ->call('save')
        ->assertRedirect(route('dashboard.tenders'));

    expect(Tender::count())->toBe(1);
    expect(Tender::first()->title_ar)->toBe('مناقصة جديدة');
});

it('can create tender via action', function (): void {
    $dto = TenderData::fromRequest([
        'titleAr' => 'مناقصة تجريبية',
        'summary' => 'ملخص المناقصة التجريبية',
        'description' => 'وصف المناقصة التجريبية',
        'issuingDepartment' => 'دائرة المشتريات',
        'publicationDate' => now()->toDateString(),
        'submissionDeadline' => now()->addMonth()->toDateString(),
        'status' => 'draft',
        'createdBy' => 1,
        'updatedBy' => 1,
    ]);

    $tender = app(CreateTenderAction::class)->execute($dto);

    expect($tender)->toBeInstanceOf(Tender::class);
    expect($tender->title_ar)->toBe('مناقصة تجريبية');
    expect($tender->status->value)->toBe('draft');
});

// ============================================
// Validation
// ============================================

it('validates required fields on tender create', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(TenderForm::class)
        ->set('titleAr', '')
        ->call('save')
        ->assertHasErrors(['titleAr' => 'required']);
});

it('validates submission deadline is after publication date', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(TenderForm::class)
        ->set('titleAr', 'مناقصة')
        ->set('summary', 'ملخص')
        ->set('description', 'وصف')
        ->set('issuingDepartment', 'دائرة')
        ->set('publicationDate', now()->addMonth()->toDateString())
        ->set('submissionDeadline', now()->toDateString())
        ->call('save')
        ->assertHasErrors(['submissionDeadline' => 'after_or_equal']);
});

// ============================================
// Publish Workflow
// ============================================

it('can publish a tender', function (): void {
    $tender = Tender::factory()->draft()->create();

    app(PublishTenderAction::class)->execute($tender->id);

    expect($tender->fresh()->status->value)->toBe('open');
});

it('admin can publish tender from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tender = Tender::factory()->draft()->create();

    actingAs($user);

    Livewire::test(TendersIndex::class)
        ->call('publish', $tender->id)
        ->assertSessionHas('success');

    expect($tender->fresh()->status->value)->toBe('open');
});

// ============================================
// Cancel Tender
// ============================================

it('can cancel a tender', function (): void {
    $tender = Tender::factory()->create();

    app(CancelTenderAction::class)->execute($tender->id);

    expect($tender->fresh()->status->value)->toBe('cancelled');
});

it('admin can cancel tender from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tender = Tender::factory()->create();

    actingAs($user);

    Livewire::test(TendersIndex::class)
        ->call('cancel', $tender->id)
        ->assertSessionHas('success');

    expect($tender->fresh()->status->value)->toBe('cancelled');
});

// ============================================
// Archive Tender
// ============================================

it('can archive a tender', function (): void {
    $tender = Tender::factory()->closed()->create();

    app(ArchiveTenderAction::class)->execute($tender->id);

    expect($tender->fresh()->status->value)->toBe('archived');
});

it('admin can archive tender from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tender = Tender::factory()->closed()->create();

    actingAs($user);

    Livewire::test(TendersIndex::class)
        ->call('archive', $tender->id)
        ->assertSessionHas('success');

    expect($tender->fresh()->status->value)->toBe('archived');
});

// ============================================
// Toggle Featured
// ============================================

it('can toggle featured status on tender', function (): void {
    $tender = Tender::factory()->create(['is_featured' => false]);

    app(ToggleFeaturedTenderAction::class)->execute($tender->id);

    expect($tender->fresh()->is_featured)->toBeTrue();
});

it('admin can toggle featured from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tender = Tender::factory()->create(['is_featured' => false]);

    actingAs($user);

    Livewire::test(TendersIndex::class)
        ->call('toggleFeatured', $tender->id)
        ->assertSessionHas('success');

    expect($tender->fresh()->is_featured)->toBeTrue();
});

// ============================================
// Delete
// ============================================

it('can soft delete a tender', function (): void {
    $tender = Tender::factory()->create();

    app(TenderRepositoryInterface::class)->delete($tender->id);

    expect(Tender::count())->toBe(0);
    expect(Tender::withTrashed()->count())->toBe(1);
});

it('admin can delete tender from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tender = Tender::factory()->create();

    actingAs($user);

    Livewire::test(TendersIndex::class)
        ->call('confirmDelete', $tender->id)
        ->assertSet('showDeleteModal', true)
        ->call('delete')
        ->assertSessionHas('success');

    expect(Tender::count())->toBe(0);
});

// ============================================
// Slug Generation
// ============================================

it('auto-generates slug from title_ar on tender create', function (): void {
    $tender = Tender::factory()->create(['title_ar' => 'مناقصة جديدة جدا', 'slug' => null]);

    expect($tender->fresh()->slug)->not->toBeNull();
});

// ============================================
// Pagination
// ============================================

it('dashboard paginates tenders', function (): void {
    Tender::factory()->count(25)->create();

    $paginator = app(TenderRepositoryInterface::class)->paginateDashboard();

    expect($paginator->total())->toBe(25);
});

// ============================================
// View Count
// ============================================

it('can increment tender views count', function (): void {
    $tender = Tender::factory()->create(['views_count' => 0]);

    app(TenderRepositoryInterface::class)->incrementViews($tender->id);

    expect($tender->fresh()->views_count)->toBe(1);
});
