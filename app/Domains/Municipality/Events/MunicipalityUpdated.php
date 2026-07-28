<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class MunicipalityUpdated
{
    use Dispatchable;

    public function __construct(
        public readonly string $section,
    ) {}
}
