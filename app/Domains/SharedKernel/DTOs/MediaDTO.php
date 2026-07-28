<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\DTOs;

final readonly class MediaDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $mediableId = null,
        public ?string $mediableType = null,
        public string $collection = 'attachment',
        public string $disk = 'public',
        public string $path = '',
        public ?string $mimeType = null,
        public ?int $size = null,
        public ?int $width = null,
        public ?int $height = null,
        public ?string $title = null,
        public ?string $alt = null,
        public int $displayOrder = 0,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            mediableId: isset($validated['mediable_id']) ? (int) $validated['mediable_id'] : null,
            mediableType: $validated['mediable_type'] ?? null,
            collection: $validated['collection'],
            disk: $validated['disk'] ?? 'public',
            path: $validated['path'] ?? '',
            mimeType: $validated['mime_type'] ?? null,
            size: isset($validated['size']) ? (int) $validated['size'] : null,
            width: isset($validated['width']) ? (int) $validated['width'] : null,
            height: isset($validated['height']) ? (int) $validated['height'] : null,
            title: $validated['title'] ?? null,
            alt: $validated['alt'] ?? null,
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }

    public static function fromUpload(array $uploadData, array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            mediableId: isset($data['mediable_id']) ? (int) $data['mediable_id'] : null,
            mediableType: $data['mediable_type'] ?? null,
            collection: $data['collection'],
            disk: $uploadData['disk'],
            path: $uploadData['path'],
            mimeType: $uploadData['mime_type'],
            size: $uploadData['size'],
            width: $uploadData['width'] ?? null,
            height: $uploadData['height'] ?? null,
            title: $data['title'] ?? null,
            alt: $data['alt'] ?? null,
            displayOrder: (int) ($data['display_order'] ?? 0),
            isActive: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'mediable_id' => $this->mediableId,
            'mediable_type' => $this->mediableType,
            'collection' => $this->collection,
            'disk' => $this->disk,
            'path' => $this->path,
            'mime_type' => $this->mimeType,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'title' => $this->title,
            'alt' => $this->alt,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];
    }
}
