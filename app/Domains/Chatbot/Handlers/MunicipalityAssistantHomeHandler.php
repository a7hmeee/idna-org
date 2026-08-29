<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityAssistantHomeHandler implements ChatResponseHandlerInterface
{
    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityAssistantHome;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        return new ChatResponseData(
            message: 'أهلًا وسهلًا في المساعد الذكي لبلدية إذنا. كيف أقدر أساعدك؟',
            type: 'municipality_main_menu',
            actions: [
                ['label' => 'الخدمات الإلكترونية', 'value' => 'الخدمات الإلكترونية'],
                ['label' => 'جدول المياه', 'value' => 'جدول المياه'],
                ['label' => 'تقديم شكوى', 'value' => 'تقديم شكوى'],
                ['label' => 'متابعة طلب', 'value' => 'تتبع طلب'],
                ['label' => 'الوظائف', 'value' => 'وظائف'],
                ['label' => 'الأخبار والإعلانات', 'value' => 'أخبار'],
                ['label' => 'قرارات المجلس', 'value' => 'قرارات المجلس'],
                ['label' => 'أعضاء المجلس', 'value' => 'أعضاء المجلس'],
                ['label' => 'المرافق العامة', 'value' => 'مرافق'],
                ['label' => 'المكاتب الهندسية', 'value' => 'مكاتب هندسية'],
                ['label' => 'معلومات البلدية', 'value' => 'عن البلدية'],
                ['label' => 'تواصل معنا', 'value' => 'تواصل معنا'],
            ],
        );
    }
}
