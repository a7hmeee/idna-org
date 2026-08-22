<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatIntentRepositoryInterface;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Models\ChatIntent;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatIntentRepository implements ChatIntentRepositoryInterface
{
    public function __construct(
        private ChatIntent $model,
    ) {}

    public function allActive(): Collection
    {
        return $this->model->active()->orderBy('sort_order')->get();
    }

    public function findByName(string $name): ?ChatIntent
    {
        return $this->model->where('name', $name)->first();
    }

    public function synchronizeFromEnum(): int
    {
        $count = 0;

        foreach (ChatbotIntent::cases() as $intent) {
            $this->model->updateOrCreate(
                ['name' => $intent->value],
                [
                    'label_ar' => $intent->label(),
                    'is_active' => true,
                    'sort_order' => array_search($intent, ChatbotIntent::cases(), true),
                ],
            );
            $count++;
        }

        return $count;
    }

    public function getMinimumConfidence(string $name): ?float
    {
        $intent = $this->findByName($name);
        if ($intent === null || $intent->minimum_confidence === null) {
            return null;
        }

        return (float) $intent->minimum_confidence;
    }
}
