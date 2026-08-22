<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatbotFeedback;
use Illuminate\Database\Eloquent\Collection;

interface ChatbotFeedbackRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?ChatbotFeedback;

    public function create(array $data): ChatbotFeedback;

    public function update(int $id, array $data): ChatbotFeedback;

    public function delete(int $id): bool;

    public function getByMessage(int $messageId): ?ChatbotFeedback;
}
