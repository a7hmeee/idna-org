<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final readonly class MediaUploadService
{
    public function upload(UploadedFile $file, string $collection): array
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString() . '.' . $extension;
        $path = $file->storeAs("municipality/media/{$collection}", $filename, 'public');

        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $width = null;
        $height = null;

        if (str_starts_with($mimeType, 'image/') && $mimeType !== 'image/svg+xml') {
            [$width, $height] = getimagesize($file->path());
        }

        return [
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'size' => $size,
            'width' => $width,
            'height' => $height,
        ];
    }

    public function delete(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
