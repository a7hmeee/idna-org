<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Actions;

use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\DTOs\MediaDTO;
use App\Domains\SharedKernel\Models\Media;
use App\Domains\SharedKernel\Services\MediaUploadService;
use Illuminate\Http\UploadedFile;

final readonly class SaveMediaAction
{
    public function __construct(
        private MediaRepositoryInterface $repository,
        private MediaUploadService $uploadService,
    ) {}

    public function execute(?UploadedFile $file, array $data, ?int $id = null): Media
    {
        if ($file) {
            $uploadData = $this->uploadService->upload($file, $data['collection']);

            if ($id) {
                $oldMedia = $this->repository->findById($id);
                if ($oldMedia) {
                    $this->uploadService->delete($oldMedia->path, $oldMedia->disk);
                }
            }

            $dto = MediaDTO::fromUpload($uploadData, $data);
        } else {
            $dto = MediaDTO::fromRequest($data);
        }

        $media = $this->repository->save($dto, $id);

        MunicipalityUpdated::dispatch('media');

        return $media;
    }
}
