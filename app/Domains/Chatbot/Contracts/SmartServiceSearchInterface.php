<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ServiceSearchResultCollection;

interface SmartServiceSearchInterface
{
    public function search(
        string $message,
        ?int $currentServiceId = null,
        int $limit = 5,
    ): ServiceSearchResultCollection;

    public function clearCache(): void;
}
