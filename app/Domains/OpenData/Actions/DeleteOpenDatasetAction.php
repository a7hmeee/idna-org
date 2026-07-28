<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Actions;

use App\Domains\OpenData\Models\OpenDataset;
use Illuminate\Support\Facades\Storage;

final class DeleteOpenDatasetAction
{
    public function execute(OpenDataset $dataset): void
    {
        if ($dataset->file_path && Storage::disk('public')->exists($dataset->file_path)) {
            Storage::disk('public')->delete($dataset->file_path);
        }

        $dataset->delete();
    }
}
