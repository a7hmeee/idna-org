<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatIntent;
use Illuminate\Database\Eloquent\Collection;

interface ChatIntentRepositoryInterface
{
    public function allActive(): Collection;

    public function findByName(string $name): ?ChatIntent;

    public function synchronizeFromEnum(): int;

    public function getMinimumConfidence(string $name): ?float;
}
