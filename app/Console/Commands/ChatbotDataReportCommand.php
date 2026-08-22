<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class ChatbotDataReportCommand extends Command
{
    protected $signature = 'chatbot:data-report
        {--table= : Specific domain to report (services, water, facilities, jobs, council, contact, departments, offices, news, announcements)}';

    protected $description = 'Print database counts and status for chatbot domains';

    private function reportTable(string $label, callable $query, ?callable $publicQuery = null): void
    {
        $total = (int) $query();
        $public = $publicQuery ? (int) $publicQuery() : $total;

        $this->line("  Total: {$total}");
        $this->line("  Public: {$public}");
    }

    public function handle(): int
    {
        $filter = $this->option('table');

        $domains = [
            'Electronic Service Categories' => function () {
                return DB::table('service_categories')->count();
            },
            'Electronic Services' => function () {
                return DB::table('electronic_services')->count();
            },
            'Service Search Terms' => function () {
                return DB::table('service_search_terms')->count();
            },
            'Water Areas' => function () {
                return DB::table('water_areas')->count();
            },
            'Water Schedules' => function () {
                return DB::table('water_schedules')->count();
            },
            'Facilities' => function () {
                return DB::table('public_facilities')->count();
            },
            'Jobs' => function () {
                return DB::table('job_offers')->count();
            },
            'Council Members' => function () {
                return DB::table('council_members')->count();
            },
            'Council Decisions' => function () {
                return DB::table('council_decisions')->count();
            },
            'Municipality Contacts' => function () {
                return DB::table('municipality_contacts')->count();
            },
            'Departments' => function () {
                return DB::table('departments')->count();
            },
            'Engineering Offices' => function () {
                return DB::table('engineering_offices')->count();
            },
            'News Items' => function () {
                return DB::table('news_items')->count();
            },
            'Announcements' => function () {
                return DB::table('announcements')->count();
            },
        ];

        $publicQueries = [
            'Electronic Service Categories' => fn () => DB::table('service_categories')->where('is_public', true)->where('status', 'active')->count(),
            'Electronic Services' => fn () => DB::table('electronic_services')->where('is_public', true)->where('status', 'active')->count(),
            'Service Search Terms' => fn () => DB::table('service_search_terms')->where('is_active', true)->count(),
            'Water Areas' => fn () => DB::table('water_areas')->where('is_active', true)->count(),
            'Water Schedules' => fn () => DB::table('water_schedules')->where('is_public', true)->count(),
            'Facilities' => fn () => DB::table('public_facilities')->where('is_public', true)->count(),
            'Jobs' => fn () => DB::table('job_offers')->where('is_public', true)->where('status', 'published')->count(),
            'Council Members' => fn () => DB::table('council_members')->where('is_public', true)->count(),
            'Council Decisions' => fn () => DB::table('council_decisions')->where('status', 'published')->count(),
            'Municipality Contacts' => fn () => DB::table('municipality_contacts')->where('is_active', true)->count(),
            'Departments' => fn () => DB::table('departments')->where('is_public', true)->count(),
            'Engineering Offices' => fn () => DB::table('engineering_offices')->where('is_public', true)->count(),
            'News Items' => fn () => DB::table('news_items')->where('is_public', true)->where('status', 'published')->count(),
            'Announcements' => fn () => DB::table('announcements')->where('status', 'published')->count(),
        ];

        $this->info('Chatbot Data Report');
        $this->line(str_repeat('=', 50));

        foreach ($domains as $label => $totalQuery) {
            if ($filter !== null && ! str_contains(strtolower($label), strtolower($filter))) {
                continue;
            }

            $this->line("<fg=cyan>{$label}</>");
            $this->reportTable($label, $totalQuery, $publicQueries[$label] ?? null);

            $dupQuery = match ($label) {
                'Electronic Service Categories' => DB::table('service_categories')->select('name', DB::raw('COUNT(*) as cnt'))->groupBy('name')->having('cnt', '>', 1),
                'Electronic Services' => DB::table('electronic_services')->select('name', DB::raw('COUNT(*) as cnt'))->groupBy('name')->having('cnt', '>', 1),
                default => null,
            };

            if ($dupQuery !== null) {
                $dups = $dupQuery->count();
                $this->line("  Duplicates: {$dups}");
            }

            $this->newLine();
        }

        return self::SUCCESS;
    }
}
