<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Repositories;

use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class EloquentCouncilDecisionRepository implements CouncilDecisionRepositoryInterface
{
    public function paginateForDashboard(?string $search = null, ?string $status = null, ?string $type = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = CouncilDecision::latest('decision_date');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('decision_number', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?CouncilDecision
    {
        return CouncilDecision::find($id);
    }

    public function create(array $data): CouncilDecision
    {
        return DB::transaction(function () use ($data): CouncilDecision {
            return CouncilDecision::create($data);
        });
    }

    public function update(int $id, array $data): CouncilDecision
    {
        return DB::transaction(function () use ($id, $data): CouncilDecision {
            $decision = CouncilDecision::findOrFail($id);
            $decision->update($data);

            return $decision->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return CouncilDecision::findOrFail($id)->delete();
        });
    }

    public function publish(int $id): CouncilDecision
    {
        return DB::transaction(function () use ($id): CouncilDecision {
            $decision = CouncilDecision::findOrFail($id);
            $decision->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            return $decision->fresh();
        });
    }

    public function archive(int $id): CouncilDecision
    {
        return DB::transaction(function () use ($id): CouncilDecision {
            $decision = CouncilDecision::findOrFail($id);
            $decision->update([
                'status' => 'archived',
                'archived_at' => now(),
            ]);

            return $decision->fresh();
        });
    }

    public function cancel(int $id): CouncilDecision
    {
        return DB::transaction(function () use ($id): CouncilDecision {
            $decision = CouncilDecision::findOrFail($id);
            $decision->update([
                'status' => 'cancelled',
            ]);

            return $decision->fresh();
        });
    }

    public function paginatePublicDecisions(?string $search = null, ?string $type = null, ?int $year = null, string $sort = 'latest', int $perPage = 12): LengthAwarePaginator
    {
        $query = CouncilDecision::query()
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('decision_number', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('session_number', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($year) {
            $query->whereYear('decision_date', $year);
        }

        $query->orderBy(
            match ($sort) {
                'oldest' => 'decision_date',
                'number' => 'decision_number',
                default => 'decision_date',
            },
            match ($sort) {
                'oldest' => 'asc',
                default => 'desc',
            }
        );

        return $query->paginate($perPage);
    }

    public function findPublicById(int $id): ?CouncilDecision
    {
        return CouncilDecision::query()
            ->where('id', $id)
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->first();
    }

    public function getRelatedPublishedDecisions(int $decisionId, string $type, int $limit = 3): array
    {
        return CouncilDecision::query()
            ->where('id', '!=', $decisionId)
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->where('type', $type)
            ->orderBy('decision_date', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getPreviousDecision(int $decisionId, \DateTimeInterface|string $decisionDate): ?CouncilDecision
    {
        $dateString = $decisionDate instanceof \DateTimeInterface ? $decisionDate->format('Y-m-d') : $decisionDate;

        return CouncilDecision::query()
            ->where('id', '!=', $decisionId)
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->where(function ($q) use ($decisionId, $dateString): void {
                $q->where('decision_date', '<', $dateString)
                    ->orWhere(function ($q2) use ($decisionId, $dateString): void {
                        $q2->where('decision_date', $dateString)
                            ->where('id', '<', $decisionId);
                    });
            })
            ->orderBy('decision_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getNextDecision(int $decisionId, \DateTimeInterface|string $decisionDate): ?CouncilDecision
    {
        $dateString = $decisionDate instanceof \DateTimeInterface ? $decisionDate->format('Y-m-d') : $decisionDate;

        return CouncilDecision::query()
            ->where('id', '!=', $decisionId)
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->where(function ($q) use ($decisionId, $dateString): void {
                $q->where('decision_date', '>', $dateString)
                    ->orWhere(function ($q2) use ($decisionId, $dateString): void {
                        $q2->where('decision_date', $dateString)
                            ->where('id', '>', $decisionId);
                    });
            })
            ->orderBy('decision_date', 'asc')
            ->orderBy('id', 'asc')
            ->first();
    }

    public function getPublicYears(): array
    {
        return Cache::remember('public.council-decisions.years', 600, function (): array {
            $yearExpr = $this->yearExtractExpression('decision_date');

            return CouncilDecision::query()
                ->where('status', CouncilDecisionStatus::Published->value)
                ->where('is_public', true)
                ->whereNotNull('published_at')
                ->whereNotNull('decision_date')
                ->selectRaw("DISTINCT {$yearExpr} as year")
                ->orderByRaw('year DESC')
                ->pluck('year')
                ->map(fn ($year): int => (int) $year)
                ->toArray();
        });
    }

    /**
     * Build a database-portable YEAR extraction expression for the given column.
     */
    private function yearExtractExpression(string $column): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'pgsql' => "EXTRACT(YEAR FROM {$column})",
            'sqlite' => "strftime('%Y', {$column})",
            default => "YEAR({$column})",
        };
    }

    public function getPublicStatistics(): array
    {
        return Cache::remember('public.council-decisions.statistics', 600, function (): array {
            $total = CouncilDecision::query()
                ->where('status', CouncilDecisionStatus::Published->value)
                ->where('is_public', true)
                ->whereNotNull('published_at')
                ->count();

            $currentYear = now()->year;
            $thisYear = CouncilDecision::query()
                ->where('status', CouncilDecisionStatus::Published->value)
                ->where('is_public', true)
                ->whereNotNull('published_at')
                ->whereYear('decision_date', $currentYear)
                ->count();

            $latest = CouncilDecision::query()
                ->where('status', CouncilDecisionStatus::Published->value)
                ->where('is_public', true)
                ->whereNotNull('published_at')
                ->orderBy('decision_date', 'desc')
                ->first(['decision_date']);

            $typeCounts = CouncilDecision::query()
                ->where('status', CouncilDecisionStatus::Published->value)
                ->where('is_public', true)
                ->whereNotNull('published_at')
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();

            return [
                'total' => $total,
                'this_year' => $thisYear,
                'latest_date' => $latest?->decision_date?->format('Y-m-d'),
                'type_counts' => $typeCounts,
                'type_count' => count($typeCounts),
            ];
        });
    }

    public function getHomepagePublishedDecisions(int $limit = 5): array
    {
        $featured = CouncilDecision::query()
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->orderBy('decision_date', 'desc')
            ->limit(1)
            ->get()
            ->toArray();

        $recent = CouncilDecision::query()
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->orderBy('decision_date', 'desc')
            ->skip(1)
            ->limit($limit - 1)
            ->get()
            ->toArray();

        return [
            'featured' => $featured[0] ?? null,
            'recent' => $recent,
        ];
    }

    public function getLatestPublished(int $limit = 5): array
    {
        return CouncilDecision::query()
            ->where('status', CouncilDecisionStatus::Published->value)
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->orderBy('decision_date', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
