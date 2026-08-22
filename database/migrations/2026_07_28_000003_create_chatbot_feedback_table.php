<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_feedback', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('message_id')->constrained('chatbot_messages')->cascadeOnDelete();
            $table->string('type');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['message_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_feedback');
    }
};
