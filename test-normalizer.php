<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Services\ArabicTextNormalizer;

$normalizer = new ArabicTextNormalizer;

$testWords = ['الشؤون الإدارية', 'رخص البناء', 'الخدمات المالية', 'الخدمات الاجتماعية', 'الخدمات التخطيطية'];

foreach ($testWords as $word) {
    echo "normalize('$word') = '".$normalizer->normalize($word)."'\n";
}

// Test exact match
$normalized = $normalizer->normalize('الشؤون الإدارية');
$categoryName = 'الشؤون الإدارية';
$normalizedCatName = $normalizer->normalize($categoryName);
echo "\nExact match test:\n";
echo "  normalized input: '$normalized'\n";
echo "  normalized cat:   '$normalizedCatName'\n";
echo '  match: '.($normalized === $normalizedCatName ? 'YES' : 'NO')."\n";
