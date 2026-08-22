<?php

declare(strict_types=1);

namespace App\Livewire\Roles;

use App\Domains\RoleManagement\Actions\CreateRoleAction;
use App\Domains\RoleManagement\Actions\DeleteRoleAction;
use App\Domains\RoleManagement\Actions\UpdateRoleAction;
use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;
use App\Domains\RoleManagement\DTOs\CreateRoleDTO;
use App\Domains\RoleManagement\DTOs\UpdateRoleDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class RoleIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingRoleId = null;

    public ?int $deletingRoleId = null;

    public string $name = '';

    public array $selectedPermissions = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getRoles(): LengthAwarePaginator
    {
        return app(RoleRepositoryInterface::class)->paginate(
            perPage: 15,
            search: $this->search ?: null,
        );
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function createRole(CreateRoleAction $action): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'selectedPermissions' => ['nullable', 'array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $validated['permissions'] = $validated['selectedPermissions'] ?? [];
        unset($validated['selectedPermissions']);

        $action->execute(CreateRoleDTO::fromRequest($validated));

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', 'تم إنشاء الدور بنجاح.');
    }

    public function openEditModal(int $roleId): void
    {
        $role = app(RoleRepositoryInterface::class)->findById($roleId);

        if ($role) {
            $this->editingRoleId = $roleId;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
            $this->showEditModal = true;
        }
    }

    public function updateRole(UpdateRoleAction $action): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->editingRoleId)],
            'selectedPermissions' => ['nullable', 'array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $validated['permissions'] = $validated['selectedPermissions'] ?? [];
        unset($validated['selectedPermissions']);

        $action->execute(
            $this->editingRoleId,
            UpdateRoleDTO::fromRequest($validated),
        );

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('success', 'تم تحديث الدور بنجاح.');
    }

    public function confirmDelete(int $roleId): void
    {
        $this->deletingRoleId = $roleId;
        $this->showDeleteModal = true;
    }

    public function deleteRole(DeleteRoleAction $action): void
    {
        $role = app(RoleRepositoryInterface::class)->findById($this->deletingRoleId);

        if ($role && $role->name === 'Super Admin') {
            session()->flash('error', 'لا يمكن حذف دور المدير العام.');
            $this->showDeleteModal = false;

            return;
        }

        $action->execute($this->deletingRoleId);

        $this->showDeleteModal = false;
        $this->deletingRoleId = null;
        session()->flash('success', 'تم حذف الدور بنجاح.');
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

    private function resetForm(): void
    {
        $this->reset(['name', 'selectedPermissions', 'editingRoleId', 'deletingRoleId']);
    }

    public function render()
    {
        return view('livewire.roles.index', [
            'roles' => $this->getRoles(),
            'permissionsByGroup' => app(RoleRepositoryInterface::class)->getPermissionsGrouped(),
        ]);
    }
}
