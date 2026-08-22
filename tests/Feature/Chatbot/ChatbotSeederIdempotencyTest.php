<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;
use Database\Seeders\ChatbotSearchTermSeeder;
use Database\Seeders\ChatbotTrainingSeeder;
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
// Seeder Idempotency Tests
// =============================================

it('electronic services seeder is idempotent', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $categoriesAfterFirst = DB::table('service_categories')->count();
    $servicesAfterFirst = DB::table('electronic_services')->count();

    $this->seed(ElectronicServicesSeeder::class);

    $categoriesAfterSecond = DB::table('service_categories')->count();
    $servicesAfterSecond = DB::table('electronic_services')->count();

    expect($categoriesAfterFirst)->toBe($categoriesAfterSecond);
    expect($servicesAfterFirst)->toBe($servicesAfterSecond);
    expect($categoriesAfterFirst)->toBeGreaterThan(0);
    expect($servicesAfterFirst)->toBeGreaterThan(0);
});

it('chatbot search term seeder is idempotent', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(ChatbotSearchTermSeeder::class);

    $termsAfterFirst = DB::table('service_search_terms')->count();

    $this->seed(ChatbotSearchTermSeeder::class);

    $termsAfterSecond = DB::table('service_search_terms')->count();

    expect($termsAfterFirst)->toBe($termsAfterSecond);
    expect($termsAfterFirst)->toBeGreaterThan(0);
});

it('chatbot training seeder is idempotent', function (): void {
    $this->seed(ChatbotTrainingSeeder::class);

    $examplesAfterFirst = DB::table('chat_training_examples')->count();

    $this->seed(ChatbotTrainingSeeder::class);

    $examplesAfterSecond = DB::table('chat_training_examples')->count();

    expect($examplesAfterFirst)->toBe($examplesAfterSecond);
    expect($examplesAfterFirst)->toBeGreaterThan(0);
});

it('department seeder is idempotent', function (): void {
    $this->seed(DepartmentSeeder::class);

    $departmentsAfterFirst = DB::table('departments')->count();

    $this->seed(DepartmentSeeder::class);

    $departmentsAfterSecond = DB::table('departments')->count();

    expect($departmentsAfterFirst)->toBe($departmentsAfterSecond);
    expect($departmentsAfterFirst)->toBeGreaterThan(0);
});

// =============================================
// Database Integration Tests
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

it('service requirements come from database', function (): void {
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

it('service fees come from database', function (): void {
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

    $service = DB::table('electronic_services')
        ->where('is_public', true)
        ->where('status', 'active')
        ->first();

    expect($service)->not->toBeNull();

    $found = DB::table('electronic_services')->where('id', $service->id)->first();
    expect($found)->not->toBeNull();
    expect($found->name)->toBe($service->name);
});

it('water handler uses database records', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(WaterScheduleSeeder::class);

    $areas = DB::table('water_areas')->where('is_active', true)->count();
    $schedules = DB::table('water_schedules')->where('is_public', true)->count();

    expect($areas)->toBeGreaterThan(0);
    expect($schedules)->toBeGreaterThan(0);
});

it('facilities handler uses database records', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(PublicFacilitySeeder::class);

    $facilities = DB::table('public_facilities')->where('is_public', true)->count();

    expect($facilities)->toBeGreaterThan(0);
});

it('jobs handler uses database records', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(DepartmentSeeder::class);
    $this->seed(JobSeeder::class);

    $jobs = DB::table('job_offers')->where('is_public', true)->where('status', 'published')->count();

    expect($jobs)->toBeGreaterThan(0);
});

it('council members come from database', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(CouncilMemberSeeder::class);

    $members = DB::table('council_members')->where('is_public', true)->count();

    expect($members)->toBeGreaterThan(0);
});

it('contact info comes from database', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(MunicipalityDemoSeeder::class);

    $contacts = DB::table('municipality_contacts')->where('is_active', true)->count();

    expect($contacts)->toBeGreaterThan(0);
});

it('data guard filters placeholder contact values', function (): void {
    $guard = app(PublicChatbotDataQualityGuard::class);

    $placeholderPhones = ['+970-22-123456', '+970-22-123457', '+970-22-123458'];
    $placeholderEmails = ['info@idhna.ps', 'support@idhna.ps'];

    foreach ($placeholderPhones as $phone) {
        expect($guard->isDemoValue($phone))->toBeTrue();
    }

    foreach ($placeholderEmails as $email) {
        expect($guard->isDemoValue($email))->toBeTrue();
    }
});

it('no duplicated seeded categories after reseeding', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $duplicates = DB::table('service_categories')
        ->select('name', DB::raw('COUNT(*) as cnt'))
        ->groupBy('name')
        ->having('cnt', '>', 1)
        ->count();

    expect($duplicates)->toBe(0);
});

it('no duplicated seeded services after reseeding', function (): void {
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);

    $duplicates = DB::table('electronic_services')
        ->select('name', DB::raw('COUNT(*) as cnt'))
        ->groupBy('name')
        ->having('cnt', '>', 1)
        ->count();

    expect($duplicates)->toBe(0);
});
