<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Illuminate\Support\Facades\Cache;

// Clear all cache
Cache::clear();

$sessionId = 'contact-test-'.time();
$action = app(ProcessRuleBasedChatMessageAction::class);

// Turn 1: مرحبا
$incoming1 = new IncomingChatMessageData(message: 'مرحبا', sessionId: $sessionId);
$action->execute($incoming1);

// Turn 2: 11 (municipality contact)
$incoming2 = new IncomingChatMessageData(message: '11', sessionId: $sessionId);
$response2 = $action->execute($incoming2);

echo "Contact response:\n";
echo '  type: '.$response2->type."\n";
echo '  message: '.$response2->message."\n";
echo '  items count: '.count($response2->items)."\n";

foreach ($response2->items as $item) {
    echo '  Item: type='.($item['type'] ?? 'NULL').' label='.($item['label'] ?? 'NULL').' value='.($item['value'] ?? 'NULL').' url='.($item['url'] ?? 'NULL')."\n";
}

// Check if any demo values leaked through
echo "\nChecking for demo values in output...\n";
$allText = $response2->message.' '.json_encode($response2->items);
if (str_contains($allText, '123456') || str_contains($allText, '123457') || str_contains($allText, 'info@idhna.ps') || str_contains($allText, 'support@idhna.ps')) {
    echo "*** FAILURE: Demo values found in output! ***\n";
} else {
    echo "No demo values found.\n";
}

if (empty($response2->items)) {
    echo "*** PASS: All demo contacts filtered out ***\n";
} else {
    echo "Items remain after filtering.\n";
}
