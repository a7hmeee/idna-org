<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\CouncilMemberDetailsData;

interface CouncilMemberQueryInterface
{
    public function getPublishedCouncilMembers(int $limit = 10): array;

    public function searchPublishedCouncilMembers(string $query, int $limit = 5): array;

    public function getPublishedCouncilMemberById(int $id): ?CouncilMemberDetailsData;

    public function getPublishedMayor(): ?CouncilMemberDetailsData;
}
