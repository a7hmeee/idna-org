<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageStatisticData;
use App\Domains\Homepage\Models\HomepageStatistic;

final readonly class UpdateHomepageStatisticAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id, HomepageStatisticData $dto): HomepageStatistic
    {
        $stat = $this->repository->updateStatistic($id, $dto->toArray());

        CacheForgetHomepageDataAction::execute();

        return $stat;
    }
}
