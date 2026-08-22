<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_requests')) {
            Schema::create('contact_requests', function (Blueprint $table): void {
                $table->id();
                $table->string('tracking_number')->nullable()->unique();
                $table->string('name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('message');
                $table->string('department')->nullable();
                $table->string('status')->default('pending');
                $table->string('source')->default('chatbot');
                $table->string('session_id')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
                $table->index(['status', 'submitted_at']);
                $table->index('tracking_number');
            });
        } else {
            Schema::table('contact_requests', function (Blueprint $table): void {
                if (! Schema::hasColumn('contact_requests', 'tracking_number')) {
                    $table->string('tracking_number')->nullable()->unique();
                }
                if (! Schema::hasColumn('contact_requests', 'source')) {
                    $table->string('source')->default('chatbot');
                }
                if (! Schema::hasColumn('contact_requests', 'submitted_at')) {
                    $table->timestamp('submitted_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contact_requests')) {
            Schema::table('contact_requests', function (Blueprint $table): void {
                if (Schema::hasColumn('contact_requests', 'tracking_number')) {
                    $table->dropColumn('tracking_number');
                }
                if (Schema::hasColumn('contact_requests', 'source')) {
                    $table->dropColumn('source');
                }
                if (Schema::hasColumn('contact_requests', 'submitted_at')) {
                    $table->dropColumn('submitted_at');
                }
            });
        }
    }
};
