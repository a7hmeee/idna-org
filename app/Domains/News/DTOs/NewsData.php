<?php

declare(strict_types=1);

namespace App\Domains\News\DTOs;

use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;

final readonly class NewsData
{
    public function __construct(
        public string $titleAr,
        public string $summary,
        public string $content,
        public NewsCategory $category,
        public NewsStatus $status,
        public string $publishAt,
        public ?string $titleEn = null,
        public ?string $slug = null,
        public ?string $coverImagePath = null,
        public ?string $mobileImagePath = null,
        public ?array $gallery = null,
        public ?string $author = null,
        public ?bool $isFeatured = null,
        public ?bool $isPublic = null,
        public ?int $viewsCount = null,
        public ?int $displayOrder = null,
        public ?string $metaTitle = null,
        public ?string $metaDescription = null,
        public ?array $metaKeywords = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            titleAr: $data['titleAr'],
            summary: $data['summary'],
            content: $data['content'],
            category: $data['category'] instanceof NewsCategory ? $data['category'] : NewsCategory::from($data['category']),
            status: $data['status'] instanceof NewsStatus ? $data['status'] : NewsStatus::from($data['status']),
            publishAt: $data['publishAt'],
            titleEn: $data['titleEn'] ?? null,
            slug: $data['slug'] ?? null,
            coverImagePath: $data['coverImagePath'] ?? null,
            mobileImagePath: $data['mobileImagePath'] ?? null,
            gallery: $data['gallery'] ?? null,
            author: $data['author'] ?? null,
            isFeatured: $data['isFeatured'] ?? null,
            isPublic: $data['isPublic'] ?? null,
            viewsCount: $data['viewsCount'] ?? null,
            displayOrder: $data['displayOrder'] ?? null,
            metaTitle: $data['metaTitle'] ?? null,
            metaDescription: $data['metaDescription'] ?? null,
            metaKeywords: $data['metaKeywords'] ?? null,
            createdBy: $data['createdBy'] ?? null,
            updatedBy: $data['updatedBy'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title_ar' => $this->titleAr,
            'title_en' => $this->titleEn,
            'slug' => $this->slug,
            'category' => $this->category->value,
            'summary' => $this->summary,
            'content' => $this->content,
            'cover_image_path' => $this->coverImagePath,
            'mobile_image_path' => $this->mobileImagePath,
            'gallery' => $this->gallery,
            'author' => $this->author,
            'status' => $this->status->value,
            'is_featured' => $this->isFeatured ?? false,
            'is_public' => $this->isPublic ?? true,
            'publish_at' => $this->publishAt,
            'views_count' => $this->viewsCount ?? 0,
            'display_order' => $this->displayOrder ?? 0,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
