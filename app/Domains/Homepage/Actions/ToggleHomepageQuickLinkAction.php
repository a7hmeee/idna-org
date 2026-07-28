<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageQuickLink;

final readonly class ToggleHomepageQuickLinkAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id): HomepageQuickLink
    {
        $link = $this->repository->toggleQuickLink($id);

        CacheForgetHomepageDataAction::execute();

        return $link;
    }
}
