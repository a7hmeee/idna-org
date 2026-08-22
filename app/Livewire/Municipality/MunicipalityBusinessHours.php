<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Actions\DeleteBusinessHourAction;
use App\Domains\SharedKernel\Actions\SaveBusinessHourAction;
use App\Domains\SharedKernel\Contracts\BusinessHourRepositoryInterface;
use App\Domains\SharedKernel\DTOs\BusinessHourDTO;
use App\Domains\SharedKernel\Enums\BusinessDay;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityBusinessHours extends Component
{
    public bool $showForm = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $day = 'saturday';

    public ?string $openingTime = null;

    public ?string $closingTime = null;

    public bool $isClosed = false;

    public int $displayOrder = 0;

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('manageBusinessHours', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updateBusinessHours', Municipality::class);

        $businessHour = app(MunicipalityRepositoryInterface::class)->findBusinessHour($id);

        if ($businessHour) {
            $this->editingId = $id;
            $this->day = $businessHour->day;
            $this->openingTime = $businessHour->opening_time;
            $this->closingTime = $businessHour->closing_time;
            $this->isClosed = $businessHour->is_closed;
            $this->displayOrder = $businessHour->display_order;
            $this->showForm = true;
        }
    }

    public function save(SaveBusinessHourAction $action): void
    {
        $this->authorize($this->editingId ? 'updateBusinessHours' : 'manageBusinessHours', Municipality::class);

        $validated = $this->validate([
            'day' => ['required', 'string', Rule::in(BusinessDay::values())],
            'openingTime' => ['nullable', 'string', 'date_format:H:i'],
            'closingTime' => ['nullable', 'string', 'date_format:H:i', 'after:openingTime'],
            'isClosed' => ['required', 'boolean'],
            'displayOrder' => ['required', 'integer', 'min:0'],
        ]);

        if ($validated['isClosed']) {
            $validated['openingTime'] = null;
            $validated['closingTime'] = null;
        }

        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();
        $validated['hourable_id'] = $municipality->id;
        $validated['hourable_type'] = $municipality->getMorphClass();

        $action->execute(BusinessHourDTO::fromRequest($validated), $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ ساعات العمل بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('manageBusinessHours', Municipality::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteBusinessHourAction $action): void
    {
        $this->authorize('manageBusinessHours', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف ساعات العمل بنجاح.');
    }

    public function updatedIsClosed(bool $value): void
    {
        if ($value) {
            $this->openingTime = null;
            $this->closingTime = null;
        }
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
        $this->reset(['day', 'openingTime', 'closingTime', 'isClosed', 'displayOrder', 'editingId']);
        $this->day = 'saturday';
    }

    public function render()
    {
        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        return view('livewire.municipality.business-hours', [
            'businessHours' => app(BusinessHourRepositoryInterface::class)->getForModel($municipality),
            'dayOptions' => BusinessDay::options(),
        ]);
    }
}
