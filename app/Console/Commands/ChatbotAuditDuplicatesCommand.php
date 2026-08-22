<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class ChatbotAuditDuplicatesCommand extends Command
{
    protected $signature = 'chatbot:audit-duplicates
        {--fix : Safely remove confirmed duplicate records}
        {--table= : Specific table to audit (categories, services, water, facilities, council, jobs, departments, offices)}';

    protected $description = 'Audit chatbot data tables for duplicate records and optionally clean them up';

    private const TABLES = [
        'categories' => [
            'table' => 'service_categories',
            'key' => 'name',
            'preserve' => 'lowest_id',
            'foreign_keys' => [
                ['table' => 'electronic_services', 'column' => 'service_category_id'],
            ],
        ],
        'services' => [
            'table' => 'electronic_services',
            'key' => 'name',
            'preserve' => 'lowest_id',
            'foreign_keys' => [
                ['table' => 'service_search_terms', 'column' => 'electronic_service_id'],
                ['table' => 'service_portal_clicks', 'column' => 'electronic_service_id'],
                ['table' => 'service_views', 'column' => 'electronic_service_id'],
            ],
        ],
        'water' => [
            'table' => 'water_areas',
            'key' => 'name',
            'preserve' => 'lowest_id',
            'foreign_keys' => [],
        ],
        'facilities' => [
            'table' => 'public_facilities',
            'key' => 'name',
            'preserve' => 'lowest_id',
            'foreign_keys' => [],
        ],
        'council' => [
            'table' => 'council_members',
            'key' => 'full_name',
            'preserve' => 'lowest_id',
            'foreign_keys' => [],
        ],
        'jobs' => [
            'table' => 'job_offers',
            'key' => 'title',
            'preserve' => 'lowest_id',
            'foreign_keys' => [],
        ],
        'departments' => [
            'table' => 'departments',
            'key' => 'slug',
            'preserve' => 'lowest_id',
            'foreign_keys' => [],
        ],
        'offices' => [
            'table' => 'engineering_offices',
            'key' => 'office_name',
            'preserve' => 'lowest_id',
            'foreign_keys' => [],
        ],
    ];

    public function handle(): int
    {
        $tableFilter = $this->option('table');

        $tables = $tableFilter
            ? array_filter(self::TABLES, fn ($config, $name) => $name === $tableFilter, ARRAY_FILTER_USE_BOTH)
            : self::TABLES;

        if (empty($tables)) {
            $this->error("Unknown table: {$tableFilter}. Available: ".implode(', ', array_keys(self::TABLES)));

            return self::FAILURE;
        }

        $totalDuplicates = 0;

        foreach ($tables as $name => $config) {
            $duplicates = $this->findDuplicates($config);
            $count = count($duplicates);

            if ($count === 0) {
                $this->info("[{$name}] No duplicates found.");

                continue;
            }

            $totalDuplicates += $count;
            $this->warn("[{$name}] Found {$count} duplicate group(s):");

            foreach ($duplicates as $group) {
                $this->line("  {$config['key']}: {$group['key_value']} => IDs: ".implode(', ', $group['ids'])." (count: {$group['count']})");
            }

            if ($this->option('fix')) {
                $this->newLine();
                $this->info("  Cleaning duplicates in {$config['table']}...");

                DB::transaction(function () use ($config, $duplicates): void {
                    foreach ($duplicates as $group) {
                        $this->cleanDuplicateGroup($config, $group);
                    }
                });

                $this->line('  <fg=green>Done.</>');
            }
        }

        $this->newLine();
        $this->info('Total duplicate groups found: '.$totalDuplicates);

        return $totalDuplicates > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function findDuplicates(array $config): array
    {
        $table = $config['table'];
        $key = $config['key'];

        $results = DB::table($table)
            ->select($key, DB::raw('COUNT(*) as cnt'), DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'))
            ->groupBy($key)
            ->having('cnt', '>', 1)
            ->get();

        $duplicates = [];
        foreach ($results as $row) {
            $ids = explode(',', $row->ids);
            $duplicates[] = [
                'key_value' => $row->{$key},
                'count' => (int) $row->cnt,
                'ids' => $ids,
                'preserve_id' => $ids[0],
                'delete_ids' => array_slice($ids, 1),
            ];
        }

        return $duplicates;
    }

    private function cleanDuplicateGroup(array $config, array $group): void
    {
        $table = $config['table'];
        $preserveId = $group['preserve_id'];
        $deleteIds = $group['delete_ids'];

        foreach ($config['foreign_keys'] as $fk) {
            foreach ($deleteIds as $oldId) {
                DB::table($fk['table'])
                    ->where($fk['column'], $oldId)
                    ->update([$fk['column'] => $preserveId]);
            }
        }

        DB::table($table)->whereIn('id', $deleteIds)->delete();

        $this->line('    Removed IDs: '.implode(', ', $deleteIds)." (preserved ID: {$preserveId})");
    }
}
