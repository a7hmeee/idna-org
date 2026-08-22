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
                ['label' => 'الخدمات الإلكترونية', 'value' => 'الخدمات الإلكترونية', 'intent' => 'service_search'],
                ['label' => 'جدول المياه', 'value' => 'جدول المياه', 'intent' => 'water_schedule'],
                ['label' => 'تقديم شكوى', 'value' => 'تقديم شكوى', 'intent' => 'create_complaint'],
                ['label' => 'متابعة طلب', 'value' => 'تتبع طلب', 'intent' => 'track_workflow'],
                ['label' => 'الوظائف', 'value' => 'وظائف', 'intent' => 'jobs_open'],
                ['label' => 'الأخبار والإعلانات', 'value' => 'أخبار', 'intent' => 'latest_news'],
                ['label' => 'قرارات المجلس', 'value' => 'قرارات المجلس', 'intent' => 'latest_council_decisions'],
                ['label' => 'أعضاء المجلس', 'value' => 'أعضاء المجلس', 'intent' => 'council_members_list'],
                ['label' => 'المرافق العامة', 'value' => 'مرافق', 'intent' => 'facilities_list'],
                ['label' => 'المكاتب الهندسية', 'value' => 'مكاتب هندسية', 'intent' => 'engineering_offices_list'],
                ['label' => 'معلومات البلدية', 'value' => 'عن البلدية', 'intent' => 'municipality_about'],
                ['label' => 'تواصل معنا', 'value' => 'تواصل معنا', 'intent' => 'municipality_contact'],
            ],
        );
    }
}
