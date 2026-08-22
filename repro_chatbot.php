<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;

$action = app(ProcessRuleBasedChatMessageAction::class);
$context = app(ConversationContextInterface::class);
$session = 'repro-'.bin2hex(random_bytes(4));

function dumpState(string $label, $state): void
{
    echo "=== {$label} ===\n";
    echo "state: {$state->state->value}\n";
    echo 'pending_field: '.var_export($state->pendingField, true)."\n";
    echo 'current_domain: '.var_export($state->currentDomain, true)."\n";
    echo 'current_service_id: '.var_export($state->currentServiceId, true)."\n";
    echo 'clarification_options: '.count($state->clarificationOptions)."\n";
    foreach ($state->clarificationOptions as $i => $opt) {
        echo '  ['.($i + 1)."] name={$opt['name']} key={$opt['key']} norm={$opt['normalized_label']}\n";
    }
}

function send(ProcessRuleBasedChatMessageAction $action, string $session, string $message): void
{
    echo "\n>>> USER: {$message}\n";
    $incoming = new IncomingChatMessageData(message: $message, sessionId: $session);
    try {
        $response = $action->execute($incoming);
        echo "BOT: {$response->message}\n";
    } catch (Throwable $e) {
        echo 'EXCEPTION: '.$e::class.': '.$e->getMessage()."\n";
        echo 'AT: '.$e->getFile().':'.$e->getLine()."\n";
    }
}

dumpState('TURN 0 (fresh)', $context->getState($session));
send($action, $session, 'خدمات البلدية');
dumpState('AFTER TURN 1', $context->getState($session));
send($action, $session, 'الشؤون الإدارية');
dumpState('AFTER TURN 2', $context->getState($session));
send($action, $session, '2');
dumpState('AFTER TURN 3', $context->getState($session));
