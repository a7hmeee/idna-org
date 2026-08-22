<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chatbot_conversations', function (Blueprint $table): void {
            if (! Schema::hasColumn('chatbot_conversations', 'current_service_id')) {
                $table->unsignedBigInteger('current_service_id')->nullable()->after('status');
            }
            if (! Schema::hasColumn('chatbot_conversations', 'current_service_name')) {
                $table->string('current_service_name')->nullable()->after('current_service_id');
            }
            if (! Schema::hasColumn('chatbot_conversations', 'last_intent')) {
                $table->string('last_intent')->nullable()->after('current_service_name');
            }
            if (! Schema::hasColumn('chatbot_conversations', 'previous_intent')) {
                $table->string('previous_intent')->nullable()->after('last_intent');
            }
            if (! Schema::hasColumn('chatbot_conversations', 'context_updated_at')) {
                $table->timestamp('context_updated_at')->nullable()->after('metadata');
            }
        });

        Schema::table('chatbot_service_aliases', function (Blueprint $table): void {
            if (! Schema::hasColumn('chatbot_service_aliases', 'metadata')) {
                $table->json('metadata')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chatbot_conversations', function (Blueprint $table): void {
            $table->dropColumn([
                'current_service_id',
                'current_service_name',
                'last_intent',
                'previous_intent',
                'context_updated_at',
            ]);
        });

        Schema::table('chatbot_service_aliases', function (Blueprint $table): void {
            $table->dropColumn('metadata');
        });
    }
};
