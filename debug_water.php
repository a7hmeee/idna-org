<?php

declare(strict_types=1);
use Illuminate\Support\Carbon;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$ws = app('App\Domains\WaterSchedule\Repositories\EloquentWaterScheduleRepository');
$s = $ws->getLatestScheduleForArea(1);
echo 'start_time type: '.gettype($s->start_time)."\n";
echo 'start_time raw: '.var_export($s->start_time, true)."\n";
echo 'start_time class: '.(is_object($s->start_time) ? get_class($s->start_time) : gettype($s->start_time))."\n";

if ($s->start_time instanceof Carbon) {
    echo 'start_time->format(H:i): '.$s->start_time->format('H:i')."\n";
}

echo 'end_time raw: '.var_export($s->end_time, true)."\n";

$d = app('App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface');
$data = $d->getLatestScheduleForArea(1);
echo 'DTO startTime type: '.gettype($data->startTime)."\n";
echo 'DTO startTime: '.var_export($data->startTime, true)."\n";
echo 'DTO endTime: '.var_export($data->endTime, true)."\n";
echo 'DTO scheduleDate: '.var_export($data->scheduleDate, true)."\n";

echo 'today: '.now()->toDateString()."\n";
echo 'is_same_day: '.var_export($data->scheduleDate === now()->toDateString(), true)."\n";
