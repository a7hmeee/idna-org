<?php

declare(strict_types=1);

$cols = Schema::getColumnListing('jobs');
echo 'jobs columns: '.implode(',', $cols)."\n";

$rows = DB::table('jobs')->select('status', 'is_public')->distinct()->get();
echo "jobs status/is_public combos:\n";
foreach ($rows as $r) {
    echo "  status={$r->status} is_public={$r->is_public}\n";
}

$cols = Schema::getColumnListing('public_facilities');
echo "\npublic_facilities columns: ".implode(',', $cols)."\n";
$rows = DB::table('public_facilities')->select('status', 'is_public')->distinct()->get();
foreach ($rows as $r) {
    echo "  status={$r->status} is_public={$r->is_public}\n";
}

$cols = Schema::getColumnListing('engineering_offices');
echo "\nengineering_offices columns: ".implode(',', $cols)."\n";
$rows = DB::table('engineering_offices')->select('approval_status', 'is_public', 'status')->distinct()->get();
foreach ($rows as $r) {
    echo "  approval_status={$r->approval_status} is_public={$r->is_public} status={$r->status}\n";
}

echo "\ncache keys:\n";
$cache = Cache::get('chatbot:open-jobs');
var_dump(is_array($cache) ? 'array count='.count($cache) : gettype($cache));
$fac = Cache::get('chatbot:facilities');
var_dump(is_array($fac) ? 'array count='.count($fac) : gettype($fac));
$eo = Cache::get('chatbot:engineering-offices');
var_dump(is_array($eo) ? 'array count='.count($eo) : gettype($eo));

echo "DONE\n";
