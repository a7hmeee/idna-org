<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_requests', function (Blueprint $table): void {
            $table->text('internal_notes')->nullable()->after('message');
            $table->text('response_notes')->nullable()->after('internal_notes');
            $table->string('assigned_to')->nullable()->after('response_notes');
            $table->string('assigned_department')->nullable()->after('assigned_to');
            $table->timestamp('in_progress_at')->nullable()->after('resolved_at');
        });
    }

    public function down(): void
    {
        Schema::table('contact_requests', function (Blueprint $table): void {
            $table->dropColumn(['internal_notes', 'response_notes', 'assigned_to', 'assigned_department', 'in_progress_at']);
        });
    }
};
