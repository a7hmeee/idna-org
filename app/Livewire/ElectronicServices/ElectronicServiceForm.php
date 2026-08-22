<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\Department\Models\Department;
use App\Domains\ElectronicServices\Actions\CreateElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\PublishElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\UpdateElectronicServiceAction;
use App\Domains\ElectronicServices\DTOs\ElectronicServiceData;
use App\Domains\ElectronicServices\Enums\ElectronicServiceStatus;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\ElectronicServices\Requests\StoreElectronicServiceRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ElectronicServiceForm extends Component
{
    public ?int $serviceId = null;

    public string $service_category_id = '';

    public string $department_id = '';

    public string $name = '';

    public ?string $slug = null;

    public ?string $summary = null;

    public ?string $description = null;

    public ?string $eligibility = null;

    public array $requirements = [];

    public array $documents = [];

    public array $steps = [];

    public array $fees = [];

    public ?string $processing_time = null;

    public ?string $portal_url = null;

    public bool $requires_login = true;

    public string $status = 'draft';

    public bool $is_public = true;

    public bool $is_featured = false;

    public int $sort_order = 0;

    protected function rules(): array
    {
        return (new StoreElectronicServiceRequest)->rules();
    }

    public function mount(?ElectronicService $service = null): void
    {
        if ($service?->exists) {
            $this->authorize('update', ElectronicService::class);

            $this->serviceId = $service->id;
            $this->service_category_id = (string) $service->service_category_id;
            $this->department_id = (string) ($service->department_id ?? '');
            $this->name = $service->name;
            $this->slug = $service->slug;
            $this->summary = $service->summary;
            $this->description = $service->description;
            $this->eligibility = $service->eligibility;
            $this->requirements = $service->requirements ?? [];
            $this->documents = $service->documents ?? [];
            $this->steps = $service->steps ?? [];
            $this->fees = $service->fees ?? [];
            $this->processing_time = $service->processing_time;
            $this->portal_url = $service->portal_url;
            $this->requires_login = $service->requires_login;
            $this->status = $service->status;
            $this->is_public = $service->is_public;
            $this->is_featured = $service->is_featured;
            $this->sort_order = $service->sort_order;
        } else {
            $this->authorize('create', ElectronicService::class);
        }
    }

    public function addRequirement(): void
    {
        $this->requirements[] = ['title' => '', 'description' => '', 'is_required' => true];
    }

    public function removeRequirement(int $index): void
    {
        unset($this->requirements[$index]);
        $this->requirements = array_values($this->requirements);
    }

    public function addDocument(): void
    {
        $this->documents[] = ['name' => '', 'description' => '', 'is_required' => true];
    }

    public function removeDocument(int $index): void
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
    }

    public function addStep(): void
    {
        $this->steps[] = ['title' => '', 'description' => ''];
    }

    public function removeStep(int $index): void
    {
        unset($this->steps[$index]);
        $this->steps = array_values($this->steps);
    }

    public function addFee(): void
    {
        $this->fees[] = ['title' => '', 'amount' => '0', 'currency' => 'ILS', 'notes' => ''];
    }

    public function removeFee(int $index): void
    {
        unset($this->fees[$index]);
        $this->fees = array_values($this->fees);
    }

    public function publish(PublishElectronicServiceAction $action): void
    {
        if (! $this->serviceId) {
            return;
        }

        $this->authorize('publish', ElectronicService::class);

        $action->execute($this->serviceId);

        $this->status = 'active';
        session()->flash('success', 'تم نشر الخدمة بنجاح.');
    }

    public function save(CreateElectronicServiceAction|UpdateElectronicServiceAction $action): void
    {
        $validated = $this->validate();

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $dto = ElectronicServiceData::fromRequest($validated);

        if ($this->serviceId) {
            $action = app(UpdateElectronicServiceAction::class);
            $action->execute($this->serviceId, $dto);
            session()->flash('success', 'تم تحديث الخدمة بنجاح.');
        } else {
            $action = app(CreateElectronicServiceAction::class);
            $service = $action->execute($dto);
            $this->serviceId = $service->id;
            session()->flash('success', 'تم إنشاء الخدمة بنجاح.');
        }

        $this->redirectRoute('dashboard.electronic-services.services');
    }

    public function render()
    {
        return view('livewire.electronic-services.electronic-service-form', [
            'categories' => ServiceCategory::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'statusOptions' => ElectronicServiceStatus::options(),
            'canPublish' => $this->serviceId && auth()->user()->can('publish', ElectronicService::class),
        ]);
    }
}
