<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Support\Facades\DB;

$sessionId = 'trace-test-'.time();

$action = app(ProcessRuleBasedChatMessageAction::class);

// Turn 1: بدي خدمة
$incoming1 = new IncomingChatMessageData(
    message: 'بدي خدمة',
    sessionId: $sessionId,
);

echo "=== TURN 1: بدي خدمة ===\n";
$response1 = $action->execute($incoming1);
echo 'Response type: '.$response1->type."\n";
echo 'Response message: '.$response1->message."\n";
echo 'Actions count: '.count($response1->actions)."\n";
foreach ($response1->actions as $i => $act) {
    echo "  Action $i: label=".($act['label'] ?? $act['value'] ?? '???').' value='.($act['value'] ?? '???')."\n";
}

// Check state after turn 1
$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
echo "\nConversation metadata after turn 1:\n";
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  state: '.($meta['state'] ?? 'NULL')."\n";
echo '  pending_field: '.($meta['pending_field'] ?? 'NULL')."\n";
echo '  needs_clarification: '.($meta['needs_clarification'] ?? 'NULL')."\n";
echo '  clarification_options count: '.count($meta['clarification_options'] ?? [])."\n";
if (! empty($meta['clarification_options'])) {
    foreach ($meta['clarification_options'] as $i => $opt) {
        echo "  Option $i: name=".($opt['name'] ?? '???').' normalized_label='.($opt['normalized_label'] ?? 'NULL').' entity_id='.($opt['entity_id'] ?? 'NULL').' entity_type='.($opt['entity_type'] ?? 'NULL')."\n";
    }
}

// Turn 2: الشؤون الإدارية
$incoming2 = new IncomingChatMessageData(
    message: 'الشؤون الإدارية',
    sessionId: $sessionId,
);

echo "\n=== TURN 2: الشؤون الإدارية ===\n";
$response2 = $action->execute($incoming2);
echo 'Response type: '.$response2->type."\n";
echo 'Response message: '.$response2->message."\n";
echo 'Actions count: '.count($response2->actions)."\n";
if (! empty($response2->actions)) {
    foreach ($response2->actions as $i => $act) {
        echo "  Action $i: label=".($act['label'] ?? '???')."\n";
    }
}

// Check state after turn 2
$conv2 = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
echo "\nConversation metadata after turn 2:\n";
$meta2 = json_decode($conv2->metadata ?? '{}', true);
echo '  state: '.($meta2['state'] ?? 'NULL')."\n";
echo '  pending_field: '.($meta2['pending_field'] ?? 'NULL')."\n";
echo '  last_intent: '.($meta2['last_intent'] ?? 'NULL')."\n";
echo '  current_category_id: '.($meta2['current_category_id'] ?? 'NULL')."\n";

// Check messages
$messages = DB::table('chatbot_messages')->where('conversation_id', $conv2->id)->orderBy('id')->get(['role', 'content']);
echo "\nMessages:\n";
foreach ($messages as $msg) {
    echo "  {$msg->role}: ".substr($msg->content, 0, 80)."\n";
}
