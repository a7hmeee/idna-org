<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Actions;

use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Models\OpenDataset;
use Illuminate\Http\UploadedFile;

final class CreateOpenDatasetAction
{
    public function execute(array $data, ?UploadedFile $file = null): OpenDataset
    {
        if ($file) {
            $data['file_path'] = $file->store('open-data', 'public');
            $data['file_size'] = $file->getSize();
            $data['file_format'] = $file->getClientOriginalExtension();
        }

        if (! isset($data['status'])) {
            $data['status'] = OpenDataStatus::Draft;
        }

        return OpenDataset::create($data);
    }
}
