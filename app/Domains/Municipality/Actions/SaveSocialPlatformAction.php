<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\SocialPlatformDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\MunicipalitySocialPlatform;

final readonly class SaveSocialPlatformAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(SocialPlatformDTO $dto, ?int $id = null): MunicipalitySocialPlatform
    {
        $platform = $this->repository->saveSocialPlatform($dto, $id);

        MunicipalityUpdated::dispatch('social_platforms');

        return $platform;
    }
}
