<?php

declare(strict_types=1);

function cols(string $t): string
{
    try {
        return implode(',', Schema::getColumnListing($t));
    } catch (Throwable $e) {
        return 'ERR: '.$e->getMessage();
    }
}

echo 'jobs: '.cols('jobs')."\n";
echo 'public_facilities: '.cols('public_facilities')."\n";
echo 'engineering_offices: '.cols('engineering_offices')."\n";

foreach ([
    ['jobs', 'is_public'],
    ['jobs', 'status'],
    ['public_facilities', 'status'],
    ['public_facilities', 'is_public'],
    ['engineering_offices', 'is_public'],
    ['engineering_offices', 'approval_status'],
    ['engineering_offices', 'status'],
] as [$t, $c]) {
    try {
        $rows = DB::table($t)->select($c)->distinct()->get();
        $vals = collect($rows)->pluck($c)->map(fn ($v) => var_export($v, true))->implode(', ');
        echo "{$t}.{$c}: {$vals}\n";
    } catch (Throwable $e) {
        echo "{$t}.{$c}: (no column)\n";
    }
}

echo 'cache: '.implode('|', ['open-jobs' => gettype(Cache::get('chatbot:open-jobs')), 'fac' => gettype(Cache::get('chatbot:facilities')), 'eo' => gettype(Cache::get('chatbot:engineering-offices'))])."\n";
echo "DONE\n";
