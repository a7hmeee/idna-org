<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Support\Facades\DB;

echo "=== DETAILED MAIN MENU NUMERIC TEST ===\n\n";

for ($num = 1; $num <= 11; $num++) {
    $sessionId = 'menu-detail-'.$num.'-'.time().'-'.uniqid();

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

    echo "Num $num: After greeting - state=".($meta['state'] ?? 'NULL').' pending='.($meta['pending_field'] ?? 'NULL').' opts='.count($meta['clarification_options'] ?? [])."\n";

    // Check clarification_options format
    if (! empty($meta['clarification_options'])) {
        $firstOpt = $meta['clarification_options'][0] ?? [];
        echo '  First option keys: '.implode(', ', array_keys($firstOpt))."\n";
        echo '  First option: '.json_encode($firstOpt, JSON_UNESCAPED_UNICODE)."\n";
    }

    // Turn 2: numeric input
    $incoming2 = new IncomingChatMessageData(
        message: (string) $num,
        sessionId: $sessionId,
    );
    $response2 = $action->execute($incoming2);

    $responseText = substr($response2->message, 0, 100);
    $responseType = $response2->type;

    echo "  Response: type=$responseType msg=$responseText\n";
    echo '  Actions: '.count($response2->actions)."\n";
}
