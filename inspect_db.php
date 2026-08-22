<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;

echo "=== SERVICE CATEGORIES ===\n";
$cats = ServiceCategory::orderBy('sort_order')->get();
echo "Count: {$cats->count()}\n";
foreach ($cats as $c) {
    echo "  id={$c->id} name={$c->name} slug=".($c->slug ?? 'NULL').' is_public='.($c->is_public ? '1' : '0').' status='.($c->status ?? 'NULL').' sort='.($c->sort_order ?? 'NULL')."\n";
}

echo "\n=== PUBLISHED SERVICES ===\n";
$svc = ElectronicService::where('status', 'active')->where('is_public', true)->orderBy('sort_order')->get();
echo "Count: {$svc->count()}\n";
foreach ($svc as $s) {
    echo "  id={$s->id} name={$s->name} cat_id=".($s->service_category_id ?? 'NULL')."\n";
}

echo "\n=== SERVICES PER CATEGORY ===\n";
foreach ($cats as $cat) {
    $count = ElectronicService::where('service_category_id', $cat->id)->where('status', 'active')->where('is_public', true)->count();
    echo "  Cat {$cat->id} ({$cat->name}): {$count} published services\n";
}

echo "\n=== TABLE COUNTS ===\n";
$tables = ['service_categories', 'electronic_services', 'service_search_terms', 'water_areas', 'water_schedules', 'public_facilities', 'jobs', 'council_members', 'council_decisions', 'municipality_contacts', 'announcements', 'chatbot_conversations', 'chatbot_messages', 'chatbot_service_aliases', 'workflow_drafts', 'departments', 'engineering_offices', 'news', 'users'];
foreach ($tables as $t) {
    $exists = DB::getSchemaBuilder()->hasTable($t);
    echo "  $t: ".($exists ? DB::table($t)->whereNull('deleted_at')->count() : 'MISSING')."\n";
}

echo "\n=== DONE ===\n";
