<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class ChatbotAuditPublicDataCommand extends Command
{
    protected $signature = 'chatbot:audit-public-data 
                            {--table= : Specific table to audit (services, news, municipality_contact, jobs, facilities, council, water)}
                            {--dry-run : Do not modify data, only report}';

    protected $description = 'Audit public chatbot data for demo/test/corrupted values';

    public function handle(PublicChatbotDataQualityGuard $guard): int
    {
        $tables = $this->option('table') ? [$this->option('table')] : [
            'services',
            'news',
            'municipality_contact',
            'jobs',
            'facilities',
            'council',
            'water',
        ];

        $totalIssues = 0;

        foreach ($tables as $table) {
            $issues = $this->auditTable($table, $guard);
            $totalIssues += count($issues);

            if (count($issues) > 0) {
                $this->warn("Table: {$table}");
                foreach ($issues as $issue) {
                    $this->line("  [{$issue['record_id']}] {$issue['field']}: {$issue['bad_value']} ({$issue['reason']})");
                }
            } else {
                $this->info("Table: {$table} - clean");
            }
        }

        $this->info("Total issues found: {$totalIssues}");

        if (! $this->option('dry-run')) {
            $this->warn('Dry-run mode is recommended. Use --dry-run to avoid data modification.');
        }

        return $totalIssues > 0 ? 1 : 0;
    }

    private function auditTable(string $table, PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];

        try {
            $issues = match ($table) {
                'services' => $this->auditServices($guard),
                'news' => $this->auditNews($guard),
                'municipality_contact' => $this->auditMunicipalityContact($guard),
                'jobs' => $this->auditJobs($guard),
                'facilities' => $this->auditFacilities($guard),
                'council' => $this->auditCouncil($guard),
                'water' => $this->auditWater($guard),
                default => [],
            };
        } catch (\Throwable $e) {
            $this->error("Failed to audit {$table}: {$e->getMessage()}");
        }

        return $issues;
    }

    private function auditServices(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $services = DB::table('electronic_services')->get(['id', 'name', 'summary', 'description']);

        foreach ($services as $service) {
            $fields = [
                'name' => $service->name,
                'summary' => $service->summary,
                'description' => $service->description,
            ];

            $recordIssues = $guard->auditRecord('services', $service->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }

    private function auditNews(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $news = DB::table('news')->get(['id', 'title', 'content', 'summary']);

        foreach ($news as $item) {
            $fields = [
                'title' => $item->title,
                'content' => $item->content,
                'summary' => $item->summary,
            ];

            $recordIssues = $guard->auditRecord('news', $item->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }

    private function auditMunicipalityContact(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $contacts = DB::table('municipality_contacts')->get(['id', 'value', 'label', 'type']);

        foreach ($contacts as $contact) {
            $fields = [
                'value' => $contact->value,
                'label' => $contact->label,
            ];

            $recordIssues = $guard->auditRecord('municipality_contacts', $contact->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }

    private function auditJobs(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $jobs = DB::table('jobs')->get(['id', 'title', 'description', 'department_name']);

        foreach ($jobs as $job) {
            $fields = [
                'title' => $job->title,
                'description' => $job->description,
                'department_name' => $job->department_name,
            ];

            $recordIssues = $guard->auditRecord('jobs', $job->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }

    private function auditFacilities(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $facilities = DB::table('facilities')->get(['id', 'name', 'description', 'address']);

        foreach ($facilities as $facility) {
            $fields = [
                'name' => $facility->name,
                'description' => $facility->description,
                'address' => $facility->address,
            ];

            $recordIssues = $guard->auditRecord('facilities', $facility->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }

    private function auditCouncil(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $members = DB::table('council_members')->get(['id', 'name', 'role', 'bio']);

        foreach ($members as $member) {
            $fields = [
                'name' => $member->name,
                'role' => $member->role,
                'bio' => $member->bio,
            ];

            $recordIssues = $guard->auditRecord('council_members', $member->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }

    private function auditWater(PublicChatbotDataQualityGuard $guard): array
    {
        $issues = [];
        $areas = DB::table('water_areas')->get(['id', 'name', 'notes']);

        foreach ($areas as $area) {
            $fields = [
                'name' => $area->name,
                'notes' => $area->notes,
            ];

            $recordIssues = $guard->auditRecord('water_areas', $area->id, $fields);
            $issues = array_merge($issues, $recordIssues);
        }

        return $issues;
    }
}
