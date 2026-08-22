<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\CreateHomepageSlideAction;
use App\Domains\Homepage\Actions\UpdateHomepageSlideAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSlideData;
use App\Domains\Homepage\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class HomepageSlideForm extends Component
{
    use WithFileUploads;

    public ?int $slideId = null;

    public string $title = '';

    public ?string $subtitle = null;

    public ?string $description = null;

    public $image = null;

    public ?string $existingImageUrl = null;

    public ?string $buttonText = null;

    public ?string $buttonUrl = null;

    public ?string $secondaryButtonText = null;

    public ?string $secondaryButtonUrl = null;

    public ?string $badgeText = null;

    public bool $isActive = true;

    public ?int $sortOrder = null;

    public ?string $startsAt = null;

    public ?string $endsAt = null;

    public function mount(?int $slide = null): void
    {
        if ($slide) {
            $this->authorize('updateSlide', HomepageSetting::class);

            $slideModel = app(HomepageRepositoryInterface::class)->findSlide($slide);

            if (! $slideModel) {
                abort(404);
            }

            $this->slideId = $slideModel->id;
            $this->title = $slideModel->title;
            $this->subtitle = $slideModel->subtitle;
            $this->description = $slideModel->description;
            $this->existingImageUrl = $slideModel->image_url;
            $this->buttonText = $slideModel->button_text;
            $this->buttonUrl = $slideModel->button_url;
            $this->secondaryButtonText = $slideModel->secondary_button_text;
            $this->secondaryButtonUrl = $slideModel->secondary_button_url;
            $this->badgeText = $slideModel->badge_text;
            $this->isActive = $slideModel->is_active;
            $this->sortOrder = $slideModel->sort_order;
            $this->startsAt = $slideModel->starts_at?->format('Y-m-d\TH:i');
            $this->endsAt = $slideModel->ends_at?->format('Y-m-d\TH:i');
        } else {
            $this->authorize('createSlide', HomepageSetting::class);
        }
    }

    public function save(): void
    {
        if ($this->slideId) {
            $this->authorize('updateSlide', HomepageSetting::class);
        } else {
            $this->authorize('createSlide', HomepageSetting::class);
        }

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'buttonText' => ['nullable', 'string', 'max:255'],
            'buttonUrl' => ['nullable', 'string', 'max:500'],
            'secondaryButtonText' => ['nullable', 'string', 'max:255'],
            'secondaryButtonUrl' => ['nullable', 'string', 'max:500'],
            'badgeText' => ['nullable', 'string', 'max:255'],
            'isActive' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
            'startsAt' => ['nullable', 'date'],
            'endsAt' => ['nullable', 'date'],
        ]);

        if ($this->image) {
            $validated['imagePath'] = $this->image->store('homepage/slides', 'public');
        }

        if ($this->slideId) {
            $action = app(UpdateHomepageSlideAction::class);
            $action->execute($this->slideId, HomepageSlideData::fromRequest($validated));
            session()->flash('success', 'تم تحديث الشريحة بنجاح.');
        } else {
            $action = app(CreateHomepageSlideAction::class);
            $validated['createdBy'] = auth()->id();
            $action->execute(HomepageSlideData::fromRequest($validated));
            session()->flash('success', 'تم إنشاء الشريحة بنجاح.');
        }

        $this->redirect(route('dashboard.homepage.slides'), navigate: true);
    }

    public function removeImage(): void
    {
        if (! $this->slideId) {
            return;
        }

        $repo = app(HomepageRepositoryInterface::class);
        $slide = $repo->findSlide($this->slideId);

        if ($slide && $slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $repo->updateSlide($this->slideId, ['image_path' => null]);
        $this->existingImageUrl = null;
        $this->image = null;

        session()->flash('success', 'تم حذف الصورة بنجاح.');
    }

    public function render()
    {
        return view('livewire.homepage.slide-form');
    }
}
