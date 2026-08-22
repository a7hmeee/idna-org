<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatbotModelVersion;
use Illuminate\Database\Eloquent\Collection;

interface ChatbotModelVersionRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?ChatbotModelVersion;

    public function create(array $data): ChatbotModelVersion;

    public function update(int $id, array $data): ChatbotModelVersion;

    public function delete(int $id): bool;

    public function getActive(): ?ChatbotModelVersion;
}
