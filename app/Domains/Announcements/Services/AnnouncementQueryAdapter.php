<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Services;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Chatbot\Contracts\AnnouncementQueryInterface;
use App\Domains\Chatbot\DTOs\AnnouncementDetailsData;
use App\Domains\Chatbot\DTOs\AnnouncementSummaryData;
use Illuminate\Support\Facades\Cache;

final readonly class AnnouncementQueryAdapter implements AnnouncementQueryInterface
{
    private const CACHE_KEY = 'chatbot:announcements';

    private const CACHE_TTL = 600;

    public function __construct(
        private AnnouncementRepositoryInterface $repository,
    ) {}

    public function getLatestPublishedAnnouncements(int $limit = 5): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $announcements = $this->repository->getLatest($limit);

            return collect($announcements)
                ->where('status', 'published')
                ->map(fn ($item) => new AnnouncementSummaryData(
                    id: (int) $item->id,
                    title: $item->title,
                    type: $item->type?->value,
                    priority: $item->priority?->value,
                    shortDescription: $item->short_description,
                    publishedAt: $item->published_at?->format('Y-m-d H:i'),
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedAnnouncements(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $announcements = $this->repository->getPublished();

        return collect($announcements)
            ->filter(fn ($item) => str_contains(mb_strtolower($item->title), mb_strtolower($query))
                || str_contains(mb_strtolower($item->short_description ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($item) => new AnnouncementSummaryData(
                id: (int) $item->id,
                title: $item->title,
                type: $item->type?->value,
                priority: $item->priority?->value,
                shortDescription: $item->short_description,
                publishedAt: $item->published_at?->format('Y-m-d H:i'),
            ))
            ->values()
            ->all();
    }

    public function getPublishedAnnouncementById(int $id): ?AnnouncementDetailsData
    {
        $item = $this->repository->find($id);

        if ($item === null || $item->status->value !== 'published') {
            return null;
        }

        return new AnnouncementDetailsData(
            id: (int) $item->id,
            title: $item->title,
            type: $item->type?->value,
            priority: $item->priority?->value,
            shortDescription: $item->short_description,
            content: $item->content,
            externalUrl: $item->external_url,
            publishedAt: $item->published_at?->format('Y-m-d H:i'),
            startsAt: $item->starts_at?->format('Y-m-d'),
            endsAt: $item->ends_at?->format('Y-m-d'),
        );
    }
}
