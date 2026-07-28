<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;

final readonly class ReorderAnnouncementsAction
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository,
    ) {}

    public function execute(array $items): void
    {
        $this->repository->reorder($items);
    }
}
