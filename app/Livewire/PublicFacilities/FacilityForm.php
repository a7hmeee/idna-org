<?php

declare(strict_types=1);

namespace App\Livewire\PublicFacilities;

use App\Domains\PublicFacilities\Actions\CreateFacilityAction;
use App\Domains\PublicFacilities\Actions\UpdateFacilityAction;
use App\Domains\PublicFacilities\Contracts\FacilityCategoryRepositoryInterface;
use App\Domains\PublicFacilities\DTOs\FacilityData;
use App\Domains\PublicFacilities\Enums\FacilityStatus;
use App\Domains\PublicFacilities\Models\Facility;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class FacilityForm extends Component
{
    use WithFileUploads;

    public ?int $facilityId = null;

    public string $name = '';

    public ?string $facilityCategoryId = null;

    public string $summary = '';

    public string $description = '';

    public $coverImage = null;

    public ?string $existingCoverImage = null;

    public array $galleryUploads = [];

    public array $existingGallery = [];

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $workingHours = '';

    public array $services = [];

    public array $features = [];

    public array $rules = [];

    public string $status = 'draft';

    public bool $isPublic = false;

    public bool $isFeatured = false;

    public function mount(?Facility $facility = null): void
    {
        $categories = app(FacilityCategoryRepositoryInterface::class)->getActive();

        if ($facility && $facility->exists) {
            $this->authorize('update', Facility::class);

            $this->facilityId = $facility->id;
            $this->name = $facility->name;
            $this->facilityCategoryId = (string) ($facility->facility_category_id ?? '');
            $this->summary = $facility->summary;
            $this->description = $facility->description;
            $this->existingCoverImage = $facility->cover_image_path;
            $this->existingGallery = $facility->gallery ?? [];
            $this->phone = $facility->phone ?? '';
            $this->email = $facility->email ?? '';
            $this->address = $facility->address;
            $this->workingHours = $facility->working_hours ?? '';
            $this->services = $facility->services ?? [];
            $this->features = $facility->features ?? [];
            $this->rules = $facility->rules ?? [];
            $this->status = $facility->status->value;
            $this->isPublic = $facility->is_public;
            $this->isFeatured = $facility->is_featured;
        } else {
            $this->authorize('create', Facility::class);
        }
    }

    public function addService(): void
    {
        $this->services[] = '';
    }

    public function removeService(int $index): void
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
    }

    public function addFeature(): void
    {
        $this->features[] = '';
    }

    public function removeFeature(int $index): void
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function addRule(): void
    {
        $this->rules[] = '';
    }

    public function removeRule(int $index): void
    {
        unset($this->rules[$index]);
        $this->rules = array_values($this->rules);
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'facilityCategoryId' => ['nullable', 'string'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'coverImage' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'galleryUploads' => ['nullable', 'array'],
            'galleryUploads.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'workingHours' => ['nullable', 'string', 'max:255'],
            'services' => ['nullable', 'array'],
            'services.*' => ['nullable', 'string', 'max:500'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:500'],
            'rules' => ['nullable', 'array'],
            'rules.*' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'isPublic' => ['boolean'],
            'isFeatured' => ['boolean'],
        ]);

        $coverImagePath = $this->existingCoverImage;

        if ($this->coverImage) {
            if ($this->existingCoverImage) {
                Storage::disk('public')->delete($this->existingCoverImage);
            }
            $coverImagePath = $this->coverImage->store('facilities', 'public');
        }

        $gallery = $this->existingGallery;

        if ($this->galleryUploads) {
            foreach ($this->galleryUploads as $image) {
                $gallery[] = $image->store('facilities', 'public');
            }
        }

        $dto = FacilityData::fromRequest([
            ...$data,
            'facilityCategoryId' => $this->facilityCategoryId ? (int) $this->facilityCategoryId : null,
            'coverImagePath' => $coverImagePath,
            'gallery' => $gallery,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->facilityId) {
            app(UpdateFacilityAction::class)->execute($this->facilityId, $dto);
            session()->flash('success', 'تم تحديث المرفق بنجاح.');
        } else {
            app(CreateFacilityAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة المرفق بنجاح.');
        }

        $this->redirect(route('dashboard.facilities'), navigate: true);
    }

    #[On('media-selected')]
    public function onMediaSelected(int $id, string $url, string $path, ?string $target = null): void
    {
        if ($target === 'cover') {
            $this->coverImage = null;
            $this->existingCoverImage = $path;
        } elseif ($target === 'gallery') {
            $this->existingGallery = array_values(array_merge($this->existingGallery ?? [], [$path]));
        }
    }

    public function render()
    {
        $categories = app(FacilityCategoryRepositoryInterface::class)->getActive();
        $statuses = FacilityStatus::cases();

        return view('livewire.public-facilities.facility-form', compact('categories', 'statuses'));
    }
}
