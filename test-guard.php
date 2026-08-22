<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;

$guard = new PublicChatbotDataQualityGuard;

$testValues = [
    '+970-22-123456',
    '+970-22-123457',
    'info@idhna.ps',
    'support@idhna.ps',
];

foreach ($testValues as $val) {
    echo "isDemoValue('$val') => ".($guard->isDemoValue($val) ? 'TRUE' : 'FALSE')."\n";
    echo '  isDemoPhone: '.($guard->isDemoPhone($val) ? 'TRUE' : 'FALSE')."\n";
    echo '  isPlaceholderEmail: '.($guard->isPlaceholderEmail($val) ? 'TRUE' : 'FALSE')."\n";
    echo '  filterValue: '.var_export($guard->filterValue($val, 'phone'), true)."\n";
}
