<?php

declare(strict_types=1);

use App\Domains\Announcements\Actions\CreateAnnouncementAction;
use App\Domains\Announcements\Actions\PublishAnnouncementAction;
use App\Domains\Announcements\Actions\RecordAnnouncementViewAction;
use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\DTOs\AnnouncementData;
use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementStatus;
use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Announcements\Models\Announcement;
use App\Domains\Authentication\Models\User;
use App\Livewire\Admin\Announcements\AnnouncementForm;
use App\Livewire\Admin\Announcements\AnnouncementsIndex;
use App\Livewire\Announcements\PublicAnnouncementShow;
use App\Livewire\Announcements\PublicAnnouncementsIndex;
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
// Create Announcement
// ============================================

it('can create an announcement via action', function (): void {
    $dto = AnnouncementData::fromRequest([
        'title' => 'تعليق الدوام يوم الخميس',
        'type' => AnnouncementType::General,
        'priority' => AnnouncementPriority::Important,
        'status' => AnnouncementStatus::Published,
        'summary' => 'سيتم تعليق الدوام يوم الخميس',
        'content' => 'نحيطكم علماً أنه سيتم تعليق الدوام الرسمي يوم الخميس الموافق...',
        'publishAt' => now()->toDateTimeString(),
    ]);

    $announcement = app(CreateAnnouncementAction::class)->execute($dto);

    expect($announcement)->toBeInstanceOf(Announcement::class);
    expect($announcement->title)->toBe('تعليق الدوام يوم الخميس');
    expect($announcement->type)->toBe(AnnouncementType::General);
    expect($announcement->priority)->toBe(AnnouncementPriority::Important);
    expect($announcement->status)->toBe(AnnouncementStatus::Published);
});

it('can create an announcement with all optional fields', function (): void {
    $dto = AnnouncementData::fromRequest([
        'title' => 'إعلان مع مرفقات',
        'type' => AnnouncementType::TenderNotice,
        'priority' => AnnouncementPriority::Normal,
        'status' => AnnouncementStatus::Draft,
        'summary' => 'إعلان مناقصة',
        'content' => 'تفاصيل المناقصة',
        'externalUrl' => 'https://example.com/tender',
        'isFeatured' => true,
    ]);

    $announcement = app(CreateAnnouncementAction::class)->execute($dto);

    expect($announcement->is_featured)->toBeTrue();
});

// ============================================
// Publish Announcement
// ============================================

it('can publish an announcement', function (): void {
    $announcement = Announcement::factory()->draft()->create();

    app(PublishAnnouncementAction::class)->execute($announcement->id);

    expect($announcement->fresh()->status->value)->toBe('published');
    expect($announcement->fresh()->publish_at)->not->toBeNull();
});

// ============================================
// Unpublish Announcement
// ============================================

it('can unpublish an announcement', function (): void {
    $announcement = Announcement::factory()->create();

    app(AnnouncementRepositoryInterface::class)->unpublish($announcement->id);

    expect($announcement->fresh()->status->value)->toBe('draft');
});

// ============================================
// Toggle Featured
// ============================================

it('can toggle featured status', function (): void {
    $announcement = Announcement::factory()->create(['is_featured' => false]);

    app(AnnouncementRepositoryInterface::class)->toggleFeatured($announcement->id);

    expect($announcement->fresh()->is_featured)->toBeTrue();
});

// ============================================
// Record View
// ============================================

it('can record a view', function (): void {
    $announcement = Announcement::factory()->create(['views_count' => 0]);

    app(RecordAnnouncementViewAction::class)->execute($announcement->id);

    expect($announcement->fresh()->views_count)->toBe(1);
});

// ============================================
// Delete Announcement (Soft Delete)
// ============================================

it('can soft delete an announcement', function (): void {
    $announcement = Announcement::factory()->create();

    app(AnnouncementRepositoryInterface::class)->delete($announcement->id);

    expect(Announcement::count())->toBe(0);
    expect(Announcement::withTrashed()->count())->toBe(1);
});

// ============================================
// Public Visibility
// ============================================

it('published announcements are visible on public page', function (): void {
    Announcement::factory()->count(3)->create();
    Announcement::factory()->draft()->create();

    Livewire::test(PublicAnnouncementsIndex::class)
        ->assertCount('announcements.items', 3);
});

it('draft announcements are not visible on public page', function (): void {
    Announcement::factory()->create();
    Announcement::factory()->draft()->create();

    Livewire::test(PublicAnnouncementsIndex::class)
        ->assertCount('announcements.items', 1);
});

