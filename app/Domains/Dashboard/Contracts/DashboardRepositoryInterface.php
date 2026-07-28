<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Contracts;

interface DashboardRepositoryInterface
{
    public function getExecutiveDashboard(): array;
}
