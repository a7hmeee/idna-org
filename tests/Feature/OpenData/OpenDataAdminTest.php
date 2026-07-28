<?php

declare(strict_types=1);

use App\Domains\OpenData\Models\OpenDataset;
use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Enums\OpenDataType;
use App\Domains\Authentication\Models\User;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('redirects unauthenticated user from admin page', function (): void {
    $this->get(route('dashboard.open-data'))->assertRedirect(route('login'));
});

it('returns 403 without permission', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Employee');

    $this->actingAs($user)
        ->get(route('dashboard.open-data'))
        ->assertForbidden();
});

it('admin can view open data index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    OpenDataset::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('dashboard.open-data'))
        ->assertOk();
});

it('admin can create open dataset', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user)
        ->get(route('dashboard.open-data.create'))
        ->assertOk();
});

it('admin can view edit form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $dataset = OpenDataset::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.open-data.edit', $dataset))
        ->assertOk();
});

it('public open data page returns 200', function (): void {
    $this->get(route('public.open-data.index'))->assertOk();
});

it('public open data page renders livewire component', function (): void {
    $this->get(route('public.open-data.index'))
        ->assertSeeLivewire(\App\Livewire\OpenData\OpenDataIndex::class);
});

it('public open data shows empty state when no data', function (): void {
    $this->get(route('public.open-data.index'))
        ->assertSee('البيانات المفتوحة');
});

it('public open data shows published datasets', function (): void {
    OpenDataset::factory()->published()->create(['title' => 'إحصاءات السكان 2026']);

    $this->get(route('public.open-data.index'))
        ->assertSee('إحصاءات السكان 2026');
});

it('public open data hides draft datasets', function (): void {
    OpenDataset::factory()->draft()->create(['title' => 'مسودة غير منشورة']);

    $this->get(route('public.open-data.index'))
        ->assertDontSee('مسودة غير منشورة');
});

it('open data repository binding resolves', function (): void {
    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);

    expect($repo)->toBeInstanceOf(\App\Domains\OpenData\Repositories\EloquentOpenDataRepository::class);
});

it('open data repository has all required methods', function (): void {
    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);

    expect($repo)->toHaveMethods([
        'getDatasets', 'find', 'findBySlug', 'getFeaturedDatasets',
        'getLatestDatasets', 'getCategories', 'downloadDataset',
    ]);
});

it('repository returns published datasets', function (): void {
    OpenDataset::factory()->published()->count(3)->create();
    OpenDataset::factory()->draft()->create();

    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);
    $datasets = $repo->getDatasets();

    expect($datasets->total())->toBe(3);
});

it('repository filters by type', function (): void {
    OpenDataset::factory()->published()->count(2)->create(['type' => OpenDataType::Dataset]);
    OpenDataset::factory()->published()->create(['type' => OpenDataType::Report]);

    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);
    $datasets = $repo->getDatasets(type: OpenDataType::Dataset);

    expect($datasets->total())->toBe(2);
});

it('repository searches by title', function (): void {
    OpenDataset::factory()->published()->create(['title' => 'إحصاءات السكان']);
    OpenDataset::factory()->published()->create(['title' => 'ميزانية البلدية']);

    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);
    $datasets = $repo->getDatasets(search: 'إحصاءات');

    expect($datasets->total())->toBe(1);
});

it('repository returns featured datasets', function (): void {
    OpenDataset::factory()->published()->featured()->count(2)->create();
    OpenDataset::factory()->published()->create();

    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);
    $featured = $repo->getFeaturedDatasets();

    expect($featured)->toHaveCount(2);
});

it('repository returns distinct categories', function (): void {
    OpenDataset::factory()->published()->create(['category' => 'إحصاءات']);
    OpenDataset::factory()->published()->create(['category' => 'تقارير']);
    OpenDataset::factory()->published()->create(['category' => 'إحصاءات']);

    $repo = app(\App\Domains\OpenData\Contracts\OpenDataRepositoryInterface::class);
    $categories = $repo->getCategories();

    expect($categories)->toHaveCount(2);
});
