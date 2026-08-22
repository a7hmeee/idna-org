<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Services;

use App\Domains\Chatbot\Contracts\CouncilMemberQueryInterface;
use App\Domains\Chatbot\DTOs\CouncilMemberDetailsData;
use App\Domains\Chatbot\DTOs\CouncilMemberSummaryData;
use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class CouncilMemberQueryAdapter implements CouncilMemberQueryInterface
{
    private const CACHE_KEY = 'chatbot:council-members';

    private const CACHE_TTL = 3600;

    public function __construct(
        private CouncilMemberRepositoryInterface $repository,
    ) {}

    public function getPublishedCouncilMembers(int $limit = 10): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $members = $this->repository->getPublicMembers();

            return collect($members)
                ->take($limit)
                ->map(fn ($member) => new CouncilMemberSummaryData(
                    id: (int) $member->id,
                    fullName: $member->full_name,
                    position: $member->position,
                    qualification: $member->qualification,
                    committee: $member->committee,
                    status: $member->status ?? 'active',
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedCouncilMembers(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $members = $this->repository->getPublicMembers();

        return collect($members)
            ->filter(fn ($member) => str_contains(mb_strtolower($member->full_name), mb_strtolower($query))
                || str_contains(mb_strtolower($member->position ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($member) => new CouncilMemberSummaryData(
                id: (int) $member->id,
                fullName: $member->full_name,
                position: $member->position,
                qualification: $member->qualification,
                committee: $member->committee,
                status: $member->status ?? 'active',
            ))
            ->values()
            ->all();
    }

    public function getPublishedCouncilMemberById(int $id): ?CouncilMemberDetailsData
    {
        $member = $this->repository->find($id);

        if ($member === null || ! $member->is_public) {
            return null;
        }

        return new CouncilMemberDetailsData(
            id: (int) $member->id,
            fullName: $member->full_name,
            slug: $member->slug,
            position: $member->position,
            qualification: $member->qualification,
            profession: $member->profession,
            bio: $member->bio,
            committee: $member->committee,
            termStart: $member->term_start?->format('Y-m-d'),
            termEnd: $member->term_end?->format('Y-m-d'),
            yearsOfExperience: $member->years_of_experience !== null ? (string) $member->years_of_experience : null,
            status: $member->status ?? 'active',
            isPublic: (bool) $member->is_public,
        );
    }

    public function getPublishedMayor(): ?CouncilMemberDetailsData
    {
        $member = $this->repository->getMayor();

        if ($member === null || ! $member->is_public) {
            return null;
        }

        return new CouncilMemberDetailsData(
            id: (int) $member->id,
            fullName: $member->full_name,
            slug: $member->slug,
            position: $member->position,
            qualification: $member->qualification,
            profession: $member->profession,
            bio: $member->bio,
            committee: $member->committee,
            termStart: $member->term_start?->format('Y-m-d'),
            termEnd: $member->term_end?->format('Y-m-d'),
            yearsOfExperience: $member->years_of_experience !== null ? (string) $member->years_of_experience : null,
            status: $member->status ?? 'active',
            isPublic: (bool) $member->is_public,
        );
    }
}
