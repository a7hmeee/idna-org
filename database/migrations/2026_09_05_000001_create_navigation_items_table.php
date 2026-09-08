<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table): void {
            $table->id();
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->string('route_params')->nullable()->comment('JSON encoded route parameters');
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_external')->default(false);
            $table->string('target')->default('_self')->comment('_self or _blank');
            $table->string('section')->default('main')->comment('main, top_bar, mobile');
            $table->boolean('open_in_new_tab')->default(false);
            $table->timestamps();

            $table->index(['is_active', 'sort_order', 'section']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
    }
};
