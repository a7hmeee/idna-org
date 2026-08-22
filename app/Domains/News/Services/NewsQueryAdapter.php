<?php

declare(strict_types=1);

namespace App\Domains\News\Services;

use App\Domains\Chatbot\Contracts\NewsQueryInterface;
use App\Domains\Chatbot\DTOs\NewsDetailsData;
use App\Domains\Chatbot\DTOs\NewsSummaryData;
use App\Domains\News\Contracts\NewsRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class NewsQueryAdapter implements NewsQueryInterface
{
    private const CACHE_KEY = 'chatbot:news';

    private const CACHE_TTL = 600;

    public function __construct(
        private NewsRepositoryInterface $repository,
    ) {}

    public function getLatestPublishedNews(int $limit = 5): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $news = $this->repository->getLatest($limit);

            return collect($news)
                ->where('status', 'published')
                ->where('is_public', true)
                ->map(fn ($item) => new NewsSummaryData(
                    id: (int) $item->id,
                    title: $item->title_ar,
                    slug: $item->slug,
                    category: $item->category?->value,
                    summary: $item->summary,
                    publishAt: $item->publish_at?->format('Y-m-d H:i'),
                    author: $item->author,
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedNews(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $news = $this->repository->getPublished();

        return collect($news)
            ->filter(fn ($item) => str_contains(mb_strtolower($item->title_ar), mb_strtolower($query))
                || str_contains(mb_strtolower($item->summary ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($item) => new NewsSummaryData(
                id: (int) $item->id,
                title: $item->title_ar,
                slug: $item->slug,
                category: $item->category?->value,
                summary: $item->summary,
                publishAt: $item->publish_at?->format('Y-m-d H:i'),
                author: $item->author,
            ))
            ->values()
            ->all();
    }

    public function getPublishedNewsById(int $id): ?NewsDetailsData
    {
        $item = $this->repository->find($id);

        if ($item === null || $item->status->value !== 'published' || ! $item->is_public) {
            return null;
        }

        return new NewsDetailsData(
            id: (int) $item->id,
            title: $item->title_ar,
            slug: $item->slug,
            category: $item->category?->value,
            summary: $item->summary,
            content: $item->content,
            publishAt: $item->publish_at?->format('Y-m-d H:i'),
            author: $item->author,
            coverImageUrl: $item->getCoverImageUrlAttribute(),
        );
    }
}
