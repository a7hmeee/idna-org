<?php

declare(strict_types=1);

use App\Domains\Tenders\Enums\TenderStatus;
use App\Domains\Tenders\Models\Tender;
use App\Livewire\Tenders\PublicTenderShow;
use App\Livewire\Tenders\PublicTendersIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

// ============================================
// Public Index
// ============================================

it('public tenders index page returns 200', function (): void {
    get(route('public.tenders.index'))->assertOk();
});

it('public tenders index renders livewire component', function (): void {
    Livewire::test(PublicTendersIndex::class)
        ->assertSuccessful();
});

it('public tenders index shows open tenders', function (): void {
    Tender::factory()->count(3)->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 3);
});

it('public tenders index hides draft tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->draft()->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 1);
});

it('public tenders index hides closed tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->closed()->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 1);
});

it('public tenders index hides cancelled tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->cancelled()->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 1);
});

it('public tenders index hides archived tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->archived()->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 1);
});

it('public tenders index hides awarded tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->awarded()->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 1);
});

it('public tenders index hides non-public tenders', function (): void {
    Tender::factory()->create(['is_public' => false]);

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 0);
});

// ============================================
// Expired Tenders (auto-expired)
// ============================================

it('public tenders index hides expired open tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->create(['submission_deadline' => now()->subDay()->toDateString(), 'status' => TenderStatus::Open]);

    Livewire::test(PublicTendersIndex::class)
        ->assertCount('tenders.items', 1);
});

it('tender published scope excludes expired tenders', function (): void {
    Tender::factory()->create(['submission_deadline' => now()->subDay()->toDateString(), 'status' => TenderStatus::Open]);

    $count = Tender::published()->count();

    expect($count)->toBe(0);
});

it('tender published scope excludes future publication dates', function (): void {
    Tender::factory()->create(['publication_date' => now()->addMonth()->toDateString()]);

    $count = Tender::published()->count();

    expect($count)->toBe(0);
});

// ============================================
// Search
// ============================================

it('public tenders index can search by title', function (): void {
    Tender::factory()->create(['title_ar' => 'مناقصة بناء مدرسة']);
    Tender::factory()->create(['title_ar' => 'مناقصة صيانة طرق']);

    Livewire::test(PublicTendersIndex::class)
        ->set('search', 'مدرسة')
        ->assertCount('tenders.items', 1);
});

// ============================================
// Featured Filter
// ============================================

it('public tenders index featured filter works', function (): void {
    Tender::factory()->featured()->create();
    Tender::factory()->create();

    Livewire::test(PublicTendersIndex::class)
        ->set('filter', 'featured');
});

it('featured tenders are displayed on public index', function (): void {
    Tender::factory()->featured()->count(2)->create();

    Livewire::test(PublicTendersIndex::class)
        ->assertSuccessful();
});

// ============================================
// Public Show
// ============================================

it('public tender show page returns 200 for open tender', function (): void {
    $tender = Tender::factory()->create();

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertSuccessful()
        ->assertSee($tender->title_ar);
});

it('public tender show returns 404 for draft tender', function (): void {
    $tender = Tender::factory()->draft()->create();

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertStatus(404);
});

it('public tender show returns 404 for closed tender', function (): void {
    $tender = Tender::factory()->closed()->create();

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertStatus(404);
});

it('public tender show returns 404 for cancelled tender', function (): void {
    $tender = Tender::factory()->cancelled()->create();

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertStatus(404);
});

it('public tender show returns 404 for archived tender', function (): void {
    $tender = Tender::factory()->archived()->create();

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertStatus(404);
});

it('public tender show returns 404 for awarded tender', function (): void {
    $tender = Tender::factory()->awarded()->create();

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertStatus(404);
});

it('public tender show returns 404 for non-public tender', function (): void {
    $tender = Tender::factory()->create(['is_public' => false]);

    Livewire::test(PublicTenderShow::class, ['tender' => $tender])
        ->assertStatus(404);
});

it('public tender show increments view count', function (): void {
    $tender = Tender::factory()->create(['views_count' => 2]);

    Livewire::test(PublicTenderShow::class, ['tender' => $tender]);

    expect($tender->fresh()->views_count)->toBe(3);
});

// ============================================
// Slug-based routing
// ============================================

it('public tender show by slug works', function (): void {
    $tender = Tender::factory()->create();

    get(route('public.tenders.show', $tender->slug))->assertOk();
});

it('public tender show by invalid slug returns 404', function (): void {
    get(route('public.tenders.show', 'this-slug-does-not-exist'))
        ->assertStatus(404);
});

// ============================================
// Model Scopes
// ============================================

it('published scope only returns open and public tenders', function (): void {
    Tender::factory()->create();
    Tender::factory()->draft()->create();

    $count = Tender::published()->count();

    expect($count)->toBe(1);
});

it('featured scope only returns featured tenders', function (): void {
    Tender::factory()->featured()->create();
    Tender::factory()->create();

    $count = Tender::featured()->count();

    expect($count)->toBe(1);
});

it('open scope returns tenders with future deadlines', function (): void {
    Tender::factory()->create(['submission_deadline' => now()->addDay()->toDateString()]);
    Tender::factory()->create(['submission_deadline' => now()->subDay()->toDateString()]);

    $count = Tender::open()->count();

    expect($count)->toBe(1);
});

it('closed scope returns tenders with past deadlines', function (): void {
    Tender::factory()->create(['submission_deadline' => now()->subDay()->toDateString()]);
    Tender::factory()->create(['submission_deadline' => now()->addDay()->toDateString()]);

    $count = Tender::closed()->count();

    expect($count)->toBe(1);
});

// ============================================
// Factory States
// ============================================

it('tender factory can create all states', function (): void {
    Tender::factory()->draft()->create();
    Tender::factory()->closed()->create();
    Tender::factory()->awarded()->create();
    Tender::factory()->cancelled()->create();
    Tender::factory()->archived()->create();

    expect(Tender::count())->toBe(5);
    expect(Tender::where('status', 'draft')->count())->toBe(1);
    expect(Tender::where('status', 'closed')->count())->toBe(1);
    expect(Tender::where('status', 'awarded')->count())->toBe(1);
    expect(Tender::where('status', 'cancelled')->count())->toBe(1);
    expect(Tender::where('status', 'archived')->count())->toBe(1);
});
