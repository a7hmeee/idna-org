<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use Illuminate\Support\Facades\DB;

$app['config']->set('app.debug', true);
error_reporting(E_ALL);

$action = app(ProcessRuleBasedChatMessageAction::class);
$context = app(ConversationContextInterface::class);
$normalizer = app(ArabicTextNormalizer::class);

function dumpState(string $label, $state, $context): void
{
    echo "  STATE {$label}:\n";
    echo "    state={$state->state->value}\n";
    echo '    pending_field='.var_export($state->pendingField, true)."\n";
    echo '    current_domain='.var_export($state->currentDomain, true)."\n";
    echo '    current_service_id='.var_export($state->currentServiceId, true)."\n";
    echo '    current_category_id='.var_export($state->currentCategoryId, true)."\n";
    echo '    needs_clarification='.var_export($state->needsClarification, true)."\n";
    echo '    clarification_options_count='.count($state->clarificationOptions)."\n";
    foreach ($state->clarificationOptions as $i => $opt) {
        $norm = var_export($opt['normalized_label'] ?? null, true);
        echo '    ['.($i + 1).'] name='.($opt['name'] ?? 'NULL').' | label='.($opt['label'] ?? 'NULL').' | key='.($opt['key'] ?? 'NULL').' | norm_label='.$norm.' | entity_type='.($opt['entity_type'] ?? 'NULL').' | entity_id='.var_export($opt['entity_id'] ?? ($opt['id'] ?? null), true)."\n";
    }
    $conv = DB::table('chatbot_conversations')->where('session_id', $context ?? null)->first();
}

function send(ProcessRuleBasedChatMessageAction $action, string $session, string $message, $normalizer, $context): void
{
    echo "\n>>> USER: {$message}\n";
    echo '  normalized: '.$normalizer->normalize($message)."\n";
    $incoming = new IncomingChatMessageData(message: $message, sessionId: $session);
    try {
        $response = $action->execute($incoming);
        echo "  BOT type={$response->type}: ".substr($response->message, 0, 120)."\n";
        if (! empty($response->actions)) {
            echo '  actions: '.count($response->actions).' | first='.json_encode($response->actions[0] ?? [], JSON_UNESCAPED_UNICODE)."\n";
        }
        if (! empty($response->items)) {
            echo '  items: '.count($response->items)."\n";
            foreach (array_slice($response->items, 0, 3) as $item) {
                echo '    item: '.json_encode($item, JSON_UNESCAPED_UNICODE)."\n";
            }
        }
    } catch (Throwable $e) {
        echo '  EXCEPTION: '.$e::class.': '.$e->getMessage()."\n";
        echo '  AT: '.$e->getFile().':'.$e->getLine()."\n";
    }
}

echo "########## ACCEPTANCE FLOW A: بدي خدمة → الشؤون الإدارية ##########\n";
$session = 'trace-A-'.bin2hex(random_bytes(4));
echo "session_id={$session}\n";
dumpState('fresh', $context->getState($session), $session);
send($action, $session, 'بدي خدمة', $normalizer, $context);
dumpState('after turn 1', $context->getState($session), $session);
send($action, $session, 'الشؤون الإدارية', $normalizer, $context);
dumpState('after turn 2', $context->getState($session), $session);

echo "\n########## ACCEPTANCE FLOW C: جدول توزيع المياه → حي البلد ##########\n";
$session2 = 'trace-C-'.bin2hex(random_bytes(4));
send($action, $session2, 'جدول توزيع المياه', $normalizer, $context);
dumpState('after turn 1', $context->getState($session2), $session2);
send($action, $session2, 'حي البلد', $normalizer, $context);

echo "\n########## ACCEPTANCE FLOW F: مرحبا → 6 ##########\n";
$session3 = 'trace-F-'.bin2hex(random_bytes(4));
send($action, $session3, 'مرحبا', $normalizer, $context);
dumpState('after turn 1', $context->getState($session3), $session3);
send($action, $session3, '6', $normalizer, $context);

echo "\n########## ACCEPTANCE FLOW E: gpk / خدمات البلدية → 3 ##########\n";
$session4 = 'trace-E-'.bin2hex(random_bytes(4));
send($action, $session4, 'خدمات البلدية', $normalizer, $context);
dumpState('after turn 1', $context->getState($session4), $session4);
send($action, $session4, '3', $normalizer, $context);

echo "\n=== DONE ===\n";
