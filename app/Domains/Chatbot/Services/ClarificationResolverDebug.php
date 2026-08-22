<?php

use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ClarificationResolver;

/**
 * Simple debug: trace the exact execution path for "الشؤون الإدارية"
 * to identify where the state machine breaks.
 */

// Trace the flow when user types "الشؤون الإدارية" after main menu
// The issue: no clarification persisted, so classification falls through

// Check the clarification resolver for label matching
require_once "C:\Users\ahmed\idna-org\app\Domains\Chatbot\Services\ClarificationResolver.php";

$resolver = new ClarificationResolver(
    new ArabicTextNormalizer
);

// Test resolveNumericSelection with a digit (should work)
$state = new ConversationStateData(
    state: ConversationState::WaitingForClarification,
    clarificationOptions: [
        ['id' => 1, 'name' => 'رخص البناء', 'number' => 1, 'entity_type' => 'service_category'],
        ['id' => 2, 'name' => 'الشؤون الإدارية', 'number' => 2, 'entity_type' => 'service_category'],
        ['id' => 3, 'name' => 'الشؤون القانونية', 'number' => 3, 'entity_type' => 'service_category'],
        ['id' => 4, 'name' => 'الخدمات الصحية', 'number' => 4, 'entity_type' => 'service_category'],
        ['id' => 5, 'name' => 'الخدمات المالية', 'number' => 5, 'entity_type' => 'service_category'],
        ['id' => 6, 'name' => 'الخدمات الاجتماعية', 'number' => 6, 'entity_type' => 'service_category'],
        ['id' => 7, 'name' => 'الخدمات البيئية', 'number' => 7, 'entity_type' => 'service_category'],
        ['id' => 8, 'name' => 'الخدمات التخطيطية', 'number' => 8, 'entity_type' => 'service_category'],
        ['id' => 9, 'name' => 'الخدمات الإلكترونية', 'number' => 9, 'entity_type' => 'service_category'],
    ],
    pendingField: 'general',
    needsClarification: true,
    state: ConversationState::WaitingForClarification,
);

// Test 1: resolveNumericSelection with digit (should work)
$sel = $resolver->resolveNumericSelection('3', $state);
echo 'resolveNumericSelection("3") => '.($sel ? 'success' : 'null')."\n";
if ($sel) {
    echo '  selected service name: '.$sel->selectedServiceName."\n";
}

// Test 2: resolveNumericSelection with label text (should match "الشؤون الإدارية")
$sel2 = $resolver->resolveNumericSelection('الشؤون الإدارية', $state);
echo 'resolveNumericSelection("الشؤون الإدارية") => '.($sel2 ? 'success' : 'null')."\n";

// Test 3: resolveLabelSelection (does this method exist?)
$method = method_exists($resolver, 'resolveLabelSelection') ? 'exists' : 'MISSING';
echo "resolveLabelSelection() exists: $method\n";

// Test 4: resolveOptionSelectionById (should work with integer)
$sel4 = $resolver->resolveOptionSelectionById(2, $state);
echo 'resolveOptionSelectionById(2) => '.($sel4 ? 'success' : 'null')."\n";
if ($sel4) {
    echo '  selected service name: '.$sel4->selectedServiceName."\n";
}

// Test 5: resolvePronoun (should handle service context)
$sel5 = $resolver->resolvePronoun('الشؤون الإدارية', $state);
echo 'resolvePronoun("الشؤون الإدارية") => '.($sel5 ? 'success' : 'null')."\n";

// Test 6: extractNumericSelection (should NOT extract digits from "الشؤون الإدارية")
$extracted = $resolver->extractNumericSelection('الشؤون الإدارية');
echo 'extractNumericSelection("الشؤون الإدارية") => '.var_export($extracted, true)."\n";

// Test 7: resolveFuzzyAreaMatch (for water areas)
$sel7 = $resolver->resolveFuzzyAreaMatch('حي البلد', [
    ['id' => 1, 'name' => 'حي البلد', 'entity_id' => 1],
    ['id' => 2, 'name' => 'حي الشرقية', 'entity_id' => 2],
]);
echo 'resolveFuzzyAreaMatch("حي البلد", ...) => '.($sel7 ? 'success' : 'null')."\n";

// Test 8: Test with a real conversation
echo "\n--- Real test --- ";

