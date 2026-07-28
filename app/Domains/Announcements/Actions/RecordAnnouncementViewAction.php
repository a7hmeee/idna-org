<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;

final readonly class RecordAnnouncementViewAction
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository,
    ) {}

    public function execute(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}
