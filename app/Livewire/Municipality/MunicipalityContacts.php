<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\DeleteContactAction;
use App\Domains\Municipality\Actions\SaveContactAction;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\ContactDTO;
use App\Domains\Municipality\Enums\ContactType;
use App\Domains\Municipality\Models\Municipality;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityContacts extends Component
{
    public bool $showForm = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $type = 'phone';

    public string $label = '';

    public ?string $value = null;

    public ?string $icon = null;

    public ?string $url = null;

    public int $displayOrder = 0;

    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('createContact', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updateContact', Municipality::class);

        $contact = app(MunicipalityRepositoryInterface::class)->findContact($id);

        if ($contact) {
            $this->editingId = $id;
            $this->type = $contact->type;
            $this->label = $contact->label;
            $this->value = $contact->value;
            $this->icon = $contact->icon;
            $this->url = $contact->url;
            $this->displayOrder = $contact->display_order;
            $this->isActive = $contact->is_active;
            $this->showForm = true;
        }
    }

    public function save(SaveContactAction $action): void
    {
        $this->authorize($this->editingId ? 'updateContact' : 'createContact', Municipality::class);

        $validated = $this->validate([
            'type' => ['required', 'string', Rule::in(ContactType::values())],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:2000'],
            'displayOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['required', 'boolean'],
        ]);

        $action->execute(ContactDTO::fromRequest($validated), $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ جهة الاتصال بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteContact', Municipality::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteContactAction $action): void
    {
        $this->authorize('deleteContact', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف جهة الاتصال بنجاح.');
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
        $this->reset(['type', 'label', 'value', 'icon', 'url', 'displayOrder', 'isActive', 'editingId']);
        $this->type = 'phone';
        $this->isActive = true;
    }

    public function render()
    {
        return view('livewire.municipality.contacts', [
            'contacts' => app(MunicipalityRepositoryInterface::class)->getContacts(),
            'contactTypes' => ContactType::options(),
        ]);
    }
}
