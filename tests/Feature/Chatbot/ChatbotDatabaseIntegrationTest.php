<?php

declare(strict_types=1);

use App\Domains\ElectronicServices\Models\ElectronicService;
use Database\Seeders\CouncilMemberSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ElectronicServicesSeeder;
use Database\Seeders\JobSeeder;
use Database\Seeders\MunicipalityDemoSeeder;
use Database\Seeders\PublicFacilitySeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WaterScheduleSeeder;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// =============================================
// Service Category / Service Selection
// =============================================

it('displayed service categories come from database', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $categories = DB::table('service_categories')
        ->where('is_public', true)
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->get(['id', 'name']);

    expect($categories)->not->toBeEmpty();
    expect($categories->first()->name)->not->toBeEmpty();
});

it('displayed services come from database', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $services = DB::table('electronic_services')
        ->where('is_public', true)
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->get(['id', 'name', 'service_category_id']);

    expect($services)->not->toBeEmpty();
    expect($services->first()->name)->not->toBeEmpty();
});

it('service requirements come from database record', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $service = DB::table('electronic_services')
        ->where('is_public', true)
        ->where('status', 'active')
        ->first();

    expect($service)->not->toBeNull();
    $requirements = json_decode($service->requirements ?? '[]', true);
    expect($requirements)->toBeArray();
});

it('service fees come from database record', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $service = DB::table('electronic_services')
        ->where('is_public', true)
        ->where('status', 'active')
        ->first();

    expect($service)->not->toBeNull();
    $fees = json_decode($service->fees ?? '[]', true);
    expect($fees)->toBeArray();
});

it('category selection resolves by real database ID', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $category = DB::table('service_categories')
        ->where('is_public', true)
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->first();

    expect($category)->not->toBeNull();

    $services = DB::table('electronic_services')
        ->where('service_category_id', $category->id)
        ->where('is_public', true)
        ->where('status', 'active')
        ->get();

    expect($services)->not->toBeEmpty();
});

it('service selection resolves by real database ID', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $service = ElectronicService::where('is_public', true)->where('status', 'active')->first();

    expect($service)->not->toBeNull();

    $found = ElectronicService::where('id', $service->id)->first();
    expect($found)->not->toBeNull();
    expect($found->name)->toBe($service->name);
});

// =============================================
// Water Schedule
// =============================================

it('water schedule uses database areas', function (): void {
    $this->seed(WaterScheduleSeeder::class);

    $areas = DB::table('water_areas')->where('is_active', true)->count();
    $schedules = DB::table('water_schedules')->where('is_public', true)->count();

    expect($areas)->toBeGreaterThan(0);
    expect($schedules)->toBeGreaterThan(0);
});

// =============================================
// Facilities
// =============================================

it('facilities come from database', function (): void {
    $this->seed(PublicFacilitySeeder::class);

    $facilities = DB::table('public_facilities')->where('is_public', true)->count();

    expect($facilities)->toBeGreaterThan(0);
});

// =============================================
// Jobs
// =============================================

it('jobs come from database', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(JobSeeder::class);

    $jobs = DB::table('job_offers')->where('is_public', true)->where('status', 'published')->count();

    expect($jobs)->toBeGreaterThan(0);
});

// =============================================
// Council
// =============================================

it('council members come from database', function (): void {
    $this->seed(CouncilMemberSeeder::class);

    $members = DB::table('council_members')->where('is_public', true)->count();

    expect($members)->toBeGreaterThan(0);
});

// =============================================
// Contact Info
// =============================================

it('municipality contact comes from database', function (): void {
    $this->seed(MunicipalityDemoSeeder::class);

    $contacts = DB::table('municipality_contacts')->where('is_active', true)->count();

    expect($contacts)->toBeGreaterThan(0);
});

// =============================================
// Data Quality
// =============================================

it('seeded services have no corrupted content in requirements', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $services = DB::table('electronic_services')->get(['id', 'requirements', 'documents']);

    foreach ($services as $service) {
        $requirements = json_decode($service->requirements ?? '[]', true) ?: [];
        $documents = json_decode($service->documents ?? '[]', true) ?: [];

        foreach (array_merge($requirements, $documents) as $item) {
            expect($item)->not->toMatch('/m\s*sclerosis/i');
        }
    }
});

it('category names are unique in database', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $duplicates = DB::table('service_categories')
        ->select('name', DB::raw('COUNT(*) as cnt'))
        ->groupBy('name')
        ->having('cnt', '>', 1)
        ->count();

    expect($duplicates)->toBe(0);
});

it('service names are unique in database', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $duplicates = DB::table('electronic_services')
        ->select('name', DB::raw('COUNT(*) as cnt'))
        ->groupBy('name')
        ->having('cnt', '>', 1)
        ->count();

    expect($duplicates)->toBe(0);
});
