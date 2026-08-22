<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\AnnouncementDetailsData;

interface AnnouncementQueryInterface
{
    public function getLatestPublishedAnnouncements(int $limit = 5): array;

    public function searchPublishedAnnouncements(string $query, int $limit = 5): array;

    public function getPublishedAnnouncementById(int $id): ?AnnouncementDetailsData;
}
