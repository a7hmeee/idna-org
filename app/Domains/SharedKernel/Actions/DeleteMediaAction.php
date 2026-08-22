<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Actions;

use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\Services\MediaUploadService;

final readonly class DeleteMediaAction
{
    public function __construct(
        private MediaRepositoryInterface $repository,
        private MediaUploadService $uploadService,
    ) {}

    public function execute(int $id): bool
    {
        $media = $this->repository->findById($id);

        if (! $media) {
            return false;
        }

        $path = $media->path;
        $disk = $media->disk;

        $result = $this->repository->delete($id);

        if ($result) {
            $this->uploadService->delete($path, $disk);
        }

        MunicipalityUpdated::dispatch('media');

        return $result;
    }
}
