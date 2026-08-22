<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_performance_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('context', 100);
            $table->string('handler_class', 255)->nullable();
            $table->string('action_label', 100)->nullable();
            $table->unsignedInteger('duration_ms')->default(0);
            $table->unsignedBigInteger('memory_bytes')->nullable();
            $table->unsignedSmallInteger('db_query_count')->nullable();
            $table->unsignedSmallInteger('cache_hits')->nullable();
            $table->unsignedSmallInteger('cache_misses')->nullable();
            $table->boolean('slow_flag')->default(false);
            $table->timestamps();

            $table->index(['context', 'created_at']);
            $table->index(['slow_flag', 'created_at']);
            $table->index(['handler_class']);
            $table->index(['duration_ms']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_performance_logs');
    }
};
