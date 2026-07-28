<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\WaterSchedule\Actions\CopyPreviousScheduleAction;
use App\Domains\WaterSchedule\Actions\CreateWaterAreaAction;
use App\Domains\WaterSchedule\Actions\CreateWaterScheduleAction;
use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\DTOs\WaterAreaData;
use App\Domains\WaterSchedule\DTOs\WaterScheduleData;
use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use App\Livewire\WaterSchedule\PublicWaterSchedule;
use App\Livewire\WaterSchedule\WaterAreasForm;
use App\Livewire\WaterSchedule\WaterAreasIndex;
use App\Livewire\WaterSchedule\WaterMaintenanceForm;
use App\Livewire\WaterSchedule\WaterMaintenanceIndex;
use App\Livewire\WaterSchedule\WaterScheduleDashboard;
use Database\Factories\WaterSchedule\WaterAreaFactory;
use Database\Factories\WaterSchedule\WaterMaintenanceFactory;
use Database\Factories\WaterSchedule\WaterScheduleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

// ============================================
// Water Area Tests
// ============================================

it('can create a water area', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['water.create', 'water.view']);

    actingAs($user);

    Livewire::test(WaterAreasForm::class)
        ->set('name', 'حي البلد')
        ->set('description', 'المنطقة المركزية')
        ->set('displayOrder', 1)
        ->call('save')
        ->assertRedirect(route('dashboard.water-schedule.areas'));

    expect(WaterArea::where('name', 'حي البلد')->exists())->toBeTrue();
});

it('can list water areas', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('water.view');

    WaterArea::factory()->count(3)->create();

    actingAs($user);

    Livewire::test(WaterAreasIndex::class)
        ->assertCount('areas.items', 3);
});

it('admin can toggle water area active status', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['water.update', 'water.view']);

    $area = WaterArea::factory()->create(['is_active' => true]);

    actingAs($user);

    Livewire::test(WaterAreasIndex::class)
        ->call('toggleActive', $area->id);

    expect($area->fresh()->is_active)->toBeFalse();
});

it('unauthorized user cannot create area', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(WaterAreasForm::class)
        ->set('name', 'منطقة غير مصرح بها')
        ->call('save')
        ->assertForbidden();
});

// ============================================
// Water Schedule Tests
// ============================================

it('can create a water schedule via action', function (): void {
    $area = WaterArea::factory()->create();

    $action = app(CreateWaterScheduleAction::class);
    $dto = WaterScheduleData::fromRequest([
        'waterAreaId' => $area->id,
        'scheduleDate' => now()->toDateString(),
        'startTime' => '08:00',
        'endTime' => '16:00',
        'status' => WaterScheduleStatus::Available->value,
    ]);

    $schedule = $action->execute($dto);

    expect($schedule)->toBeInstanceOf(WaterSchedule::class);
    expect($schedule->water_area_id)->toBe($area->id);
    expect($schedule->start_time)->toBe('08:00');
});

it('can copy previous day schedule', function (): void {
    $area = WaterArea::factory()->create();
    $yesterday = now()->subDay()->toDateString();
    $today = now()->toDateString();

    WaterSchedule::factory()->forDate($yesterday)->create([
        'water_area_id' => $area->id,
        'start_time' => '08:00',
        'end_time' => '16:00',
        'status' => WaterScheduleStatus::Available->value,
    ]);

    $action = app(CopyPreviousScheduleAction::class);
    $count = $action->execute($today);

    expect($count)->toBe(1);

    $copied = WaterSchedule::where('schedule_date', $today)
        ->where('water_area_id', $area->id)
        ->first();

    expect($copied)->not->toBeNull();
    expect($copied->start_time)->toBe('08:00');
    expect($copied->end_time)->toBe('16:00');
});

it('can get today schedule', function (): void {
    $area = WaterArea::factory()->create();
    $today = now()->toDateString();

    WaterSchedule::factory()->forDate($today)->create([
        'water_area_id' => $area->id,
        'is_public' => true,
    ]);

    $repository = app(WaterScheduleRepositoryInterface::class);
    $schedules = $repository->getToday();

    expect($schedules)->toHaveCount(1);
    expect($schedules->first()->water_area_id)->toBe($area->id);
});

it('returns latest published when no schedule for today', function (): void {
    $area = WaterArea::factory()->create();
    $yesterday = now()->subDay()->toDateString();

    WaterSchedule::factory()->forDate($yesterday)->create([
        'water_area_id' => $area->id,
        'is_public' => true,
    ]);

    $repository = app(WaterScheduleRepositoryInterface::class);
    $schedules = $repository->getLatestPublished();

    expect($schedules)->toHaveCount(1);
    expect($schedules->first()->schedule_date->toDateString())->toBe($yesterday);
});

it('can publish today schedule', function (): void {
    $area = WaterArea::factory()->create();
    $today = now()->toDateString();

    WaterSchedule::factory()->forDate($today)->create([
        'water_area_id' => $area->id,
        'is_public' => false,
    ]);

    $repository = app(WaterScheduleRepositoryInterface::class);
    $repository->publishToday($today);

    expect(WaterSchedule::where('schedule_date', $today)
        ->where('is_public', true)
        ->count()
    )->toBe(1);
});

// ============================================
// Water Maintenance Tests
// ============================================

it('can get active maintenance', function (): void {
    WaterMaintenance::factory()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHours(3),
        'status' => 'active',
        'is_public' => true,
    ]);

    $repository = app(WaterMaintenanceRepositoryInterface::class);
    $maintenance = $repository->getActiveMaintenance();

    expect($maintenance)->not->toBeNull();
    expect($maintenance->status)->toBe('active');
});

it('does not return finished maintenance as active', function (): void {
    WaterMaintenance::factory()->finished()->create();

    $repository = app(WaterMaintenanceRepositoryInterface::class);
    $maintenance = $repository->getActiveMaintenance();

    expect($maintenance)->toBeNull();
});

// ============================================
// Permission Tests
// ============================================

it('only authorized users can view water schedule dashboard', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.water-schedule'))
        ->assertStatus(403);
});

it('authorized users can view water schedule dashboard', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('water.view');

    actingAs($user);

    Livewire::test(WaterScheduleDashboard::class)
        ->assertOk();
});

it('only authorized users can create water areas', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    get(route('dashboard.water-schedule.areas.create'))
        ->assertStatus(403);
});

// ============================================
// Public Page Tests
// ============================================

it('public water schedule page loads successfully', function (): void {
    get(route('public.water-schedule'))
        ->assertSuccessful();
});

it('public page shows schedule when available', function (): void {
    $area = WaterArea::factory()->create(['name' => 'حي البلد']);
    $today = now()->toDateString();

    WaterSchedule::factory()->forDate($today)->create([
        'water_area_id' => $area->id,
        'is_public' => true,
        'status' => WaterScheduleStatus::Available->value,
    ]);

    Livewire::test(PublicWaterSchedule::class)
        ->set('selectedAreaId', $area->id)
        ->assertSee('حي البلد')
        ->assertSee(WaterScheduleStatus::Available->label());
});

it('public page shows maintenance alert', function (): void {
    WaterMaintenance::factory()->create([
        'title' => 'صيانة على الخط الرئيسي',
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHours(3),
        'status' => 'active',
        'is_public' => true,
    ]);

    Livewire::test(PublicWaterSchedule::class)
        ->assertSee('صيانة على الخط الرئيسي');
});
