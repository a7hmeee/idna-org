<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // All columns and indexes already exist in create_announcements_table migration.
        // This migration is intentionally a no-op to prevent duplicate column errors.
    }

    public function down(): void
    {
        // No-op for consistency with up().
    }
};
