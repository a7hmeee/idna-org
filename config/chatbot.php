<?php

declare(strict_types=1);

return [
    'enabled' => env('CHATBOT_ENABLED', false),

    'model' => env('CHATBOT_MODEL', 'default'),

    'max_conversation_length' => (int) env('CHATBOT_MAX_CONVERSATION_LENGTH', 50),

    'session' => [
        'ttl' => (int) env('CHATBOT_SESSION_TTL', 3600),
        'cookie' => env('CHATBOT_SESSION_COOKIE', 'chatbot_session'),
    ],

    'response' => [
        'timeout' => (int) env('CHATBOT_RESPONSE_TIMEOUT', 30),
    ],

    // Phase 2 - Rule Based MVP
    'rule_based_enabled' => env('CHATBOT_RULE_BASED_ENABLED', true),

    'max_message_length' => (int) env('CHATBOT_MAX_MESSAGE_LENGTH', 500),

    'search_limit' => (int) env('CHATBOT_SEARCH_LIMIT', 5),

    'session_key' => env('CHATBOT_SESSION_KEY', 'chatbot_session_id'),

    'store_messages' => env('CHATBOT_STORE_MESSAGES', true),

    'public_disclaimer' => env(
        'CHATBOT_PUBLIC_DISCLAIMER',
        'هذا المساعد يقدم معلومات عامة عن الخدمات البلدية ولا يعتبر وثيقة رسمية.',
    ),

    'rate_limit' => [
        'max_attempts' => (int) env('CHATBOT_RATE_LIMIT_MAX', 30),
        'decay_seconds' => (int) env('CHATBOT_RATE_LIMIT_DECAY', 60),
    ],

    // Phase 3 — ML Intent Classification
    'ml_enabled' => env('CHATBOT_ML_ENABLED', false),

    'ml_default_confidence_threshold' => (float) env('CHATBOT_ML_CONFIDENCE_THRESHOLD', 0.70),

    'ml_model_storage_path' => env(
        'CHATBOT_MODEL_STORAGE_PATH',
        storage_path('app/private/chatbot/models'),
    ),

    'training' => [
        'minimum_examples_per_intent' => (int) env('CHATBOT_TRAINING_MIN_EXAMPLES', 10),
        'default_algorithm' => env('CHATBOT_TRAINING_ALGORITHM', 'naive_bayes'),
        'seed_on_migrate' => env('CHATBOT_TRAINING_SEED_ON_MIGRATE', false),
    ],

    // Phase 4 — Conversation Context
    'context' => [
        'ttl' => (int) env('CHATBOT_CONTEXT_TTL', 1200),
        'enabled' => env('CHATBOT_CONTEXT_ENABLED', true),
    ],

    // Phase 5 — Smart Service Search
    'service_search' => [
        'enabled' => env('CHATBOT_SMART_SEARCH_ENABLED', true),
        'minimum_token_length' => 2,
        'result_limit' => 5,
        'auto_select_threshold' => 0.88,
        'clarification_threshold' => 0.55,
        'minimum_score_gap' => 0.15,
        'cache_ttl' => 3600,
        'stop_words' => [
            'بدي',
            'اريد',
            'أريد',
            'حاب',
            'حابب',
            'ممكن',
            'لو',
            'سمحت',
            'اعرف',
            'معرفه',
            'معرفة',
            'عن',
            'على',
            'في',
            'من',
            'الى',
            'إلى',
            'الي',
            'شو',
            'ايش',
            'إيش',
            'كيف',
            'خدمة',
            'معلومات',
            'استفسار',
            'احكيلي',
            'تريد',
            'نريد',
            'عندي',
            'دورلي',
        ],
    ],

    // Phase 6 — Domain Cache Configuration
    'cache' => [
        'municipality_info' => (int) env('CHATBOT_CACHE_MUNICIPALITY', 86400),
        'departments' => (int) env('CHATBOT_CACHE_DEPARTMENTS', 3600),
        'facilities' => (int) env('CHATBOT_CACHE_FACILITIES', 3600),
        'engineering_offices' => (int) env('CHATBOT_CACHE_ENG_OFFICES', 3600),
        'council_members' => (int) env('CHATBOT_CACHE_COUNCIL_MEMBERS', 3600),
        'jobs' => (int) env('CHATBOT_CACHE_JOBS', 900),
        'news' => (int) env('CHATBOT_CACHE_NEWS', 600),
        'announcements' => (int) env('CHATBOT_CACHE_ANNOUNCEMENTS', 600),
        'council_decisions' => (int) env('CHATBOT_CACHE_COUNCIL_DECISIONS', 900),
        'water_schedules' => (int) env('CHATBOT_CACHE_WATER_SCHEDULES', 300),
    ],

    // Phase 8 - Chatbot Analytics
    'analytics' => [
        'enabled' => env('CHATBOT_ANALYTICS_ENABLED', true),
        'slow_threshold_ms' => (int) env('CHATBOT_ANALYTICS_SLOW_THRESHOLD', 500),
    ],

    'public_widget' => [
        'enabled' => env('CHATBOT_PUBLIC_WIDGET_ENABLED', true),
    ],
];
