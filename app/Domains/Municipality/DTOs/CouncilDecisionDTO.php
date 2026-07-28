<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class CouncilDecisionDTO
{
    public function __construct(
        public ?int $id = null,
        public string $decisionNumber = '',
        public string $title = '',
        public ?string $summary = null,
        public ?string $content = null,
        public string $type = 'administrative',
        public string $status = 'draft',
        public ?string $decisionDate = null,
        public ?string $sessionNumber = null,
        public ?string $attachmentPath = null,
        public bool $isPublic = false,
        public int $sortOrder = 0,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
        public ?string $publishedAt = null,
        public ?string $archivedAt = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            decisionNumber: $validated['decision_number'],
            title: $validated['title'],
            summary: $validated['summary'] ?? null,
            content: $validated['content'] ?? null,
            type: $validated['type'],
            status: $validated['status'] ?? 'draft',
            decisionDate: $validated['decision_date'] ?? null,
            sessionNumber: $validated['session_number'] ?? null,
            attachmentPath: $validated['attachment_path'] ?? null,
            isPublic: (bool) ($validated['is_public'] ?? false),
            sortOrder: (int) ($validated['sort_order'] ?? 0),
            createdBy: isset($validated['created_by']) ? (int) $validated['created_by'] : null,
            updatedBy: isset($validated['updated_by']) ? (int) $validated['updated_by'] : null,
            publishedAt: $validated['published_at'] ?? null,
            archivedAt: $validated['archived_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'decision_number' => $this->decisionNumber,
            'title' => $this->title,
            'summary' => $this->summary,
            'content' => $this->content,
            'type' => $this->type,
            'status' => $this->status,
            'decision_date' => $this->decisionDate,
            'session_number' => $this->sessionNumber,
            'attachment_path' => $this->attachmentPath,
            'is_public' => $this->isPublic,
            'sort_order' => $this->sortOrder,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'published_at' => $this->publishedAt,
            'archived_at' => $this->archivedAt,
        ];
    }
}
