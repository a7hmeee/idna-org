<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\ChatIntentRepositoryInterface;
use App\Domains\Chatbot\Contracts\IntentModelTrainerInterface;
use Illuminate\Console\Command;

final class ChatbotTrainCommand extends Command
{
    protected $signature = 'chatbot:train
        {--algorithm=naive_bayes : Algorithm to use (naive_bayes)}
        {--minimum=10 : Minimum examples per intent}
        {--activate : Activate the model after training}
        {--dry-run : Show dataset statistics without training}';

    protected $description = 'Train the chatbot intent classification model';

    public function handle(
        IntentModelTrainerInterface $trainer,
        ChatIntentRepositoryInterface $intentRepository,
    ): int {
        $algorithm = $this->option('algorithm');
        $minimum = (int) $this->option('minimum');
        $activate = (bool) $this->option('activate');
        $dryRun = (bool) $this->option('dry-run');

        $intentRepository->synchronizeFromEnum();

        if ($dryRun) {
            $this->info('Dry run mode — no training will be performed.');
            $this->newLine();
            $this->line('Configuration:');
            $this->line("  Algorithm:       {$algorithm}");
            $this->line("  Minimum/intent:  {$minimum}");
            $this->line('  Auto-activate:   '.($activate ? 'yes' : 'no'));

            return self::SUCCESS;
        }

        $this->info('Training chatbot intent model...');
        $this->newLine();

        $result = $trainer->train(
            algorithm: $algorithm,
            minimumExamples: $minimum,
            activate: $activate,
        );

        if (! $result->success) {
            $this->error('Training failed: '.($result->error ?? 'Unknown error'));

            return self::FAILURE;
        }

        $this->line("  Version:           {$result->version}");
        $this->line("  Examples:          {$result->examplesCount}");
        $this->line("  Intents:           {$result->intentsCount}");
        $this->line("  Duration:          {$result->trainingDurationMs} ms");
        $this->line("  Fingerprint:       {$result->datasetFingerprint}");
        $this->line("  Artifact:          {$result->artifactPath}");

        if ($activate) {
            $this->newLine();
            $this->info('Model activated successfully.');
        }

        return self::SUCCESS;
    }
}
