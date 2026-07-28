<?php

declare(strict_types=1);

namespace App\Domains\Homepage\DTOs;

final readonly class HomepageSlideData
{
    public function __construct(
        public string $title,
        public ?string $pageKey = null,
        public ?string $subtitle = null,
        public ?string $description = null,
        public ?string $imagePath = null,
        public ?string $mobileImagePath = null,
        public ?string $buttonText = null,
        public ?string $buttonUrl = null,
        public ?string $secondaryButtonText = null,
        public ?string $secondaryButtonUrl = null,
        public ?string $badgeText = null,
        public ?bool $isActive = null,
        public ?int $sortOrder = null,
        public ?string $startsAt = null,
        public ?string $endsAt = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            title: $validated['title'],
            pageKey: $validated['pageKey'] ?? null,
            subtitle: $validated['subtitle'] ?? null,
            description: $validated['description'] ?? null,
            imagePath: $validated['imagePath'] ?? null,
            mobileImagePath: $validated['mobileImagePath'] ?? null,
            buttonText: $validated['buttonText'] ?? null,
            buttonUrl: $validated['buttonUrl'] ?? null,
            secondaryButtonText: $validated['secondaryButtonText'] ?? null,
            secondaryButtonUrl: $validated['secondaryButtonUrl'] ?? null,
            badgeText: $validated['badgeText'] ?? null,
            isActive: isset($validated['isActive']) ? (bool) $validated['isActive'] : null,
            sortOrder: isset($validated['sortOrder']) ? (int) $validated['sortOrder'] : null,
            startsAt: $validated['startsAt'] ?? null,
            endsAt: $validated['endsAt'] ?? null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'page_key' => $this->pageKey,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'image_path' => $this->imagePath,
            'mobile_image_path' => $this->mobileImagePath,
            'button_text' => $this->buttonText,
            'button_url' => $this->buttonUrl,
            'secondary_button_text' => $this->secondaryButtonText,
            'secondary_button_url' => $this->secondaryButtonUrl,
            'badge_text' => $this->badgeText,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
