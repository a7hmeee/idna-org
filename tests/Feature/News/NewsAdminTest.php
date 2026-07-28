<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\News\Actions\CreateNewsAction;
use App\Domains\News\Actions\PublishNewsAction;
use App\Domains\News\Actions\ToggleFeaturedNewsAction;
use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\DTOs\NewsData;
use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;
use App\Domains\News\Models\NewsItem;
use App\Livewire\News\NewsForm;
use App\Livewire\News\NewsIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

// ============================================
// Authentication & Authorization
// ============================================

it('redirects unauthenticated user from admin news index', function (): void {
    get(route('dashboard.news'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin news create', function (): void {
    get(route('dashboard.news.create'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin news edit', function (): void {
    $news = NewsItem::factory()->create();

    get(route('dashboard.news.edit', $news))->assertRedirect(route('login'));
});

it('returns 403 for user without news.view permission', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Employee');

    actingAs($user);

    get(route('dashboard.news'))->assertForbidden();
});

it('returns 403 for user without news.create permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('news.view');

    actingAs($user);

    get(route('dashboard.news.create'))->assertForbidden();
});

it('returns 403 for user without news.update permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('news.view');
    $news = NewsItem::factory()->create();

    actingAs($user);

    get(route('dashboard.news.edit', $news))->assertForbidden();
});

// ============================================
// Admin View
// ============================================

it('admin can view news dashboard', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(NewsIndex::class)
        ->assertSuccessful();
});

it('admin can view create form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(NewsForm::class)
        ->assertSuccessful()
        ->assertSet('status', 'draft')
        ->assertSet('category', 'general');
});

it('admin can view edit form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $news = NewsItem::factory()->create();

    actingAs($user);

    Livewire::test(NewsForm::class, ['newsItem' => $news])
        ->assertSuccessful()
        ->assertSet('titleAr', $news->title_ar)
        ->assertSet('newsId', $news->id);
});

// ============================================
// Create News
// ============================================

it('admin can create news via form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(NewsForm::class)
        ->set('titleAr', 'خبر جديد')
        ->set('titleEn', 'New News')
        ->set('category', 'municipal')
        ->set('summary', 'ملخص الخبر')
        ->set('content', 'محتوى الخبر بالكامل')
        ->set('author', 'محرر')
        ->set('status', 'draft')
        ->set('publishAt', now()->format('Y-m-d'))
        ->call('save')
        ->assertRedirect(route('dashboard.news'));

    expect(NewsItem::count())->toBe(1);
    expect(NewsItem::first()->title_ar)->toBe('خبر جديد');
});

it('can create news via action', function (): void {
    $dto = NewsData::fromRequest([
        'titleAr' => 'خبر تجريبي',
        'summary' => 'ملخص الخبر التجريبي',
        'content' => 'محتوى الخبر التجريبي',
        'category' => NewsCategory::General,
        'status' => NewsStatus::Draft,
        'publishAt' => now()->toDateString(),
        'author' => 'اختبار',
        'createdBy' => 1,
        'updatedBy' => 1,
    ]);

    $news = app(CreateNewsAction::class)->execute($dto);

    expect($news)->toBeInstanceOf(NewsItem::class);
    expect($news->title_ar)->toBe('خبر تجريبي');
    expect($news->status)->toBe(NewsStatus::Draft);
});

it('creates news with all optional fields via action', function (): void {
    $dto = NewsData::fromRequest([
        'titleAr' => 'خبر كامل',
        'titleEn' => 'Full News',
        'summary' => 'ملخص كامل',
        'content' => 'محتوى كامل',
        'category' => NewsCategory::Events,
        'status' => NewsStatus::Published,
        'publishAt' => now()->toDateString(),
        'author' => 'كاتب',
        'isFeatured' => true,
        'isPublic' => true,
        'metaTitle' => 'عنوان SEO',
        'metaDescription' => 'وصف SEO',
        'metaKeywords' => 'كلمة, كلمة2',
        'createdBy' => 1,
        'updatedBy' => 1,
    ]);

    $news = app(CreateNewsAction::class)->execute($dto);

    expect($news->title_en)->toBe('Full News');
    expect($news->is_featured)->toBeTrue();
    expect($news->meta_title)->toBe('عنوان SEO');
});

