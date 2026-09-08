<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable()->comment('Snapshot of actor name');
            $table->string('user_email')->nullable()->comment('Snapshot of actor email');
            $table->string('action');
            $table->string('module')->nullable();
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable()->comment('Before state (redacted for sensitive fields)');
            $table->json('new_values')->nullable()->comment('After state (redacted for sensitive fields)');
            $table->json('changed_fields')->nullable()->comment('List of changed field names');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->string('status')->default('success')->comment('success, failure, error');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'module', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
