<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Actions\DeleteMediaAction;
use App\Domains\SharedKernel\Actions\SaveMediaAction;
use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\Enums\MediaCollection;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class MunicipalityMedia extends Component
{
    use WithFileUploads;

    public bool $showForm = false;

    public bool $showDeleteModal = false;

    public bool $showDetailsModal = false;

    public bool $showUsageModal = false;

    public bool $showPreviewModal = false;

    public bool $showWarningModal = false;

    public string $viewMode = 'grid';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public ?int $warningId = null;

    public ?int $detailsId = null;

    public ?int $usageId = null;

    public ?int $previewId = null;

    public $file = null;

    public ?string $previewUrl = null;

    public string $collection = 'attachment';

    public ?string $title = null;

    public ?string $alt = null;

    public int $displayOrder = 0;

    public bool $isActive = true;

    public string $search = '';

    public string $filterCollection = '';

    public string $filterType = '';

    public string $filterStatus = '';

    public string $filterUsage = '';

    public string $sortBy = 'created';

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('createMedia', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updateMedia', Municipality::class);

        $media = app(MunicipalityRepositoryInterface::class)->findMedia($id);

        if ($media) {
            $this->editingId = $id;
            $this->collection = $media->collection;
            $this->title = $media->title;
            $this->alt = $media->alt;
            $this->displayOrder = $media->display_order;
            $this->isActive = $media->is_active;
            $this->previewUrl = Storage::disk($media->disk)->exists($media->path)
                ? asset('storage/'.$media->path)
                : null;
            $this->showForm = true;
        }
    }

    public function openDetailsModal(int $id): void
    {
        $this->detailsId = $id;
        $this->showDetailsModal = true;
    }

    public function openUsageModal(int $id): void
    {
        $this->usageId = $id;
        $this->showUsageModal = true;
    }

    public function openPreviewModal(int $id): void
    {
        $this->previewId = $id;
        $this->showPreviewModal = true;
    }

    public function copyUrl(int $id): void
    {
        $media = Media::find($id);
        if ($media) {
            $url = asset('storage/'.$media->path);
            $this->dispatch('copy-to-clipboard', url: $url);
            session()->flash('success', 'تم نسخ الرابط بنجاح.');
        }
    }

    public function replaceMedia(int $id): void
    {
        $this->authorize('updateMedia', Municipality::class);

        $media = Media::find($id);
        if ($media) {
            $this->editingId = $id;
            $this->collection = $media->collection;
            $this->title = $media->title;
            $this->alt = $media->alt;
            $this->displayOrder = $media->display_order;
            $this->isActive = $media->is_active;
            $this->previewUrl = Storage::disk($media->disk)->exists($media->path)
                ? asset('storage/'.$media->path)
                : null;
            $this->showForm = true;
        }
    }

    public function updatedFile(): void
    {
        [$maxSize, $allowedMimes] = match ($this->collection) {
            'logo', 'white_logo', 'dark_logo', 'favicon', 'mobile_logo' => [2048, 'jpg,jpeg,png,gif,webp,svg'],
            'hero', 'cover', 'banner', 'gallery', 'images', 'about_image', 'statistics_bg', 'portal-cta', 'departments-hero', 'council-hero', 'decisions-hero' => [5120, 'jpg,jpeg,png,gif,webp'],
            default => [10240, 'jpg,jpeg,png,gif,webp,svg,pdf,doc,docx'],
        };

        $this->validateOnly('file', [
            'file' => ['required', 'file', "max:{$maxSize}", "mimes:{$allowedMimes}"],
        ]);
    }

    public function removeFilePreview(): void
    {
        $this->file = null;
        $this->previewUrl = null;
    }

    public function save(SaveMediaAction $action): void
    {
        $this->authorize($this->editingId ? 'updateMedia' : 'createMedia', Municipality::class);

        [$maxSize, $allowedMimes] = match ($this->collection) {
            'logo', 'white_logo', 'dark_logo', 'favicon', 'mobile_logo' => [2048, 'jpg,jpeg,png,gif,webp,svg'],
            'hero', 'cover', 'banner', 'gallery', 'images', 'about_image', 'statistics_bg', 'portal-cta', 'departments-hero', 'council-hero', 'decisions-hero' => [5120, 'jpg,jpeg,png,gif,webp'],
            default => [10240, 'jpg,jpeg,png,gif,webp,svg,pdf,doc,docx'],
        };

        $fileRequired = $this->editingId ? 'nullable' : 'required';

        $rules = [
            'file' => [$fileRequired, 'file', "max:{$maxSize}", "mimes:{$allowedMimes}"],
            'collection' => ['required', 'string', Rule::in(MediaCollection::values())],
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:500'],
            'displayOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['required', 'boolean'],
        ];

        $this->validate($rules);

        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $data = [
            'collection' => $this->collection,
            'mediable_id' => $municipality->id,
            'mediable_type' => $municipality->getMorphClass(),
            'title' => $this->title,
            'alt' => $this->alt,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];

        $action->execute($this->file, $data, $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ المرفق بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteMedia', Municipality::class);

        $media = Media::find($id);
        if ($media && $media->isUsed()) {
            $this->warningId = $id;
            $this->showWarningModal = true;

            return;
        }

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteAnyway(DeleteMediaAction $action): void
    {
        $this->authorize('deleteMedia', Municipality::class);

        $action->execute($this->warningId);

        $this->showWarningModal = false;
        $this->warningId = null;
        session()->flash('success', 'تم حذف المرفق بنجاح.');
    }

    public function closeWarningModal(): void
    {
        $this->showWarningModal = false;
        $this->warningId = null;
    }

    public function delete(DeleteMediaAction $action): void
    {
        $this->authorize('deleteMedia', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المرفق بنجاح.');
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

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->detailsId = null;
    }

    public function closeUsageModal(): void
    {
        $this->showUsageModal = false;
        $this->usageId = null;
    }

    public function closePreviewModal(): void
    {
        $this->showPreviewModal = false;
        $this->previewId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['file', 'previewUrl', 'collection', 'title', 'alt', 'displayOrder', 'isActive', 'editingId']);
        $this->collection = 'attachment';
        $this->isActive = true;
    }

    public function render()
    {
        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $filters = [
            'search' => $this->search,
            'collection' => $this->filterCollection,
            'type' => $this->filterType,
            'status' => $this->filterStatus,
            'usage' => $this->filterUsage,
            'sort' => $this->sortBy,
        ];

        return view('livewire.municipality.media', [
            'mediaItems' => app(MediaRepositoryInterface::class)->search($municipality, $filters, 20),
            'collectionOptions' => MediaCollection::options(),
            'selectedMedia' => $this->detailsId ? Media::find($this->detailsId) : null,
            'usageMedia' => $this->usageId ? Media::find($this->usageId) : null,
            'previewMedia' => $this->previewId ? Media::find($this->previewId) : null,
        ]);
    }
}
