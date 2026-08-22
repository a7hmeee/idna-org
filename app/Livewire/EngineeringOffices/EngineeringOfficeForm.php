<?php

declare(strict_types=1);

namespace App\Livewire\EngineeringOffices;

use App\Domains\EngineeringOffices\Actions\CreateEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\UpdateEngineeringOfficeAction;
use App\Domains\EngineeringOffices\DTOs\EngineeringOfficeData;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeApprovalStatus;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeStatus;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use App\Domains\EngineeringOffices\Requests\StoreEngineeringOfficeRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class EngineeringOfficeForm extends Component
{
    public ?int $officeId = null;

    public string $office_name = '';

    public ?string $slug = null;

    public ?string $engineer_name = null;

    public ?string $license_number = null;

    public ?string $phone = null;

    public ?string $mobile = null;

    public ?string $email = null;

    public ?string $address = null;

    public array $specializations = [];

    public string $approval_status = 'approved';

    public string $status = 'active';

    public ?string $notes = null;

    public bool $is_public = false;

    public int $sort_order = 0;

    public ?string $expires_at = null;

    public function mount(?EngineeringOffice $office = null): void
    {
        if ($office?->exists) {
            $this->authorize('update', EngineeringOffice::class);

            $this->officeId = $office->id;
            $this->office_name = $office->office_name;
            $this->slug = $office->slug;
            $this->engineer_name = $office->engineer_name;
            $this->license_number = $office->license_number;
            $this->phone = $office->phone;
            $this->mobile = $office->mobile;
            $this->email = $office->email;
            $this->address = $office->address;
            $this->specializations = $office->specializations ?? [];
            $this->approval_status = $office->approval_status;
            $this->status = $office->status;
            $this->notes = $office->notes;
            $this->is_public = $office->is_public;
            $this->sort_order = $office->sort_order;
            $this->expires_at = $office->expires_at?->format('Y-m-d');
        } else {
            $this->authorize('create', EngineeringOffice::class);
        }
    }

    public function addSpecialization(): void
    {
        $this->specializations[] = '';
    }

    public function removeSpecialization(int $index): void
    {
        unset($this->specializations[$index]);
        $this->specializations = array_values($this->specializations);
    }

    public function save(): void
    {
        $validated = $this->validate((new StoreEngineeringOfficeRequest)->rules());

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $dto = EngineeringOfficeData::fromRequest($validated);

        if ($this->officeId) {
            app(UpdateEngineeringOfficeAction::class)->execute($this->officeId, $dto);
            session()->flash('success', 'تم تحديث المكتب الهندسي بنجاح.');
        } else {
            $office = app(CreateEngineeringOfficeAction::class)->execute($dto);
            $this->officeId = $office->id;
            session()->flash('success', 'تم إنشاء المكتب الهندسي بنجاح.');
        }

        $this->redirectRoute('dashboard.engineering-offices');
    }

    public function render()
    {
        return view('livewire.engineering-offices.engineering-office-form', [
            'approvalStatusOptions' => EngineeringOfficeApprovalStatus::options(),
            'statusOptions' => EngineeringOfficeStatus::options(),
        ]);
    }
}
