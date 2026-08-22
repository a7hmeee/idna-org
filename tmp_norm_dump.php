<?php

use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\RuleIntentDetector;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$norm = app(ArabicTextNormalizer::class);

$msgs = [
    'reg7' => 'الوظاء',
    'reg13a' => 'جدول توزيع المياه',
    'reg13b' => '1',
];

foreach ($msgs as $k => $m) {
    $n = $norm->normalize($m);
    echo $k.': raw=['.json_encode($m).'] hex='.bin2hex($m).PHP_EOL;
    echo $k.': norm=['.json_encode($n).'] hex='.bin2hex($n).PHP_EOL;
    echo '---'.PHP_EOL;
}

// Check what 'jobs_open' rule pattern normalizes to
echo 'JobsOpen patterns normalized:'.PHP_EOL;
$ref = new ReflectionClass(RuleIntentDetector::class);
