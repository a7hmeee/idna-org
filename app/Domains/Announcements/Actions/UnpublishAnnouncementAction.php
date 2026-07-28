<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\Models\Announcement;

final readonly class UnpublishAnnouncementAction
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Announcement
    {
        return $this->repository->unpublish($id);
    }
}
