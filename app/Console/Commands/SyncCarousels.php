<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Homepage\Services\CarouselRegistry;
use Illuminate\Console\Command;

final class SyncCarousels extends Command
{
    protected $signature = 'carousels:sync';

    protected $description = 'Synchronize all discovered carousels into the centralized configuration database';

    public function handle(): int
    {
        $this->info('جاري مزامنة الكاروسيلات...');

        $added = CarouselRegistry::sync();

        if ($added > 0) {
            $this->info("✓ تم إضافة {$added} كاروسيل جديد.");
        } else {
            $this->info('✓ جميع الكاروسيلات مسجلة بالفعل.');
        }

        $total = CarouselRegistry::all()->count();
        $this->info("إجمالي الكاروسيلات المسجلة: {$total}");

        return self::SUCCESS;
    }
}
