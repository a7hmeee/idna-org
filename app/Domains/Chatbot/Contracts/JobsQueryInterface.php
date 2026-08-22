<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\JobDetailsData;

interface JobsQueryInterface
{
    public function getOpenJobs(int $limit = 5): array;

    public function getLatestPublishedJobs(int $limit = 5): array;

    public function searchPublishedJobs(string $query, int $limit = 5): array;

    public function getPublishedJobById(int $id): ?JobDetailsData;
}
