<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\ChatbotModelVersionRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class ChatbotActivateModelCommand extends Command
{
    protected $signature = 'chatbot:activate-model
        {version : The model version to activate (e.g., 20260728120000)}
        {--force : Skip confirmation prompt}';

    protected $description = 'Activate a specific chatbot model version';

    public function handle(
        ChatbotModelVersionRepositoryInterface $modelVersionRepository,
    ): int {
        $version = $this->argument('version');
        $force = (bool) $this->option('force');

        $allVersions = $modelVersionRepository->all();

        $model = $allVersions->firstWhere('version', $version);

        if ($model === null) {
            $this->error("Model version '{$version}' not found.");
            $this->newLine();
            $this->line('Available versions:');
            foreach ($allVersions as $v) {
                $status = $v->status === 'active' ? ' (active)' : '';
                $this->line("  {$v->version}{$status}");
            }

            return self::FAILURE;
        }

        if ($model->status === 'active') {
            $this->warn("Model version '{$version}' is already active.");

            return self::SUCCESS;
        }

        if (! $force && ! $this->confirm("Activate model version '{$version}'? This will deactivate any currently active model.")) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($modelVersionRepository, $model): void {
            $activeModel = $modelVersionRepository->getActive();
            if ($activeModel !== null) {
                $modelVersionRepository->update($activeModel->id, ['status' => 'ready']);
                $this->line("Deactivated: {$activeModel->version}");
            }

            $modelVersionRepository->update($model->id, ['status' => 'active']);
        });

        $this->info("Activated model version '{$version}'.");

        return self::SUCCESS;
    }
}
