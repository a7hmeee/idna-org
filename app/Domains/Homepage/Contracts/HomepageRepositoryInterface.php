<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Contracts;

use App\Domains\Homepage\Models\HomepageQuickLink;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Homepage\Models\HomepageStatistic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface HomepageRepositoryInterface
{
    public function getSettings(): HomepageSetting;

    public function updateSettings(array $data): HomepageSetting;

    public function getActiveSlides(): Collection;

    public function getPageSlides(string $pageKey): Collection;

    public function paginateSlides(?string $search = null, ?string $status = null): LengthAwarePaginator;

    public function paginatePageSlides(string $pageKey, ?string $search = null, ?string $status = null): LengthAwarePaginator;

    public function findSlide(int $id): ?HomepageSlide;

    public function createSlide(array $data): HomepageSlide;

    public function updateSlide(int $id, array $data): HomepageSlide;

    public function deleteSlide(int $id): bool;

    public function toggleSlide(int $id): HomepageSlide;

    public function reorderSlides(array $orders): void;

    public function getSections(): Collection;

    public function getEnabledSections(): Collection;

    public function updateSection(string $key, array $data): HomepageSection;

    public function reorderSections(array $orders): void;

    public function getQuickLinks(): Collection;

    public function paginateQuickLinks(?string $search = null, ?string $status = null): LengthAwarePaginator;

    public function findQuickLink(int $id): ?HomepageQuickLink;

    public function createQuickLink(array $data): HomepageQuickLink;

    public function updateQuickLink(int $id, array $data): HomepageQuickLink;

    public function deleteQuickLink(int $id): bool;

    public function toggleQuickLink(int $id): HomepageQuickLink;

    public function reorderQuickLinks(array $orders): void;

    public function getStatistics(): Collection;

    public function paginateStatistics(?string $search = null, ?string $status = null): LengthAwarePaginator;

    public function findStatistic(int $id): ?HomepageStatistic;

    public function createStatistic(array $data): HomepageStatistic;

    public function updateStatistic(int $id, array $data): HomepageStatistic;

    public function deleteStatistic(int $id): bool;

    public function toggleStatistic(int $id): HomepageStatistic;

    public function reorderStatistics(array $orders): void;
}
