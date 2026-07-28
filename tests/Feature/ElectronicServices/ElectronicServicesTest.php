<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

// Categories - Permissions
it('redirects unauthenticated user to login for categories', function (): void {
    get(route('dashboard.electronic-services.categories'))
        ->assertRedirect(route('login'));
});

it('returns 403 for user without service_categories.view permission', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('dashboard.electronic-services.categories'))
        ->assertForbidden();
});

it('admin can view categories index', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('service_categories.view');

    actingAs($user)
        ->get(route('dashboard.electronic-services.categories'))
        ->assertSuccessful();
});

it('admin can view create category form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('service_categories.create');

    actingAs($user)
        ->get(route('dashboard.electronic-services.categories.create'))
        ->assertSuccessful();
});

it('admin can create category', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['service_categories.create', 'service_categories.view']);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\ElectronicServices\ServiceCategoryForm::class)
        ->set('name', 'خدمات الكهرباء')
        ->set('status', 'active')
        ->call('save')
        ->assertRedirect(route('dashboard.electronic-services.categories'));
});

it('admin can view edit category form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('service_categories.update');

    $category = ServiceCategory::factory()->create();

    actingAs($user)
        ->get(route('dashboard.electronic-services.categories.edit', $category))
        ->assertSuccessful();
});

it('admin can view category show page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('service_categories.view');

    $category = ServiceCategory::factory()->create();

    actingAs($user)
        ->get(route('dashboard.electronic-services.categories.show', $category))
        ->assertSuccessful();
});

it('admin can update category', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['service_categories.update', 'service_categories.view']);

    $category = ServiceCategory::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\ElectronicServices\ServiceCategoryForm::class, ['category' => $category])
        ->set('name', 'خدمات المياه')
        ->call('save')
        ->assertRedirect(route('dashboard.electronic-services.categories'));
});

it('admin can delete category', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['service_categories.delete', 'service_categories.view']);

    $category = ServiceCategory::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\ElectronicServices\ServiceCategoriesIndex::class)
        ->call('confirmDelete', $category->id)
        ->assertSet('showDeleteModal', true)
        ->call('delete');
});

// Services - Permissions
it('redirects unauthenticated user to login for services', function (): void {
    get(route('dashboard.electronic-services.services'))
        ->assertRedirect(route('login'));
});

it('returns 403 for user without electronic_services.view permission', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('dashboard.electronic-services.services'))
        ->assertForbidden();
});

it('admin can view services index', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('electronic_services.view');

    actingAs($user)
        ->get(route('dashboard.electronic-services.services'))
        ->assertSuccessful();
});

it('admin can view create service form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('electronic_services.create');

    actingAs($user)
        ->get(route('dashboard.electronic-services.services.create'))
        ->assertSuccessful();
});

it('admin can create service with JSON steps', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['electronic_services.create', 'electronic_services.view']);

    $category = ServiceCategory::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\ElectronicServices\ElectronicServiceForm::class)
        ->set('name', 'طلب توصيل كهرباء')
        ->set('service_category_id', (string) $category->id)
        ->set('status', 'draft')
        ->call('addStep')
        ->set('steps.0.title', 'تعبئة الطلب')
        ->set('steps.0.description', 'قم بتعبئة البيانات المطلوبة')
        ->call('addRequirement')
        ->set('requirements.0.title', 'صورة الهوية')
        ->set('requirements.0.is_required', true)
        ->call('save')
        ->assertRedirect(route('dashboard.electronic-services.services'));
});

it('admin can view edit service form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('electronic_services.update');

    $category = ServiceCategory::factory()->create();
    $service = ElectronicService::factory()->create(['service_category_id' => $category->id]);

    actingAs($user)
        ->get(route('dashboard.electronic-services.services.edit', $service))
        ->assertSuccessful();
});

it('admin can publish service', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['electronic_services.publish', 'electronic_services.view']);

    $category = ServiceCategory::factory()->create();
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'status' => 'draft',
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\ElectronicServices\ElectronicServiceShow::class, ['service' => $service])
        ->call('publish');

    expect($service->fresh()->status)->toBe('active');
});

it('admin can view analytics', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('electronic_services.analytics');

    actingAs($user)
        ->get(route('dashboard.electronic-services.analytics'))
        ->assertSuccessful();
});

// Public pages
it('public user can view services portal', function (): void {
    get(route('public.services.index'))->assertSuccessful();
});

it('public user can view service detail page', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
    ]);

    get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertSuccessful();
});

it('service view is tracked on public page', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'views_count' => 0,
    ]);

    $initialViews = $service->views_count;

    get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]));

    expect($service->fresh()->views_count)->toBe($initialViews + 1);
});

it('getMostViewed returns ordered services', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);

    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'views_count' => 10,
        'status' => 'active',
        'is_public' => true,
    ]);
    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'views_count' => 20,
        'status' => 'active',
        'is_public' => true,
    ]);

    $repo = app(\App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface::class);
    $mostViewed = $repo->getMostViewed(5);

    expect($mostViewed->count())->toBe(2);
    expect($mostViewed->first()->views_count)->toBe(20);
});

it('getMostClicked returns ordered services', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);

    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'portal_clicks_count' => 5,
        'status' => 'active',
        'is_public' => true,
    ]);
    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'portal_clicks_count' => 15,
        'status' => 'active',
        'is_public' => true,
    ]);

    $repo = app(\App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface::class);
    $mostClicked = $repo->getMostClicked(5);

    expect($mostClicked->count())->toBe(2);
    expect($mostClicked->first()->portal_clicks_count)->toBe(15);
});

