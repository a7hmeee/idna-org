<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use Illuminate\Console\Command;

final class ChatbotPredictCommand extends Command
{
    protected $signature = 'chatbot:predict
        {message : The Arabic message to predict intent for}
        {--show-probs : Show class probabilities}';

    protected $description = 'Predict intent for a given message';

    public function handle(
        HybridIntentPredictorInterface $predictor,
        ArabicTextNormalizer $normalizer,
    ): int {
        $message = $this->argument('message');
        $showProbs = (bool) $this->option('show-probs');

        $normalized = $normalizer->normalize($message);

        $this->line("Original:    {$message}");
        $this->line("Normalized:  {$normalized}");
        $this->newLine();

        $result = $predictor->predict($normalized);

        $this->line("Intent:      {$result->intent->value} ({$result->intent->label()})");
        $this->line("Confidence:  {$result->confidence}");
        $this->line("Source:      {$result->source}");
        $this->line('Accepted:    '.($result->accepted ? 'yes' : 'no'));

        if ($result->rejectionReason !== null) {
            $this->line("Rejection:   {$result->rejectionReason}");
        }

        if ($result->matchedRule !== null) {
            $this->line("Rule:        {$result->matchedRule}");
        }

        if ($result->modelVersion !== null) {
            $this->line("Model:       {$result->modelVersion}");
        }

        if ($showProbs && ! empty($result->classProbabilities)) {
            $this->newLine();
            $this->line('Class probabilities:');
            foreach ($result->classProbabilities as $class => $prob) {
                $this->line("  {$class}: {$prob}");
            }
        }

        return self::SUCCESS;
    }
}
