<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Repositories;

use App\Domains\ChatbotAnalytics\Contracts\DatasetVersionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Models\ChatbotDatasetVersion;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentDatasetVersionRepository implements DatasetVersionRepositoryInterface
{
    public function __construct(
        private ChatbotDatasetVersion $model,
    ) {}

    public function create(array $data): ChatbotDatasetVersion
    {
        return $this->model->create($data);
    }

    public function getAll(): Collection
    {
        return $this->model->orderByDesc('created_at')->get();
    }

    public function findByFingerprint(string $fingerprint): ?ChatbotDatasetVersion
    {
        return $this->model->where('fingerprint', $fingerprint)->first();
    }

    public function setBaseline(int $id): void
    {
        $this->model->where('is_baseline', true)->update(['is_baseline' => false]);
        $this->model->where('id', $id)->update(['is_baseline' => true]);
    }

    public function getLatest(): ?ChatbotDatasetVersion
    {
        return $this->model->orderByDesc('created_at')->first();
    }
}
