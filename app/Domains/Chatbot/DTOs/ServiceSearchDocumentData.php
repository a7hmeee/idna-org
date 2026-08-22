<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ServiceSearchDocumentData
{
    public function __construct(
        public int $serviceId,
        public string $officialName,
        public string $normalizedOfficialName,
        public array $aliases = [],
        public array $normalizedAliases = [],
        public array $keywords = [],
        public array $normalizedKeywords = [],
        public array $searchablePhrases = [],
        public array $normalizedSearchablePhrases = [],
        public array $citizenExpressions = [],
        public array $normalizedCitizenExpressions = [],
        public ?string $shortDescription = null,
        public ?string $normalizedShortDescription = null,
        public ?string $categoryName = null,
        public ?string $normalizedCategoryName = null,
        public int $priority = 0,
        public bool $isPublished = true,
        public ?string $applicationUrl = null,
        public ?string $updatedAt = null,
    ) {}

    public function toArray(): array
    {
        return [
            'serviceId' => $this->serviceId,
            'officialName' => $this->officialName,
            'normalizedOfficialName' => $this->normalizedOfficialName,
            'aliases' => $this->aliases,
            'normalizedAliases' => $this->normalizedAliases,
            'keywords' => $this->keywords,
            'normalizedKeywords' => $this->normalizedKeywords,
            'searchablePhrases' => $this->searchablePhrases,
            'normalizedSearchablePhrases' => $this->normalizedSearchablePhrases,
            'citizenExpressions' => $this->citizenExpressions,
            'normalizedCitizenExpressions' => $this->normalizedCitizenExpressions,
            'shortDescription' => $this->shortDescription,
            'normalizedShortDescription' => $this->normalizedShortDescription,
            'categoryName' => $this->categoryName,
            'normalizedCategoryName' => $this->normalizedCategoryName,
            'priority' => $this->priority,
            'isPublished' => $this->isPublished,
            'applicationUrl' => $this->applicationUrl,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
