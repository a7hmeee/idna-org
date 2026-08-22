<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$wq = app('App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface');

$schedule = $wq->getCurrentScheduleForArea(1);
echo 'getCurrentScheduleForArea(1): '.var_export($schedule, true)."\n";

if ($schedule === null) {
    $schedule = $wq->getLatestScheduleForArea(1);
    echo 'getLatestScheduleForArea(1): '.var_export($schedule, true)."\n";
    echo 'startTime: '.var_export($schedule->startTime ?? 'NULL', true)."\n";
    echo 'endTime: '.var_export($schedule->endTime ?? 'NULL', true)."\n";
    echo 'scheduleDate: '.var_export($schedule->scheduleDate ?? 'NULL', true)."\n";
    echo 'today: '.now()->toDateString()."\n";
    echo 'interpolated: '."{$schedule->startTime} - {$schedule->endTime}"."\n";
    echo 'condition startTime && endTime: '.var_export($schedule->startTime && $schedule->endTime, true)."\n";
}

echo "DONE\n";
