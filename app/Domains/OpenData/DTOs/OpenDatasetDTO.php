<?php

declare(strict_types=1);

namespace App\Domains\OpenData\DTOs;

use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Enums\OpenDataType;

final class OpenDatasetDTO
{
    public function __construct(
        public readonly string $title,
        public readonly OpenDataType $type,
        public readonly ?string $category = null,
        public readonly ?string $description = null,
        public readonly ?string $externalUrl = null,
        public readonly ?OpenDataStatus $status = null,
        public readonly bool $isFeatured = false,
        public readonly ?int $displayOrder = null,
        public readonly ?int $createdBy = null,
    ) {}
}
