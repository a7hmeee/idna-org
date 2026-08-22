<?php

declare(strict_types=1);

use App\Domains\Homepage\Models\HomepageSlide;
use App\Livewire\PublicPageCarousel;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('loads slides for engineering offices page', function (): void {
    HomepageSlide::create([
        'page_key' => 'engineering-offices',
        'title' => 'المكاتب الهندسية',
        'image_path' => 'test.jpg',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'engineering-offices'])
        ->assertOk()
        ->assertSee('المكاتب الهندسية');
});

it('loads slides for open data page', function (): void {
    HomepageSlide::create([
        'page_key' => 'open-data',
        'title' => 'البيانات المفتوحة',
        'image_path' => 'test.jpg',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'open-data'])
        ->assertOk()
        ->assertSee('البيانات المفتوحة');
});

it('loads slides for water schedule page', function (): void {
    HomepageSlide::create([
        'page_key' => 'water-schedule',
        'title' => 'جدول المياه',
        'image_path' => 'test.jpg',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'water-schedule'])
        ->assertOk()
        ->assertSee('جدول المياه');
});

it('does not leak slides between pages', function (): void {
    HomepageSlide::create([
        'page_key' => 'engineering-offices',
        'title' => 'المكاتب الهندسية',
        'image_path' => 'test.jpg',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'open-data'])
        ->assertDontSee('المكاتب الهندسية');
});

it('excludes inactive slides', function (): void {
    HomepageSlide::create([
        'page_key' => 'engineering-offices',
        'title' => 'شريحة غير نشطة',
        'image_path' => 'test.jpg',
        'is_active' => false,
        'sort_order' => 0,
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'engineering-offices'])
        ->assertDontSee('شريحة غير نشطة');
});

it('shows empty when no slides exist', function (): void {
    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'engineering-offices'])
        ->assertOk();
});

it('has multiple indicator when more than one slide', function (): void {
    HomepageSlide::create([
        'page_key' => 'engineering-offices',
        'title' => 'شريحة 1',
        'image_path' => 'test1.jpg',
        'is_active' => true,
        'sort_order' => 0,
    ]);
    HomepageSlide::create([
        'page_key' => 'engineering-offices',
        'title' => 'شريحة 2',
        'image_path' => 'test2.jpg',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'engineering-offices'])
        ->assertOk();
});

it('handles expired slides correctly', function (): void {
    HomepageSlide::create([
        'page_key' => 'engineering-offices',
        'title' => 'شريحة منتهية',
        'image_path' => 'test.jpg',
        'is_active' => true,
        'sort_order' => 0,
        'starts_at' => now()->subDays(10),
        'ends_at' => now()->subDays(5),
    ]);

    Livewire::test(PublicPageCarousel::class, ['pageKey' => 'engineering-offices'])
        ->assertDontSee('شريحة منتهية');
});
