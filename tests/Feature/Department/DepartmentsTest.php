<?php

declare(strict_types=1);

use App\Domains\Authentication\Models\User;
use App\Domains\Department\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

it('redirects unauthenticated user to login', function (): void {
    get(route('dashboard.departments'))
        ->assertRedirect(route('login'));
});

it('returns 403 for user without departments.view permission', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('dashboard.departments'))
        ->assertForbidden();
});

it('admin can view departments index', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('departments.view');

    actingAs($user)
        ->get(route('dashboard.departments'))
        ->assertSuccessful();
});

it('admin can view create department form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('departments.create');

    actingAs($user)
        ->get(route('dashboard.departments.create'))
        ->assertSuccessful();
});

it('admin can view edit department form', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('departments.update');

    $department = Department::factory()->create();

    actingAs($user)
        ->get(route('dashboard.departments.edit', $department))
        ->assertSuccessful();
});

it('admin can view department show page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('departments.view');

    $department = Department::factory()->create();

    actingAs($user)
        ->get(route('dashboard.departments.show', $department))
        ->assertSuccessful();
});

it('admin can create department', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['departments.create', 'departments.view']);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Department\DepartmentForm::class)
        ->set('name', 'دائرة الهندسة')
        ->set('status', 'active')
        ->call('save')
        ->assertRedirect(route('dashboard.departments'));

    expect(Department::where('name', 'دائرة الهندسة')->exists())->toBeTrue();
});

it('admin can update department', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['departments.update', 'departments.view']);

    $department = Department::factory()->create([
        'name' => 'الاسم القديم',
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Department\DepartmentForm::class, ['department' => $department])
        ->set('name', 'الاسم الجديد')
        ->call('save')
        ->assertRedirect(route('dashboard.departments'));

    expect($department->fresh()->name)->toBe('الاسم الجديد');
});

it('admin can toggle public', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['departments.publish', 'departments.view']);

    $department = Department::factory()->create([
        'is_public' => false,
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Department\DepartmentsIndex::class)
        ->call('togglePublic', $department->id)
        ->assertSessionHas('success');

    expect($department->fresh()->is_public)->toBeTrue();
});

it('admin can toggle featured', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['departments.feature', 'departments.view']);

    $department = Department::factory()->create([
        'is_featured' => false,
    ]);

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Department\DepartmentsIndex::class)
        ->call('toggleFeatured', $department->id)
        ->assertSessionHas('success');

    expect($department->fresh()->is_featured)->toBeTrue();
});

it('unauthorized user cannot manage departments', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('departments.view');

    $department = Department::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Department\DepartmentsIndex::class)
        ->call('togglePublic', $department->id)
        ->assertForbidden();
});

it('admin can delete department', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(['departments.delete', 'departments.view']);

    $department = Department::factory()->create();

    actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Department\DepartmentsIndex::class)
        ->call('confirmDelete', $department->id)
        ->assertSet('showDeleteModal', true)
        ->call('delete')
        ->assertSessionHas('success');

    expect(Department::find($department->id))->toBeNull();
});
