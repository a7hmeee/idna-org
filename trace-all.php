<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Support\Facades\DB;

echo "=== MAIN MENU NUMERIC TEST ===\n";
echo "Test: مرحبا → then each number 1-11\n\n";

for ($num = 1; $num <= 11; $num++) {
    $sessionId = 'menu-test-'.$num.'-'.time().'-'.uniqid();

    $action = app(ProcessRuleBasedChatMessageAction::class);

    // Turn 1: مرحبا
    $incoming1 = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: $sessionId,
    );
    $response1 = $action->execute($incoming1);

    // Check state after greeting
    $conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
    $meta = json_decode($conv->metadata ?? '{}', true);

    // Check main menu options are persisted
    $optionsCount = count($meta['clarification_options'] ?? []);
    $pendingField = $meta['pending_field'] ?? 'NULL';
    $state = $meta['state'] ?? 'NULL';

    // Turn 2: numeric input
    $incoming2 = new IncomingChatMessageData(
        message: (string) $num,
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);

    $responseText = substr($response2->message, 0, 80);
    $responseType = $response2->type;

    echo "  $num → state=$state, pending=$pendingField, opts=$optionsCount | response_type=$responseType | msg=$responseText\n";
}

echo "\n=== GREETING RESET TEST ===\n";
$sessionId = 'greeting-reset-test-'.time();

// Turn 1: تقديم شكوى (start complaint)
$action = app(ProcessRuleBasedChatMessageAction::class);
$incoming1 = new IncomingChatMessageData(message: 'تقديم شكوى', sessionId: $sessionId);
$response1 = $action->execute($incoming1);
echo 'Turn 1 (تقديم شكوى): type='.$response1->type.' msg='.substr($response1->message, 0, 80)."\n";

// Check state
$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  State after turn 1: state='.($meta['state'] ?? 'NULL').' domain='.($meta['current_domain'] ?? 'NULL').' workflow='.($meta['workflow_type'] ?? 'NULL')."\n";

// Turn 2: مرحبا (greeting to reset)
$incoming2 = new IncomingChatMessageData(message: 'مرحبا', sessionId: $sessionId);
$response2 = $action->execute($incoming2);
echo 'Turn 2 (مرحبا): type='.$response2->type.' msg='.substr($response2->message, 0, 80)."\n";

// Check state after reset
$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  State after turn 2: state='.($meta['state'] ?? 'NULL').' domain='.($meta['current_domain'] ?? 'NULL').' pending='.($meta['pending_field'] ?? 'NULL').' opts='.count($meta['clarification_options'] ?? [])."\n";

// Turn 3: 3 (should start complaint)
$incoming3 = new IncomingChatMessageData(message: '3', sessionId: $sessionId);
$response3 = $action->execute($incoming3);
echo 'Turn 3 (3): type='.$response3->type.' msg='.substr($response3->message, 0, 80)."\n";

echo "\n=== WATER SCHEDULE TEST ===\n";
$sessionId = 'water-test-'.time();

// Turn 1: جدول توزيع المياه
$action = app(ProcessRuleBasedChatMessageAction::class);
$incoming1 = new IncomingChatMessageData(message: 'جدول توزيع المياه', sessionId: $sessionId);
$response1 = $action->execute($incoming1);
echo 'Turn 1 (جدول توزيع المياه): type='.$response1->type.' msg='.substr($response1->message, 0, 80)."\n";
echo '  clarificationType: '.($response1->clarificationType ?? 'NULL')."\n";
echo '  Actions count: '.count($response1->actions)."\n";

// Check state
$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  State: state='.($meta['state'] ?? 'NULL').' pending='.($meta['pending_field'] ?? 'NULL')."\n";
echo '  Options count: '.count($meta['clarification_options'] ?? [])."\n";
if (! empty($meta['clarification_options'])) {
    foreach ($meta['clarification_options'] as $i => $opt) {
        echo "    Option $i: name=".($opt['name'] ?? '???').' normalized_label='.($opt['normalized_label'] ?? 'NULL')."\n";
    }
}

// Turn 2: 1 (numeric)
$incoming2 = new IncomingChatMessageData(message: '1', sessionId: $sessionId);
$response2 = $action->execute($incoming2);
echo "\nTurn 2 (1): type=".$response2->type.' msg='.substr($response2->message, 0, 80)."\n";

// Turn 2b: حي البلد (text)
$sessionId2 = 'water-test2-'.time();
$action2 = app(ProcessRuleBasedChatMessageAction::class);
$incoming1b = new IncomingChatMessageData(message: 'جدول توزيع المياه', sessionId: $sessionId2);
$action2->execute($incoming1b);
$incoming2b = new IncomingChatMessageData(message: 'حي البلد', sessionId: $sessionId2);
$response2b = $action2->execute($incoming2b);
echo "\nTurn 2 (حي البلد): type=".$response2b->type.' msg='.substr($response2b->message, 0, 80)."\n";

echo "\n=== CONTACT DATA TEST ===\n";
$sessionId = 'contact-test-'.time();
$action = app(ProcessRuleBasedChatMessageAction::class);

// Turn 1: مرحبا then 11
$incoming1 = new IncomingChatMessageData(message: 'مرحبا', sessionId: $sessionId);
$action->execute($incoming1);
$incoming2 = new IncomingChatMessageData(message: '11', sessionId: $sessionId);
$response2 = $action->execute($incoming2);
echo 'Contact response type: '.$response2->type."\n";
echo 'Contact response message: '.substr($response2->message, 0, 100)."\n";
echo 'Items count: '.count($response2->items)."\n";
foreach ($response2->items as $item) {
    echo '  Item: type='.($item['type'] ?? 'NULL').' label='.($item['label'] ?? 'NULL').' value='.($item['value'] ?? 'NULL')."\n";
}
