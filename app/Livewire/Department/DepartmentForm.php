<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Domains\Department\Actions\CreateDepartmentAction;
use App\Domains\Department\Actions\UpdateDepartmentAction;
use App\Domains\Department\DTOs\DepartmentDTO;
use App\Domains\Department\Enums\DepartmentStatus;
use App\Domains\Department\Models\Department;
use App\Domains\Department\Services\DepartmentCoverImageService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class DepartmentForm extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $name = '';

    public ?string $short_description = null;

    public ?string $description = null;

    public ?string $icon = null;

    public $cover_image = null;

    public ?string $existingCoverImageUrl = null;

    public ?string $existingCoverImagePath = null;

    public ?string $manager_name = null;

    public ?string $manager_position = null;

    public ?string $phone = null;

    public ?string $extension = null;

    public ?string $mobile = null;

    public ?string $email = null;

    public ?string $office_location = null;

    public ?string $working_hours = null;

    public ?string $vision = null;

    public ?string $mission = null;

    public ?string $responsibilities = null;

    public string $status = 'active';

    public int $display_order = 0;

    public bool $is_public = true;

    public bool $is_featured = false;

    public function mount(?Department $department = null): void
    {
        if ($department && $department->exists) {
            $this->authorize('update', Department::class);

            $this->editingId = $department->id;
            $this->name = $department->name;
            $this->short_description = $department->short_description;
            $this->description = $department->description;
            $this->icon = $department->icon;
            $this->existingCoverImageUrl = $department->cover_image_url;
            $this->existingCoverImagePath = $department->cover_image_path;
            $this->manager_name = $department->manager_name;
            $this->manager_position = $department->manager_position;
            $this->phone = $department->phone;
            $this->extension = $department->extension;
            $this->mobile = $department->mobile;
            $this->email = $department->email;
            $this->office_location = $department->office_location;
            $this->working_hours = $department->working_hours;
            $this->vision = $department->vision;
            $this->mission = $department->mission;
            $this->responsibilities = $department->responsibilities;
            $this->status = $department->status;
            $this->display_order = $department->display_order;
            $this->is_public = $department->is_public;
            $this->is_featured = $department->is_featured;
        } else {
            $this->authorize('create', Department::class);
        }
    }

    public function updatedCoverImage(): void
    {
        $this->validateOnly('cover_image', [
            'cover_image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);
    }

    public function removeCoverImage(): void
    {
        $this->cover_image = null;
    }

    public function save(): void
    {
        $this->authorize($this->editingId ? 'update' : 'create', Department::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'cover_image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'manager_position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'extension' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'office_location' => ['nullable', 'string', 'max:500'],
            'working_hours' => ['nullable', 'string', 'max:500'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'status' => ['required', Rule::in(DepartmentStatus::values())],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_public' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
        ]);

        $coverImagePath = $this->existingCoverImagePath;

        if ($this->cover_image) {
            $photoService = app(DepartmentCoverImageService::class);

            if ($this->editingId && $coverImagePath) {
                $photoService->delete($coverImagePath);
            }

            $coverImagePath = $photoService->upload($this->cover_image);
        }

        $data = array_merge($validated, [
            'cover_image_path' => $coverImagePath,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $dto = DepartmentDTO::fromRequest($data);

        if ($this->editingId) {
            app(UpdateDepartmentAction::class)->execute($this->editingId, $dto);
        } else {
            app(CreateDepartmentAction::class)->execute($dto);
        }

        session()->flash('success', $this->editingId ? 'تم تحديث الدائرة بنجاح.' : 'تم إنشاء الدائرة بنجاح.');

        $this->redirect(route('dashboard.departments'), navigate: true);
    }

    public function render()
    {
        return view('livewire.department.department-form', [
            'statusOptions' => DepartmentStatus::options(),
        ]);
    }
}
