<?php

declare(strict_types=1);

namespace App\Livewire\Shared;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\Enums\MediaCollection;
use App\Domains\SharedKernel\Models\Media;
use Livewire\Attributes\On;
use Livewire\Component;

final class MediaPicker extends Component
{
    public bool $showModal = false;

    public ?int $selectedMediaId = null;

    public ?string $selectedMediaUrl = null;

    public ?string $target = null;

    public ?string $restrictCollection = null;

    public string $search = '';

    public string $filterCollection = '';

    public string $filterType = '';

    public function openModal(): void
    {
        $this->showModal = true;
        $this->search = '';
        $this->filterCollection = '';
        $this->filterType = '';
    }

    #[On('open-media-picker')]
    public function openFromEvent(array $payload = []): void
    {
        $target = $payload['target'] ?? null;

        if ($this->target !== null && $target !== $this->target) {
            return;
        }

        if ($this->restrictCollection) {
            $this->filterCollection = $this->restrictCollection;
        }

        $this->openModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->search = '';
        $this->filterCollection = '';
        $this->filterType = '';
    }

    public function selectMedia(int $id): void
    {
        $media = Media::find($id);
        if ($media) {
            $this->selectedMediaId = $id;
            $this->selectedMediaUrl = asset('storage/'.$media->path);
            $this->dispatch('media-selected', id: $id, url: $this->selectedMediaUrl, path: $media->path, target: $this->target);
            $this->closeModal();
        }
    }

    public function clearSelection(): void
    {
        $this->selectedMediaId = null;
        $this->selectedMediaUrl = null;
        $this->dispatch('media-cleared');
    }

    public function render()
    {
        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $filters = [
            'search' => $this->search,
            'collection' => $this->filterCollection,
            'type' => $this->filterType,
        ];

        return view('livewire.shared.media-picker', [
            'mediaItems' => app(MediaRepositoryInterface::class)->search($municipality, $filters, 24),
            'collectionOptions' => MediaCollection::options(),
            'selectedMedia' => $this->selectedMediaId ? Media::find($this->selectedMediaId) : null,
        ]);
    }
}
