<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Actions;

use App\Domains\OpenData\Models\OpenDataset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class UpdateOpenDatasetAction
{
    public function execute(OpenDataset $dataset, array $data, ?UploadedFile $file = null): OpenDataset
    {
        if ($file) {
            if ($dataset->file_path) {
                Storage::disk('public')->delete($dataset->file_path);
            }

            $data['file_path'] = $file->store('open-data', 'public');
            $data['file_size'] = $file->getSize();
            $data['file_format'] = $file->getClientOriginalExtension();
        }

        $dataset->update($data);

        return $dataset->fresh();
    }
}
