<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// 1) Parse the trace JSONL and show raw_user_text + normalized as hex for reg-7
$traceFile = storage_path('logs/chatbot_trace.jsonl');
echo "=== TRACE reg-7 ===\n";
if (file_exists($traceFile)) {
    foreach (file($traceFile, FILE_IGNORE_NEW_LINES) as $line) {
        $row = json_decode($line, true);
        if (! isset($row['session_id']) || ! str_contains($row['session_id'] ?? '', 'reg-7-')) {
            // also catch by session prefix stored earlier
        }
        $sid = $row['session_id'] ?? '';
        if ($sid === '') {
            continue;
        }
        foreach (['raw_user_text', 'normalized_user_text', 'normalized', 'predicted_intent', 'route_domain', 'state_before', 'current_domain', 'normalized'] as $k) {
            if (isset($row[$k])) {
                $v = $row[$k];
                echo $k.': ['.$v.'] hex='.bin2hex((string) $v)."\n";
            }
        }
        echo '---event: '.($row['event'] ?? '?')."\n";
    }
}

echo "\n=== TEST FILE LINE 114 raw bytes ===\n";
$lines = file('tests/Feature/Chatbot/PreviousFixesRegressionTest.php', FILE_IGNORE_NEW_LINES);
$line = $lines[113]; // 0-indexed line 114
echo 'LINE114: '.$line."\n";
// Extract the Arabic string between single quotes
if (preg_match("/'([^']+)'/", $line, $m)) {
    $arabic = $m[1];
    echo 'EXTRACTED: ['.$arabic.'] hex='.bin2hex($arabic)."\n";
}
