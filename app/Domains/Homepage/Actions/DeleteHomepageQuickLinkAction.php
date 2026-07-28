<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;

final readonly class DeleteHomepageQuickLinkAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->deleteQuickLink($id);

        CacheForgetHomepageDataAction::execute();

        return $result;
    }
}
