<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== WATER SCHEDULES (raw) ===\n";
$schedules = DB::table('water_schedules')->get();
echo "Total water_schedules: {$schedules->count()}\n";
foreach ($schedules as $s) {
    echo "  id={$s->id} area_id={$s->water_area_id} date=".($s->schedule_date ?? 'NULL').' day='.($s->day_of_week ?? 'NULL').' start='.($s->start_time ?? 'NULL').' end='.($s->end_time ?? 'NULL').' time_slot='.($s->time_slot ?? 'NULL')."\n";
}

echo "\n=== WATER AREAS (columns) ===\n";
$cols = DB::getSchemaBuilder()->getColumnListing('water_areas');
echo 'Columns: '.implode(',', $cols)."\n";
$areas = DB::table('water_areas')->get();
foreach ($areas as $a) {
    $arr = (array) $a;
    echo "  id={$a->id} name={$a->name} ".json_encode($arr, JSON_UNESCAPED_UNICODE)."\n";
}

echo "\n=== WATER SCHEDULE QUERY ADAPTER TEST ===\n";
$ws = app('App\Domains\WaterSchedule\Services\WaterScheduleQueryAdapter');
echo 'Published areas count: '.count($ws->getPublishedAreas())."\n";

$area1 = $ws->getPublishedAreas()[0] ?? null;
if ($area1) {
    echo "First area: id={$area1->id} name={$area1->name}\n";
    echo "  getCurrentScheduleForArea:\n";
    $cur = $ws->getCurrentScheduleForArea($area1->id);
    echo '    '.var_export($cur, true)."\n";
    echo "  getNextScheduleForArea:\n";
    $nxt = $ws->getNextScheduleForArea($area1->id);
    echo '    '.var_export($nxt, true)."\n";
    echo "  getTodaySchedules:\n";
    $tod = $ws->getTodaySchedules();
    echo '    count='.count($tod)."\n";
}

echo "\n=== SERVICE SEARCH TERMS SAMPLE ===\n";
$terms = DB::table('service_search_terms')->orderBy('electronic_service_id')->limit(20)->get();
foreach ($terms as $t) {
    echo "  service_id={$t->electronic_service_id} term={$t->term} norm={$t->normalized_term} type={$t->type}\n";
}

echo "\n=== CHATBOT SERVICE ALIASES ===\n";
$aliases = DB::table('chatbot_service_aliases')->limit(20)->get();
echo "Count: {$aliases->count()}\n";
foreach ($aliases as $a) {
    echo "  alias={$a->alias} service_key=".($a->service_key ?? 'NULL').' is_active='.($a->is_active ? '1' : '0')."\n";
}

echo "\n=== DONE ===\n";
