<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\DTOs\AnnouncementData;
use App\Domains\Announcements\Models\Announcement;

final readonly class CreateAnnouncementAction
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository,
    ) {}

    public function execute(AnnouncementData $dto): Announcement
    {
        return $this->repository->create($dto->toArray());
    }
}
