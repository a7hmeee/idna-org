<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\ChatTrainingExampleRepositoryInterface;
use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\DTOs\ModelEvaluationResultData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use Illuminate\Console\Command;

final class ChatbotEvaluateCommand extends Command
{
    protected $signature = 'chatbot:evaluate
        {--model-version= : Specific model version to evaluate (defaults to active)}
        {--intent= : Evaluate only this specific intent}';

    protected $description = 'Evaluate the chatbot intent model against the training dataset';

    public function handle(
        HybridIntentPredictorInterface $predictor,
        ArabicTextNormalizer $normalizer,
        ChatTrainingExampleRepositoryInterface $exampleRepository,
    ): int {
        $examples = $exampleRepository->getVerifiedActiveExamples();
        $intentFilter = $this->option('intent');

        if ($examples->isEmpty()) {
            $this->error('No training examples found.');

            return self::FAILURE;
        }

        $this->info('Evaluating model against '.$examples->count().' training examples...');
        $this->newLine();

        $correctPredictions = 0;
        $rejectedCount = 0;
        $invalidCount = 0;
        $totalExamples = 0;
        $confusionMatrix = [];
        $intentHits = [];
        $intentTotals = [];

        foreach (ChatbotIntent::cases() as $intent) {
            $confusionMatrix[$intent->value] = [];
            $intentHits[$intent->value] = 0;
            $intentTotals[$intent->value] = 0;
            foreach (ChatbotIntent::cases() as $other) {
                $confusionMatrix[$intent->value][$other->value] = 0;
            }
        }

        foreach ($examples as $example) {
            $expectedLabel = $example->intent->name;

            if ($intentFilter !== null && $expectedLabel !== $intentFilter) {
                continue;
            }

            $totalExamples++;
            $intentTotals[$expectedLabel]++;

            try {
                $result = $predictor->predict($example->normalized_text);

                $predictedLabel = $result->intent->value;

                if ($result->accepted === false) {
                    $rejectedCount++;
                    $predictedLabel = ChatbotIntent::Unknown->value;
                }

                if ($predictedLabel === $expectedLabel) {
                    $correctPredictions++;
                    $intentHits[$expectedLabel]++;
                }

                $confusionMatrix[$expectedLabel][$predictedLabel]++;
            } catch (\Throwable) {
                $invalidCount++;
            }
        }

        $accuracy = $totalExamples > 0 ? $correctPredictions / $totalExamples : 0.0;

        $perIntentPrecision = [];
        $perIntentRecall = [];
        $perIntentF1 = [];

        foreach (ChatbotIntent::cases() as $intent) {
            $label = $intent->value;
            $tp = $confusionMatrix[$label][$label] ?? 0;

            $predictedTotal = 0;
            foreach (ChatbotIntent::cases() as $other) {
                $predictedTotal += $confusionMatrix[$other->value][$label] ?? 0;
            }

            $actualTotal = $intentTotals[$label];

            $precision = $predictedTotal > 0 ? $tp / $predictedTotal : 0.0;
            $recall = $actualTotal > 0 ? $tp / $actualTotal : 0.0;
            $f1 = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0.0;

            $perIntentPrecision[$label] = round($precision, 4);
            $perIntentRecall[$label] = round($recall, 4);
            $perIntentF1[$label] = round($f1, 4);
        }

        $result = new ModelEvaluationResultData(
            totalExamples: $totalExamples,
            correctPredictions: $correctPredictions,
            accuracy: round($accuracy, 4),
            perIntentPrecision: $perIntentPrecision,
            perIntentRecall: $perIntentRecall,
            perIntentF1: $perIntentF1,
            confusionMatrix: $confusionMatrix,
            rejectedCount: $rejectedCount,
            invalidCount: $invalidCount,
            reliable: $totalExamples >= 50,
        );

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total examples', (string) $result->totalExamples],
                ['Correct predictions', (string) $result->correctPredictions],
                ['Accuracy', (string) ($result->accuracy * 100).'%'],
                ['Rejected (low confidence)', (string) $result->rejectedCount],
                ['Errors', (string) $result->invalidCount],
            ],
        );

        $this->newLine();
        $this->line('Per-intent metrics:');

        $perIntentRows = [];
        foreach (ChatbotIntent::cases() as $intent) {
            $label = $intent->value;
            if ($intentFilter !== null && $label !== $intentFilter) {
                continue;
            }
            $perIntentRows[] = [
                $intent->label(),
                (string) $intentTotals[$label],
                (string) $intentHits[$label],
                (string) ($perIntentPrecision[$label] * 100).'%',
                (string) ($perIntentRecall[$label] * 100).'%',
                (string) ($perIntentF1[$label] * 100).'%',
            ];
        }

        $this->table(
            ['Intent', 'Examples', 'Correct', 'Precision', 'Recall', 'F1'],
            $perIntentRows,
        );

        if (! $result->reliable) {
            $this->newLine();
            $this->warn('Dataset is small — evaluation may not be reliable.');
        }

        return self::SUCCESS;
    }
}
