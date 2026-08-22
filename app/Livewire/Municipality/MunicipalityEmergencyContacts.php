<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Actions\DeleteEmergencyContactAction;
use App\Domains\SharedKernel\Actions\SaveEmergencyContactAction;
use App\Domains\SharedKernel\Contracts\EmergencyContactRepositoryInterface;
use App\Domains\SharedKernel\DTOs\EmergencyContactDTO;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityEmergencyContacts extends Component
{
    public bool $showForm = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public ?string $department = null;

    public string $phone = '';

    public ?string $icon = null;

    public int $displayOrder = 0;

    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('createEmergencyContact', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updateEmergencyContact', Municipality::class);

        $contact = app(MunicipalityRepositoryInterface::class)->findEmergencyContact($id);

        if ($contact) {
            $this->editingId = $id;
            $this->name = $contact->name;
            $this->department = $contact->department;
            $this->phone = $contact->phone;
            $this->icon = $contact->icon;
            $this->displayOrder = $contact->display_order;
            $this->isActive = $contact->is_active;
            $this->showForm = true;
        }
    }

    public function save(SaveEmergencyContactAction $action): void
    {
        $this->authorize($this->editingId ? 'updateEmergencyContact' : 'createEmergencyContact', Municipality::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:100'],
            'displayOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['required', 'boolean'],
        ]);

        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();
        $validated['contactable_id'] = $municipality->id;
        $validated['contactable_type'] = $municipality->getMorphClass();

        $action->execute(EmergencyContactDTO::fromRequest($validated), $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ جهة اتصال الطوارئ بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteEmergencyContact', Municipality::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteEmergencyContactAction $action): void
    {
        $this->authorize('deleteEmergencyContact', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف جهة اتصال الطوارئ بنجاح.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'department', 'phone', 'icon', 'displayOrder', 'isActive', 'editingId']);
        $this->isActive = true;
    }

    public function render()
    {
        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        return view('livewire.municipality.emergency-contacts', [
            'contacts' => app(EmergencyContactRepositoryInterface::class)->getForModel($municipality),
        ]);
    }
}
