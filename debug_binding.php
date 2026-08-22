<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Binding: '.get_class(app('App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface'))."\n";
echo 'Methods: '.implode(', ', get_class_methods(app('App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface')))."\n";
