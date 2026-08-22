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

// Clear cache for fresh trace
DB::table('chatbot_conversations')->where('session_id', 'like', 'trace-%')->delete();

$action = app(ProcessRuleBasedChatMessageAction::class);
$context = app(ConversationContextInterface::class);
$normalizer = app(ArabicTextNormalizer::class);

$traceLines = [];
$turnCounter = 0;
$globalConversationId = 0;

function logTurn(array &$traceLines, int &$turnCounter, string $session, string $rawText, $normalizer, $context, $botResponse = null, ?Throwable $ex = null): void
{
    $turnCounter++;
    $state = $context->getState($session);
    $conv = DB::table('chatbot_conversations')->where('session_id', $session)->first();
    $conversationId = $conv ? $conv->id : 'NEW';
    if ($conv) {
        $GLOBALS['globalConversationId'] = $conv->id;
    }

    $options = [];
    foreach ($state->clarificationOptions as $i => $opt) {
        $options[] = [
            'position' => $i + 1,
            'label' => $opt['label'] ?? ($opt['name'] ?? null),
            'normalized_label' => $opt['normalized_label'] ?? null,
            'key' => $opt['key'] ?? null,
            'entity_type' => $opt['entity_type'] ?? null,
            'entity_id' => $opt['entity_id'] ?? ($opt['id'] ?? null),
        ];
    }

    $traceLines[] = "TURN {$turnCounter}";
    $traceLines[] = "  session_id: {$session}";
    $traceLines[] = "  conversation_id: {$conversationId}";
    $traceLines[] = "  raw_user_text: {$rawText}";
    $traceLines[] = '  normalized_user_text: '.$normalizer->normalize($rawText);
    $traceLines[] = "  stored_state: {$state->state->value}";
    $traceLines[] = '  stored_clarification_type: '.($state->pendingField ?? 'NULL');
    $traceLines[] = '  stored_needs_clarification: '.($state->needsClarification ? 'true' : 'false');
    $traceLines[] = '  stored_options_count: '.count($state->clarificationOptions);
    $traceLines[] = '  stored_options: '.json_encode($options, JSON_UNESCAPED_UNICODE);
    $traceLines[] = '  current_category_id: '.var_export($state->currentCategoryId, true);
    $traceLines[] = '  current_service_id: '.var_export($state->currentServiceId, true);
    if ($ex !== null) {
        $traceLines[] = '  EXCEPTION: '.$ex::class.': '.$ex->getMessage();
        $traceLines[] = '  AT: '.$ex->getFile().':'.$ex->getLine();
        $traceLines[] = '  fallback_reached: YES';
    } else {
        $traceLines[] = "  bot_response_type: {$botResponse->type}";
        $traceLines[] = '  bot_response_message: '.substr($botResponse->message, 0, 200);
        $traceLines[] = '  bot_actions_count: '.count($botResponse->actions ?? []);
        $traceLines[] = '  bot_items_count: '.count($botResponse->items ?? []);
        $traceLines[] = '  resolved_method: (see bot response)';
        $traceLines[] = '  fallback_reached: '.($botResponse->type === 'empty_state' || $botResponse->type === 'text' && str_contains($botResponse->message, 'ما لقيت') ? 'YES' : 'NO');
    }
    $traceLines[] = '';
}

function sendAndTrace(ProcessRuleBasedChatMessageAction $action, string $session, string $message, $normalizer, $context, array &$traceLines, int &$turnCounter): ?string
{
    $traceLines[] = ">>> USER (session={$session}): {$message}";
    $incoming = new IncomingChatMessageData(message: $message, sessionId: $session);
    $botResponse = null;
    try {
        $botResponse = $action->execute($incoming);
    } catch (Throwable $e) {
        logTurn($traceLines, $turnCounter, $session, $message, $normalizer, $context, null, $e);

        return null;
    }
    logTurn($traceLines, $turnCounter, $session, $message, $normalizer, $context, $botResponse);

    return $botResponse->message;
}

// ============ DB COUNTS ============
$traceLines[] = '=== DATABASE COUNTS (verified live) ===';
$traceLines[] = '  service_categories: '.DB::table('service_categories')->where('is_public', true)->where('status', 'active')->count();
$traceLines[] = '  electronic_services (active+public): '.DB::table('electronic_services')->where('status', 'active')->where('is_public', true)->count();
$traceLines[] = '  service_search_terms (active): '.DB::table('service_search_terms')->where('is_active', true)->count();
$traceLines[] = '  water_areas (active): '.DB::table('water_areas')->where('is_active', true)->count();
$traceLines[] = '  water_schedules (public): '.DB::table('water_schedules')->where('is_public', true)->count();
$traceLines[] = '  public_facilities: '.(DB::getSchemaBuilder()->hasTable('public_facilities') ? DB::table('public_facilities')->count() : 'N/A');
$traceLines[] = '  jobs: '.(DB::getSchemaBuilder()->hasTable('jobs') ? DB::table('jobs')->count() : 'N/A');
$traceLines[] = '  council_members: '.(DB::getSchemaBuilder()->hasTable('council_members') ? DB::table('council_members')->count() : 'N/A');
$traceLines[] = '  council_decisions: '.(DB::getSchemaBuilder()->hasTable('council_decisions') ? DB::table('council_decisions')->count() : 'N/A');
$traceLines[] = '  municipality_contacts: '.(DB::getSchemaBuilder()->hasTable('municipality_contacts') ? DB::table('municipality_contacts')->count() : 'N/A');
$traceLines[] = '';