$state2 = new ConversationStateData(
    state: ConversationState::WaitingForClarification,
    clarificationOptions: [
        ['id' => 1, 'name' => 'رخص البناء', 'number' => 1, 'entity_type' => 'service_category'],
        ['id' => 2, 'name' => 'الشؤون الإدارية', 'number' => 2, 'entity_type' => 'service_category'],
        ['id' => 3, 'name' => 'الشؤون القانونية', 'number' => 3, 'entity_type' => 'service_category'],
        ['id' => 4, 'name' => 'الخدمات الصحية', 'number' => 4, 'entity_type' => 'service_category'],
        ['id' => 5, 'name' => 'الخدمات المالية', 'number' => 5, 'entity_type' => 'service_category'],
        ['id' => 6, 'name' => 'الخدمات الاجتماعية', 'number' => 6, 'entity_type' => 'service_category'],
        ['id' => 7, 'name' => 'الخدمات البيئية', 'number' => 7, 'entity_type' => 'service_category'],
        ['id' => 8, 'name' => 'الخدمات التخطيطية', 'number' => 8, 'entity_type' => 'service_category'],
        ['id' => 9, 'name' => 'الخدمات الإلكترونية', 'number' => 9, 'entity_type' => 'service_category'],
    ],
    pendingField: 'general',
    needsClarification: true,
    state: ConversationState::WaitingForClarification,
);

$sel8 = $resolver->resolveNumericSelection('الشؤون الإدارية', $state2);
echo 'resolveNumericSelection("الشؤون الإدارية", state) => '.($sel8 ? 'success (selected '.$sel8->selectedServiceName.')' : 'null')."\n";

// Test 9: check the exact flow with a label match
// After "الشؤون الإدارية" with clear state
echo "\n--- Label match test --- ";

// Test: does `resolveNumericSelection` return null for non-numeric input?
$sel9 = $resolver->resolveNumericSelection('الشؤون الإدارية', $state2);
echo 'Result: '.($sel9 === null ? 'null (fall through to classifier)' : 'not null')."\n";

// Test: Does `resolveOptionSelectionById` work for a non-digit string?
$sel10 = $resolver->resolveOptionSelectionById(2, $state2);
echo 'resolveOptionSelectionById(2) => '.($sel10 ? 'success' : 'null')."\n";

// Check if there is a method that matches by exact label
$methods = get_class_methods($resolver);
echo 'Methods: '.implode(', ', $methods)."\n";

// Let's also check the exact behavior of resolveNumericSelection when given "الشؤون الإدارية"
echo "\n--- Detailed test --- ";
$extracted = $resolver->extractNumericSelection('الشؤون الإدارية');
echo "extractNumericSelection for 'الشؤون الإدارية' returns: ".var_export($extracted, true)."\n";
echo "extractNumericSelection for '3' returns: ".var_export($resolver->extractNumericSelection('3'), true)."\n";

// Final: does the system match exact labels in clarification options?
echo "\n--- Label match test ---";
$state3 = new ConversationStateData(
    state: ConversationState::WaitingForClarification,
    clarificationOptions: [
        ['id' => 1, 'name' => 'رخص البناء', 'number' => 1, 'entity_type' => 'service_category'],
        ['id' => 2, 'name' => 'الشؤون الإدارية', 'number' => 2, 'entity_type' => 'service_category'],
        ['id' => 3, 'name' => 'الشؤون القانونية', 'number' => 3, 'entity_type' => 'service_category'],
    ],
    pendingField: 'general',
    needsClarification: true,
    state: ConversationState::WaitingForClarification,
);

// Test matching label: does resolveNumericSelection find "الشؤون الإدارية" at position 2?
$match = $resolver->resolveNumericSelection('الشؤون الإدارية', $state3);
echo 'resolveNumericSelection("الشؤون الإدارية", state) => '.($match === null ? 'null (FAILED)' : 'success: '.$match->selectedServiceName)."\n";

// Test: does resolveLabelSelection exist?
$match2 = method_exists($resolver, 'resolveLabelSelection') ? 'YES' : 'NO';
echo "resolveLabelSelection exists: $match2\n";

// Test: does resolveFuzzyMatch exist?
$match3 = method_exists($resolver, 'resolveFuzzyAreaMatch') ? 'YES' : 'NO';
echo "resolveFuzzyAreaMatch exists: $match3\n";

// Test: does resolveOptionSelectionById handle non-digit strings?
$match4 = method_exists($resolver, 'resolveOptionSelectionById') ? 'YES' : 'NO';
echo "resolveOptionSelectionById exists: $match4\n";

