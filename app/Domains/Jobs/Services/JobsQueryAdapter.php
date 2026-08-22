<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Services;

use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\DTOs\JobDetailsData;
use App\Domains\Chatbot\DTOs\JobSummaryData;
use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class JobsQueryAdapter implements JobsQueryInterface
{
    private const CACHE_KEY_LATEST = 'chatbot:open-jobs';

    private const CACHE_TTL = 900;

    public function __construct(
        private JobRepositoryInterface $repository,
    ) {}

    public function getOpenJobs(int $limit = 5): array
    {
        return Cache::remember(self::CACHE_KEY_LATEST, self::CACHE_TTL, function () use ($limit): array {
            $jobs = $this->repository->getPublished();

            return collect($jobs)
                ->where('status', 'published')
                ->where('is_public', true)
                ->take($limit)
                ->map(fn ($job) => new JobSummaryData(
                    id: (int) $job->id,
                    title: $job->title,
                    departmentName: $job->department?->name,
                    employmentType: $job->employment_type?->value,
                    location: $job->location,
                    closingAt: $job->closing_at?->format('Y-m-d'),
                    status: $job->status->value,
                ))
                ->values()
                ->all();
        });
    }

    public function getLatestPublishedJobs(int $limit = 5): array
    {
        return $this->getOpenJobs($limit);
    }

    public function searchPublishedJobs(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY_LATEST);

        $jobs = $this->repository->getPublished();

        return collect($jobs)
            ->filter(fn ($job) => str_contains(mb_strtolower($job->title), mb_strtolower($query))
                || str_contains(mb_strtolower($job->summary ?? ''), mb_strtolower($query))
                || str_contains(mb_strtolower($job->description ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($job) => new JobSummaryData(
                id: (int) $job->id,
                title: $job->title,
                departmentName: $job->department?->name,
                employmentType: $job->employment_type?->value,
                location: $job->location,
                closingAt: $job->closing_at?->format('Y-m-d'),
                status: $job->status->value,
            ))
            ->values()
            ->all();
    }

    public function getPublishedJobById(int $id): ?JobDetailsData
    {
        $job = $this->repository->find($id);

        if ($job === null || $job->status->value !== 'published' || ! $job->is_public) {
            return null;
        }

        return new JobDetailsData(
            id: (int) $job->id,
            title: $job->title,
            slug: $job->slug,
            jobNumber: $job->job_number,
            departmentName: $job->department?->name,
            employmentType: $job->employment_type?->value,
            location: $job->location,
            salary: $job->salary,
            vacancies: $job->vacancies,
            summary: $job->summary,
            description: $job->description,
            requirements: $job->requirements ?? [],
            responsibilities: $job->responsibilities ?? [],
            benefits: $job->benefits ?? [],
            requiredDocuments: $job->required_documents ?? [],
            applicationMethod: $job->application_method?->value,
            applicationUrl: $job->application_url,
            applicationEmail: $job->application_email,
            applicationPhone: $job->application_phone,
            publishAt: $job->publish_at?->format('Y-m-d'),
            closingAt: $job->closing_at?->format('Y-m-d'),
            status: $job->status->value,
        );
    }
}