it('getByCategory returns services for given category', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $otherCategory = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);

    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'status' => 'active',
        'is_public' => true,
    ]);
    ElectronicService::factory()->create([
        'service_category_id' => $otherCategory->id,
        'status' => 'active',
        'is_public' => true,
    ]);

    $repo = app(\App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface::class);
    $services = $repo->getByCategory($category->id);

    expect($services->count())->toBe(1);
});

// === New Public Services Tests ===

it('services page shows public root categories only', function (): void {
    $rootCategory = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active', 'parent_id' => null]);
    $childCategory = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active', 'parent_id' => $rootCategory->id]);

    $response = get(route('public.services.index'))->assertSuccessful();

    $response->assertSee($rootCategory->name);
});

it('hidden categories are not displayed on portal page', function (): void {
    $publicCategory = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $hiddenCategory = ServiceCategory::factory()->create(['is_public' => false, 'status' => 'active']);

    $response = get(route('public.services.index'))->assertSuccessful();

    $response->assertSee($publicCategory->name);
    $response->assertDontSee($hiddenCategory->name);
});

it('non-public service returns 404', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => false,
        'status' => 'active',
    ]);

    get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertStatus(404);
});

it('inactive service returns 404', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'draft',
    ]);

    get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertStatus(404);
});

it('service detail displays JSON requirements', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'requirements' => [
            ['title' => 'صورة الهوية', 'is_required' => true, 'description' => 'صورة واضحة'],
            ['title' => 'إثبات سكن', 'is_required' => true, 'description' => 'فاتورة كهرباء'],
        ],
    ]);

    $response = get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertSuccessful();

    $response->assertSee('صورة الهوية');
    $response->assertSee('إثبات سكن');
    $response->assertSee('إلزامي');
});

it('service detail displays documents', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'documents' => [
            ['name' => 'الهوية الشخصية', 'description' => 'صورة عن الهوية', 'is_required' => true],
            ['name' => 'عقد ملكية', 'description' => 'عقد ملكية مثبت', 'is_required' => true],
        ],
    ]);

    $response = get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertSuccessful();

    $response->assertSee('الهوية الشخصية');
    $response->assertSee('عقد ملكية');
});

it('service detail displays steps in saved order', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'steps' => [
            ['title' => 'الخطوة الأولى', 'description' => 'قم بتعبئة النموذج'],
            ['title' => 'الخطوة الثانية', 'description' => 'قم بتقديم الطلب'],
        ],
    ]);

    $response = get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertSuccessful();

    $response->assertSee('الخطوة الأولى');
    $response->assertSee('الخطوة الثانية');
});

it('service detail displays zero fees as مجانية', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'fees' => [
            ['title' => 'رسوم التقديم', 'amount' => 0, 'currency' => 'ILS', 'notes' => ''],
        ],
    ]);

    $response = get(route('public.services.show', ['category' => $category->slug, 'service' => $service->slug]))
        ->assertSuccessful();

    $response->assertSee('مجانية');
});

it('portal click tracking works via livewire', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);
    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'portal_url' => 'https://portal.example.com/test',
        'portal_clicks_count' => 0,
    ]);

    $initialClicks = $service->portal_clicks_count;

    \Livewire\Livewire::test(\App\Livewire\ElectronicServices\PublicServiceDetail::class, ['service' => $service])
        ->call('goToPortal');

    expect($service->fresh()->portal_clicks_count)->toBe($initialClicks + 1);
});

it('related services exclude current service', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);

    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'name' => 'الخدمة الأولى',
    ]);
    ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'name' => 'الخدمة الثانية',
    ]);

    $service = ElectronicService::factory()->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
        'name' => 'الخدمة الحالية',
    ]);

    $repo = app(\App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface::class);
    $related = $repo->getRelatedServices($category->id, $service->id, 10);

    expect($related->count())->toBe(2);
    expect($related->pluck('name'))->not->toContain('الخدمة الحالية');
});

it('category with no services shows empty state', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);

    get(route('public.services.category', $category->slug))
        ->assertSuccessful()
        ->assertSee('لا توجد خدمات');
});

it('services page uses public layout with municipality header', function (): void {
    get(route('public.services.index'))
        ->assertSuccessful()
        ->assertSee('بلدية إذنا');
});

it('services page shows homepage footer', function (): void {
    get(route('public.services.index'))
        ->assertSuccessful()
        ->assertSee('جميع الحقوق محفوظة');
});

it('child categories not shown on portal page', function (): void {
    $root = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active', 'parent_id' => null, 'name' => 'تصنيف جذر']);
    $child = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active', 'parent_id' => $root->id, 'name' => 'تصنيف فرعي']);

    $response = get(route('public.services.index'))->assertSuccessful();
    $response->assertSee('تصنيف جذر');
    $response->assertDontSee('تصنيف فرعي');
});

it('services page does not hardcode portal URL', function (): void {
    $response = get(route('public.services.index'))->assertSuccessful();
    $content = $response->getContent();

    expect($content)->not->toContain('portal.example.com');
});

it('category services are paginated', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => true, 'status' => 'active']);

    ElectronicService::factory()->count(15)->create([
        'service_category_id' => $category->id,
        'is_public' => true,
        'status' => 'active',
    ]);

    $response = get(route('public.services.category', $category->slug))
        ->assertSuccessful();
});

it('hidden category returns 404', function (): void {
    $category = ServiceCategory::factory()->create(['is_public' => false, 'status' => 'active']);

    get(route('public.services.category', $category->slug))
        ->assertStatus(404);
});
