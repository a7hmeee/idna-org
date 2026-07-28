<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageStatistic;

final readonly class ToggleHomepageStatisticAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id): HomepageStatistic
    {
        $stat = $this->repository->toggleStatistic($id);

        CacheForgetHomepageDataAction::execute();

        return $stat;
    }
}
