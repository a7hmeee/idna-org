<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_drafts', function (Blueprint $table): void {
            $table->id();
            $table->string('workflow_type');
            $table->string('session_id');
            $table->unsignedBigInteger('citizen_user_id')->nullable();
            $table->string('current_step');
            $table->json('answers')->nullable();
            $table->json('validation_errors')->nullable();
            $table->string('status')->default('collecting_data');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('final_entity_type')->nullable();
            $table->unsignedBigInteger('final_entity_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['session_id', 'status']);
            $table->index(['citizen_user_id', 'status']);
            $table->index('expires_at');
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_drafts');
    }
};
