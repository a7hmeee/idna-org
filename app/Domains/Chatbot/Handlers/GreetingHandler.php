<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class GreetingHandler implements ChatResponseHandlerInterface
{
    public const WELCOME_MESSAGE = 'مرحباً بك في المساعد الذكي لبلدية إذنا. إذا حابب تتكلم معي، اكتب سؤالك أو اختر أحد الخيارات السريعة.';

    public const MAIN_MENU_ACTIONS = [
        ['label' => 'الخدمات الإلكترونية', 'value' => 'الخدمات الإلكترونية'],
        ['label' => 'تقديم شكوى', 'value' => 'تقديم شكوى'],
        ['label' => 'طلب اتصال', 'value' => 'طلب اتصال'],
        ['label' => 'متابعة طلب', 'value' => 'تتبع طلب'],
        ['label' => 'جدول توزيع المياه', 'value' => 'جدول توزيع المياه'],
        ['label' => 'المرافق العامة', 'value' => 'المرافق العامة'],
        ['label' => 'الوظائف', 'value' => 'الوظائف'],
        ['label' => 'أعضاء المجلس البلدي', 'value' => 'أعضاء المجلس البلدي'],
        ['label' => 'قرارات المجلس', 'value' => 'قرارات المجلس'],
        ['label' => 'تواصل مع البلدية', 'value' => 'تواصل مع البلدية'],
    ];

    public const MAIN_MENU_CLARIFICATION_OPTIONS = [
        ['position' => 1, 'key' => 'main-menu:electronic-services', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'الخدمات الإلكترونية'],
        ['position' => 2, 'key' => 'main-menu:municipal-services', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'الخدمات البلدية'],
        ['position' => 3, 'key' => 'main-menu:complaint', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'تقديم شكوى'],
        ['position' => 4, 'key' => 'main-menu:contact-request', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'طلب اتصال'],
        ['position' => 5, 'key' => 'main-menu:tracking', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'متابعة طلب'],
        ['position' => 6, 'key' => 'main-menu:water', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'جدول توزيع المياه'],
        ['position' => 7, 'key' => 'main-menu:facilities', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'المرافق العامة'],
        ['position' => 8, 'key' => 'main-menu:jobs', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'الوظائف'],
        ['position' => 9, 'key' => 'main-menu:council-members', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'أعضاء المجلس البلدي'],
        ['position' => 10, 'key' => 'main-menu:council-decisions', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'قرارات المجلس'],
        ['position' => 11, 'key' => 'main-menu:municipality-contact', 'entity_type' => 'municipality_main_menu', 'entity_id' => null, 'label' => 'تواصل مع البلدية'],
    ];

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::Greeting;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        return new ChatResponseData(
            message: self::WELCOME_MESSAGE,
            type: 'text',
            actions: self::MAIN_MENU_ACTIONS,
            clarificationType: 'municipality_main_menu',
            items: self::MAIN_MENU_CLARIFICATION_OPTIONS,
        );
    }
}
