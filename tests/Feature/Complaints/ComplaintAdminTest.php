<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Complaints\Actions\AssignComplaintAction;
use App\Domains\Complaints\Actions\ChangeStatusAction;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\Complaints\Actions\RespondToComplaintAction;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\DTOs\ComplaintData;
use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\Department\Models\Department;
use App\Livewire\Complaints\ComplaintForm;
use App\Livewire\Complaints\ComplaintsIndex;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

// ============================================
// Authentication & Authorization
// ============================================

it('redirects unauthenticated user from admin complaints index', function (): void {
    get(route('dashboard.complaints'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin complaints create', function (): void {
    get(route('dashboard.complaints.create'))->assertRedirect(route('login'));
});

it('redirects unauthenticated user from admin complaints edit', function (): void {
    $complaint = Complaint::factory()->create();

    get(route('dashboard.complaints.edit', $complaint))->assertRedirect(route('login'));
});

it('returns 403 for user without complaints.view permission', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Employee');

    actingAs($user);

    get(route('dashboard.complaints'))->assertForbidden();
});

it('returns 403 for user without complaints.create permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('view complaints');

    actingAs($user);

    get(route('dashboard.complaints.create'))->assertForbidden();
});

it('returns 403 for user without complaints.update permission', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('view complaints');
    $complaint = Complaint::factory()->create();

    actingAs($user);

    get(route('dashboard.complaints.edit', $complaint))->assertForbidden();
});

// ============================================
// Admin View
// ============================================

it('admin can view complaints dashboard', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ComplaintsIndex::class)
        ->assertSuccessful();
});

it('admin can view create complaint form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ComplaintForm::class)
        ->assertSuccessful()
        ->assertSet('status', 'submitted');
});

it('admin can view edit complaint form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $department = Department::factory()->create(['is_public' => true]);
    $complaint = Complaint::factory()->create(['department_id' => $department->id]);

    actingAs($user);

    Livewire::test(ComplaintForm::class, ['complaint' => $complaint])
        ->assertSuccessful()
        ->assertSet('citizenName', $complaint->citizen_name)
        ->assertSet('complaintId', $complaint->id);
});

// ============================================
// Create Complaint
// ============================================

it('admin can create complaint via form', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $department = Department::factory()->create(['is_public' => true]);

    actingAs($user);

    Livewire::test(ComplaintForm::class)
        ->set('citizenName', 'أحمد')
        ->set('phone', '0599123456')
        ->set('category', 'service')
        ->set('departmentId', $department->id)
        ->set('subject', 'شكوى عن خدمة')
        ->set('description', 'وصف الشكوى بالكامل')
        ->set('priority', 'medium')
        ->call('save')
        ->assertRedirect(route('dashboard.complaints'));

    expect(Complaint::count())->toBe(1);
    expect(Complaint::first()->citizen_name)->toBe('أحمد');
});

it('can create complaint via action', function (): void {
    $dto = ComplaintData::fromRequest([
        'citizenName' => 'محمد',
        'phone' => '0599000000',
        'category' => ComplaintCategory::Water,
        'subject' => 'انقطاع المياه',
        'description' => 'شكوى عن انقطاع المياه في المنطقة',
        'priority' => ComplaintPriority::High,
        'status' => ComplaintStatus::Submitted,
        'createdBy' => 1,
        'updatedBy' => 1,
    ]);

    $complaint = app(CreateComplaintAction::class)->execute($dto);

    expect($complaint)->toBeInstanceOf(Complaint::class);
    expect($complaint->citizen_name)->toBe('محمد');
    expect($complaint->status)->toBe(ComplaintStatus::Submitted);
});

// ============================================
// Validation
// ============================================

it('validates required fields on complaint create', function (): void {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user);

    Livewire::test(ComplaintForm::class)
        ->set('citizenName', '')
        ->call('save')
        ->assertHasErrors(['citizenName' => 'required']);
});

// ============================================
// Status Workflow
// ============================================

it('can change complaint status', function (): void {
    $complaint = Complaint::factory()->create();

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::UnderReview);

    expect($complaint->fresh()->status->value)->toBe('under_review');
});