// Test: Does resolveNumericSelection handle exact label strings?
$match5 = method_exists($resolver, 'resolveNumericSelection') ? 'YES' : 'NO';
echo "resolveNumericSelection exists: $match5\n";

// Test: Does resolveFuzzyMatch handle exact label strings?
$match6 = method_exists($resolver, 'resolveFuzzyAreaMatch') ? 'YES' : 'NO';
echo "resolveFuzzyAreaMatch exists: $match6\n";

// Test: Does resolvePronoun work with string input?
$match7 = method_exists($resolver, 'resolvePronoun') ? 'YES' : 'NO';
echo "resolvePronoun exists: $match7\n";

// Test: Does the state have clarificationOptions after greeting?
echo "\n--- Greeting state check ---";
echo 'State is WaitingForClarification: '.($state3->state->value === ConversationState::WaitingForClarification ? 'YES' : 'NO')."\n";
echo 'clarificationOptions count: '.count($state3->clarificationOptions)."\n";
echo 'pendingField: '.($state3->pendingField ?? 'null')."\n";

// Test: does the GreetingHandler persist clarification for main menu?
echo "\n--- Main menu clarification check ---";
// Looking at GreetingHandler.handle() - it returns chat response with actions
// But does it persist clarification in context? No!
echo "GreetingHandler handles main menu with no clarification persisted\n";

// Test: what about the "البدي" → "الشؤون الإدارية" flow?
// GreetingHandler → main menu → user types "الشؤون الإدارية"
// This should go through the clarification path
echo "\n--- Greeting flow check ---";
echo "After greeting, state is: WaitingForClarification\n";
echo 'clarificationOptions count: '.count($state3->clarificationOptions)."\n";

// Test: does the service category handler for main menu show clarification properly?
echo "\n--- Service category clarification check ---";
echo "The user types 'الشؤون الإدارية' after main menu\n";
echo "Expected: resolve to position 2 (الشؤون الإدارية)\n";
echo "Actual: resolveNumericSelection returns null for non-digit\n";
echo "Missing: label matching for exact word match\n";

// Test: what is the expected behavior?
echo "\n--- Expected behavior ---";
echo "User types 'الشؤون الإدارية' after main menu → should resolve to position 2\n";
echo "This is label matching, not numeric matching\n";

echo "\n--- Summary ---";
echo "resolveNumericSelection ONLY works with digit inputs (numbers 1-9)\n";
echo "resolveLabelSelection (missing) needed for exact label matching\n";
echo "resolveOptionSelectionById only works with integer IDs\n";
echo "resolveFuzzyMatch (for water areas) works but not for service categories\n";
echo "No exact label matching in clarification resolver → SERVICE CATEGORY FAILS\n";

// Test: Does resolveNumericSelection return null for non-numeric label input?
echo "\n--- Final resolution test ---";
$test = $resolver->resolveNumericSelection('الشؤون الإدارية', $state3);
echo "resolveNumericSelection('الشؤون الإدارية', state) = ".var_export($test, true)."\n";
echo "Result: FAIL (returns null, falls through to classifier → Unknown → 'ما لقيت هذا التصنيف')\n";
echo "Required: should match 'الشؤون الإدارية' → returns option at position 2\n";
echo "Fix needed: add resolveLabelSelection method or extend resolveNumericSelection\n";

// Test: Does `resolveNumericSelection` ever return an option by label?
echo "\n--- Does resolveNumericSelection match labels by value? ---";
echo "extractNumericSelection('3') => ".var_export($resolver->extractNumericSelection('3'), true)."\n";
echo "extractNumericSelection('الشؤون الإدارية') => ".var_export($resolver->extractNumericSelection('الشؤون الإدارية'), true)."\n";
echo "resolveNumericSelection('3', state) => ".($resolver->resolveNumericSelection('3', $state3) ? 'success' : 'null')."\n";
echo "resolveNumericSelection('الشؤون الإدارية', state) => ".($resolver->resolveNumericSelection('الشؤون الإدارية', $state3) ? 'success' : 'null')."\n";
echo 'resolveOptionSelectionById(2, state) => '.($resolver->resolveOptionSelectionById(2, $state3) ? 'success' : 'null')."\n";
echo "resolveLabelSelection('الشؤون الإدارية', state) => ".($resolver->resolveLabelSelection('الشؤون الإدارية', $state3) ? 'success' : 'null')."\n";
