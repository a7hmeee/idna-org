<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;

final readonly class GuidedServiceDiscoveryService
{
    private const BROAD_REQUEST_PATTERNS = [
        '/^بدي\s+خدمة/u',
        '/^بدي\s+اعمل\s+معاملة/u',
        '/^بدي\s+اقدم\s+طلب/u',
        '/^شو\s+الخدمات/u',
        '/^ساعدني\s+الاقي\s+خدمة/u',
        '/^مش\s+عارف\s+اسم\s+الخدمة/u',
        '/^الخدمات$/u',
        '/^بدي\s+خدمه/u',
        '/^بدي\s+اعرف\s+على\s+الخدمات/u',
        '/^عرض\s+الخدمات/u',
        '/^قائمة\s+الخدمات/u',
        '/^الخدمات\s+المنشورة/u',
        '/^ما\s+الخدمات/u',
        '/^شو\s+الخدمات\s+المنشورة/u',
    ];

    /**
     * Electronic-services entry points. These always open the DB-backed
     * categories flow and never use the intent classifier.
     */
    private const ELECTRONIC_REQUEST_PATTERNS = [
        '/^الخدمات\s+الالكترونية$/u',
        '/^خدمات\s+الكترونية$/u',
        '/^خدمات\s+الالكترونية$/u',
        '/^شو\s+الخدمات\s+الالكترونية$/u',
        '/^شو\s+الخدمات\s+الالكترونيه$/u',
        '/^الخدمات\s+البلدية$/u',
        '/^خدمات\s+البلدية$/u',
        '/^شو\s+الخدمات\s+البلدية$/u',
    ];

    private const STOPWORD_TOKENS = [
        'بدي', 'بده', 'بها', 'بهم', 'اريد', 'ابغا', 'ابغى', 'شو', 'من', 'في',
        'على', 'علي', 'عن', 'عشان', 'حابب', 'اعرف', 'اطلب', 'انا', 'لي',
        'قديش', 'كم', 'كيف',
    ];

    private const ARABIC_ORDINALS = [
        'الاول' => 1, 'الاولي' => 1, 'اول' => 1, 'اولي' => 1, 'واحد' => 1,
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

    /**
     * Service-property actions offered after a service is selected. The
     * keys resolve through ChatbotActionRegistry and never trap the
     * conversation in a waiting state.
     */
    private const SERVICE_ACTION_KEYS = [
        'المتطلبات' => 'requirements',
        'الرسوم' => 'fees',
        'خطوات التقديم' => 'steps',
        'مدة الخدمة' => 'duration',
        'مكان التقديم' => 'location',
        'رابط التقديم' => 'application-link',
    ];

    private const BACK_CANCELLATION_PATTERNS = [
        '/^رجوع$/u',
        '/^الغاء$/u',
        '/^ابدا\s+من\s+جديد$/u',
        '/^ترجيع$/u',
        '/^cancel$/u',
        '/^back$/u',
    ];

    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
        private ArabicTextNormalizer $normalizer,
        private ArabicTypoMatcher $typoMatcher = new ArabicTypoMatcher,
    ) {}

    public function isBackOrCancel(string $normalized): bool
    {
        return $this->checkBackOrCancel($normalized) !== null;
    }

    /**
     * True when the typed text asks to (re)show the available services
     * ("شو الخدمات الي عندكو", "عرض الخدمات", "الخدمات الإلكترونية"…).
     * Used to separate LIST requests from SELECT requests inside guided
     * states, so a list question never lands in the fallback message.
     */
    public function isListServicesRequest(string $normalized): bool
    {
        return $this->isBroadServiceRequest($normalized)
            || $this->isElectronicServicesRequest($normalized);
    }

    public function handle(IncomingChatMessageData $incoming, ConversationStateData $state): ?array
    {
        $normalized = $this->normalizer->normalize($incoming->message);

        if ($normalized === '') {
            return null;
        }

        // Typed trusted keys resolve by ID and never depend on intent
        // prediction. Category keys open the DB-backed category flow.
        // The normalizer strips ":", so the raw message is also matched.
        if (preg_match('/^service-category:(\d+)$/', $incoming->message, $m)) {
            return $this->startCategoryServices((int) $m[1]);
        }

        if (preg_match('/^service:(\d+)$/', $incoming->message, $m)) {
            return $this->handleServiceId((int) $m[1]);
        }

        if (preg_match('/^service-category:(\d+)$/', $normalized, $m)) {
            return $this->startCategoryServices((int) $m[1]);
        }

        if (preg_match('/^service:(\d+)$/', $normalized, $m)) {
            return $this->handleServiceId((int) $m[1]);
        }

        \Log::debug('GDS.handle', [
            'message' => $incoming->message,
            'normalized' => $normalized,
            'state' => $state->state->value,
            'pending_field' => $state->pendingField,
            'clarification_options_count' => count($state->clarificationOptions),
        ]);

        if ($state->state === ConversationState::WaitingForServiceSelection) {
            return $this->handleServiceSelection($normalized, $state, $incoming->sessionId);
        }

        // The electronic flow is the only service flow: an explicit request
        // ("الخدمات الإلكترونية") and a broad service request ("بدي خدمة",
        // "شو الخدمات") both open the categories list straight from the
        // database.
        if ($this->isElectronicServicesRequest($normalized) || $this->isBroadServiceRequest($normalized)) {
            return $this->startElectronicDiscovery($incoming);
        }

        // A category or service is already on screen: typing a category
        // name re-opens that category's services instead of falling into
        // the unknown-message flow.
        if ($state->currentServiceId !== null || $state->currentCategoryId !== null) {
            $categoryMatch = $this->matchCategoryFromDatabase($normalized);

            if ($categoryMatch !== null) {
                return $this->startCategoryServices((int) $categoryMatch['id']);
            }
        }

        return null;
    }

    /**
     * True when the typed text matches one of the currently offered
     * service/category options. Such input is a selection, never a domain
     * switch to whatever intent prediction would suggest.
     */
    public function matchesOfferedOption(string $normalized, ConversationStateData $state): bool
    {
        if ($state->state !== ConversationState::WaitingForServiceSelection) {
            return false;
        }

        if (preg_match('/^service:(\d+)$/', $normalized)) {
            return true;
        }

        if (preg_match('/^service-category:(\d+)$/', $normalized)) {
            return true;
        }

        foreach ($state->clarificationOptions as $option) {
            $name = $this->normalizer->normalize((string) ($option['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            if ($normalized === $name || str_contains($normalized, $name) || str_contains($name, $normalized)) {
                return true;
            }
        }

        return false;
    }

    /**
     * A user clicked "service:{id}" — select it and show the DB-backed
     * service details.
     */
    public function handleServiceId(int $serviceId): array
    {
        $overview = $this->serviceQuery->getPublishedOverview($serviceId);

        if ($overview === null) {
            return [
                'response' => new ChatResponseData(
                    message: 'الخدمة المطلوبة غير متوفرة حالياً.',
                    type: 'text',
                    isFallbackResponse: true,
                ),
                'context' => [
                    'state' => ConversationState::Normal->value,
                    'current_domain' => 'electronic_services',
                ],
            ];
        }

        return $this->selectedServicePayload($serviceId, $overview->name);
    }

    /**
     * The electronic-services flow: DB-backed categories list. Only
     * categories that actually have published services are shown. Picking
     * a category opens its published services; picking a service shows the
     * real DB-backed details. Selection by number or name happens on the
     * following turn from the persisted options.
     */
    public function startElectronicDiscovery(IncomingChatMessageData $incoming): array
    {
        $categories = $this->serviceQuery->getPublishedServiceCategories();

        // Only categories with at least one published service are offered.
        $categories = array_values(array_filter(
            $categories,
            fn (array $category): bool => ! empty($this->serviceQuery->getPublishedServicesByCategory((int) $category['id'])),
        ));

        if (empty($categories)) {
            return [
                'response' => new ChatResponseData(
                    message: 'لا توجد تصنيفات خدمات منشورة حالياً.',
                    type: 'text',
                ),
                'context' => [
                    'state' => ConversationState::Normal->value,
                    'current_domain' => 'electronic_services',
                    'current_category_id' => null,
                    'current_category_name' => null,
                    'current_service_id' => null,
                    'current_service_name' => null,
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                ],
            ];
        }

        // The list is the municipality's published services; the header
        // echoes the wording the citizen actually asked for.
        $header = str_contains($this->normalizer->normalize($incoming->message), 'بلدية')
            ? 'خدمات البلدية — اختار التصنيف:'
            : 'الخدمات الإلكترونية — اختار التصنيف:';

        $lines = [$header];
        $actions = [];

        foreach ($categories as $i => $cat) {
            $num = $i + 1;
            $lines[] = "{$num} {$cat['name']}";
            $actions[] = [
                'label' => $cat['name'],
                'value' => "service-category:{$cat['id']}",
                'payload' => ['category_id' => $cat['id']],
                'category_id' => $cat['id'],
            ];
        }

        $lines[] = 'ممكن تختار رقم التصنيف أو اسمه.';

        return [
            'response' => new ChatResponseData(
                message: implode("\n", $lines),
                type: 'text',
                actions: $actions,
            ),
            'context' => [
                'state' => ConversationState::WaitingForServiceSelection->value,
                'current_domain' => 'electronic_services',
                'current_category_id' => null,
                'current_category_name' => null,
                'current_service_id' => null,
                'current_service_name' => null,
                'needs_clarification' => false,
                'pending_field' => 'service_category',
                'pending_selected_option' => null,
                'clarification_options' => array_map(fn (array $cat, int $i): array => [
                    'id' => $cat['id'],
                    'name' => $cat['name'],
                    'label' => $cat['name'],
                    'normalized_label' => $this->normalizer->normalize($cat['name']),
                    'key' => "service-category:{$cat['id']}",
                    'type' => "service-category:{$cat['id']}",
                    'entity_type' => 'service_category',
                    'entity_id' => $cat['id'],
                    'position' => $i + 1,
                ], $categories, array_keys($categories)),
                'last_intent' => ChatbotIntent::ServiceSearch->value,
                'should_stop_pipeline' => true,
            ],
        ];
    }

    /**
     * Opens a single category's published services, straight from the
     * database.
     */
    public function startCategoryServices(int $categoryId, ?string $categoryName = null): array
    {
        $category = $this->serviceQuery->getCategoryById($categoryId);

        if ($category === null) {
            // Verified against the database before answering: the category
            // does not exist or is not public.
            return [
                'response' => new ChatResponseData(
                    message: 'التصنيف المطلوب غير متوفر حالياً.',
                    type: 'text',
                    isFallbackResponse: true,
                ),
                'context' => [
                    'state' => ConversationState::Normal->value,
                    'current_domain' => 'electronic_services',
                ],
            ];
        }

        $name = $category['name'] ?? $categoryName ?? '';

        $services = $this->serviceQuery->getPublishedServicesByCategory($categoryId);

        if (empty($services)) {
            // Verified against the database before answering: the category
            // exists but nothing is published under it yet.
            return [
                'response' => new ChatResponseData(
                    message: "لا توجد خدمات منشورة في هذا التصنيف حالياً.\nاختر تصنيفاً آخر.",
                    type: 'text',
                    actions: [
                        ['label' => 'تصنيفات الخدمات', 'value' => 'main-menu:electronic-services'],
                    ],
                ),
                'context' => [
                    'state' => ConversationState::Normal->value,
                    'current_domain' => 'electronic_services',
                    'current_category_id' => $categoryId,
                    'current_category_name' => $name,
                    'current_service_id' => null,
                    'current_service_name' => null,
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                    'last_intent' => ChatbotIntent::ServiceSearch->value,
                    'should_stop_pipeline' => true,
                ],
            ];
        }

        return $this->servicesListPayload($services, $categoryId, $name);
    }

    /**
     * The numbered services list of one category, straight from the
     * database.
     */
    private function servicesListPayload(array $services, int $categoryId, string $categoryName): array
    {
        $lines = ["خدمات {$categoryName}:"];
        $actions = [];

        foreach ($services as $i => $svc) {
            $num = $i + 1;
            $lines[] = "{$num} {$svc['name']}";
            $actions[] = [
                'label' => $svc['name'],
                'value' => "service:{$svc['id']}",
                'payload' => ['service_id' => $svc['id']],
                'service_id' => $svc['id'],
                'category_id' => $svc['category_id'] ?? $categoryId,
            ];
        }

        $lines[] = 'ممكن تختار رقم الخدمة أو اسمها.';

        return [
            'response' => new ChatResponseData(
                message: implode("\n", $lines),
                type: 'text',
                actions: $actions,
            ),
            'context' => [
                'state' => ConversationState::WaitingForServiceSelection->value,
                'current_domain' => 'electronic_services',
                'current_category_id' => $categoryId,
                'current_category_name' => $categoryName,
                'current_service_id' => null,
                'current_service_name' => null,
                'needs_clarification' => false,
                'pending_field' => 'electronic_service',
                'pending_selected_option' => null,
                'clarification_options' => array_map(fn (array $svc, int $i): array => [
                    'id' => $svc['id'],
                    'name' => $svc['name'],
                    'label' => $svc['name'],
                    'normalized_label' => $this->normalizer->normalize($svc['name']),
                    'key' => "service:{$svc['id']}",
                    'type' => "service:{$svc['id']}",
                    'entity_type' => 'electronic_service',
                    'entity_id' => $svc['id'],
                    'position' => $i + 1,
                ], $services, array_keys($services)),
                'last_intent' => ChatbotIntent::ServiceSearch->value,
                'should_stop_pipeline' => true,
            ],
        ];
    }

    private function isElectronicServicesRequest(string $normalized): bool
    {
        foreach (self::ELECTRONIC_REQUEST_PATTERNS as $pattern) {
            if (preg_match($pattern, $normalized)) {
                return true;
            }
        }

        return false;
    }

    private function isBroadServiceRequest(string $normalized): bool
    {
        foreach (self::BROAD_REQUEST_PATTERNS as $pattern) {
            if (preg_match($pattern, $normalized)) {
                return true;
            }
        }

        return false;
    }

    private function handleServiceSelection(string $normalized, ConversationStateData $state, string $sessionId): ?array
    {
        $backResult = $this->checkBackOrCancel($normalized);

        if ($backResult === 'cancel') {
            return [
                'response' => new ChatResponseData(
                    message: 'تم إلغاء البحث عن الخدمة. كيف أقدر أساعدك؟',
                    type: 'text',
                    actions: [
                        ['label' => 'الخدمات الإلكترونية', 'value' => 'main-menu:electronic-services'],
                        ['label' => 'تقديم شكوى', 'value' => 'main-menu:complaint'],
                        ['label' => 'طلب اتصال', 'value' => 'main-menu:contact-request'],
                        ['label' => 'متابعة طلب', 'value' => 'main-menu:tracking'],
                        ['label' => 'جدول توزيع المياه', 'value' => 'main-menu:water'],
                    ],
                ),
                'context' => [
                    'state' => ConversationState::Normal->value,
                    'current_domain' => null,
                    'current_category_id' => null,
                    'current_category_name' => null,
                    'current_service_id' => null,
                    'current_service_name' => null,
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                ],
            ];
        }

        if ($backResult === 'back') {
            // Back from a category's services list goes to the categories;
            // back from the categories list refreshes it.
            if ($state->pendingField === 'electronic_service') {
                if ($state->currentCategoryId !== null) {
                    return $this->startCategoryServices($state->currentCategoryId, $state->currentCategoryName);
                }
            }

            $electronicRequest = new IncomingChatMessageData(
                message: 'الخدمات الإلكترونية',
                sessionId: $sessionId,
            );

            return $this->startElectronicDiscovery($electronicRequest);
        }

        $options = $state->clarificationOptions;

        if (empty($options)) {
            return null;
        }

        $matchedBy = 'none';
        $matched = $this->matchServiceByNumber($normalized, $options);

        if ($matched === null) {
            $matched = $this->matchService($normalized, $options);
            $matchedBy = 'exact_normalized_label';
        } else {
            $matchedBy = 'numeric_position';
        }

        if ($matched === null) {
            $matched = $this->tokenAwareServiceMatch($normalized, $options);
            $matchedBy = 'token_aware_label';
        }

        if ($matched === null) {
            // The user may have typed the name of a category or service
            // from elsewhere. Resolve it straight from the database —
            // never assume it does not exist.
            $categoryMatch = $this->matchCategoryFromDatabase($normalized);

            if ($categoryMatch !== null) {
                return $this->startCategoryServices((int) $categoryMatch['id']);
            }

            $serviceMatch = $this->matchServiceFromDatabase($normalized);

            if ($serviceMatch !== null) {
                return $this->handleServiceId((int) $serviceMatch['id']);
            }

            // A LIST request inside a selection state re-shows the current
            // list (categories or category services) instead of failing.
            if ($this->isListServicesRequest($normalized)) {
                if ($state->pendingField === 'electronic_service' && $state->currentCategoryId !== null) {
                    return $this->startCategoryServices($state->currentCategoryId, $state->currentCategoryName);
                }

                $electronicRequest = new IncomingChatMessageData(
                    message: 'الخدمات الإلكترونية',
                    sessionId: $sessionId,
                );

                return $this->startElectronicDiscovery($electronicRequest);
            }

            // Second consecutive fallback: stop guessing and hand over the
            // current list so the user can simply pick.
            if ($state->fallbackCount >= 2) {
                if ($state->pendingField === 'electronic_service' && $state->currentCategoryId !== null) {
                    return $this->startCategoryServices($state->currentCategoryId, $state->currentCategoryName);
                }

                $electronicRequest = new IncomingChatMessageData(
                    message: 'الخدمات الإلكترونية',
                    sessionId: $sessionId,
                );

                return $this->startElectronicDiscovery($electronicRequest);
            }

            return [
                'response' => new ChatResponseData(
                    message: 'مش قادر أحدد الخدمة المطلوبة 😅 اختار خدمة من القائمة أو اكتب اسمها.',
                    type: 'text',
                    actions: array_map(fn (array $opt): array => [
                        'label' => $opt['name'],
                        'value' => $opt['key'] ?? (string) $opt['position'],
                        'payload' => ['option_id' => $opt['id'] ?? null],
                    ], $options),
                    isFallbackResponse: true,
                ),
                'context' => [
                    'state' => $state->state->value,
                    'current_domain' => $state->currentDomain,
                    'current_category_id' => $state->currentCategoryId,
                    'current_category_name' => $state->currentCategoryName,
                    'current_service_id' => $state->currentServiceId,
                    'current_service_name' => $state->currentServiceName,
                    'needs_clarification' => true,
                    'pending_field' => 'service_selection',
                    'pending_selected_option' => null,
                    'clarification_options' => $state->clarificationOptions,
                    'should_stop_pipeline' => true,
                ],
            ];
        }

        $entityType = $matched['entity_type'] ?? null;
        $optionKey = $matched['key'] ?? '';

        // A category selection opens the category's services.
        if ($entityType === 'service_category' || str_starts_with((string) $optionKey, 'service-category:')) {
            $categoryId = (int) ($matched['entity_id'] ?? $matched['id'] ?? 0);

            ChatbotTrace::log([
                'event' => 'resolution',
                'session_id' => $sessionId,
                'raw_user_text' => null,
                'path' => 'clarification',
                'method' => $matchedBy,
                'entity_type' => 'service_category',
                'matched_label' => $matched['name'] ?? null,
                'matched_id' => $categoryId,
                'matched_position' => $matched['position'] ?? null,
            ]);

            return $this->startCategoryServices($categoryId, $matched['name'] ?? null);
        }

        $serviceId = (int) $matched['id'];
        $serviceName = $matched['name'];

        ChatbotTrace::log([
            'event' => 'resolution',
            'session_id' => $sessionId,
            'raw_user_text' => null,
            'path' => 'clarification',
            'method' => $matchedBy,
            'entity_type' => 'electronic_service',
            'matched_label' => $serviceName,
            'matched_id' => $serviceId,
            'matched_position' => $matched['position'] ?? null,
        ]);

        return $this->selectedServicePayload($serviceId, $serviceName, $state);
    }

    /**
     * The full DB-backed service details. Every section is rendered only
     * when the database actually has that data — no hardcoded content.
     */
    private function selectedServicePayload(int $serviceId, string $serviceName, ?ConversationStateData $state = null): array
    {
        $overview = $this->serviceQuery->getPublishedOverview($serviceId);

        if ($overview === null) {
            return [
                'response' => new ChatResponseData(
                    message: 'الخدمة المطلوبة غير متوفرة حالياً.',
                    type: 'text',
                    isFallbackResponse: true,
                ),
                'context' => [
                    'state' => ConversationState::Normal->value,
                    'current_domain' => 'electronic_services',
                ],
            ];
        }

        $categoryId = $state?->currentCategoryId;
        $categoryName = $state?->currentCategoryName;
        $displayName = $overview->name ?? $serviceName;

        $lines = [$displayName];

        if (! empty($overview->description)) {
            $lines[] = '';
            $lines[] = $overview->description;
        }

        $requirements = array_values(array_filter(
            is_array($overview->requirements) ? $overview->requirements : [],
            fn (mixed $requirement): bool => is_string($requirement) && $requirement !== '',
        ));

        if (! empty($requirements)) {
            $lines[] = '';
            $lines[] = 'المتطلبات:';
            foreach ($requirements as $requirement) {
                $lines[] = "• {$requirement}";
            }
        }

        $fees = array_values(array_filter(
            is_array($overview->fees) ? $overview->fees : [],
            fn (mixed $fee): bool => is_string($fee) && $fee !== '',
        ));

        if (! empty($fees)) {
            $lines[] = '';
            $lines[] = 'الرسوم:';
            foreach ($fees as $fee) {
                $lines[] = "• {$fee}";
            }
        }

        if (! empty($overview->processingTime)) {
            $lines[] = '';
            $lines[] = "مدة الخدمة: {$overview->processingTime}";
        }

        $steps = array_values(array_filter(
            is_array($overview->steps) ? $overview->steps : [],
            fn (mixed $step): bool => is_string($step) && $step !== '',
        ));

        if (! empty($steps)) {
            $lines[] = '';
            $lines[] = 'خطوات التقديم:';
            foreach ($steps as $index => $step) {
                $lines[] = ($index + 1).". {$step}";
            }
        }

        if (! empty($overview->location)) {
            $lines[] = '';
            $lines[] = "مكان التقديم: {$overview->location}";
        }

        if (! empty($overview->portalUrl)) {
            $lines[] = '';
            $lines[] = "رابط التقديم: {$overview->portalUrl}";
        }

        $lines[] = '';
        $lines[] = 'ممكن تسأل عن أي تفصيل:';

        $actions = [];

        foreach (self::SERVICE_ACTION_KEYS as $actionLabel => $actionKey) {
            $actions[] = [
                'label' => $actionLabel,
                'value' => "service-action:{$actionKey}:{$serviceId}",
                'payload' => ['service_id' => $serviceId],
                'service_id' => $serviceId,
                'service_name' => $displayName,
            ];
        }

        return [
            'response' => new ChatResponseData(
                message: implode("\n", $lines),
                type: 'text',
                actions: $actions,
            ),
            'context' => [
                'state' => ConversationState::Normal->value,
                'current_domain' => 'electronic_services',
                'current_category_id' => $categoryId,
                'current_category_name' => $categoryName,
                'current_service_id' => $serviceId,
                'current_service_name' => $displayName,
                'needs_clarification' => false,
                'pending_field' => null,
                'pending_selected_option' => null,
                'clarification_options' => [],
                'last_intent' => ChatbotIntent::ServiceSearch->value,
                'should_stop_pipeline' => true,
            ],
        ];
    }

    private function checkBackOrCancel(string $normalized): ?string
    {
        foreach (self::BACK_CANCELLATION_PATTERNS as $pattern) {
            if (preg_match($pattern, $normalized)) {
                if (str_contains($normalized, 'الغاء') || str_contains($normalized, 'cancel')) {
                    return 'cancel';
                }

                return 'back';
            }
        }

        return null;
    }

    /**
     * Resolves a category name straight from the database. The database —
     * never a hardcoded map — decides whether a category exists.
     */
    private function matchCategoryFromDatabase(string $normalized): ?array
    {
        $normalizedInput = $this->normalizer->normalize($normalized);

        if ($normalizedInput === '') {
            return null;
        }

        foreach ($this->serviceQuery->getPublishedServiceCategories() as $category) {
            $categoryName = $category['name'] ?? '';

            if ($categoryName === '') {
                continue;
            }

            if ($normalizedInput === $this->normalizer->normalize($categoryName)) {
                return $category;
            }
        }

        return null;
    }

    /**
     * Resolves a service name straight from the database across ALL
     * published services. The database — never a hardcoded map — decides
     * whether a service exists.
     */
    private function matchServiceFromDatabase(string $normalized): ?array
    {
        $normalizedInput = $this->normalizer->normalize($normalized);

        if ($normalizedInput === '') {
            return null;
        }

        $allServices = [];

        foreach ($this->serviceQuery->getPublishedServiceCategories() as $category) {
            foreach ($this->serviceQuery->getPublishedServicesByCategory((int) $category['id']) as $svc) {
                $svcName = $svc['name'] ?? '';

                if ($svcName === '') {
                    continue;
                }

                if ($normalizedInput === $this->normalizer->normalize($svcName)) {
                    return $svc;
                }

                $allServices[] = $svc;
            }
        }

        return $this->tokenAwareServiceMatch($normalizedInput, $allServices);
    }

    /**
     * Token-aware matching for names the citizen typed loosely ("الخدمة
     * الرقمية", "بدي طلب خدمة رقمية"). Every meaningful input token must
     * appear inside the candidate name (after removing the "ال" article),
     * and exactly one candidate may satisfy that — ambiguity returns null.
     */
    private function tokenAwareServiceMatch(string $normalized, array $options): ?array
    {
        $inputTokens = array_values(array_filter(
            preg_split('/\s+/u', $normalized) ?: [],
            fn (string $token): bool => $token !== '' && ! in_array($token, self::STOPWORD_TOKENS, true),
        ));

        if (count($inputTokens) < 2) {
            return null;
        }

        $dearticledInput = array_map(fn (string $token): string => $this->deArticle($token), $inputTokens);

        $matches = [];

        foreach ($options as $option) {
            $nameTokens = preg_split('/\s+/u', $this->normalizer->normalize((string) ($option['name'] ?? ''))) ?: [];
            $dearticledName = array_map(fn (string $token): string => $this->deArticle($token), $nameTokens);
            $nameJoin = implode(' ', $dearticledName);

            $allContained = true;

            foreach ($dearticledInput as $token) {
                if (! in_array($token, $dearticledName, true) && ! str_contains($nameJoin, $token)) {
                    $allContained = false;
                    break;
                }
            }

            if ($allContained) {
                $matches[] = $option;
            }
        }

        return count($matches) === 1 ? $matches[0] : null;
    }

    private function deArticle(string $token): string
    {
        return preg_replace('/^ال/u', '', $token) ?? $token;
    }

    private function matchServiceByNumber(string $normalized, array $options): ?array
    {
        $normalized = str_replace(
            ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $normalized,
        );

        $number = null;

        if (preg_match('/^(?:رقم\s*)?(\d+)$/u', $normalized, $m)) {
            $number = (int) $m[1];
        } elseif (isset(self::ARABIC_ORDINALS[$normalized])) {
            $number = self::ARABIC_ORDINALS[$normalized];
        }

        if ($number === null) {
            return null;
        }

        $index = $number - 1;

        return $options[$index] ?? null;
    }

    private function matchService(string $normalized, array $options): ?array
    {
        $bestMatch = null;
        $bestScore = 0.0;

        foreach ($options as $option) {
            $optionNormalizedLabel = $option['normalized_label'] ?? '';
            $optionName = $option['name'] ?? '';
            $normalizedOptionName = $this->normalizer->normalize($optionName);

            if ($normalized === '') {
                continue;
            }

            if ($normalized === $optionNormalizedLabel || $normalized === $normalizedOptionName) {
                return $option;
            }

            $score = $this->typoMatcher->match($normalized, $normalizedOptionName);
            if ($score !== null && $score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $option;
            } elseif ($score !== null && $score === $bestScore && $bestMatch !== null) {
                return null;
            }
        }

        if ($bestMatch !== null && $bestScore >= 0.80) {
            return $bestMatch;
        }

        return null;
    }
}