// ============ ACCEPTANCE FLOW A ============
$traceLines[] = '=== ACCEPTANCE FLOW A: بدي خدمة → الشؤون الإدارية ===';
$traceLines[] = '  Expected: category resolution success, show services for category 2 (5 services)';
$traceLines[] = "  PASS if: no 'ما لقيت هذا التصنيف'";
$traceLines[] = '';
$sessionA = 'trace-A-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionA, 'بدي خدمة', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionA, 'الشؤون الإدارية', $normalizer, $context, $traceLines, $turnCounter);

// ============ ACCEPTANCE FLOW B ============
$traceLines[] = '=== ACCEPTANCE FLOW B: بدي خدمة → 2 (numeric) ===';
$traceLines[] = '  Expected: category 2 (الشؤون الإدارية) selected by number';
$traceLines[] = '';
$sessionB = 'trace-B-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionB, 'بدي خدمة', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionB, '2', $normalizer, $context, $traceLines, $turnCounter);

// ============ ACCEPTANCE FLOW C ============
$traceLines[] = '=== ACCEPTANCE FLOW C: جدول توزيع المياه → حي البلد ===';
$traceLines[] = '  Expected: water schedule shown (falls back to latest if no today schedule)';
$traceLines[] = '  PASS if: shows schedule data from DB (date 2026-08-11, 08:00-16:00)';
$traceLines[] = '';
$sessionC = 'trace-C-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionC, 'جدول توزيع المياه', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionC, 'حي البلد', $normalizer, $context, $traceLines, $turnCounter);

// ============ ACCEPTANCE FLOW D ============
$traceLines[] = '=== ACCEPTANCE FLOW D: جدول توزيع المياه → 1 (numeric) ===';
$traceLines[] = '  Expected: same result as حي البلد';
$traceLines[] = '';
$sessionD = 'trace-D-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionD, 'جدول توزيع المياه', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionD, '1', $normalizer, $context, $traceLines, $turnCounter);

// ============ ACCEPTANCE FLOW E ============
$traceLines[] = '=== ACCEPTANCE FLOW E: gpk → 3 ===';
$traceLines[] = '  Expected: gpk → main menu, 3 = تقديم شكوى (complaint workflow)';
$traceLines[] = '';
$sessionE = 'trace-E-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionE, 'gpk', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionE, '3', $normalizer, $context, $traceLines, $turnCounter);

// ============ ACCEPTANCE FLOW F ============
$traceLines[] = '=== ACCEPTANCE FLOW F: مرحبا → 6 ===';
$traceLines[] = '  Expected: 6 = جدول توزيع المياه → water areas';
$traceLines[] = '';
$sessionF = 'trace-F-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionF, 'مرحبا', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionF, '6', $normalizer, $context, $traceLines, $turnCounter);

// ============ ADDITIONAL CATEGORY TESTS ============
$traceLines[] = "\n=== ADDITIONAL CATEGORY TESTS (all via برنامج بدي خدمة) ===";
$categories = [
    'الشؤون الإدارية' => 2,
    'الخدمات المالية' => 5,
    'الخدمات الاجتماعية' => 6,
    'الخدمات التخطيطية' => 8,
    'الخدمات الإلكترونية' => 9,
    'رخص البناء' => 1,
    'الشؤون القانونية' => 3,
    'الخدمات الصحية' => 4,
    'الخدمات البيئية' => 7,
];
foreach ($categories as $label => $expectedId) {
    $traceLines[] = "--- Category: {$label} (expected id={$expectedId}) ---";
    $session = 'trace-cat-'.bin2hex(random_bytes(4));
    sendAndTrace($action, $session, 'بدي خدمة', $normalizer, $context, $traceLines, $turnCounter);
    sendAndTrace($action, $session, $label, $normalizer, $context, $traceLines, $turnCounter);
}

// ============ ARABIC DIGITS TEST ============
$traceLines[] = "\n=== ARABIC-INDIC DIGITS: مرحبا → ٦ (Arabic-Indic 6) ===";
$sessionG = 'trace-G-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionG, 'مرحبا', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionG, '٦', $normalizer, $context, $traceLines, $turnCounter);

// ============ WATER AREA BY NUMBER ============
$traceLines[] = "\n=== WATER AREA BY NUMBER: جدول توزيع المياه → 1 ===";
$sessionH = 'trace-H-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionH, 'جدول توزيع المياه', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionH, '1', $normalizer, $context, $traceLines, $turnCounter);

// ============ REPEATED INVALID INPUT ============
$traceLines[] = "\n=== REPEATED INVALID INPUT: مرحبا → invalid → invalid ===";
$sessionI = 'trace-I-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionI, 'مرحبا', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionI, 'xyz123', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionI, 'xyz123', $normalizer, $context, $traceLines, $turnCounter);

// ============ SERVICE DETAILS AFTER CATEGORY ============
$traceLines[] = "\n=== SERVICE SELECTION: بدي خدمة → الشؤون الإدارية → طلب صرف مكافأة نهاية الخدمة ===";
$sessionJ = 'trace-J-'.bin2hex(random_bytes(4));
sendAndTrace($action, $sessionJ, 'بدي خدمة', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionJ, 'الشؤون الإدارية', $normalizer, $context, $traceLines, $turnCounter);
sendAndTrace($action, $sessionJ, 'طلب صرف مكافأة نهاية الخدمة', $normalizer, $context, $traceLines, $turnCounter);

file_put_contents(__DIR__.'/trace.txt', implode("\n", $traceLines));
echo 'trace.txt written ('.count($traceLines)." lines)\n";
echo "DONE\n";
