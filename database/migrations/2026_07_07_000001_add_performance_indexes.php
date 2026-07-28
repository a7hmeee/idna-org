<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('department_id');
            $table->index('status');
            $table->index('last_login_at');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->index('manager_id');
            $table->index('is_active');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->index('group');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->index(['queue', 'reserved_at', 'available_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['department_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['last_login_at']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['manager_id']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['group']);
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['queue', 'reserved_at', 'available_at']);
        });
    }
};
