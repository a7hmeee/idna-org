<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('site_title')->nullable();
            $table->text('site_subtitle')->nullable();
            $table->string('portal_url')->nullable();
            $table->string('primary_button_text')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->string('welcome_title')->nullable();
            $table->longText('welcome_description')->nullable();
            $table->string('mayor_message_title')->nullable();
            $table->text('mayor_message')->nullable();
            $table->string('mayor_image_path')->nullable();
            $table->boolean('show_mayor_message')->default(true);
            $table->string('contact_cta_title')->nullable();
            $table->text('contact_cta_description')->nullable();
            $table->string('contact_cta_button_text')->nullable();
            $table->string('contact_cta_button_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
