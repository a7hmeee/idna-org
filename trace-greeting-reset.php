<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Support\Facades\DB;

app('cache')->flush();

$sessionId = 'greeting-reset-trace-'.time();
$action = app(ProcessRuleBasedChatMessageAction::class);

// Turn 1: تقديم شكوى (start complaint)
$incoming1 = new IncomingChatMessageData(message: 'تقديم شكوى', sessionId: $sessionId);
$response1 = $action->execute($incoming1);
echo "Turn 1 (تقديم شكوى):\n";
echo '  type: '.$response1->type."\n";
echo '  msg: '.substr($response1->message, 0, 80)."\n";

$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  State: state='.($meta['state'] ?? 'NULL').' domain='.($meta['current_domain'] ?? 'NULL').' workflow='.($meta['workflow_type'] ?? 'NULL').' pending='.($meta['pending_field'] ?? 'NULL')."\n";

// Turn 2: مرحبا (greeting to reset)
$incoming2 = new IncomingChatMessageData(message: 'مرحبا', sessionId: $sessionId);
$response2 = $action->execute($incoming2);
echo "\nTurn 2 (مرحبا):\n";
echo '  type: '.$response2->type."\n";
echo '  msg: '.substr($response2->message, 0, 80)."\n";
echo '  actions count: '.count($response2->actions)."\n";
echo '  clarificationType: '.($response2->clarificationType ?? 'NULL')."\n";

$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  State: state='.($meta['state'] ?? 'NULL').' domain='.($meta['current_domain'] ?? 'NULL').' workflow='.($meta['workflow_type'] ?? 'NULL').' pending='.($meta['pending_field'] ?? 'NULL').' opts='.count($meta['clarification_options'] ?? [])."\n";

// Turn 3: 3 (should start complaint again)
$incoming3 = new IncomingChatMessageData(message: '3', sessionId: $sessionId);
$response3 = $action->execute($incoming3);
echo "\nTurn 3 (3):\n";
echo '  type: '.$response3->type."\n";
echo '  msg: '.substr($response3->message, 0, 80)."\n";
echo '  workflow: '.json_encode($response3->workflow ?? null, JSON_UNESCAPED_UNICODE)."\n";

// Turn 3b: 8 (should start jobs)
$sessionId2 = 'greeting-reset-trace2-'.time();
$action2 = app(ProcessRuleBasedChatMessageAction::class);
$incoming1b = new IncomingChatMessageData(message: 'تقديم شكوى', sessionId: $sessionId2);
$action2->execute($incoming1b);
$incoming2b = new IncomingChatMessageData(message: 'مرحبا', sessionId: $sessionId2);
$response2b = $action2->execute($incoming2b);
echo "\n--- Greeting reset then 8 (jobs) ---\n";
echo 'Turn 2 (مرحبا): actions='.count($response2b->actions)."\n";
$incoming3b = new IncomingChatMessageData(message: '8', sessionId: $sessionId2);
$response3b = $action2->execute($incoming3b);
echo 'Turn 3 (8): type='.$response3b->type.' msg='.substr($response3b->message, 0, 80)."\n";

// Turn 3c: 6 (should start water)
$sessionId3 = 'greeting-reset-trace3-'.time();
$action3 = app(ProcessRuleBasedChatMessageAction::class);
$incoming1c = new IncomingChatMessageData(message: 'تقديم شكوى', sessionId: $sessionId3);
$action3->execute($incoming1c);
$incoming2c = new IncomingChatMessageData(message: 'مرحبا', sessionId: $sessionId3);
$action3->execute($incoming2c);
echo "\n--- Greeting reset then 6 (water) ---\n";
$incoming3c = new IncomingChatMessageData(message: '6', sessionId: $sessionId3);
$response3c = $action3->execute($incoming3c);
echo 'Turn 3 (6): type='.$response3c->type.' msg='.substr($response3c->message, 0, 80)."\n";
