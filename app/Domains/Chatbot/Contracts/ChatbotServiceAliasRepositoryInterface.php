<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatbotServiceAlias;
use Illuminate\Database\Eloquent\Collection;

interface ChatbotServiceAliasRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?ChatbotServiceAlias;

    public function create(array $data): ChatbotServiceAlias;

    public function update(int $id, array $data): ChatbotServiceAlias;

    public function delete(int $id): bool;

    public function findByAlias(string $alias): ?ChatbotServiceAlias;
}
