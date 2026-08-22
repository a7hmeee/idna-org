<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\ElectronicServices\Models\ServiceSearchTerm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class RepairServiceDataCommand extends Command
{
    protected $signature = 'municipality:repair-service-data
        {--dry-run : Show what would be merged without making changes}
        {--apply : Apply the repairs (runs inside a DB transaction)}';

    protected $description = 'Detect and repair duplicate service categories and electronic services';

    private function isDryRun(): bool
    {
        return (bool) $this->option('dry-run');
    }

    private function isApply(): bool
    {
        return (bool) $this->option('apply');
    }

    public function handle(): int
    {
        if ($this->isDryRun() && $this->isApply()) {
            $this->error('Cannot use --dry-run and --apply together.');

            return self::FAILURE;
        }

        if (! $this->isDryRun() && ! $this->isApply()) {
            $this->error('Please specify either --dry-run or --apply.');

            return self::FAILURE;
        }

        $mode = $this->isDryRun() ? 'DRY-RUN' : 'APPLY';
        $this->info("🔧 Service Data Repair — {$mode} mode");
        $this->newLine();

        $audit = [
            'mode' => $mode,
            'timestamp' => now()->toIso8601String(),
            'category_duplicates' => [],
            'service_duplicates' => [],
            'search_term_duplicates' => [],
            'reassignments' => [],
            'deletions' => [],
            'summary' => [],
        ];

        try {
            $canonicalCategories = $this->buildCanonicalCategoryMap();
            $this->repairCategories($audit, $canonicalCategories);
            $this->newLine();
            $this->repairServices($audit, $canonicalCategories);
            $this->newLine();
            $this->repairSearchTerms($audit);
            $this->newLine();
            $this->printSummary($audit);
            if (! $this->isDryRun()) {
                $this->writeAuditLog($audit);
            }

            if ($this->isApply()) {
                $this->info('✅ Repairs applied successfully.');
            } else {
                $this->info('🔍 Dry-run complete. No changes were made.');
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed: '.$e->getMessage());
            if ($this->isApply()) {
                DB::rollBack();
            }

            return self::FAILURE;
        }
    }

    private function normalizeArabic(string $str): string
    {
        $str = preg_replace('/[أإآ]/u', 'ا', $str);
        $str = preg_replace('/[\x{064B}-\x{065F}]/u', '', $str);
        $str = str_replace('ـ', '', $str);
        $str = preg_replace('/\s+/u', ' ', $str);

        return trim($str);
    }

    private function buildCanonicalCategoryMap(): array
    {
        $categories = ServiceCategory::all();
        $groups = [];
        foreach ($categories as $cat) {
            $norm = $this->normalizeArabic($cat->name);
            $groups[$norm][] = $cat;
        }

        $map = [];
        foreach ($groups as $normName => $group) {
            if (count($group) <= 1) {
                continue;
            }
            $ids = array_map(fn ($c) => $c->id, $group);
            $canonicalId = $this->chooseCanonicalCategory($ids);
            foreach ($group as $cat) {
                $map[$cat->id] = $canonicalId;
            }
        }

        return $map;
    }

    private function repairCategories(array &$audit, array $canonicalCategories): void
    {
        $this->line('<fg=cyan>── Categories ──────────────────────────────</>');

        $categories = ServiceCategory::all();
        $groups = [];
        foreach ($categories as $cat) {
            $norm = $this->normalizeArabic($cat->name);
            $groups[$norm][] = $cat;
        }
        $duplicateGroups = array_filter($groups, fn ($v) => count($v) > 1);
        ksort($duplicateGroups);

        $totalDuplicateCats = 0;
        $totalRemovedCats = 0;

        foreach ($duplicateGroups as $normName => $group) {
            $ids = array_map(fn ($c) => $c->id, $group);
            $canonicalId = $this->chooseCanonicalCategory($ids);
            $canonical = ServiceCategory::find($canonicalId);
            $duplicateIds = array_values(array_diff($ids, [$canonicalId]));

            $this->line("  Group '{$canonical->name}' (".count($group).' rows)');
            $this->line("    Canonical : id {$canonicalId} (slug: {$canonical->slug}, status: {$canonical->status}, public: {$canonical->is_public}, services: ".$canonical->services()->count().')');
            $this->line('    Duplicates: '.implode(', ', $duplicateIds));

            $audit['category_duplicates'][] = [
                'name' => $canonical->name,
                'normalized_name' => $normName,
                'count' => count($group),
                'ids' => $ids,
                'canonical_id' => $canonicalId,
                'duplicate_ids' => $duplicateIds,
            ];

            if ($this->isApply()) {
                DB::transaction(function () use ($canonicalId, $duplicateIds, $audit, $canonical) {
                    foreach ($duplicateIds as $dupId) {
                        $dup = ServiceCategory::find($dupId);
                        if (! $dup) {
                            continue;
                        }

                        $dup->services()->update(['service_category_id' => $canonicalId]);
                        $this->mergeCategoryMetadata($canonical, $dup);

                        $dup->delete();
                        $audit['deletions'][] = [
                            'type' => 'category',
                            'id' => $dupId,
                            'name' => $dup->name,
                            'reason' => 'duplicate of category id '.$canonicalId,
                        ];
                    }

                    $canonical->refresh();
                });
            }

            $totalDuplicateCats += count($group);
            $totalRemovedCats += count($duplicateIds);
        }

        $this->line('  Total duplicate category groups : '.count($duplicateGroups));
        $this->line("  Total category rows involved    : {$totalDuplicateCats}");
        $this->line("  Categories to remove            : {$totalRemovedCats}");

        $audit['summary']['categories'] = [
            'duplicate_groups' => count($duplicateGroups),
            'total_rows_involved' => $totalDuplicateCats,
            'to_remove' => $totalRemovedCats,
        ];
    }

    private function chooseCanonicalCategory(array $ids): int
    {
        $categories = ServiceCategory::whereIn('id', $ids)->get();

        if ($categories->count() === 1) {
            return $categories->first()->id;
        }

        $scored = $categories->map(function ($cat) {
            $servicesCount = $cat->services()->count();
            $hasCompleteSlug = ! preg_match('/-\d+$/', $cat->slug);

            return [
                'id' => $cat->id,
                'score' => match (true) {
                    $cat->status === 'active' && $cat->is_public => 100000,
                    $cat->status === 'active' || $cat->is_public => 50000,
                    default => 0,
                } + ($servicesCount * 1000)
                + ($hasCompleteSlug ? 100 : 0)
                + (int) ($cat->created_at?->timestamp ?? 0)
                + (PHP_INT_MAX - $cat->id),
            ];
        });

        return $scored->sortByDesc('score')->first()['id'];
    }

    private function mergeCategoryMetadata(ServiceCategory $canonical, ServiceCategory $duplicate): void
    {
        $mergeFields = ['description', 'icon', 'image_path', 'sort_order', 'status', 'is_public'];

        foreach ($mergeFields as $field) {
            $dupValue = $duplicate->$field;
            $canonValue = $canonical->$field;

            if ($dupValue !== null && $dupValue !== '' && ($canonValue === null || $canonValue === '')) {
                $canonical->$field = $dupValue;
            }
        }

        if ($canonical->isDirty()) {
            $canonical->save();
        }
    }

    private function repairServices(array &$audit, array $canonicalCategories): void
    {
        $this->line('<fg=cyan>── Services ──────────────────────────────</>');

        $allServices = ElectronicService::all();
        $groups = [];
        foreach ($allServices as $svc) {
            $groups[$svc->name][] = $svc;
        }
        $duplicateGroups = array_filter($groups, fn ($v) => count($v) > 1);
        ksort($duplicateGroups);

        $totalDuplicateServices = 0;
        $totalReassigned = 0;
        $totalRemoved = 0;

        foreach ($duplicateGroups as $name => $group) {
            $ids = array_map(fn ($s) => $s->id, $group);
            $canonicalId = $this->chooseCanonicalService($ids);
            $canonical = ElectronicService::find($canonicalId);
            $duplicateIds = array_values(array_diff($ids, [$canonicalId]));

            $canonicalCategoryId = $canonicalCategories[$canonical->service_category_id] ?? $canonical->service_category_id;

            $this->line("  Group '{$canonical->name}' (".count($group).' rows)');
            $this->line("    Canonical : id {$canonicalId} (cat: {$canonical->service_category_id} -> {$canonicalCategoryId}, slug: {$canonical->slug}, status: {$canonical->status}, public: {$canonical->is_public})");
            $this->line('    Duplicates: '.implode(', ', $duplicateIds)." (will move to cat {$canonicalCategoryId})");

            $audit['service_duplicates'][] = [
                'name' => $canonical->name,
                'category_id' => $canonical->service_category_id,
                'canonical_category_id' => $canonicalCategoryId,
                'count' => count($group),
                'ids' => $ids,
                'canonical_id' => $canonicalId,
                'duplicate_ids' => $duplicateIds,
            ];

            if ($this->isApply()) {
                DB::transaction(function () use ($canonicalId, $duplicateIds, $canonicalCategoryId, $audit, $canonical) {
                    foreach ($duplicateIds as $dupId) {
                        $dup = ElectronicService::find($dupId);
                        if (! $dup) {
                            continue;
                        }

                        ServiceSearchTerm::where('electronic_service_id', $dupId)
                            ->update(['electronic_service_id' => $canonicalId]);

                        DB::table('service_views')->where('electronic_service_id', $dupId)
                            ->update(['electronic_service_id' => $canonicalId]);
                        DB::table('service_portal_clicks')->where('electronic_service_id', $dupId)
                            ->update(['electronic_service_id' => $canonicalId]);

                        $dup->update(['service_category_id' => $canonicalCategoryId]);

                        $this->mergeServiceMetadata($canonical, $dup);

                        $dup->delete();
                        $audit['deletions'][] = [
                            'type' => 'service',
                            'id' => $dupId,
                            'name' => $dup->name,
                            'category_id' => $dup->service_category_id,
                            'reason' => 'duplicate of service id '.$canonicalId,
                        ];
                    }

                    $canonical->update(['service_category_id' => $canonicalCategoryId]);
                    $canonical->refresh();
                });
            }

            $totalDuplicateServices += count($group);
            $totalReassigned += count($duplicateIds);
            $totalRemoved += count($duplicateIds);
        }

        $this->line('  Total duplicate service groups  : '.count($duplicateGroups));
        $this->line("  Total service rows involved     : {$totalDuplicateServices}");
        $this->line("  Services reassigned / removed   : {$totalReassigned}");

        $audit['summary']['services'] = [
            'duplicate_groups' => count($duplicateGroups),
            'total_rows_involved' => $totalDuplicateServices,
            'reassigned' => $totalReassigned,
            'removed' => $totalRemoved,
        ];
    }

    private function chooseCanonicalService(array $ids): int
    {
        $services = ElectronicService::whereIn('id', $ids)->get();

        if ($services->count() === 1) {
            return $services->first()->id;
        }

        $scored = $services->map(function ($svc) {
            $hasCompleteSlug = ! preg_match('/-\d+$/', $svc->slug);
            $engagement = (int) $svc->views_count + (int) $svc->portal_clicks_count;

            return [
                'id' => $svc->id,
                'score' => match (true) {
                    $svc->status === 'active' && $svc->is_public => 100000,
                    $svc->status === 'active' || $svc->is_public => 50000,
                    default => 0,
                } + ($engagement * 10)
                + ($hasCompleteSlug ? 100 : 0)
                + (int) ($svc->published_at?->timestamp ?? 0)
                + (int) ($svc->created_at?->timestamp ?? 0)
                + (PHP_INT_MAX - $svc->id),
            ];
        });

        return $scored->sortByDesc('score')->first()['id'];
    }

    private function mergeServiceMetadata(ElectronicService $canonical, ElectronicService $duplicate): void
    {
        $mergeFields = [
            'description', 'summary', 'eligibility', 'requirements',
            'documents', 'steps', 'fees', 'processing_time', 'portal_url',
            'sort_order', 'status', 'is_public', 'is_featured',
        ];

        foreach ($mergeFields as $field) {
            $dupValue = $duplicate->$field;
            $canonValue = $canonical->$field;

            if ($dupValue !== null && $dupValue !== '' && ($canonValue === null || $canonValue === '' || $dupValue !== $canonValue)) {
                if (is_array($canonValue) && is_array($dupValue)) {
                    $canonical->$field = array_replace($canonValue, $dupValue);
                } elseif (is_string($dupValue) && mb_strlen($dupValue) > mb_strlen((string) $canonValue)) {
                    $canonical->$field = $dupValue;
                } else {
                    $canonical->$field = $dupValue;
                }
            }
        }

        $canonical->views_count = max((int) $canonical->views_count, (int) $duplicate->views_count);
        $canonical->portal_clicks_count = max((int) $canonical->portal_clicks_count, (int) $duplicate->portal_clicks_count);

        if ($canonical->isDirty()) {
            $canonical->save();
        }
    }

    private function repairSearchTerms(array &$audit): void
    {
        $this->line('<fg=cyan>── Search Terms ──────────────────────────</>');

        $duplicateTerms = DB::table('service_search_terms as sst')
            ->select('sst.electronic_service_id', 'sst.normalized_term', DB::raw('COUNT(*) as cnt'), DB::raw('GROUP_CONCAT(sst.id) as ids'))
            ->groupBy('sst.electronic_service_id', 'sst.normalized_term')
            ->having('cnt', '>', 1)
            ->get();

        $totalDuplicateTerms = 0;
        $totalRemovedTerms = 0;

        foreach ($duplicateTerms as $group) {
            $ids = explode(',', $group->ids);
            $keepId = (int) $ids[0];
            $removeIds = array_map('intval', array_slice($ids, 1));

            $this->line("  Service {$group->electronic_service_id} norm '{$group->normalized_term}' ({$group->cnt} rows)");
            $this->line("    Keep id {$keepId}, remove ".implode(', ', $removeIds));

            $audit['search_term_duplicates'][] = [
                'service_id' => (int) $group->electronic_service_id,
                'normalized_term' => $group->normalized_term,
                'count' => (int) $group->cnt,
                'ids' => $ids,
                'keep_id' => $keepId,
                'remove_ids' => $removeIds,
            ];

            if ($this->isApply()) {
                ServiceSearchTerm::whereIn('id', $removeIds)->delete();
            }

            $totalDuplicateTerms += $group->cnt;
            $totalRemovedTerms += count($removeIds);
        }

        $this->line('  Total duplicate term groups : '.$duplicateTerms->count());
        $this->line("  Terms to remove             : {$totalRemovedTerms}");

        $audit['summary']['search_terms'] = [
            'duplicate_groups' => $duplicateTerms->count(),
            'total_rows_involved' => $totalDuplicateTerms,
            'removed' => $totalRemovedTerms,
        ];
    }

    private function printSummary(array $audit): void
    {
        $this->newLine();
        $this->line('<fg=green>── Summary ──────────────────────────────</>');

        if (isset($audit['summary']['categories'])) {
            $c = $audit['summary']['categories'];
            $this->line("  Categories duplicate groups : {$c['duplicate_groups']}");
            $this->line("  Categories to remove        : {$c['to_remove']}");
        }

        if (isset($audit['summary']['services'])) {
            $s = $audit['summary']['services'];
            $this->line("  Service duplicate groups    : {$s['duplicate_groups']}");
            $this->line("  Services reassigned/removed : {$s['reassigned']}");
        }

        if (isset($audit['summary']['search_terms'])) {
            $t = $audit['summary']['search_terms'];
            $this->line("  Search term duplicate groups: {$t['duplicate_groups']}");
            $this->line("  Search terms to remove      : {$t['removed']}");
        }

        $this->line('  Mode                        : '.$audit['mode']);
        $this->newLine();
    }

    private function writeAuditLog(array $audit): void
    {
        $logPath = storage_path('logs/service-repair-audit.json');
        $existing = [];

        if (file_exists($logPath)) {
            $existing = json_decode(file_get_contents($logPath), true) ?? [];
        }

        $existing[] = $audit;
        file_put_contents($logPath, json_encode($existing, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
