<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Audits electronic services for demo/placeholder content that must not
 * reach citizens through the chatbot (e.g. "صورة هوية م sclerosis").
 *
 * The audit is always reported; hiding is opt-in via --exclude-demo and only
 * applies to records with a definite demo marker (embedded Latin words,
 * non-Arabic scripts, lorem/demo markers). Nothing is ever invented here.
 */
final class ChatbotAuditServiceDataCommand extends Command
{
    protected $signature = 'municipality:audit-service-data
        {--exclude-demo : Hide clearly demo services (is_public=false, status=archived) inside a transaction}
        {--limit=0 : Only audit the first N services (0 = all)}';

    protected $description = 'Audit electronic services for demo/placeholder content';

    private const ARRAY_FIELDS = ['requirements', 'documents', 'steps', 'fees'];

    private const DEMO_PATTERNS = [
        '/[a-zA-Z]{3,}/u',
        '/[\x{4E00}-\x{9FFF}\x{3040}-\x{30FF}\x{AC00}-\x{D7AF}\x{0400}-\x{04FF}]/u',
        '/lorem|ipsum|dolor|placeholder|dummy|sample data|demo/i',
    ];

    public function handle(): int
    {
        $query = ElectronicService::query()->orderBy('id');

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $services = $query->get();
        $this->line('<fg=cyan>── Electronic Service Content Audit ─────────────────</>');
        $this->line('  Inspecting '.$services->count().' service(s)...');
        $this->newLine();

        $flagged = [];

        foreach ($services as $service) {
            $findings = $this->inspectService($service);

            if ($findings === []) {
                continue;
            }

            $flagged[$service->id] = [
                'service' => $service,
                'findings' => $findings,
            ];

            $this->line("  <fg=yellow>[{$service->id}]</> {$service->name} (cat: {$service->service_category_id}, status: {$service->status}, public: ".($service->is_public ? 'yes' : 'no').')');

            foreach ($findings as $finding) {
                $this->line('    - '.$finding['field'].': '.$finding['reason']);
                $this->line('      value: '.$finding['preview']);
            }
        }

        $this->newLine();

        if ($flagged === []) {
            $this->info('  No demo/placeholder content found.');
            $this->line('  Mode: audit only. No changes were made.');

            return self::SUCCESS;
        }

        $this->line('  Total flagged services: '.count($flagged));
        $this->line('  Mode: audit only. No changes were made.');

        if (! $this->option('exclude-demo')) {
            $this->newLine();
            $this->info('  Run with --exclude-demo to hide the flagged services (is_public=false, status=archived) after review.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('  Hiding flagged services inside a DB transaction...');

        DB::transaction(function () use ($flagged): void {
            foreach ($flagged as $entry) {
                $service = $entry['service'];

                $service->update([
                    'is_public' => false,
                    'status' => 'archived',
                ]);

                $this->line("  <fg=green>Hidden [{$service->id}]</> {$service->name}");
            }
        });

        $this->line('  Done. Review the audit log above before publishing new content.');

        return self::SUCCESS;
    }

    /**
     * @return list<array{field: string, reason: string, preview: string}>
     */
    private function inspectService(ElectronicService $service): array
    {
        $findings = [];

        foreach (self::ARRAY_FIELDS as $field) {
            $value = $service->{$field};

            if (! is_array($value)) {
                continue;
            }

            foreach ($value as $item) {
                $strings = $this->extractStrings($item);

                foreach ($strings as $string) {
                    $trimmed = trim((string) $string);

                    if ($trimmed === '') {
                        continue;
                    }

                    foreach (self::DEMO_PATTERNS as $pattern) {
                        if (preg_match($pattern, $trimmed)) {
                            $findings[] = [
                                'field' => $field,
                                'reason' => 'suspected demo/placeholder content',
                                'preview' => mb_substr($trimmed, 0, 120),
                            ];
                            break;
                        }
                    }
                }
            }
        }

        return $findings;
    }

    /**
     * @return list<string>
     */
    private function extractStrings(mixed $item): array
    {
        if (is_string($item)) {
            return [$item];
        }

        if (! is_array($item)) {
            return [];
        }

        $strings = [];

        foreach ($item as $value) {
            if (is_string($value)) {
                $strings[] = $value;
            } elseif (is_array($value)) {
                $strings = array_merge($strings, $this->extractStrings($value));
            }
        }

        return $strings;
    }
}
