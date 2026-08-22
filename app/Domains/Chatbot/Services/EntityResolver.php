<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Contracts\EntityResolverInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;

final readonly class EntityResolver implements EntityResolverInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
        private ChatbotServiceAliasRepositoryInterface $aliasRepository,
        private ArabicTextNormalizer $normalizer,
    ) {}

    public function resolve(string $normalizedMessage, ?string $currentServiceName = null): ?ResolvedServiceData
    {
        $candidates = $this->resolveAllOrdered($normalizedMessage);

        if (! empty($candidates)) {
            return $candidates[0];
        }

        if ($currentServiceName !== null) {
            $resolved = $this->serviceQuery->findPublishedByExactName(
                $this->normalizer->normalize($currentServiceName)
            );
            if ($resolved !== null) {
                return $resolved;
            }
        }

        return null;
    }

    public function resolveMultiple(string $normalizedMessage): array
    {
        return $this->resolveAllOrdered($normalizedMessage);
    }

    private function resolveAllOrdered(string $normalizedMessage): array
    {
        $results = [];
        $seenIds = [];

        $addIfNew = function (?ResolvedServiceData $service) use (&$results, &$seenIds): void {
            if ($service === null || isset($seenIds[$service->id])) {
                return;
            }
            $seenIds[$service->id] = true;
            $results[] = $service;
        };

        // 1. Exact official name
        $addIfNew($this->serviceQuery->findPublishedByExactName($normalizedMessage));

        // 2. Exact alias
        $alias = $this->aliasRepository->findByAlias($normalizedMessage);
        if ($alias !== null && $alias->is_active) {
            $addIfNew($this->serviceQuery->findPublishedByExactName(
                $this->normalizer->normalize($alias->service_key)
            ));
        }

        // 3. Contained official name
        $addIfNew($this->serviceQuery->findPublishedByText($normalizedMessage));

        // 4. Contained aliases (ranked by length desc, priority, official name)
        $activeAliases = $this->aliasRepository->all()->where('is_active', true);

        $matchedAliases = [];
        foreach ($activeAliases as $alias) {
            $normalizedAlias = $this->normalizer->normalize($alias->alias);
            if (mb_strlen($normalizedAlias) < 3) {
                continue;
            }
            if (str_contains($normalizedMessage, $normalizedAlias)) {
                $priority = $alias->metadata['priority'] ?? 0;
                $matchedAliases[] = [
                    'alias' => $alias,
                    'normalized' => $normalizedAlias,
                    'priority' => (int) $priority,
                    'length' => mb_strlen($normalizedAlias),
                ];
            }
        }

        usort($matchedAliases, function (array $a, array $b) use ($normalizedMessage): int {
            // 1. Exact match beats contained match
            $aExact = $a['normalized'] === $normalizedMessage ? 1 : 0;
            $bExact = $b['normalized'] === $normalizedMessage ? 1 : 0;
            if ($aExact !== $bExact) {
                return $bExact <=> $aExact;
            }
            // 2. Longest alias first
            if ($a['length'] !== $b['length']) {
                return $b['length'] <=> $a['length'];
            }
            // 3. Highest priority first
            if ($a['priority'] !== $b['priority']) {
                return $b['priority'] <=> $a['priority'];
            }

            // 4. Official name alphabetically
            return strcmp($a['alias']->service_key, $b['alias']->service_key);
        });

        foreach ($matchedAliases as $matched) {
            $addIfNew($this->serviceQuery->findPublishedByExactName(
                $this->normalizer->normalize($matched['alias']->service_key)
            ));
        }

        return $results;
    }
}
