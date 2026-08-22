<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\CouncilDecisionDetailsData;

interface CouncilDecisionQueryInterface
{
    public function getLatestPublishedDecisions(int $limit = 5): array;

    public function searchPublishedDecisions(string $query, int $limit = 5): array;

    public function getPublishedDecisionById(int $id): ?CouncilDecisionDetailsData;

    public function searchPublishedDecisionsByDate(string $date, int $limit = 5): array;
}