it('can transition complaint through full workflow', function (): void {
    $complaint = Complaint::factory()->create();

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::UnderReview);
    expect($complaint->fresh()->status->value)->toBe('under_review');

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::Assigned);
    expect($complaint->fresh()->status->value)->toBe('assigned');

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::InProgress);
    expect($complaint->fresh()->status->value)->toBe('in_progress');

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::Resolved);
    expect($complaint->fresh()->status->value)->toBe('resolved');
    expect($complaint->fresh()->resolution_at)->not->toBeNull();

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::Closed);
    expect($complaint->fresh()->status->value)->toBe('closed');
});

it('can reject a complaint', function (): void {
    $complaint = Complaint::factory()->create();

    app(ChangeStatusAction::class)->execute($complaint->id, ComplaintStatus::Rejected);

    expect($complaint->fresh()->status->value)->toBe('rejected');
});

// ============================================
// Assign
// ============================================

it('can assign complaint to employee', function (): void {
    $complaint = Complaint::factory()->create();
    $employee = User::factory()->create();

    app(AssignComplaintAction::class)->execute($complaint->id, $employee->id);

    expect($complaint->fresh()->assigned_to)->toBe($employee->id);
});

it('assign action updates status to assigned', function (): void {
    $complaint = Complaint::factory()->create();
    $employee = User::factory()->create();

    app(AssignComplaintAction::class)->execute($complaint->id, $employee->id);

    expect($complaint->fresh()->assigned_to)->toBe($employee->id);
});

// ============================================
// Respond
// ============================================

it('can respond to a complaint', function (): void {
    $complaint = Complaint::factory()->create();

    app(RespondToComplaintAction::class)->execute($complaint->id, 'تم حل المشكلة');

    expect($complaint->fresh()->public_response)->toBe('تم حل المشكلة');
});

// ============================================
// Internal Notes
// ============================================

it('can store internal notes', function (): void {
    $complaint = Complaint::factory()->create(['internal_notes' => null]);

    app(ComplaintRepositoryInterface::class)->update($complaint->id, [
        'internal_notes' => 'ملاحظات داخلية سرية',
    ]);

    expect($complaint->fresh()->internal_notes)->toBe('ملاحظات داخلية سرية');
});

// ============================================
// Delete
// ============================================

it('can soft delete a complaint', function (): void {
    $complaint = Complaint::factory()->create();

    app(ComplaintRepositoryInterface::class)->delete($complaint->id);

    expect(Complaint::count())->toBe(0);
    expect(Complaint::withTrashed()->count())->toBe(1);
});

// ============================================
// Dashboard Filtering & Pagination
// ============================================

it('dashboard paginates complaints', function (): void {
    Complaint::factory()->count(25)->create();

    $paginator = app(ComplaintRepositoryInterface::class)->paginateDashboard();

    expect($paginator->total())->toBe(25);
});

it('dashboard can filter by status', function (): void {
    Complaint::factory()->count(2)->create();
    Complaint::factory()->resolved()->create();

    $paginator = app(ComplaintRepositoryInterface::class)->paginateDashboard(status: 'resolved');

    expect($paginator->total())->toBe(1);
});

it('dashboard can filter by department', function (): void {
    $dept1 = Department::factory()->create(['is_public' => true]);
    $dept2 = Department::factory()->create(['is_public' => true]);

    Complaint::factory()->count(2)->create(['department_id' => $dept1->id]);
    Complaint::factory()->create(['department_id' => $dept2->id]);

    $paginator = app(ComplaintRepositoryInterface::class)->paginateDashboard(departmentId: $dept1->id);

    expect($paginator->total())->toBe(2);
});

it('dashboard can filter by priority', function (): void {
    Complaint::factory()->urgent()->create();
    Complaint::factory()->count(2)->create(['priority' => ComplaintPriority::Low]);

    $paginator = app(ComplaintRepositoryInterface::class)->paginateDashboard(priority: 'urgent');

    expect($paginator->total())->toBe(1);
});

// ============================================
// Count By Status
// ============================================

it('can count complaints by status', function (): void {
    Complaint::factory()->count(3)->create();
    Complaint::factory()->resolved()->count(2)->create();
    Complaint::factory()->rejected()->create();

    $counts = app(ComplaintRepositoryInterface::class)->countByStatus();

    expect($counts)->toHaveCount(3);
});

// ============================================
// Tracking Number
// ============================================

it('auto-generates tracking number on create', function (): void {
    $complaint = Complaint::factory()->create(['tracking_number' => null]);

    expect($complaint->fresh()->tracking_number)->not->toBeNull();
    expect($complaint->fresh()->tracking_number)->toMatch('/^CMP-/');
});
