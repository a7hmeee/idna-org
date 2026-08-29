<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\CreateCouncilMemberAction;
use App\Domains\Municipality\Actions\UpdateCouncilMemberAction;
use App\Domains\Municipality\DTOs\CouncilMemberDTO;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Enums\CouncilMemberStatus;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Services\CouncilMemberPhotoService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class CouncilMemberForm extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $full_name = '';

    public ?string $national_number = null;

    public string $position = 'council_member';

    public ?string $qualification = null;

    public ?string $profession = null;

    public ?string $bio = null;

    public $photo = null;

    public ?string $existingPhotoUrl = null;

    public ?string $existingPhotoPath = null;

    public ?string $phone = null;

    public ?string $mobile = null;

    public ?string $email = null;

    public ?string $address = null;

    public ?string $facebook = null;

    public ?string $twitter = null;

    public ?string $linkedin = null;

    public ?string $term_start = null;

    public ?string $term_end = null;

    public ?int $years_of_experience = null;

    public ?string $committee = null;

    public string $status = 'active';

    public int $display_order = 0;

    public bool $is_public = false;

    public bool $is_featured = false;

    public function mount(?CouncilMember $councilMember = null): void
    {
        if ($councilMember && $councilMember->exists) {
            $this->authorize('update', CouncilMember::class);

            $this->editingId = $councilMember->id;
            $this->full_name = $councilMember->full_name;
            $this->national_number = $councilMember->national_number;
            $this->position = $councilMember->position;
            $this->qualification = $councilMember->qualification;
            $this->profession = $councilMember->profession;
            $this->bio = $councilMember->bio;
            $this->existingPhotoUrl = $councilMember->photo_url;
            $this->existingPhotoPath = $councilMember->photo_path;
            $this->phone = $councilMember->phone;
            $this->mobile = $councilMember->mobile;
            $this->email = $councilMember->email;
            $this->address = $councilMember->address;
            $this->facebook = $councilMember->facebook;
            $this->twitter = $councilMember->twitter;
            $this->linkedin = $councilMember->linkedin;
            $this->term_start = $councilMember->term_start?->format('Y-m-d');
            $this->term_end = $councilMember->term_end?->format('Y-m-d');
            $this->years_of_experience = $councilMember->years_of_experience;
            $this->committee = $councilMember->committee;
            $this->status = $councilMember->status;
            $this->display_order = $councilMember->display_order;
            $this->is_public = $councilMember->is_public;
            $this->is_featured = $councilMember->is_featured;
        } else {
            $this->authorize('create', CouncilMember::class);
        }
    }

    public function updatedPhoto(): void
    {
        $this->validateOnly('photo', [
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
    }

    public function save(): void
    {
        $this->authorize($this->editingId ? 'update' : 'create', CouncilMember::class);

        $validated = $this->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'national_number' => ['nullable', 'string', 'max:50'],
            'position' => ['required', Rule::in(CouncilMemberPosition::values())],
            'qualification' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'facebook' => ['nullable', 'url', 'max:2000'],
            'twitter' => ['nullable', 'url', 'max:2000'],
            'linkedin' => ['nullable', 'url', 'max:2000'],
            'term_start' => ['required', 'date'],
            'term_end' => ['nullable', 'date', 'after:term_start'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:100'],
            'committee' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(CouncilMemberStatus::values())],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_public' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
        ]);

        $photoPath = $this->existingPhotoPath;

        if (! $photoPath && $this->editingId) {
            $existingMember = CouncilMember::find($this->editingId);
            $photoPath = $existingMember?->photo_path;
        }

        if ($this->photo) {
            $photoService = app(CouncilMemberPhotoService::class);

            if ($this->editingId && $photoPath) {
                $photoService->delete($photoPath);
            }

            $photoPath = $photoService->upload($this->photo);
        }

        $data = array_merge($validated, [
            'photo_path' => $photoPath,
            'updated_by' => auth()->id(),
        ]);

        if (! $this->editingId) {
            $data['created_by'] = auth()->id();
        }

        $dto = CouncilMemberDTO::fromRequest($data);

        if ($this->editingId) {
            app(UpdateCouncilMemberAction::class)->execute($this->editingId, $dto);
        } else {
            app(CreateCouncilMemberAction::class)->execute($dto);
        }

        session()->flash('success', $this->editingId ? 'تم تحديث العضو بنجاح.' : 'تم إنشاء العضو بنجاح.');

        $this->redirect(route('dashboard.municipality.council-members'), navigate: true);
    }

    public function render()
    {
        return view('livewire.municipality.council-member-form', [
            'positionOptions' => CouncilMemberPosition::options(),
            'statusOptions' => CouncilMemberStatus::options(),
        ]);
    }
}
