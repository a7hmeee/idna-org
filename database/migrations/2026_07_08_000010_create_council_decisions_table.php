<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('council_decisions', function (Blueprint $table): void {
            $table->id();
            $table->string('decision_number')->unique();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('type');
            $table->string('status');
            $table->date('decision_date')->nullable();
            $table->string('session_number')->nullable();
            $table->string('attachment_path')->nullable();
            $table->boolean('is_public')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type']);
            $table->index('decision_number');
            $table->index('decision_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('council_decisions');
    }
};
