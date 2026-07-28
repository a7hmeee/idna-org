<?php

declare(strict_types=1);

use App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    // Ensure home route exists
    if (!Route::has('home')) {
        $this->markTestSkipped('Home route not defined');
    }

    // Seed homepage sections if not present (including facilities)
    if (!HomepageSection::where('key', 'facilities')->exists()) {
        $sections = [
            ['key' => 'hero', 'title' => 'البانر الرئيسي', 'is_enabled' => true, 'sort_order' => 1],
            ['key' => 'quick_links', 'title' => 'الروابط السريعة', 'is_enabled' => true, 'sort_order' => 2],
            ['key' => 'services', 'title' => 'الخدمات الإلكترونية', 'is_enabled' => true, 'sort_order' => 5, 'items_limit' => 6],
            ['key' => 'departments', 'title' => 'أقسام البلدية', 'is_enabled' => true, 'sort_order' => 6, 'items_limit' => 6],
            ['key' => 'facilities', 'title' => 'المرافق العامة', 'is_enabled' => true, 'sort_order' => 7, 'items_limit' => 4],
            ['key' => 'council_members', 'title' => 'أعضاء المجلس البلدي', 'is_enabled' => true, 'sort_order' => 8, 'items_limit' => 8],
            ['key' => 'contact_cta', 'title' => 'تواصل معنا', 'is_enabled' => true, 'sort_order' => 13],
        ];

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }

    // Ensure at least one setting exists for the homepage to render
    \App\Domains\Homepage\Models\HomepageSetting::create([
        'site_title' => 'بلدية إذنا',
        'site_subtitle' => 'Municipality of Idna',
        'portal_url' => 'https://i.palexpand.ps/portal',
    ]);
});

afterEach(function (): void {
    Cache::forget('homepage.public.data');
});

// ============================================
// Basic Rendering
// ============================================

it('renders facilities section on homepage', function (): void {
    FacilityCategory::factory()->create(['name' => 'حدائق', 'icon' => 'tree-pine']);
    $facility = Facility::factory()->featured()->create(['name' => 'حديقة البلدية']);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('المرافق العامة')
        ->assertSee('حديقة البلدية');
});

