<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use Illuminate\Support\Facades\DB;

$normalizer = new ArabicTextNormalizer;

$testCategories = ['الخدمات المالية', 'الخدمات الاجتماعية', 'الخدمات التخطيطية'];

foreach ($testCategories as $category) {
    $sessionId = 'trace-cat-'.str_replace(' ', '-', $category).'-'.time().'-'.uniqid();

    $action = app(ProcessRuleBasedChatMessageAction::class);

    // Turn 1: بدي خدمة
    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $action->execute($incoming1);

    // Get stored clarification options
    $conv = DB::table('chatbot_conversations')->where('session_id', $sessionId)->first();
    $meta = json_decode($conv->metadata ?? '{}', true);

    echo "\n=== Testing: $category ===\n";
    echo "Normalized input: '".$normalizer->normalize($category)."'\n";

    // Find the matching option
    $found = false;
    foreach ($meta['clarification_options'] as $opt) {
        $normLabel = $opt['normalized_label'] ?? $normalizer->normalize($opt['name'] ?? '');
        $matching = $normalizer->normalize($category);
        if ($matching === $normLabel) {
            echo 'EXACT MATCH FOUND: option name='.$opt['name']." normalized_label=$normLabel entity_id=".$opt['entity_id']."\n";
            $found = true;
            break;
        }
    }
    if (! $found) {
        echo "NO EXACT MATCH! Checking all options:\n";
        foreach ($meta['clarification_options'] as $opt) {
            echo '  name='.$opt['name'].' normalized_label='.($opt['normalized_label'] ?? 'NULL')."\n";
        }
    }

    // Turn 2: type the category name
    $inputNormalized = $normalizer->normalize($category);
    $incoming2 = new IncomingChatMessageData(
        message: $category,
        sessionId: $sessionId,
    );

    $response2 = $action->execute($incoming2);
    echo 'Response: type='.$response2->type.' message='.substr($response2->message, 0, 100)."\n";
    echo 'Actions count: '.count($response2->actions)."\n";

    if ($response2->type === 'text' && str_contains($response2->message, 'ما لقيت')) {
        echo "*** FAILURE: Category not found! ***\n";
    } elseif (str_contains($response2->message, 'الخدمات في تصنيف')) {
        echo "*** SUCCESS: Category resolved! ***\n";
    }
}
