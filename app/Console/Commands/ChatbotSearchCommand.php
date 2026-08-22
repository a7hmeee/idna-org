<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\SmartServiceSearchInterface;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;
use Illuminate\Console\Command;

final class ChatbotSearchCommand extends Command
{
    protected $signature = 'chatbot:search
                            {query : The search query in Arabic}
                            {--show-scores : Display detailed scores for each candidate}
                            {--limit=5 : Maximum number of candidates to show}
                            {--conversation= : Optional conversation/session ID for context}';

    protected $description = 'Test the smart service search with a natural Arabic query';

    public function handle(SmartServiceSearchInterface $search, ServiceSearchTokenizer $tokenizer): int
    {
        $query = $this->argument('query');
        $showScores = $this->option('show-scores');
        $limit = (int) $this->option('limit');
        $conversationId = $this->option('conversation');

        $this->newLine();
        $this->components->twoColumnDetail('Original', $query);

        $result = $search->search(
            message: $query,
            currentServiceId: null,
            limit: $limit,
        );

        $this->components->twoColumnDetail('Normalized', $result->normalizedMessage);
        $this->components->twoColumnDetail('Tokens', implode(', ', $tokenizer->tokenize($query)));

        $this->newLine();
        $this->components->twoColumnDetail('Decision', match (true) {
            $result->isConfident => '<fg=green>AUTO_SELECTED</>',
            $result->requiresClarification => '<fg=yellow>CLARIFICATION</>',
            $result->noMatch => '<fg=red>NO_MATCH</>',
            default => '<fg=yellow>LOW_CONFIDENCE</>',
        });

        if ($result->bestMatch !== null) {
            $this->components->twoColumnDetail('Best score', (string) $result->bestMatch->score);
            $this->components->twoColumnDetail('Score gap', (string) $result->scoreGap);
            $this->components->twoColumnDetail('Matched by', $result->bestMatch->matchedBy);
        }

        $this->newLine();
        $this->components->twoColumnDetail('Candidates', (string) count($result->matches));

        foreach ($result->matches as $i => $match) {
            $num = $i + 1;
            $this->newLine();
            $this->components->twoColumnDetail("{$num}. {$match->serviceName}", "Score: {$match->score}");

            if ($showScores) {
                $this->components->twoColumnDetail('   Matched by', $match->matchedBy);
                $this->components->twoColumnDetail('   Terms', implode(', ', $match->matchedTerms));
                $this->components->twoColumnDetail('   Explanation', $match->explanation);
            }
        }

        if ($result->noMatch) {
            $this->newLine();
            $this->warn('No matching service found for this query.');
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
