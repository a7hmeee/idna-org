<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_views', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('electronic_service_id')->constrained()->cascadeOnDelete();
            $table->string('ip_hash')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referrer')->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();
        });

        Schema::create('service_portal_clicks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('electronic_service_id')->constrained()->cascadeOnDelete();
            $table->string('ip_hash')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referrer')->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_portal_clicks');
        Schema::dropIfExists('service_views');
    }
};
