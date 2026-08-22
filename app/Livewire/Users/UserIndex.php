<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Domains\Department\Models\Department;
use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;
use App\Domains\UserManagement\Actions\CreateUserAction;
use App\Domains\UserManagement\Actions\DeleteUserAction;
use App\Domains\UserManagement\Actions\ResetUserPasswordAction;
use App\Domains\UserManagement\Actions\UpdateUserAction;
use App\Domains\UserManagement\Contracts\UserManagementRepositoryInterface;
use App\Domains\UserManagement\DTOs\CreateUserDTO;
use App\Domains\UserManagement\DTOs\UpdateUserDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.dashboard')]
final class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterRole = '';

    public string $filterStatus = '';

    public ?int $editingUserId = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public bool $showResetPasswordModal = false;

    public ?int $deletingUserId = null;

    public ?int $resetPasswordUserId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    public ?int $department_id = null;

    public string $role = '';

    public string $status = 'active';

    public string $newPassword = '';

    public string $newPasswordConfirmation = '';

    public array $selectedPermissions = [];

    public function getPermissionsByGroup(): array
    {
        return app(RoleRepositoryInterface::class)->getPermissionsGrouped();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function getUsers(): LengthAwarePaginator
    {
        return app(UserManagementRepositoryInterface::class)->paginate(
            perPage: 15,
            search: $this->search ?: null,
            role: $this->filterRole ?: null,
            status: $this->filterStatus ?: null,
        );
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->selectedPermissions = [];
        $this->showCreateModal = true;
    }

    public function createUser(CreateUserAction $action): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user = $action->execute(CreateUserDTO::fromRequest($validated));

        if (! empty($this->selectedPermissions)) {
            app(UserManagementRepositoryInterface::class)->syncPermissions($user->id, $this->selectedPermissions);
        }

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', 'تم إنشاء المستخدم بنجاح.');
    }

    public function openEditModal(int $userId): void
    {
        $user = app(UserManagementRepositoryInterface::class)->findById($userId);

        if ($user) {
            $this->editingUserId = $userId;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
            $this->department_id = $user->department_id;
            $this->role = $user->getRoleNames()->first() ?? '';
            $this->status = $user->status;
            $this->selectedPermissions = $user->getPermissionNames()->toArray();
            $this->showEditModal = true;
        }
    }

    public function updateUser(UpdateUserAction $action): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->editingUserId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $action->execute(
            $this->editingUserId,
            UpdateUserDTO::fromRequest($validated),
        );

        app(UserManagementRepositoryInterface::class)->syncPermissions(
            $this->editingUserId,
            $this->selectedPermissions,
        );

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('success', 'تم تحديث المستخدم بنجاح.');
    }

    public function confirmDelete(int $userId): void
    {
        $this->deletingUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser(DeleteUserAction $action): void
    {
        if ($this->deletingUserId === auth()->id()) {
            session()->flash('error', 'لا يمكن حذف حسابك الخاص.');
            $this->showDeleteModal = false;

            return;
        }

        $user = app(UserManagementRepositoryInterface::class)->findById($this->deletingUserId);

        if ($user && $user->hasRole('Super Admin')) {
            session()->flash('error', 'لا يمكن حذف حساب المدير العام.');
            $this->showDeleteModal = false;

            return;
        }

        $action->execute($this->deletingUserId);

        $this->showDeleteModal = false;
        $this->deletingUserId = null;
        session()->flash('success', 'تم حذف المستخدم بنجاح.');
    }

    public function confirmResetPassword(int $userId): void
    {
        $this->resetPasswordUserId = $userId;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->showResetPasswordModal = true;
    }

    public function resetPassword(ResetUserPasswordAction $action): void
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
            'newPasswordConfirmation' => ['required', 'string', 'same:newPassword'],
        ]);

        $action->execute($this->resetPasswordUserId, $this->newPassword);

        $this->showResetPasswordModal = false;
        $this->resetPasswordUserId = null;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        session()->flash('success', 'تم إعادة تعيين كلمة المرور بنجاح.');
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    public function closeResetPasswordModal(): void
    {
        $this->showResetPasswordModal = false;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'password', 'password_confirmation', 'department_id', 'role', 'status', 'editingUserId', 'selectedPermissions']);
        $this->status = 'active';
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => $this->getUsers(),
            'roles' => Role::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'permissionsByGroup' => app(RoleRepositoryInterface::class)->getPermissionsGrouped(),
        ]);
    }
}
