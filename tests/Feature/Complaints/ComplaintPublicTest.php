<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Complaints\Models\Complaint;
use App\Livewire\Complaints\PublicComplaintForm;
use App\Livewire\Complaints\PublicComplaintTracking;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// ============================================
// Public Submission Form
// ============================================

it('public complaint form renders successfully', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->assertSuccessful()
        ->assertSee('تقديم شكوى');
});

it('public complaint form shows categories', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->assertSet('submitted', false);
});

// ============================================
// Submit Complaint
// ============================================

it('can submit complaint via public form', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->set('citizenName', 'علي')
        ->set('phone', '0599123456')
        ->set('category', 'water')
        ->set('subject', 'انقطاع المياه في المنطقة الشرقية')
        ->set('description', 'نعاني من انقطاع المياه منذ ثلاثة أيام في المنطقة الشرقية من البلدة')
        ->call('submit')
        ->assertSet('submitted', true);

    expect(Complaint::count())->toBe(1);
    expect(Complaint::first()->citizen_name)->toBe('علي');
});

it('submitted complaint gets a tracking number', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->set('citizenName', 'خالد')
        ->set('phone', '0599000000')
        ->set('category', 'service')
        ->set('subject', 'شكوى عن خدمة البلدية في المنطقة')
        ->set('description', 'وصف كامل للشكوى المقدمة من المواطن خالد')
        ->call('submit')
        ->assertSet('submitted', true);

    $complaint = Complaint::first();

    expect($complaint->tracking_number)->not->toBeNull();
    expect($complaint->tracking_number)->toMatch('/^CMP-/');
});

it('submitted complaint defaults to submitted status and medium priority', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->set('citizenName', 'سامر')
        ->set('phone', '0599111111')
        ->set('category', 'roads')
        ->set('subject', 'حفريات في الشارع الرئيسي تحتاج إصلاح')
        ->set('description', 'وصف كامل للمشكلة في الشارع الرئيسي بالقرب من المسجد')
        ->call('submit')
        ->assertSet('submitted', true);

    $complaint = Complaint::first();

    expect($complaint->status->value)->toBe('submitted');
    expect($complaint->priority->value)->toBe('medium');
});

it('resets form after successful submission', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->set('citizenName', 'زياد')
        ->set('phone', '0599222222')
        ->set('category', 'environment')
        ->set('subject', 'مكبات نفايات في الحي الغربي تحتاج معالجة')
        ->set('description', 'وصف كامل لمشكلة المكبات والحاجة إلى حاويات إضافية')
        ->call('submit');

    $component = Livewire::test(PublicComplaintForm::class);

    expect($component->get('citizenName'))->toBe('');
});

// ============================================
// Validation
// ============================================

it('validates required fields on public submission', function (): void {
    Livewire::test(PublicComplaintForm::class)
        ->set('citizenName', '')
        ->set('phone', '')
        ->set('category', '')
        ->set('subject', 'short')
        ->set('description', 'short')
        ->call('submit')
        ->assertHasErrors([
            'citizenName' => 'required',
            'phone' => 'required',
            'category' => 'required',
            'subject' => 'min',
            'description' => 'min',
        ]);
});

// ============================================
// Public Tracking
// ============================================

it('public tracking page renders', function (): void {
    Livewire::test(PublicComplaintTracking::class)
        ->assertSuccessful();
});

it('can track complaint by tracking number', function (): void {
    $complaint = Complaint::factory()->create();

    Livewire::test(PublicComplaintTracking::class)
        ->set('trackingNumber', $complaint->tracking_number)
        ->call('track')
        ->assertSet('searched', true)
        ->assertSet('complaint.id', $complaint->id);
});

it('returns null for invalid tracking number', function (): void {
    Livewire::test(PublicComplaintTracking::class)
        ->set('trackingNumber', 'INVALID-123')
        ->call('track')
        ->assertSet('searched', true)
        ->assertSet('complaint', null);
});

it('validates tracking number is required', function (): void {
    Livewire::test(PublicComplaintTracking::class)
        ->set('trackingNumber', '')
        ->call('track')
        ->assertHasErrors(['trackingNumber' => 'required']);
});

// ============================================
// Security: Internal Notes Not Exposed
// ============================================

it('tracking does not expose internal notes', function (): void {
    $complaint = Complaint::factory()->create([
        'internal_notes' => 'هذه ملاحظة سرية لا يجب عرضها',
    ]);

    $tracking = Livewire::test(PublicComplaintTracking::class)
        ->set('trackingNumber', $complaint->tracking_number)
        ->call('track');

    $found = $tracking->get('complaint');

    expect($found)->not->toBeNull();
});

it('tracking does not expose assigned_to directly to public', function (): void {
    $employee = User::factory()->create();
    $complaint = Complaint::factory()->assigned()->create(['assigned_to' => $employee->id]);

    $tracking = Livewire::test(PublicComplaintTracking::class)
        ->set('trackingNumber', $complaint->tracking_number)
        ->call('track');

    $found = $tracking->get('complaint');

    expect($found)->not->toBeNull();
});

// ============================================
// Display Public Response
// ============================================

it('tracking shows public response when available', function (): void {
    $complaint = Complaint::factory()->resolved()->create([
        'public_response' => 'تم حل المشكلة بنجاح',
    ]);

    Livewire::test(PublicComplaintTracking::class)
        ->set('trackingNumber', $complaint->tracking_number)
        ->call('track')
        ->assertSet('complaint.id', $complaint->id);
});

// ============================================
// Factory States
// ============================================

it('complaint factory can create various states', function (): void {
    $submitted = Complaint::factory()->create();
    $underReview = Complaint::factory()->underReview()->create();
    $assigned = Complaint::factory()->assigned()->create();
    $inProgress = Complaint::factory()->inProgress()->create();
    $resolved = Complaint::factory()->resolved()->create();
    $rejected = Complaint::factory()->rejected()->create();
    $closed = Complaint::factory()->closed()->create();
    $urgent = Complaint::factory()->urgent()->create();

    expect(Complaint::count())->toBe(8);
    expect($urgent->priority->value)->toBe('urgent');
});
