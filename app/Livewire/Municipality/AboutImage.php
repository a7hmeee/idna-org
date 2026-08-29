<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Actions\DeleteMediaAction;
use App\Domains\SharedKernel\Actions\SaveMediaAction;
use App\Domains\SharedKernel\Enums\MediaCollection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

final class AboutImage extends Component
{
    use WithFileUploads;

    public $file = null;

    public ?bool $showConfirmRemove = false;

    public function mount(): void
    {
        $this->authorize('update', Municipality::class);
    }

    public function updatedFile(): void
    {
        $this->validateOnly('file', [
            'file' => ['required', 'image', 'max:5120', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);
    }

    public function save(SaveMediaAction $action): void
    {
        $this->authorize('update', Municipality::class);

        $this->validate([
            'file' => ['required', 'image', 'max:5120', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $existing = $municipality->media()
            ->where('collection', MediaCollection::AboutImage->value)
            ->latest()
            ->first();

        $action->execute($this->file, [
            'collection' => MediaCollection::AboutImage->value,
            'mediable_id' => $municipality->id,
            'mediable_type' => $municipality->getMorphClass(),
            'title' => 'صورة نبذة عن البلدية',
            'alt' => $municipality->name_ar ?? null,
            'display_order' => 0,
            'is_active' => true,
        ], $existing?->id);

        $this->reset('file');
        session()->flash('about_image_success', 'تم تحديث صورة نبذة عن البلدية بنجاح.');
    }

    public function confirmRemove(): void
    {
        $this->showConfirmRemove = true;
    }

    public function remove(DeleteMediaAction $action): void
    {
        $this->authorize('update', Municipality::class);

        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $media = $municipality->media()
            ->where('collection', MediaCollection::AboutImage->value)
            ->latest()
            ->first();

        if ($media) {
            $action->execute($media->id);
        }

        $this->showConfirmRemove = false;
        session()->flash('about_image_success', 'تمت إزالة الصورة بنجاح.');
    }

    public function cancelRemove(): void
    {
        $this->showConfirmRemove = false;
    }

    public function render()
    {
        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $media = $municipality->media()
            ->where('collection', MediaCollection::AboutImage->value)
            ->latest()
            ->first();

        $imageUrl = null;
        if ($media && Storage::disk($media->disk)->exists($media->path)) {
            $imageUrl = asset('storage/'.$media->path);
        }

        return view('livewire.municipality.about-image', [
            'imageUrl' => $imageUrl,
            'media' => $media,
        ]);
    }
}
