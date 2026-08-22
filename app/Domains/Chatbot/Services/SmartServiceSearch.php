<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\Contracts\SmartServiceSearchInterface;
use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\Chatbot\DTOs\ServiceSearchMatchData;
use App\Domains\Chatbot\DTOs\ServiceSearchResultCollection;
use Illuminate\Support\Facades\Cache;

final readonly class SmartServiceSearch implements SmartServiceSearchInterface
{
    private const CACHE_KEY = 'chatbot:service-search-documents';

    private const CACHE_TTL = 3600;

    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
        private ServiceSearchTokenizer $tokenizer,
        private ServiceSearchScorer $scorer,
        private ArabicTextNormalizer $normalizer,
        private float $autoSelectThreshold = 0.88,
        private float $clarificationThreshold = 0.55,
        private float $minimumScoreGap = 0.15,
        private int $defaultLimit = 5,
    ) {}

    public function search(
        string $message,
        ?int $currentServiceId = null,
        int $limit = 5,
    ): ServiceSearchResultCollection {
        $normalizedMessage = $this->normalizer->normalize($message);
        $queryTokens = $this->tokenizer->tokenize($normalizedMessage);

        $documents = $this->getSearchDocuments();

        $matches = [];

        foreach ($documents as $document) {
            $match = $this->scorer->score(
                $document,
                $queryTokens,
                $normalizedMessage,
                $currentServiceId,
            );

            if ($match !== null) {
                $matches[] = $match;
            }
        }

        // Sort by score descending, then priority descending
        usort($matches, function (ServiceSearchMatchData $a, ServiceSearchMatchData $b): int {
            if ($a->score !== $b->score) {
                return $b->score <=> $a->score;
            }

            return $b->priority <=> $a->priority;
        });

        $matches = array_slice($matches, 0, $limit);

        if (empty($matches)) {
            return new ServiceSearchResultCollection(
                originalMessage: $message,
                normalizedMessage: $normalizedMessage,
                matches: [],
                bestMatch: null,
                isConfident: false,
                requiresClarification: false,
                noMatch: true,
            );
        }

        $bestMatch = $matches[0];
        $secondScore = $matches[1]->score ?? 0.0;
        $scoreGap = $bestMatch->score - $secondScore;

        // Check if typo-only match should prevent auto-selection
        $typoPreventsAutoSelect = $bestMatch->matchedBy === 'typo_match';

        $isConfident = ! $typoPreventsAutoSelect
            && $bestMatch->score >= $this->autoSelectThreshold
            && $scoreGap >= $this->minimumScoreGap;

        $requiresClarification = ! $isConfident
            && $bestMatch->score >= $this->clarificationThreshold
            && count($matches) > 1
            && ! $typoPreventsAutoSelect;

        return new ServiceSearchResultCollection(
            originalMessage: $message,
            normalizedMessage: $normalizedMessage,
            matches: $matches,
            bestMatch: $bestMatch,
            isConfident: $isConfident,
            requiresClarification: $requiresClarification,
            noMatch: false,
            scoreGap: $scoreGap,
        );
    }

    public function getSearchDocuments(): array
    {
        $cached = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): array {
            $documents = $this->serviceQuery->getSearchDocuments();

            return array_map(fn (ServiceSearchDocumentData $doc) => $doc->toArray(), $documents);
        });

        $documents = [];
        foreach ($cached as $data) {
            $documents[] = new ServiceSearchDocumentData(
                serviceId: $data['serviceId'],
                officialName: $data['officialName'],
                normalizedOfficialName: $data['normalizedOfficialName'],
                aliases: $data['aliases'] ?? [],
                normalizedAliases: $data['normalizedAliases'] ?? [],
                keywords: $data['keywords'] ?? [],
                normalizedKeywords: $data['normalizedKeywords'] ?? [],
                searchablePhrases: $data['searchablePhrases'] ?? [],
                normalizedSearchablePhrases: $data['normalizedSearchablePhrases'] ?? [],
                citizenExpressions: $data['citizenExpressions'] ?? [],
                normalizedCitizenExpressions: $data['normalizedCitizenExpressions'] ?? [],
                shortDescription: $data['shortDescription'] ?? null,
                normalizedShortDescription: $data['normalizedShortDescription'] ?? null,
                categoryName: $data['categoryName'] ?? null,
                normalizedCategoryName: $data['normalizedCategoryName'] ?? null,
                priority: $data['priority'] ?? 0,
                isPublished: $data['isPublished'] ?? true,
                applicationUrl: $data['applicationUrl'] ?? null,
                updatedAt: $data['updatedAt'] ?? null,
            );
        }

        return $documents;
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
