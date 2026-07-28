<?php

declare(strict_types=1);

namespace App\Domains\News\Actions;

use App\Domains\News\Contracts\NewsRepositoryInterface;

final readonly class DeleteNewsAction
{
    public function __construct(
        private NewsRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
