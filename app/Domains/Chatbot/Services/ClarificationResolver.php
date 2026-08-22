<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ClarificationResolverInterface;
use App\Domains\Chatbot\DTOs\ClarificationData;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;

final readonly class ClarificationResolver implements ClarificationResolverInterface
{
    private const PRONOUN_PATTERNS = [
        '/^(هاي|هاد|هي|هو)$/u',
        '/^(الخدمه|الرخصه)$/u',
        '/^(قديش|شو|كم|وين)\s*(رسومها|تكلفتها|اسعارها|تكلفه|اسعار)$/u',
        '/^(شو|ويني|وين)\s*(المطلوب|الخطوات|المده|خطواتها|مدتها)$/u',
        '/^(وين|ويني)\s*([أا]قدمها|قدمها|اديها|اروح|اروحها)$/u',
    ];

    private const AMBIGUITY_THRESHOLD = 1;

    private const VALID_TYPES = [
        'municipality_main_menu',
        'service',
        'service_category',
        'electronic_service',
        'water_area',
        'department',
        'job',
        'news',
        'council_decision',
        'facility',
        'engineering_office',
        'workflow_option',
        'workflow_interrupt',
        'tracking',
        'council_member',
        'general',
    ];

    private const ARABIC_ORDINALS = [
        'الأول' => 1, 'الأولى' => 1, 'اول' => 1, 'اولى' => 1, 'واحد' => 1,
        'الثاني' => 2, 'الثانية' => 2, 'تاني' => 2, 'تانية' => 2, 'اثنين' => 2, 'ثاني' => 2,
        'الثالث' => 3, 'الثالثة' => 3, 'تالت' => 3, 'تالتة' => 3, 'ثلاثه' => 3, 'ثلاثة' => 3,
        'الرابع' => 4, 'الرابعة' => 4, 'رابع' => 4, 'رابعة' => 4, 'اربعه' => 4, 'اربعة' => 4,
        'الخامس' => 5, 'الخامسة' => 5, 'خامس' => 5, 'خامسة' => 5, 'خمسه' => 5, 'خمسة' => 5,
        'السادس' => 6, 'السادسة' => 6, 'سادس' => 6, 'سادسة' => 6, 'ستة' => 6, 'سته' => 6,
        'السابع' => 7, 'السابعة' => 7, 'سابع' => 7, 'سابعة' => 7, 'سبعة' => 7, 'سبعه' => 7,
        'الثامن' => 8, 'الثامنة' => 8, 'ثامن' => 8, 'ثامنة' => 8, 'ثمانية' => 8, 'ثمانيه' => 8,
        'التاسع' => 9, 'التاسعة' => 9, 'تاسع' => 9, 'تاسعة' => 9, 'تسعة' => 9, 'تسعه' => 9,
        'العاشر' => 10, 'العاشرة' => 10, 'عاشر' => 10, 'عاشرة' => 10, 'عشرة' => 10, 'عشره' => 10,
    ];

    public function __construct(
        private ArabicTextNormalizer $normalizer,
        private ArabicTypoMatcher $typoMatcher = new ArabicTypoMatcher,
    ) {}

    public function needsClarification(string $normalizedMessage, array $candidates, string $type = 'service', ?string $domain = null, ?string $entityType = null): ?ClarificationData
    {
        if (count($candidates) <= self::AMBIGUITY_THRESHOLD) {
            return null;
        }

        if (count($candidates) > self::AMBIGUITY_THRESHOLD) {
            return $this->buildClarificationQuestion($candidates, $type, $domain, $entityType);
        }

        return null;
    }

    public function resolveNumericSelection(string $normalizedMessage, ConversationStateData $state): ?ClarificationData
    {
        if (empty($state->clarificationOptions)) {
            return null;
        }

        if ($state->state->value !== 'waiting_for_selection' && $state->state->value !== 'waiting_for_clarification') {
            return null;
        }

        $number = $this->extractNumericSelection($normalizedMessage);

        if ($number === null) {
            return null;
        }

        if ($number === PHP_INT_MAX) {
            $number = count($state->clarificationOptions);
        }

        $index = $number - 1;

        if (! isset($state->clarificationOptions[$index])) {
            return null;
        }

        $option = $state->clarificationOptions[$index];

        $pendingType = $state->pendingField ?? 'service';

        if (! $this->isValidOptionForType($option, $pendingType)) {
            return null;
        }

        return $this->buildSelectionResult($option, $number, $pendingType);
    }

    public function resolveOptionSelectionById(int $optionId, ConversationStateData $state): ?ClarificationData
    {
        if (empty($state->clarificationOptions)) {
            return null;
        }

        if ($state->state->value !== 'waiting_for_selection' && $state->state->value !== 'waiting_for_clarification') {
            return null;
        }

        $pendingType = $state->pendingField ?? 'service';

        foreach ($state->clarificationOptions as $index => $option) {
            $optionIdValue = $option['entity_id'] ?? $option['id'] ?? null;

            if ($optionIdValue !== null && (int) $optionIdValue === $optionId) {
                if (! $this->isValidOptionForType($option, $pendingType)) {
                    return null;
                }

                return $this->buildSelectionResult($option, $index + 1, $pendingType);
            }
        }

        return null;
    }

    public function resolveWaterAreaSelection(string $normalizedMessage, ConversationStateData $state): ?ClarificationData
    {
        if (empty($state->clarificationOptions)) {
            return null;
        }

        if ($state->pendingField !== 'water_area') {
            return null;
        }

        $areas = array_filter($state->clarificationOptions, fn ($opt) => ($opt['entity_type'] ?? null) === 'water_area');

        if (empty($areas)) {
            return null;
        }

        $normalizedMessage = trim($normalizedMessage);

        $result = $this->resolveOptionSelectionFromOptions($normalizedMessage, $areas, 'water_area');

        if ($result !== null) {
            return $this->buildAreaSelectionResult($result['option'], $result['option']['position'] ?? 0);
        }

        return $this->resolveFuzzyAreaMatch($normalizedMessage, $areas);
    }

    public function resolveOptionSelection(string $normalizedMessage, ConversationStateData $state): ?array
    {
        if (empty($state->clarificationOptions)) {
            return null;
        }

        if ($state->state->value !== 'waiting_for_selection' && $state->state->value !== 'waiting_for_clarification') {
            return null;
        }

        $options = $state->clarificationOptions;
        $pendingType = $state->pendingField ?? 'general';

        $trustedKey = $this->resolveTrustedKey($normalizedMessage);
        if ($trustedKey !== null) {
            foreach ($options as $index => $option) {
                $optionKey = $option['key'] ?? ($option['type'] ?? null);
                if ($optionKey !== null && $optionKey === $trustedKey) {
                    if ($this->isValidOptionForType($option, $pendingType)) {
                        $number = $index + 1;

                        return [
                            'matched' => true,
                            'match_type' => 'trusted_key',
                            'option' => $this->buildOptionData($option, $number),
                        ];
                    }
                }
            }
        }

        $entityKeyId = $this->extractEntityKeyId($normalizedMessage, $pendingType);
        if ($entityKeyId !== null) {
            foreach ($options as $index => $option) {
                $optionId = $option['entity_id'] ?? $option['id'] ?? null;
                if ($optionId !== null && (int) $optionId === $entityKeyId) {
                    if ($this->isValidOptionForType($option, $pendingType)) {
                        $number = $index + 1;

                        return [
                            'matched' => true,
                            'match_type' => 'entity_id',
                            'option' => $this->buildOptionData($option, $number),
                        ];
                    }
                }
            }
        }

        $normalizedInput = $this->normalizer->normalize($normalizedMessage);

        foreach ($options as $index => $option) {
            $label = $option['label'] ?? $option['name'] ?? '';
            $normalizedLabel = $option['normalized_label'] ?? $this->normalizer->normalize($label);

            if ($normalizedInput !== '' && $normalizedInput === $normalizedLabel) {
                if (! $this->isValidOptionForType($option, $pendingType)) {
                    continue;
                }

                return [
                    'matched' => true,
                    'match_type' => 'exact_normalized_label',
                    'option' => $this->buildOptionData($option, $index + 1),
                ];
            }
        }

        foreach ($options as $index => $option) {
            $label = $option['label'] ?? $option['name'] ?? '';

            if (trim($normalizedMessage) !== '' && trim($normalizedMessage) === trim($label)) {
                if (! $this->isValidOptionForType($option, $pendingType)) {
                    continue;
                }

                return [
                    'matched' => true,
                    'match_type' => 'exact_label',
                    'option' => $this->buildOptionData($option, $index + 1),
                ];
            }
        }

        $number = $this->extractNumericSelection($normalizedMessage);
        if ($number !== null && $number !== PHP_INT_MAX) {
            $index = $number - 1;
            if (isset($options[$index])) {
                $option = $options[$index];
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'numeric_position',
                        'option' => $this->buildOptionData($option, $number),
                    ];
                }
            }
        }

        $ordinalValue = $this->extractOrdinal($normalizedInput);
        if ($ordinalValue !== null) {
            $index = $ordinalValue - 1;
            if (isset($options[$index])) {
                $option = $options[$index];
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'ordinal',
                        'option' => $this->buildOptionData($option, $ordinalValue),
                    ];
                }
            }
        }

        if (mb_strlen($normalizedInput) >= 3) {
            $bestMatch = null;
            $bestScore = 0.0;

            foreach ($options as $index => $option) {
                $label = $option['label'] ?? $option['name'] ?? '';
                $normalizedLabel = $option['normalized_label'] ?? $this->normalizer->normalize($label);

                if ($normalizedLabel === '' || mb_strlen($normalizedLabel) < 3) {
                    continue;
                }

                if (! $this->isValidOptionForType($option, $pendingType)) {
                    continue;
                }

                $score = $this->typoMatcher->match($normalizedInput, $normalizedLabel);

                if ($score !== null && $score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = [$option, $index + 1, $score];
                } elseif ($score !== null && $score === $bestScore && $bestMatch !== null) {
                    return null;
                }
            }

            if ($bestMatch !== null && $bestMatch[2] >= 0.75) {
                return [
                    'matched' => true,
                    'match_type' => 'fuzzy',
                    'option' => $this->buildOptionData($bestMatch[0], $bestMatch[1]),
                ];
            }
        }

        return null;
    }

    private function resolveOptionSelectionFromOptions(string $normalizedMessage, array $options, string $pendingType): ?array
    {
        $normalizedInput = $this->normalizer->normalize($normalizedMessage);

        $result = $this->doResolveOptionSelection($normalizedInput, $options, $pendingType);

        if ($result !== null) {
            $index = array_search($result['option'], $options, true);
            if ($index === false) {
                foreach ($options as $i => $opt) {
                    if (($opt['entity_id'] ?? $opt['id'] ?? null) === ($result['option']['entity_id'] ?? $result['option']['id'] ?? null)
                        && ($opt['name'] ?? '') === ($result['option']['name'] ?? '')) {
                        $index = $i;
                        break;
                    }
                }
            }
            $result['option']['position'] = $index !== false ? $index + 1 : $result['option']['position'];
        }

        return $result;
    }

    private function doResolveOptionSelection(string $normalizedInput, array $options, string $pendingType): ?array
    {
        foreach ($options as $index => $option) {
            $optionKey = $option['key'] ?? null;
            if ($optionKey !== null && $this->resolveTrustedKey($normalizedInput) === $optionKey) {
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'trusted_key',
                        'option' => $this->buildOptionData($option, $index + 1),
                    ];
                }
            }
        }

        foreach ($options as $index => $option) {
            $label = $option['label'] ?? $option['name'] ?? '';
            $normalizedLabel = $option['normalized_label'] ?? $this->normalizer->normalize($label);

            if ($normalizedInput !== '' && $normalizedInput === $normalizedLabel) {
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'exact_normalized_label',
                        'option' => $this->buildOptionData($option, $index + 1),
                    ];
                }
            }
        }

        foreach ($options as $index => $option) {
            $label = $option['label'] ?? $option['name'] ?? '';

            if (trim($normalizedMessage) !== '' && trim($normalizedMessage) === trim($label)) {
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'exact_label',
                        'option' => $this->buildOptionData($option, $index + 1),
                    ];
                }
            }
        }

        $number = $this->extractNumericSelection($normalizedInput);
        if ($number !== null && $number !== PHP_INT_MAX) {
            $index = $number - 1;
            if (isset($options[$index])) {
                $option = $options[$index];
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'numeric_position',
                        'option' => $this->buildOptionData($option, $number),
                    ];
                }
            }
        }

        $ordinalValue = $this->extractOrdinal($normalizedInput);
        if ($ordinalValue !== null) {
            $index = $ordinalValue - 1;
            if (isset($options[$index])) {
                $option = $options[$index];
                if ($this->isValidOptionForType($option, $pendingType)) {
                    return [
                        'matched' => true,
                        'match_type' => 'ordinal',
                        'option' => $this->buildOptionData($option, $ordinalValue),
                    ];
                }
            }
        }

        if (mb_strlen($normalizedInput) >= 3) {
            $bestMatch = null;
            $bestScore = 0.0;

            foreach ($options as $index => $option) {
                $label = $option['label'] ?? $option['name'] ?? '';
                $normalizedLabel = $option['normalized_label'] ?? $this->normalizer->normalize($label);

                if ($normalizedLabel === '' || mb_strlen($normalizedLabel) < 3) {
                    continue;
                }

                if (! $this->isValidOptionForType($option, $pendingType)) {
                    continue;
                }

                $score = $this->typoMatcher->match($normalizedInput, $normalizedLabel);

                if ($score !== null && $score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = [$option, $index + 1, $score];
                } elseif ($score !== null && $score === $bestScore && $bestMatch !== null) {
                    return null;
                }
            }

            if ($bestMatch !== null && $bestMatch[2] >= 0.75) {
                return [
                    'matched' => true,
                    'match_type' => 'fuzzy',
                    'option' => $this->buildOptionData($bestMatch[0], $bestMatch[1]),
                ];
            }
        }

        return null;
    }

    private function resolveTrustedKey(string $input): ?string
    {
        if (preg_match('/^main-menu:([a-z0-9-]+)$/', $input, $matches)) {
            return 'main-menu:'.$matches[1];
        }

        if (preg_match('/^service-category:(\d+)$/', $input, $matches)) {
            return 'service-category:'.$matches[1];
        }

        if (preg_match('/^service:(\d+)$/', $input, $matches)) {
            return 'service:'.$matches[1];
        }

        if (preg_match('/^water-area:(\d+)$/', $input, $matches)) {
            return 'water-area:'.$matches[1];
        }

        if (preg_match('/^service-action:([a-z][a-z-]*):(\d+)$/', $input, $matches)) {
            return 'service-action:'.$matches[1].':'.$matches[2];
        }

        if (preg_match('/^workflow:(confirm|continue|switch|cancel)$/', $input, $matches)) {
            return 'workflow:'.$matches[1];
        }

        return null;
    }

    private function extractEntityKeyId(string $input, string $pendingType): ?int
    {
        if ($pendingType === 'municipality_main_menu') {
            return null;
        }

        if (preg_match('/^service-category:(\d+)$/', $input, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/^service:(\d+)$/', $input, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/^water-area:(\d+)$/', $input, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function extractOrdinal(string $normalizedInput): ?int
    {
        if (isset(self::ARABIC_ORDINALS[$normalizedInput])) {
            return self::ARABIC_ORDINALS[$normalizedInput];
        }

        return null;
    }

    private function buildOptionData(array $option, int $position): array
    {
        $key = $option['key'] ?? ($option['type'] ?? null);
        $entityType = $option['entity_type'] ?? null;

        if ($entityType === null && isset($option['type'])) {
            $entityType = $option['type'];
        }

        $entityId = $option['entity_id'] ?? ($option['id'] ?? null);
        $label = $option['label'] ?? $option['name'] ?? '';
        $normalizedLabel = $option['normalized_label'] ?? $this->normalizer->normalize($label);

        return [
            'position' => $position,
            'key' => $key,
            'entity_type' => $entityType,
            'entity_id' => $entityId !== null ? (int) $entityId : null,
            'label' => $label,
            'normalized_label' => $normalizedLabel,
            'payload' => $option['payload'] ?? $option['action_payload'] ?? [],
        ];
    }

    private function resolveFuzzyAreaMatch(string $normalizedInput, array $areas): ?ClarificationData
    {
        $input = $this->normalizer->normalize($normalizedInput);

        if (mb_strlen($input) < 3) {
            return null;
        }

        $bestArea = null;
        $bestDistance = PHP_INT_MAX;

        foreach ($areas as $area) {
            $areaName = $area['name'] ?? '';

            if ($areaName === '') {
                continue;
            }

            $areaNorm = $this->normalizer->normalize($areaName);

            if (mb_strlen($areaNorm) < 3) {
                continue;
            }

            $distance = $this->charDistance($input, $areaNorm);
            $maxAllowed = mb_strlen($input) >= 6 && mb_strlen($areaNorm) >= 6 ? 2 : 1;

            if ($distance <= $maxAllowed) {
                if ($bestArea === null || $distance < $bestDistance) {
                    $bestArea = $area;
                    $bestDistance = $distance;
                } elseif ($distance === $bestDistance) {
                    return null;
                }
            }
        }

        if ($bestArea === null) {
            return null;
        }

        return $this->buildAreaSelectionResult($bestArea, $bestArea['number'] ?? 0);
    }

    private function charDistance(string $a, string $b): int
    {
        $charsA = preg_split('//u', $a, -1, PREG_SPLIT_NO_EMPTY);
        $charsB = preg_split('//u', $b, -1, PREG_SPLIT_NO_EMPTY);

        $lenA = count($charsA);
        $lenB = count($charsB);

        $previous = range(0, $lenB);

        for ($i = 1; $i <= $lenA; $i++) {
            $current = [$i];

            for ($j = 1; $j <= $lenB; $j++) {
                $cost = $charsA[$i - 1] === $charsB[$j - 1] ? 0 : 1;

                $current[$j] = min(
                    $previous[$j] + 1,
                    $current[$j - 1] + 1,
                    $previous[$j - 1] + $cost,
                );
            }

            $previous = $current;
        }

        return $previous[$lenB];
    }

    public function resolvePronoun(string $normalizedMessage, ConversationStateData $state): ?ClarificationData
    {
        $matchedPattern = false;
        foreach (self::PRONOUN_PATTERNS as $pattern) {
            if (preg_match($pattern, $normalizedMessage)) {
                $matchedPattern = true;
                break;
            }
        }

        if (! $matchedPattern) {
            return new ClarificationData(needsClarification: false);
        }

        if ($state->currentServiceId === null || $state->currentServiceName === null) {
            return new ClarificationData(
                needsClarification: true,
                message: 'أي خدمة تقصد؟',
                type: 'service',
            );
        }

        return new ClarificationData(
            needsClarification: false,
            selectedServiceId: $state->currentServiceId,
            selectedServiceName: $state->currentServiceName,
            type: 'service',
        );
    }

    public function buildClarificationQuestion(array $candidates, string $type = 'service', ?string $domain = null, ?string $entityType = null): ClarificationData
    {
        $lines = ['هل تقصد:'];
        $options = [];

        foreach ($candidates as $i => $candidate) {
            $num = $i + 1;
            $name = $candidate instanceof ResolvedServiceData ? $candidate->name : ($candidate['name'] ?? '');
            $lines[] = "{$num} {$name}";
            $options[] = [
                'id' => $candidate instanceof ResolvedServiceData ? $candidate->id : ($candidate['id'] ?? null),
                'name' => $name,
                'number' => $num,
                'entity_type' => $entityType ?? 'service',
            ];
        }

        $lines[] = 'ممكن تختار رقم الخدمة اللي تقصدها.';

        return new ClarificationData(
            needsClarification: true,
            message: implode("\n", $lines),
            options: $options,
            type: $type,
            domain: $domain,
            entityType: $entityType,
        );
    }

    public function buildWaterAreaClarification(array $areas, string $message): ClarificationData
    {
        $lines = [$message];
        $options = [];

        foreach ($areas as $i => $area) {
            $num = $i + 1;
            $name = $area['name'] ?? $area;
            $lines[] = "{$num}. {$name}";
            $options[] = [
                'id' => $area['id'] ?? $num,
                'name' => $name,
                'number' => $num,
                'entity_type' => 'water_area',
                'entity_id' => $area['id'] ?? null,
                'key' => "water-area:{$area['id']}",
                'label' => $name,
                'normalized_label' => $this->normalizer->normalize($name),
            ];
        }

        return new ClarificationData(
            needsClarification: true,
            message: implode("\n", $lines),
            options: $options,
            type: 'water_area',
            domain: 'water_schedule',
            entityType: 'water_area',
        );
    }

    private function isValidOptionForType(array $option, string $pendingType): bool
    {
        if (! in_array($pendingType, self::VALID_TYPES, true)) {
            return true;
        }

        $optionType = $option['entity_type'] ?? null;

        if ($pendingType === 'water_area') {
            return $optionType === 'water_area';
        }

        if ($pendingType === 'electronic_service') {
            return $optionType === 'electronic_service' || $optionType === null;
        }

        if ($pendingType === 'service') {
            return $optionType === null || $optionType === 'service' || $optionType === 'electronic_service';
        }

        return $optionType === null || $optionType === $pendingType;
    }

    private function buildSelectionResult(array $option, int $number, string $pendingType): ClarificationData
    {
        return new ClarificationData(
            needsClarification: false,
            selectedOption: $number,
            selectedServiceName: $option['name'] ?? null,
            selectedServiceId: isset($option['id']) ? (int) $option['id'] : null,
            selectedAreaId: ($option['entity_type'] ?? null) === 'water_area' ? ($option['entity_id'] ?? ($option['id'] ?? null)) : null,
            selectedAreaName: ($option['entity_type'] ?? null) === 'water_area' ? ($option['name'] ?? null) : null,
            type: $pendingType,
        );
    }

    private function buildAreaSelectionResult(array $area, int $number): ClarificationData
    {
        return new ClarificationData(
            needsClarification: false,
            selectedOption: $number,
            selectedAreaId: $area['entity_id'] ?? ($area['id'] ?? null),
            selectedAreaName: $area['name'] ?? null,
            type: 'water_area',
            domain: 'water_schedule',
            entityType: 'water_area',
        );
    }

    private function extractNumericSelection(string $normalizedMessage): ?int
    {
        $normalizedMessage = str_replace(
            ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $normalizedMessage,
        );

        if (preg_match('/^(\d+)$/u', $normalizedMessage, $m)) {
            return (int) $m[1];
        }

        if (preg_match('/^رقم\s*(\d+)$/u', $normalizedMessage, $m)) {
            return (int) $m[1];
        }

        $number = $this->extractOrdinalNormalized($normalizedMessage);

        if ($number !== null) {
            return $number;
        }

        return null;
    }

    private function extractOrdinalNormalized(string $normalizedMessage): ?int
    {
        if (isset(self::ARABIC_ORDINALS[$normalizedMessage])) {
            return self::ARABIC_ORDINALS[$normalizedMessage];
        }

        return null;
    }
}
