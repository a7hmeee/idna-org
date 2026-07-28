<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Contracts;

interface HomepagePublicRepositoryInterface
{
    public function getHomePageData(): array;

    public function getHeroSlides(): array;

    public function getQuickLinks(): array;

    public function getStatistics(): array;

    public function getAutoStatistics(): array;

    public function getMunicipalityInfo(): ?array;

    public function getFeaturedServices(int $limit = 6): array;

    public function getFeaturedDepartments(int $limit = 6): array;

    public function getFeaturedCouncilMembers(int $limit = 6): array;

    public function getLatestCouncilDecisions(int $limit = 5): array;

    public function getEngineeringOffices(int $limit = 6): array;

    public function getLatestNews(int $limit = 3): array;

    public function getLatestProjects(int $limit = 3): array;

    public function getLatestAnnouncements(int $limit = 3): array;

    public function getLatestTenders(int $limit = 4): array;

    public function getLatestJobs(int $limit = 3): array;

    public function getWaterSchedule(): array;

    public function getWaterAreas(): array;

    public function getPartnerLogos(): array;

    public function getMayorData(): ?array;

    public function getDepartmentPublicServices(int $departmentId, int $limit = 4): array;

    public function getFeaturedFacilities(int $limit = 4): array;
}
