<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class ChatbotDebugTurnCommand extends Command
{
    protected $signature = 'chatbot:debug-turn
        {--session= : Session ID to use (default: auto-generated)}
        {--message= : Message to send (required)}';

    protected $description = 'Debug a single chatbot turn with full state tracing';

    public function handle(ProcessRuleBasedChatMessageAction $action): int
    {
        $message = $this->option('message');

        if ($message === null || trim($message) === '') {
            $this->error('--message is required.');

            return self::FAILURE;
        }

        $sessionId = $this->option('session') ?: 'debug-'.uniqid();

        $this->line("<fg=cyan>Session:</> {$sessionId}");
        $this->line("<fg=cyan>Input:</> {$message}");
        $this->newLine();

        $conversation = DB::table('chatbot_conversations')
            ->where('session_id', $sessionId)
            ->orderByDesc('id')
            ->first();

        if ($conversation) {
            $this->line("<fg=yellow>Existing conversation:</> ID {$conversation->id}, status={$conversation->status}");
        } else {
            $this->line('<fg=green>New conversation</>');
        }

        $incoming = new IncomingChatMessageData(
            message: $message,
            sessionId: $sessionId,
        );

        try {
            $response = $action->execute($incoming);
        } catch (\Throwable $e) {
            $this->error("Exception: {$e->getMessage()}");
            $this->line($e->getTraceAsString());

            return self::FAILURE;
        }

        $newConversation = DB::table('chatbot_conversations')
            ->where('session_id', $sessionId)
            ->orderByDesc('id')
            ->first();

        if ($newConversation) {
            $this->newLine();
            $this->line("<fg=cyan>Conversation after turn:</> ID {$newConversation->id}, status={$newConversation->status}");
            $this->line('Metadata: '.json_encode(json_decode($newConversation->metadata ?? '{}', true), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $newConversation->id ?? 0)
            ->orderByDesc('id')
            ->limit(4)
            ->get(['id', 'role', 'content', 'metadata']);

        $this->newLine();
        $this->line('<fg=cyan>Recent messages:</>');
        foreach ($messages as $msg) {
            $meta = json_decode($msg->metadata ?? '{}', true);
            $this->line("  [{$msg->id}] {$msg->role}: {$msg->content}");
            if (! empty($meta)) {
                $this->line('    metadata: '.json_encode($meta, JSON_UNESCAPED_UNICODE));
            }
        }

        $this->newLine();
        $this->line('<fg=green>Response:</>');
        $this->line('  type: '.$response->type);
        $this->line('  message: '.$response->message);
        if (! empty($response->actions)) {
            $this->line('  actions:');
            foreach ($response->actions as $actionItem) {
                $this->line('    - '.json_encode($actionItem, JSON_UNESCAPED_UNICODE));
            }
        }
        if (! empty($response->items)) {
            $this->line('  items: '.count($response->items).' items');
        }
        if (! empty($response->metadata)) {
            $this->line('  metadata: '.json_encode($response->metadata, JSON_UNESCAPED_UNICODE));
        }

        return self::SUCCESS;
    }
}
