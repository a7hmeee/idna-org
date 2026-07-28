<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Homepage\Enums\PageCarouselKey;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Livewire\PageCarousels\PageCarouselForm;
use App\Livewire\PageCarousels\PageCarouselsIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

it('admin can view page carousels index', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('homepage.slides.view');

    actingAs($user);

    get(route('dashboard.page-carousels'))->assertOk();
});

it('unauthorized user cannot view page carousels', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.page-carousels'))->assertStatus(403);
});

it('admin can create page carousel slide', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('homepage.slides.create');
    $user->givePermissionTo('homepage.slides.view');

    actingAs($user);

    Livewire::test(PageCarouselForm::class)
        ->set('pageKey', 'engineering-offices')
        ->set('title', 'المكاتب الهندسية')
        ->set('isActive', true)
        ->call('save')
        ->assertRedirect(route('dashboard.page-carousels', ['pageKey' => 'engineering-offices']));
});

it('page carousel key enum includes all required pages', function (): void {
    $keys = PageCarouselKey::values();

    expect(in_array('engineering-offices', $keys))->toBeTrue();
    expect(in_array('open-data', $keys))->toBeTrue();
    expect(in_array('water-schedule', $keys))->toBeTrue();
});

it('page carousel key labels are in arabic', function (): void {
    $engineeringLabel = PageCarouselKey::ENGINEERING_OFFICES->label();
    $openDataLabel = PageCarouselKey::OPEN_DATA->label();
    $waterLabel = PageCarouselKey::WATER_SCHEDULE->label();

    expect($engineeringLabel)->toBe('المكاتب الهندسية');
    expect($openDataLabel)->toBe('البيانات المفتوحة');
    expect($waterLabel)->toBe('جدول المياه');
});

it('page carousel form includes all page keys', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('homepage.slides.create');

    actingAs($user);

    Livewire::test(PageCarouselForm::class)
        ->assertSet('pageKey', 'services');
});