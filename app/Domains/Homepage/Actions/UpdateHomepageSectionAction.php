<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSectionData;
use App\Domains\Homepage\Models\HomepageSection;

final readonly class UpdateHomepageSectionAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(string $key, HomepageSectionData $dto): HomepageSection
    {
        $section = $this->repository->updateSection($key, $dto->toArray());

        CacheForgetHomepageDataAction::execute();

        return $section;
    }
}
