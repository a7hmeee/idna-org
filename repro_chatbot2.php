<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;

$action = app(ProcessRuleBasedChatMessageAction::class);
$context = app(ConversationContextInterface::class);

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

echo "########## SCENARIO A: الخدمات الإلكترونية -> الشؤون الإدارية ##########\n";
$s = 'repro-A-'.bin2hex(random_bytes(4));
send($action, $s, 'الخدمات الإلكترونية');
dumpState('AFTER TURN 1', $context->getState($s));
send($action, $s, 'الشؤون الإدارية');
dumpState('AFTER TURN 2', $context->getState($s));

echo "\n########## SCENARIO B: main menu -> numeric ##########\n";
$s2 = 'repro-B-'.bin2hex(random_bytes(4));
send($action, $s2, 'مرحبا');
dumpState('AFTER TURN 1', $context->getState($s2));
send($action, $s2, '2');
dumpState('AFTER TURN 2', $context->getState($s2));

echo "\n########## SCENARIO C: رقم 3 (Arabic) ##########\n";
$s3 = 'repro-C-'.bin2hex(random_bytes(4));
send($action, $s3, 'مرحبا');
dumpState('AFTER TURN 1', $context->getState($s3));
send($action, $s3, '٣');
dumpState('AFTER TURN 2', $context->getState($s3));
