<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\DeleteCustomFieldAction;
use App\Domains\Municipality\Actions\SaveCustomFieldAction;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\CustomFieldDTO;
use App\Domains\Municipality\Enums\CustomFieldType;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityCustomFields extends Component
{
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $key = '';
    public string $value = '';
    public string $type = 'text';
    public int $displayOrder = 0;
    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('createCustomField', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updateCustomField', Municipality::class);

        $field = app(MunicipalityRepositoryInterface::class)->findCustomField($id);

        if ($field) {
            $this->editingId = $id;
            $this->key = $field->key;
            $this->value = $field->value;
            $this->type = $field->type;
            $this->displayOrder = $field->display_order;
            $this->isActive = $field->is_active;
            $this->showForm = true;
        }
    }

    public function save(SaveCustomFieldAction $action): void
    {
        $this->authorize($this->editingId ? 'updateCustomField' : 'createCustomField', Municipality::class);

        $validated = $this->validate([
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string'],
            'type' => ['required', 'string', \Illuminate\Validation\Rule::in(CustomFieldType::values())],
            'displayOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['required', 'boolean'],
        ]);

        $action->execute(CustomFieldDTO::fromRequest($validated), $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ الحقل المخصص بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteCustomField', Municipality::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteCustomFieldAction $action): void
    {
        $this->authorize('deleteCustomField', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الحقل المخصص بنجاح.');
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
        $this->reset(['key', 'value', 'type', 'displayOrder', 'isActive', 'editingId']);
        $this->type = 'text';
        $this->isActive = true;
    }

    public function render()
    {
        return view('livewire.municipality.custom-fields', [
            'fields' => app(MunicipalityRepositoryInterface::class)->getCustomFields(),
            'fieldTypes' => CustomFieldType::options(),
        ]);
    }
}
