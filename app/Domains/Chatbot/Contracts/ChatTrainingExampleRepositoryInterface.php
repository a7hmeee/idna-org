<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatTrainingExample;
use Illuminate\Database\Eloquent\Collection;

interface ChatTrainingExampleRepositoryInterface
{
    public function getVerifiedActiveExamples(): Collection;

    public function getVerifiedActiveExamplesByIntent(int $intentId): Collection;

    public function create(array $data): ChatTrainingExample;

    public function update(int $id, array $data): ChatTrainingExample;

    public function deactivate(int $id): bool;

    public function countByIntent(): array;

    public function datasetFingerprint(): string;
}
