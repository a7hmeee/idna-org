<?php

declare(strict_types=1);

namespace App\Domains\News\Actions;

use App\Domains\News\Contracts\NewsRepositoryInterface;

final readonly class RecordNewsViewAction
{
    public function __construct(
        private NewsRepositoryInterface $repository,
    ) {}

    public function execute(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}
