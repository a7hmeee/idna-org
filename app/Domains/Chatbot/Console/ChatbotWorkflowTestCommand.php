<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Console;

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Console\Command;

final class ChatbotWorkflowTestCommand extends Command
{
    protected $signature = 'chatbot:workflow-test {session-id?}';

    protected $description = 'Test citizen workflows interactively via the command line';

    public function handle(ProcessRuleBasedChatMessageAction $action): int
    {
        $sessionId = $this->argument('session-id') ?? 'cli-test-'.time();

        $this->info("Session ID: {$sessionId}");
        $this->newLine();
        $this->info('=== Citizen Workflow Test CLI ===');
        $this->info('Type your messages below. Type "exit" to quit.');
        $this->newLine();

        $this->line('Try: "أريد تقديم شكوى" or "طلب اتصال" or "تتبع طلبي"');
        $this->newLine();

        while (true) {
            $input = $this->ask('You');

            if ($input === null || $input === 'exit' || $input === 'quit') {
                break;
            }

            $incoming = new IncomingChatMessageData(
                message: $input,
                sessionId: $sessionId,
            );

            try {
                $response = $action->execute($incoming);

                $this->line("Bot: {$response->message}");

                if (! empty($response->actions)) {
                    $actions = array_map(
                        fn (array $a) => $a['label'] ?? $a['value'] ?? '',
                        $response->actions,
                    );
                    $this->line('Actions: '.implode(', ', $actions));
                }

                $this->newLine();
            } catch (\Exception $e) {
                $this->error("Error: {$e->getMessage()}");
            }
        }

        $this->info('Session ended.');

        return 0;
    }
}
