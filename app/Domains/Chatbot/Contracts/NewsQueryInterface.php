<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\NewsDetailsData;

interface NewsQueryInterface
{
    public function getLatestPublishedNews(int $limit = 5): array;

    public function searchPublishedNews(string $query, int $limit = 5): array;

    public function getPublishedNewsById(int $id): ?NewsDetailsData;
}
