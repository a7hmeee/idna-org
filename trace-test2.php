<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Support\Facades\DB;

// Clear cache
app('cache')->flush();

$sessionId = 'test-numeric-cat-'.time();

$action = app(ProcessRuleBasedChatMessageAction::class);

// Turn 1: بدي خدمة
$incoming1 = new IncomingChatMessageData(message: 'بدي خدمة', sessionId: $sessionId);
$response1 = $action->execute($incoming1);
echo 'Turn 1 (بدي خدمة): type='.$response1->type.' msg='.substr($response1->message, 0, 80)."\n";

// Check state
$conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
$meta = json_decode($conv->metadata ?? '{}', true);
echo '  State: state='.($meta['state'] ?? 'NULL').' pending='.($meta['pending_field'] ?? 'NULL')."\n";
echo '  Options count: '.count($meta['clarification_options'] ?? [])."\n";
if (! empty($meta['clarification_options'])) {
    foreach ($meta['clarification_options'] as $i => $opt) {
        echo "    Opt $i: id=".($opt['id'] ?? 'NULL').' entity_id='.($opt['entity_id'] ?? 'NULL').' name='.($opt['name'] ?? 'NULL').' label='.($opt['label'] ?? 'NULL').' position='.($opt['position'] ?? 'NULL').' entity_type='.($opt['entity_type'] ?? 'NULL')."\n";
    }
}

// Turn 2: 2 (numeric)
$incoming2 = new IncomingChatMessageData(message: '2', sessionId: $sessionId);
$response2 = $action->execute($incoming2);
echo "\nTurn 2 (2): type=".$response2->type.' msg='.substr($response2->message, 0, 100)."\n";
echo 'Actions: '.count($response2->actions)."\n";
if (! empty($response2->actions)) {
    foreach ($response2->actions as $i => $act) {
        echo "  Action $i: label=".($act['label'] ?? '???')."\n";
    }
}
