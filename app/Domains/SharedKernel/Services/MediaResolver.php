<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Services;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Enums\MediaCollection;
use App\Domains\SharedKernel\Models\Media;

/**
 * Centralized, backward-compatible resolver for static-media assets.
 *
 * Each method returns the configured Media Library item (by collection) when one
 * exists and is present on disk, otherwise it falls back to the original static
 * file in /public. No database columns or static files are changed or deleted.
 */
final class MediaResolver
{
    private static function mediaUrl(?string $collection, string $fallback): string
    {
        $profile = app(MunicipalityRepositoryInterface::class)->getProfile();

        if ($profile) {
            $media = Media::query()
                ->where('mediable_type', $profile->getMorphClass())
                ->where('mediable_id', $profile->getKey())
                ->where('collection', $collection)
                ->where('is_active', true)
                ->latest()
                ->first();

            if ($media && $media->fileExists()) {
                return asset('storage/'.$media->path);
            }
        }

        return asset($fallback);
    }

    public static function logoUrl(): string
    {
        return self::mediaUrl(MediaCollection::Logo->value, 'logo.png');
    }

    public static function chatbotAvatarUrl(): string
    {
        return self::mediaUrl('chatbot_avatar', 'robot.png');
    }

    public static function faviconUrl(): string
    {
        return self::mediaUrl(MediaCollection::Favicon->value, 'favicon.ico');
    }
}
