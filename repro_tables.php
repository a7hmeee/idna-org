<?php

declare(strict_types=1);

foreach (DB::select('SHOW TABLES') as $t) {
    echo implode(array_values((array) $t))."\n";
}

echo "===== COUNTS =====\n";

$tables = ['public_facilities', 'jobs', 'job_posts', 'news', 'news_articles', 'council_members', 'council_decisions', 'departments', 'water_areas', 'announcements', 'engineering_offices', 'complaints', 'water_schedules'];

foreach ($tables as $table) {
    try {
        echo $table.': '.DB::table($table)->count()."\n";
    } catch (Throwable $e) {
        echo $table.': (missing) '.$e->getMessage()."\n";
    }
}
