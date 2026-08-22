<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Contracts\DirectServiceResolverInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;

final readonly class DirectServiceResolver implements DirectServiceResolverInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
        private ChatbotServiceAliasRepositoryInterface $aliasRepository,
        private ArabicTextNormalizer $normalizer,
    ) {}

    public function resolve(string $normalizedMessage): ?ResolvedServiceData
    {
        // 1. Exact normalized official service name
        $result = $this->serviceQuery->findPublishedByExactName($normalizedMessage);
        if ($result !== null) {
            return $result;
        }

        // 2. Exact normalized alias from chatbot_service_aliases
        $alias = $this->aliasRepository->findByAlias($normalizedMessage);
        if ($alias !== null && $alias->is_active) {
            $service = $this->serviceQuery->findPublishedByExactName(
                $this->normalizer->normalize($alias->service_key)
            );
            if ($service !== null) {
                return $service;
            }
        }

        // 3. Official service name contained clearly in the message
        $result = $this->serviceQuery->findPublishedByText($normalizedMessage);
        if ($result !== null) {
            return $result;
        }

        // 4. Alias contained clearly in the message (with short alias protection)
        $activeAliases = $this->aliasRepository->all()->where('is_active', true);
        foreach ($activeAliases as $alias) {
            $normalizedAlias = $this->normalizer->normalize($alias->alias);
            if (mb_strlen($normalizedAlias) < 3) {
                continue;
            }
            if (str_contains($normalizedMessage, $normalizedAlias)) {
                $service = $this->serviceQuery->findPublishedByExactName(
                    $this->normalizer->normalize($alias->service_key)
                );
                if ($service !== null) {
                    return $service;
                }
            }
        }

        return null;
    }
}
