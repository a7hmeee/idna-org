<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Services;

use App\Domains\Chatbot\Contracts\CouncilDecisionQueryInterface;
use App\Domains\Chatbot\DTOs\CouncilDecisionDetailsData;
use App\Domains\Chatbot\DTOs\CouncilDecisionSummaryData;
use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use Illuminate\Support\Facades\Cache;

final readonly class CouncilDecisionQueryAdapter implements CouncilDecisionQueryInterface
{
    private const CACHE_KEY = 'chatbot:council-decisions';

    private const CACHE_TTL = 900;

    public function __construct(
        private CouncilDecisionRepositoryInterface $repository,
    ) {}

    public function getLatestPublishedDecisions(int $limit = 5): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $decisions = $this->repository->getLatestPublished($limit);

            return collect($decisions)
                ->map(fn ($decision) => new CouncilDecisionSummaryData(
                    id: (int) $decision->id,
                    title: $decision->title,
                    decisionNumber: $decision->decision_number,
                    decisionDate: $decision->decision_date?->format('Y-m-d'),
                    type: $decision->type instanceof CouncilDecisionType
                        ? $decision->type->value
                        : (string) ($decision->type ?? ''),
                    summary: $decision->summary,
                    status: $decision->status instanceof CouncilDecisionStatus
                        ? $decision->status->value
                        : (string) ($decision->status ?? 'published'),
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedDecisions(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $decisions = $this->repository->getLatestPublished(50);

        return collect($decisions)
            ->filter(fn ($decision) => str_contains(mb_strtolower($decision->title), mb_strtolower($query))
                || str_contains(mb_strtolower($decision->summary ?? ''), mb_strtolower($query))
                || str_contains(mb_strtolower($decision->decision_number ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($decision) => new CouncilDecisionSummaryData(
                id: (int) $decision->id,
                title: $decision->title,
                decisionNumber: $decision->decision_number,
                decisionDate: $decision->decision_date?->format('Y-m-d'),
                type: $decision->type instanceof CouncilDecisionType
                    ? $decision->type->value
                    : (string) ($decision->type ?? ''),
                summary: $decision->summary,
                status: $decision->status instanceof CouncilDecisionStatus
                    ? $decision->status->value
                    : (string) ($decision->status ?? 'published'),
            ))
            ->values()
            ->all();
    }

    public function getPublishedDecisionById(int $id): ?CouncilDecisionDetailsData
    {
        $decision = $this->repository->findById($id);

        if ($decision === null) {
            return null;
        }

        $status = $decision->status instanceof CouncilDecisionStatus
            ? $decision->status->value
            : (string) ($decision->status ?? '');
        if ($status !== CouncilDecisionStatus::Published->value) {
            return null;
        }

        return new CouncilDecisionDetailsData(
            id: (int) $decision->id,
            title: $decision->title,
            decisionNumber: $decision->decision_number,
            type: $decision->type instanceof CouncilDecisionType
                ? $decision->type->value
                : (string) ($decision->type ?? ''),
            summary: $decision->summary,
            content: $decision->content,
            decisionDate: $decision->decision_date?->format('Y-m-d'),
            sessionNumber: $decision->session_number,
            publishedAt: $decision->published_at?->format('Y-m-d H:i'),
            attachmentPath: $decision->attachment_path,
            status: CouncilDecisionStatus::Published->value,
        );
    }

    public function searchPublishedDecisionsByDate(string $date, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $decisions = $this->repository->getLatestPublished(50);

        return collect($decisions)
            ->filter(fn ($decision) => $decision->decision_date?->format('Y-m-d') === $date
                || str_contains((string) $decision->decision_date, $date))
            ->take($limit)
            ->map(fn ($decision) => new CouncilDecisionSummaryData(
                id: (int) $decision->id,
                title: $decision->title,
                decisionNumber: $decision->decision_number,
                decisionDate: $decision->decision_date?->format('Y-m-d'),
                type: $decision->type instanceof CouncilDecisionType
                    ? $decision->type->value
                    : (string) ($decision->type ?? ''),
                summary: $decision->summary,
                status: $decision->status instanceof CouncilDecisionStatus
                    ? $decision->status->value
                    : (string) ($decision->status ?? 'published'),
            ))
            ->values()
            ->all();
    }
}
