<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Contracts;

use App\Domains\ChatbotAnalytics\Models\ChatbotDatasetVersion;
use Illuminate\Database\Eloquent\Collection;

interface DatasetVersionRepositoryInterface
{
    public function create(array $data): ChatbotDatasetVersion;

    public function getAll(): Collection;

    public function findByFingerprint(string $fingerprint): ?ChatbotDatasetVersion;

    public function setBaseline(int $id): void;

    public function getLatest(): ?ChatbotDatasetVersion;
}