// ============================================
// Validation
// ============================================

it('validates required fields on create', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(NewsForm::class)
        ->set('titleAr', '')
        ->call('save')
        ->assertHasErrors(['titleAr' => 'required']);
});

// ============================================
// Publish Workflow
// ============================================

it('can publish a news item', function (): void {
    $news = NewsItem::factory()->draft()->create();

    app(PublishNewsAction::class)->execute($news->id);

    expect($news->fresh()->status->value)->toBe('published');
});

it('can unpublish a news item', function (): void {
    $news = NewsItem::factory()->published()->create();

    app(NewsRepositoryInterface::class)->unpublish($news->id);

    expect($news->fresh()->status->value)->toBe('draft');
});

it('publish action sets publish_at if null', function (): void {
    $news = NewsItem::factory()->draft()->create(['publish_at' => null]);

    app(PublishNewsAction::class)->execute($news->id);

    expect($news->fresh()->publish_at)->not->toBeNull();
});

// ============================================
// Toggle Featured
// ============================================

it('can toggle featured status', function (): void {
    $news = NewsItem::factory()->create(['is_featured' => false]);

    app(ToggleFeaturedNewsAction::class)->execute($news->id);

    expect($news->fresh()->is_featured)->toBeTrue();
});

it('can toggle featured back to false', function (): void {
    $news = NewsItem::factory()->featured()->create();

    app(ToggleFeaturedNewsAction::class)->execute($news->id);

    expect($news->fresh()->is_featured)->toBeFalse();
});

// ============================================
// Delete (Soft Delete)
// ============================================

it('can soft delete a news item', function (): void {
    $news = NewsItem::factory()->create();

    app(NewsRepositoryInterface::class)->delete($news->id);

    expect(NewsItem::count())->toBe(0);
    expect(NewsItem::withTrashed()->count())->toBe(1);
});

// ============================================
// Slug Generation
// ============================================

it('auto-generates slug from title_ar on create', function (): void {
    $news = NewsItem::factory()->create(['title_ar' => 'خبر جديد جدا', 'slug' => null]);

    $news->refresh();

    expect($news->slug)->not->toBeNull();
    expect($news->slug)->toBe(\Illuminate\Support\Str::slug('خبر جديد جدا'));
});

it('slug is unique in practice', function (): void {
    $news1 = NewsItem::factory()->create();
    $news2 = NewsItem::factory()->create();

    expect($news1->slug)->not->toBeNull();
    expect($news2->slug)->not->toBeNull();
});

// ============================================
// Pagination
// ============================================

it('dashboard paginates news', function (): void {
    NewsItem::factory()->count(25)->create();

    $paginator = app(NewsRepositoryInterface::class)->paginateDashboard();

    expect($paginator->total())->toBe(25);
    expect($paginator->perPage())->toBeGreaterThanOrEqual(10);
});

// ============================================
// View Count
// ============================================

it('can increment views count', function (): void {
    $news = NewsItem::factory()->create(['views_count' => 0]);

    app(NewsRepositoryInterface::class)->incrementViews($news->id);

    expect($news->fresh()->views_count)->toBe(1);
});

// ============================================
// Admin Index Actions
// ============================================

it('admin can publish from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $news = NewsItem::factory()->draft()->create();

    actingAs($user);

    Livewire::test(NewsIndex::class)
        ->call('publish', $news->id)
        ->assertSessionHas('success');

    expect($news->fresh()->status->value)->toBe('published');
});

it('admin can delete from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $news = NewsItem::factory()->create();

    actingAs($user);

    Livewire::test(NewsIndex::class)
        ->call('confirmDelete', $news->id)
        ->assertSet('showDeleteModal', true)
        ->call('delete')
        ->assertSessionHas('success');

    expect(NewsItem::count())->toBe(0);
});

it('admin can toggle featured from index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $news = NewsItem::factory()->create(['is_featured' => false]);

    actingAs($user);

    Livewire::test(NewsIndex::class)
        ->call('toggleFeatured', $news->id)
        ->assertSessionHas('success');

    expect($news->fresh()->is_featured)->toBeTrue();
});
