<?php

declare(strict_types=1);

use App\Livewire\OpenData\OpenDataIndex;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('open data index page returns 200', function (): void {
    get(route('public.open-data.index'))->assertOk();
});

it('open data index renders livewire component', function (): void {
    Livewire::test(OpenDataIndex::class)
        ->assertOk()
        ->assertSee('البيانات المفتوحة')
        ->assertSet('search', '')
        ->assertSet('type', 'datasets');
});

it('open data index shows empty state when no data', function (): void {
    Livewire::test(OpenDataIndex::class)
        ->assertOk()
        ->assertSee('البيانات المفتوحة');
});

it('open data index supports type filter', function (): void {
    Livewire::test(OpenDataIndex::class)
        ->set('type', 'reports')
        ->assertSet('type', 'reports');
});
