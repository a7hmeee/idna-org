<?php

declare(strict_types=1);

use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use App\Livewire\WaterSchedule\PublicWaterSchedule;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('water schedule page returns 200', function (): void {
    get(route('public.water-schedule'))->assertOk();
});

it('water schedule renders livewire component', function (): void {
    Livewire::test(PublicWaterSchedule::class)
        ->assertOk()
        ->assertSet('selectedAreaId', '');
});

it('water schedule shows areas in dropdown', function (): void {
    $area = WaterArea::create([
        'name' => 'المنطقة الغربية',
        'is_active' => true,
        'display_order' => 0,
    ]);

    Livewire::test(PublicWaterSchedule::class)
        ->assertSee($area->name);
});

it('water schedule shows schedule when area selected', function (): void {
    $area = WaterArea::create([
        'name' => 'المنطقة الغربية',
        'is_active' => true,
        'display_order' => 0,
    ]);

    WaterSchedule::create([
        'water_area_id' => $area->id,
        'schedule_date' => now()->toDateString(),
        'start_time' => '08:00',
        'end_time' => '14:00',
        'status' => 'available',
        'is_public' => true,
        'display_order' => 0,
    ]);

    Livewire::test(PublicWaterSchedule::class)
        ->set('selectedAreaId', (string) $area->id)
        ->assertSee('08:00')
        ->assertSee('14:00');
});

it('water schedule shows empty state when no schedule', function (): void {
    $area = WaterArea::create([
        'name' => 'المنطقة الشرقية',
        'is_active' => true,
        'display_order' => 0,
    ]);

    Livewire::test(PublicWaterSchedule::class)
        ->set('selectedAreaId', (string) $area->id)
        ->assertSee('لا يوجد جدول');
});

it('water schedule shows prompt when no area selected', function (): void {
    Livewire::test(PublicWaterSchedule::class)
        ->assertSee('اختر المنطقة لعرض جدول الضخ');
});