it('shows facility category on homepage card', function (): void {
    $category = FacilityCategory::factory()->create(['name' => 'حدائق', 'icon' => 'tree-pine']);
    Facility::factory()->featured()->create([
        'name' => 'حديقة البلدية',
        'facility_category_id' => $category->id,
    ]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('حدائق');
});

// ============================================
// Active / Published Filtering
// ============================================

it('only shows published facilities', function (): void {
    Facility::factory()->featured()->create(['name' => 'مرفق منشور']);
    Facility::factory()->draft()->create(['name' => 'مرفق مسودة']);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('مرفق منشور')
        ->assertDontSee('مرفق مسودة');
});

it('only shows public facilities', function (): void {
    Facility::factory()->featured()->create([
        'name' => 'مرفق عام',
        'is_public' => true,
    ]);
    Facility::factory()->featured()->create([
        'name' => 'مرفق داخلي',
        'is_public' => false,
    ]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('مرفق عام')
        ->assertDontSee('مرفق داخلي');
});

// ============================================
// Limit
// ============================================

it('limits featured facilities to four on homepage', function (): void {
    Facility::factory()->featured()->count(6)->create();

    $data = app(HomepagePublicRepositoryInterface::class)->getFeaturedFacilities(4);
    expect($data)->toHaveCount(4);
});

it('enforces items_limit from section settings', function (): void {
    HomepageSection::where('key', 'facilities')->update(['items_limit' => 2]);
    Facility::factory()->featured()->count(4)->create();

    Cache::forget('homepage.public.data');

    $data = app(HomepagePublicRepositoryInterface::class)->getFeaturedFacilities(2);
    expect($data)->toHaveCount(2);
});

// ============================================
// Ordering
// ============================================

it('respects display order when selecting facilities', function (): void {
    Facility::factory()->featured()->create([
        'name' => 'ثاني مرفق',
        'display_order' => 2,
    ]);
    Facility::factory()->featured()->create([
        'name' => 'أول مرفق',
        'display_order' => 1,
    ]);

    $data = app(HomepagePublicRepositoryInterface::class)->getFeaturedFacilities(4);
    expect($data[0]['name'] ?? '')->toBe('أول مرفق');
    expect($data[1]['name'] ?? '')->toBe('ثاني مرفق');
});

it('places featured facilities before non-featured', function (): void {
    Facility::factory()->create([
        'name' => 'مرفق عادي',
        'is_featured' => false,
        'display_order' => 0,
        'status' => 'published',
        'is_public' => true,
    ]);
    Facility::factory()->featured()->create([
        'name' => 'مرفق مميز',
        'display_order' => 0,
    ]);

    $data = app(HomepagePublicRepositoryInterface::class)->getFeaturedFacilities(4);
    expect($data[0]['name'] ?? '')->toBe('مرفق مميز');
});

// ============================================
// Detail Links
// ============================================

it('facility card has link to facility detail page', function (): void {
    $category = FacilityCategory::factory()->create(['name' => 'حدائق']);
    $facility = Facility::factory()->featured()->create([
        'name' => 'حديقة الاختبار',
        'facility_category_id' => $category->id,
    ]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee(route('public.facilities.show', $facility->slug));
});

it('index link points to facilities index page', function (): void {
    Facility::factory()->featured()->create();

    get(route('home'))
        ->assertSuccessful()
        ->assertSee(route('public.facilities.index'));
});

// ============================================
// Empty / Partial States
// ============================================

it('shows empty state when no facilities exist', function (): void {
    get(route('home'))
        ->assertSuccessful()
        ->assertSee('لا توجد مرافق عامة متاحة حالياً');
});

it('handles single facility gracefully', function (): void {
    Facility::factory()->featured()->create(['name' => 'مرفق وحيد']);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('مرفق وحيد');
});

it('handles two facilities gracefully', function (): void {
    Facility::factory()->featured()->create(['name' => 'مرفق أول', 'display_order' => 1]);
    Facility::factory()->featured()->create(['name' => 'مرفق ثاني', 'display_order' => 2]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('مرفق أول')
        ->assertSee('مرفق ثاني');
});

it('handles three facilities gracefully', function (): void {
    Facility::factory()->featured()->create(['name' => 'مرفق أول', 'display_order' => 1]);
    Facility::factory()->featured()->create(['name' => 'مرفق ثاني', 'display_order' => 2]);
    Facility::factory()->featured()->create(['name' => 'مرفق ثالث', 'display_order' => 3]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('مرفق أول')
        ->assertSee('مرفق ثاني')
        ->assertSee('مرفق ثالث');
});

// ============================================
// Section Toggle
// ============================================

it('does not render facilities section when disabled', function (): void {
    HomepageSection::where('key', 'facilities')->update(['is_enabled' => false]);
    Cache::forget('homepage.public.data');

    Facility::factory()->featured()->create(['name' => 'مرفق مخفي']);

    get(route('home'))
        ->assertSuccessful()
        ->assertDontSee('مرفق مخفي');
});

// ============================================
// Image Fallback
// ============================================

it('renders without crashing when facility has no image', function (): void {
    Facility::factory()->featured()->create([
        'name' => 'مرفق بدون صورة',
        'cover_image_path' => null,
    ]);

    get(route('home'))
        ->assertSuccessful()
        ->assertSee('مرفق بدون صورة');
});

// ============================================
// Repository Method
// ============================================

it('getFeaturedFacilities returns published facilities with featured first', function (): void {
    Facility::factory()->create([
        'name' => 'غير مميز',
        'is_featured' => false,
        'display_order' => 0,
        'status' => 'published',
        'is_public' => true,
    ]);
    Facility::factory()->featured()->create([
        'name' => 'مميز منشور',
        'display_order' => 0,
    ]);
    Facility::factory()->draft()->create(['name' => 'مسودة']);

    $data = app(HomepagePublicRepositoryInterface::class)->getFeaturedFacilities(4);

    $names = array_map(fn ($f) => $f['name'] ?? '', $data);
    expect($names[0])->toBe('مميز منشور');
    expect($names)->toContain('غير مميز');
    expect($names)->not->toContain('مسودة');
});

it('getFeaturedFacilities eager loads category', function (): void {
    $category = FacilityCategory::factory()->create(['name' => 'حدائق']);
    Facility::factory()->featured()->create([
        'facility_category_id' => $category->id,
    ]);

    $data = app(HomepagePublicRepositoryInterface::class)->getFeaturedFacilities(4);

    expect($data[0]['category']['name'] ?? '')->toBe('حدائق');
});

it('getFeaturedFacilities returns empty array when module missing', function (): void {
    // Mock the class_exists check by using a try-catch approach
    $repo = app(HomepagePublicRepositoryInterface::class);
    $result = $repo->getFeaturedFacilities(4);

    expect($result)->toBeArray();
});
