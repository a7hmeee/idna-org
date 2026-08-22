<?php

declare(strict_types=1);

use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use App\Livewire\EngineeringOffices\PublicEngineeringOfficeShow;
use App\Livewire\EngineeringOffices\PublicEngineeringOfficesIndex;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('engineering offices index page returns 200', function (): void {
    get(route('public.engineering-offices.index'))->assertOk();
});

it('engineering offices index renders livewire component', function (): void {
    Livewire::test(PublicEngineeringOfficesIndex::class)
        ->assertOk()
        ->assertSet('search', '')
        ->assertSet('filter', 'all');
});

it('engineering offices index shows public offices', function (): void {
    EngineeringOffice::create([
        'office_name' => 'مكتب الهندسة',
        'slug' => 'maktab-al-handasa',
        'engineer_name' => 'أحمد',
        'license_number' => 'LIC-001',
        'is_public' => true,
    ]);
    EngineeringOffice::create([
        'office_name' => 'مكتب مخفي',
        'slug' => 'maktab-makhfi',
        'engineer_name' => 'مخفي',
        'license_number' => 'LIC-002',
        'is_public' => false,
    ]);

    Livewire::test(PublicEngineeringOfficesIndex::class)
        ->assertCount('offices.items', 1)
        ->assertSee('مكتب الهندسة')
        ->assertDontSee('مكتب مخفي');
});

it('engineering office detail page returns 200', function (): void {
    $office = EngineeringOffice::create([
        'office_name' => 'مكتب الهندسة',
        'slug' => 'maktab-al-handasa',
        'engineer_name' => 'أحمد',
        'license_number' => 'LIC-001',
        'is_public' => true,
    ]);

    Livewire::test(PublicEngineeringOfficeShow::class, ['office' => $office->slug])
        ->assertOk()
        ->assertSee($office->office_name)
        ->assertSee($office->engineer_name);
});

it('engineering office detail page returns 404 for unpublished', function (): void {
    $office = EngineeringOffice::create([
        'office_name' => 'مكتب مخفي',
        'slug' => 'maktab-makhfi',
        'is_public' => false,
    ]);

    Livewire::test(PublicEngineeringOfficeShow::class, ['office' => $office->slug])
        ->assertStatus(404);
});

it('engineering office detail page returns 404 for missing', function (): void {
    Livewire::test(PublicEngineeringOfficeShow::class, ['office' => 'non-existent-slug'])
        ->assertStatus(404);
});

it('engineering offices index paginates', function (): void {
    for ($i = 0; $i < 15; $i++) {
        EngineeringOffice::create([
            'office_name' => "مكتب {$i}",
            'slug' => "maktab-{$i}",
            'is_public' => true,
        ]);
    }

    Livewire::test(PublicEngineeringOfficesIndex::class)
        ->assertCount('offices.items', 12);
});

it('engineering offices index filters featured', function (): void {
    EngineeringOffice::create([
        'office_name' => 'مكتب مميز',
        'slug' => 'maktab-mumayyaz',
        'is_public' => true,
        'is_featured' => true,
    ]);
    EngineeringOffice::create([
        'office_name' => 'مكتب عادي',
        'slug' => 'maktab-adi',
        'is_public' => true,
        'is_featured' => false,
    ]);

    Livewire::test(PublicEngineeringOfficesIndex::class)
        ->set('filter', 'featured')
        ->assertCount('offices.items', 1)
        ->assertSee('مكتب مميز')
        ->assertDontSee('مكتب عادي');
});

it('engineering offices index searches', function (): void {
    EngineeringOffice::create([
        'office_name' => 'مكتب القدس',
        'slug' => 'maktab-al-quds',
        'is_public' => true,
    ]);
    EngineeringOffice::create([
        'office_name' => 'مكتب الخليل',
        'slug' => 'maktab-al-khalil',
        'is_public' => true,
    ]);

    Livewire::test(PublicEngineeringOfficesIndex::class)
        ->set('search', 'قدس')
        ->assertSee('مكتب القدس')
        ->assertDontSee('مكتب الخليل');
});
