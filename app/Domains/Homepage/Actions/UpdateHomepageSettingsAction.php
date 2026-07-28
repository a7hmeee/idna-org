<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSettingData;
use App\Domains\Homepage\Models\HomepageSetting;

final readonly class UpdateHomepageSettingsAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(HomepageSettingData $dto): HomepageSetting
    {
        $settings = $this->repository->updateSettings($dto->toArray());

        CacheForgetHomepageDataAction::execute();

        return $settings;
    }
}
