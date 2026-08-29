<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carousel_configurations', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('page')->nullable();
            $table->string('section')->nullable();
            $table->string('type')->default('hero')->comment('hero, card, content');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            // Responsive slides per view
            $table->unsignedSmallInteger('desktop_slides')->default(1);
            $table->unsignedSmallInteger('tablet_slides')->default(1);
            $table->unsignedSmallInteger('mobile_slides')->default(1);

            // Autoplay
            $table->boolean('autoplay')->default(true);
            $table->unsignedInteger('autoplay_delay')->default(8000);

            // Behavior
            $table->boolean('loop')->default(false);
            $table->boolean('show_navigation')->default(true);
            $table->boolean('show_pagination')->default(true);
            $table->boolean('pause_on_hover')->default(true);
            $table->string('direction')->default('rtl');
            $table->string('transition')->default('slide')->comment('slide, fade');

            $table->timestamps();

            $table->index(['key', 'is_active']);
            $table->index('page');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_configurations');
    }
};