it('unpublished announcement returns 404 on show page', function (): void {
    $announcement = Announcement::factory()->draft()->create();

    get(route('public.announcements.show', $announcement->slug))
        ->assertStatus(404);
});

it('published announcement is accessible on show page', function (): void {
    $announcement = Announcement::factory()->create();

    Livewire::test(PublicAnnouncementShow::class, ['announcement' => $announcement])
        ->assertSee($announcement->title);
});

// ============================================
// Urgent Announcements
// ============================================

it('urgent announcements appear in urgent scope', function (): void {
    Announcement::factory()->urgent()->create();
    Announcement::factory()->create(['priority' => 'normal']);

    $urgent = app(AnnouncementRepositoryInterface::class)->getUrgent();

    expect($urgent)->toHaveCount(1);
});

// ============================================
// Featured Announcements
// ============================================

it('featured announcements appear in featured scope', function (): void {
    Announcement::factory()->featured()->create();
    Announcement::factory()->create(['is_featured' => false]);

    $featured = app(AnnouncementRepositoryInterface::class)->getFeatured();

    expect($featured)->toHaveCount(1);
});

// ============================================
// Announcement Type Enum
// ============================================

it('has 10 announcement types', function (): void {
    $types = AnnouncementType::cases();

    expect($types)->toHaveCount(10);
    expect(AnnouncementType::General->label())->toBe('إعلان عام');
    expect(AnnouncementType::Emergency->label())->toBe('طوارئ');
    expect(AnnouncementType::RoadClosure->label())->toBe('إغلاق طريق');
});

// ============================================
// Announcement Priority Enum
// ============================================

it('has 3 announcement priorities', function (): void {
    $priorities = AnnouncementPriority::cases();

    expect($priorities)->toHaveCount(3);
    expect(AnnouncementPriority::Urgent->label())->toBe('عاجلة');
    expect(AnnouncementPriority::Important->label())->toBe('مهمة');
});

// ============================================
// Admin: Authenticated User Can View Dashboard
// ============================================

it('admin can view announcements dashboard', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(AnnouncementsIndex::class)
        ->assertSuccessful();
});

it('unauthenticated user cannot access admin', function (): void {
    get(route('dashboard.announcements'))
        ->assertRedirect(route('login'));
});

// ============================================
// Admin: Create Announcement
// ============================================

it('admin can create announcement via form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(AnnouncementForm::class)
        ->set('title', 'إعلان جديد')
        ->set('type', 'general')
        ->set('priority', 'important')
        ->set('summary', 'ملخص الإعلان')
        ->set('content', 'محتوى الإعلان')
        ->set('publishAt', now()->format('Y-m-d\TH:i'))
        ->call('save')
        ->assertRedirect(route('dashboard.announcements'));

    expect(Announcement::count())->toBe(1);
});

// ============================================
// Visibility check helper
// ============================================

it('isVisible returns correct status', function (): void {
    $published = Announcement::factory()->create(['status' => 'published']);
    $draft = Announcement::factory()->draft()->create();

    expect($published->isVisible())->toBeTrue();
    expect($draft->isVisible())->toBeFalse();
});

// ============================================
// Model scopes
// ============================================

it('published scope only returns published announcements', function (): void {
    Announcement::factory()->draft()->create();
    $valid = Announcement::factory()->create();

    $count = Announcement::published()->count();

    expect($count)->toBe(1);
});

// ============================================
// Reorder
// ============================================

it('can reorder announcements', function (): void {
    $a1 = Announcement::factory()->create(['display_order' => 0]);
    $a2 = Announcement::factory()->create(['display_order' => 1]);

    app(AnnouncementRepositoryInterface::class)->reorder([
        ['id' => $a1->id, 'order' => 1],
        ['id' => $a2->id, 'order' => 0],
    ]);

    expect($a1->fresh()->display_order)->toBe(1);
    expect($a2->fresh()->display_order)->toBe(0);
});

// ============================================
// Filtering on public index
// ============================================

it('can filter announcements by type on public page', function (): void {
    Announcement::factory()->create(['type' => 'general']);
    Announcement::factory()->create(['type' => 'emergency']);

    Livewire::test(PublicAnnouncementsIndex::class)
        ->set('type', 'general')
        ->assertCount('announcements.items', 1);
});

it('can filter announcements by priority on public page', function (): void {
    Announcement::factory()->create(['priority' => 'urgent']);
    Announcement::factory()->create(['priority' => 'normal']);

    Livewire::test(PublicAnnouncementsIndex::class)
        ->set('priority', 'urgent')
        ->assertCount('announcements.items', 1);
});
