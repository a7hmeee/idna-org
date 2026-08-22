<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_workflow_analytics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained('chatbot_conversations')->nullOnDelete();
            $table->string('workflow_type', 50);
            $table->unsignedBigInteger('workflow_draft_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedSmallInteger('current_step')->default(0);
            $table->unsignedSmallInteger('total_steps')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->boolean('was_completed')->default(false);
            $table->boolean('was_cancelled')->default(false);
            $table->unsignedSmallInteger('validation_failures')->default(0);
            $table->boolean('confirmed')->default(false);
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index(['workflow_type', 'created_at']);
            $table->index(['was_completed', 'was_cancelled']);
            $table->index(['conversation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_workflow_analytics');
    }
};
