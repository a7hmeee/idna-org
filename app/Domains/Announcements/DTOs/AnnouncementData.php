<?php

declare(strict_types=1);

namespace App\Domains\Announcements\DTOs;

use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementStatus;
use App\Domains\Announcements\Enums\AnnouncementType;

final readonly class AnnouncementData
{
    public function __construct(
        public string $title,
        public AnnouncementType $type,
        public AnnouncementPriority $priority,
        public AnnouncementStatus $status,
        public string $summary,
        public string $content,
        public ?string $imagePath = null,
        public ?string $mobileImagePath = null,
        public ?string $attachmentPath = null,
        public ?string $externalUrl = null,
        public bool $isFeatured = false,
        public int $displayOrder = 0,
        public int $viewsCount = 0,
        public ?string $startsAt = null,
        public ?string $endsAt = null,
        public ?string $publishAt = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            type: $data['type'],
            priority: $data['priority'],
            status: $data['status'],
            summary: $data['summary'] ?? $data['shortDescription'] ?? $data['short_description'] ?? '',
            content: $data['content'],
            imagePath: $data['imagePath'] ?? $data['image_path'] ?? $data['desktopImagePath'] ?? $data['desktop_image_path'] ?? null,
            mobileImagePath: $data['mobileImagePath'] ?? $data['mobile_image_path'] ?? null,
            attachmentPath: $data['attachmentPath'] ?? $data['attachment_path'] ?? null,
            externalUrl: $data['externalUrl'] ?? $data['external_url'] ?? null,
            isFeatured: $data['isFeatured'] ?? $data['is_featured'] ?? false,
            displayOrder: $data['displayOrder'] ?? $data['display_order'] ?? 0,
            viewsCount: $data['viewsCount'] ?? $data['views_count'] ?? $data['views'] ?? 0,
            startsAt: $data['startsAt'] ?? $data['starts_at'] ?? null,
            endsAt: $data['endsAt'] ?? $data['ends_at'] ?? null,
            publishAt: $data['publishAt'] ?? $data['publish_at'] ?? $data['publishedAt'] ?? $data['published_at'] ?? null,
            createdBy: $data['createdBy'] ?? $data['created_by'] ?? null,
            updatedBy: $data['updatedBy'] ?? $data['updated_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type->value,
            'priority' => $this->priority->value,
            'status' => $this->status->value,
            'short_description' => $this->summary,
            'content' => $this->content,
            'desktop_image_path' => $this->imagePath,
            'mobile_image_path' => $this->mobileImagePath,
            'attachment_path' => $this->attachmentPath,
            'external_url' => $this->externalUrl,
            'is_featured' => $this->isFeatured,
            'display_order' => $this->displayOrder,
            'views' => $this->viewsCount,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'published_at' => $this->publishAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
