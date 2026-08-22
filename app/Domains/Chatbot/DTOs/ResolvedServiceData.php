<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ResolvedServiceData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public ?string $summary = null,
        public array $steps = [],
        public array $requirements = [],
        public array $documents = [],
        public mixed $fees = null,
        public ?string $processingTime = null,
        public ?string $location = null,
        public ?string $portalUrl = null,
        public ?string $departmentName = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'summary' => $this->summary,
            'steps' => $this->steps,
            'requirements' => $this->requirements,
            'documents' => $this->documents,
            'fees' => $this->fees,
            'processing_time' => $this->processingTime,
            'location' => $this->location,
            'portal_url' => $this->portalUrl,
            'department_name' => $this->departmentName,
        ];
    }
}
