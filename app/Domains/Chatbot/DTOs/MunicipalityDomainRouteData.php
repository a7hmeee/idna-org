<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityDomainRouteData
{
    public function __construct(
        public string $domain,
        public ChatbotIntent $intent,
        public string $handlerKey,
        public float $confidence = 1.0,
        public string $source = 'intent',
        public bool $requiresEntity = false,
        public ?string $requiredEntityType = null,
        public string $explanation = '',
    ) {}

    public function isElectronicServices(): bool
    {
        return $this->domain === 'electronic_services';
    }

    public function isMunicipalityDomain(): bool
    {
        return $this->domain !== 'electronic_services' && $this->domain !== 'general';
    }
}
