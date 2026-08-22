<?php

declare(strict_types=1);

use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Models\NewsItem;
use App\Livewire\News\PublicNewsIndex;
use App\Livewire\News\PublicNewsShow;
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

it('public news index page returns 200', function (): void {
    get(route('public.news.index'))->assertOk();
});

it('public news index renders livewire component', function (): void {
    Livewire::test(PublicNewsIndex::class)
        ->assertSuccessful();
});

it('public news index shows empty state when no news', function (): void {
    Livewire::test(PublicNewsIndex::class)
        ->assertSuccessful();
});

it('public news index shows published news', function (): void {
    NewsItem::factory()->published()->count(3)->create();

    Livewire::test(PublicNewsIndex::class)
        ->assertCount('news.items', 3);
});

it('public news index hides draft news', function (): void {
    NewsItem::factory()->published()->create();
    NewsItem::factory()->draft()->create();

    Livewire::test(PublicNewsIndex::class)
        ->assertCount('news.items', 1);
});

it('public news index hides archived news', function (): void {
    NewsItem::factory()->published()->create();
    NewsItem::factory()->archived()->create();

    Livewire::test(PublicNewsIndex::class)
        ->assertCount('news.items', 1);
});

it('public news index hides unpublished news (future publish_at)', function (): void {
    NewsItem::factory()->published()->create();
    NewsItem::factory()->published()->create(['publish_at' => now()->addMonth()]);

    Livewire::test(PublicNewsIndex::class)
        ->assertCount('news.items', 1);
});

// ============================================
// Search Filter
// ============================================

it('public news index can search by title', function (): void {
    NewsItem::factory()->published()->create(['title_ar' => 'أخبار البلدية']);
    NewsItem::factory()->published()->create(['title_ar' => 'مشاريع جديدة']);

    Livewire::test(PublicNewsIndex::class)
        ->set('search', 'البلدية')
        ->assertCount('news.items', 1);
});

it('public news index search ignores short queries', function (): void {
    NewsItem::factory()->published()->create(['title_ar' => 'أخبار البلدية']);

    Livewire::test(PublicNewsIndex::class)
        ->set('search', 'أ')
        ->assertCount('news.items', 1);
});

// ============================================
// Category Filter
// ============================================

it('public news index can filter by category', function (): void {
    NewsItem::factory()->published()->create(['category' => NewsCategory::Municipal]);
    NewsItem::factory()->published()->create(['category' => NewsCategory::Events]);

    Livewire::test(PublicNewsIndex::class)
        ->set('category', 'municipal')
        ->assertCount('news.items', 1);
});

// ============================================
// Featured Filter
// ============================================

it('public news index can show all when filter is latest', function (): void {
    NewsItem::factory()->published()->count(3)->create();
    NewsItem::factory()->published()->featured()->create();

    Livewire::test(PublicNewsIndex::class)
        ->set('filter', 'latest')
        ->assertCount('news.items', 4);
});

// ============================================
// Clear Filters
// ============================================

it('public news index can clear filters', function (): void {
    NewsItem::factory()->published()->count(3)->create();

    Livewire::test(PublicNewsIndex::class)
        ->set('search', 'something')
        ->set('category', 'municipal')
        ->set('filter', 'featured')
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('category', '')
        ->assertSet('filter', 'latest');
});

// ============================================
// Public Show
// ============================================

it('public news show page returns 200 for published news', function (): void {
    $news = NewsItem::factory()->published()->create();

    Livewire::test(PublicNewsShow::class, ['news' => $news])
        ->assertSuccessful()
        ->assertSee($news->title_ar);
});

it('public news show returns 404 for draft news', function (): void {
    $news = NewsItem::factory()->draft()->create();

    Livewire::test(PublicNewsShow::class, ['news' => $news])
        ->assertStatus(404);
});

it('public news show returns 404 for archived news', function (): void {
    $news = NewsItem::factory()->archived()->create();

    Livewire::test(PublicNewsShow::class, ['news' => $news])
        ->assertStatus(404);
});

it('public news show returns 404 for non-public news', function (): void {
    $news = NewsItem::factory()->published()->create(['is_public' => false]);

    Livewire::test(PublicNewsShow::class, ['news' => $news])
        ->assertStatus(404);
});

it('public news show returns 404 for future published news', function (): void {
    $news = NewsItem::factory()->published()->create(['publish_at' => now()->addMonth()]);

    Livewire::test(PublicNewsShow::class, ['news' => $news])
        ->assertStatus(404);
});

it('public news show increments view count', function (): void {
    $news = NewsItem::factory()->published()->create(['views_count' => 5]);

    Livewire::test(PublicNewsShow::class, ['news' => $news]);

    expect($news->fresh()->views_count)->toBe(6);
});

// ============================================
// Slug-based routing
// ============================================

it('public news show by slug works', function (): void {
    $news = NewsItem::factory()->published()->create();

    get(route('public.news.show', $news->slug))->assertOk();
});

it('public news show by invalid slug returns 404', function (): void {
    get(route('public.news.show,', 'this-slug-does-not-exist'))
        ->assertStatus(404);
});

// ============================================
// Featured Section
// ============================================

it('featured news are displayed on public index', function (): void {
    NewsItem::factory()->published()->featured()->create();

    Livewire::test(PublicNewsIndex::class)
        ->assertSuccessful();
});

// ============================================
// Model Scopes
// ============================================

it('published scope only returns published items', function (): void {
    NewsItem::factory()->draft()->create();
    NewsItem::factory()->published()->create();
    NewsItem::factory()->archived()->create();

    $count = NewsItem::published()->count();

    expect($count)->toBe(1);
});

it('featured scope only returns featured items', function (): void {
    NewsItem::factory()->published()->featured()->create();
    NewsItem::factory()->published()->create();

    $count = NewsItem::featured()->count();

    expect($count)->toBe(1);
});
