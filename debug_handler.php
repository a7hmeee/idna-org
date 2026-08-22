<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Handlers\WaterScheduleHandler;

$handler = app(WaterScheduleHandler::class);

$msg = new IncomingChatMessageData(message: 'حي البلد', sessionId: 'debug-'.bin2hex(random_bytes(4)));

// Test directly
echo "=== Direct handler test ===\n";
$response = $handler->handle($msg, null);
echo 'type: '.$response->type."\n";
echo "message:\n".$response->message."\n";
echo 'items: '.json_encode($response->items, JSON_UNESCAPED_UNICODE)."\n";

echo "\n=== Via ProcessRuleBasedChatMessageAction ===\n";
$action = app('App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction');
$context = app('App\Domains\Chatbot\Contracts\ConversationContextInterface');
$s = 'handler-test-'.bin2hex(random_bytes(4));

// Turn 1: water menu
$r1 = $action->execute(new IncomingChatMessageData(message: 'جدول توزيع المياه', sessionId: $s));
echo 'Turn 1 BOT: '.substr($r1->message, 0, 80)."\n";
echo 'Turn 1 type: '.$r1->type."\n";

// Turn 2: area
$r2 = $action->execute(new IncomingChatMessageData(message: 'حي البلد', sessionId: $s));
echo 'Turn 2 BOT: '.$r2->message."\n";
echo 'Turn 2 type: '.$r2->type."\n";
echo 'Turn 2 items: '.json_encode($r2->items, JSON_UNESCAPED_UNICODE)."\n";

echo "\n=== DONE ===\n";
