<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;

final readonly class ReorderHomepageQuickLinksAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(array $orders): void
    {
        $this->repository->reorderQuickLinks($orders);

        CacheForgetHomepageDataAction::execute();
    }
}
