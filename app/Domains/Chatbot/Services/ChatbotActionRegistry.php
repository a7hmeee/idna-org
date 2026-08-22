<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ChatbotActionRegistry
{
    private const ACTION_MAP = [
        'main-menu:electronic-services' => [
            'intent' => ChatbotIntent::ServiceSearch,
            'domain' => 'electronic_services',
            'label' => 'الخدمات الإلكترونية',
            'handler' => 'guided',
            'electronic' => true,
        ],
        'main-menu:complaint' => [
            'intent' => ChatbotIntent::CreateComplaint,
            'domain' => 'citizen_workflow',
            'label' => 'تقديم شكوى',
            'handler' => 'create_complaint',
        ],
        'main-menu:contact-request' => [
            'intent' => ChatbotIntent::ContactRequest,
            'domain' => 'citizen_workflow',
            'label' => 'طلب اتصال',
            'handler' => 'contact_request',
        ],
        'main-menu:tracking' => [
            'intent' => ChatbotIntent::TrackWorkflow,
            'domain' => 'citizen_workflow',
            'label' => 'متابعة طلب',
            'handler' => 'track_workflow',
        ],
        'main-menu:water' => [
            'intent' => ChatbotIntent::WaterSchedule,
            'domain' => 'water_schedule',
            'label' => 'جدول توزيع المياه',
            'handler' => 'water_schedule',
        ],
        'main-menu:facilities' => [
            'intent' => ChatbotIntent::FacilitiesList,
            'domain' => 'facilities',
            'label' => 'المرافق العامة',
            'handler' => 'facilities_list',
        ],
        'main-menu:jobs' => [
            'intent' => ChatbotIntent::JobsOpen,
            'domain' => 'jobs',
            'label' => 'الوظائف',
            'handler' => 'jobs_open',
        ],
        'main-menu:council-members' => [
            'intent' => ChatbotIntent::CouncilMembersList,
            'domain' => 'council_members',
            'label' => 'أعضاء المجلس البلدي',
            'handler' => 'council_members_list',
        ],
        'main-menu:council-decisions' => [
            'intent' => ChatbotIntent::LatestCouncilDecisions,
            'domain' => 'council_decisions',
            'label' => 'قرارات المجلس',
            'handler' => 'latest_council_decisions',
        ],
        'main-menu:municipality-contact' => [
            'intent' => ChatbotIntent::MunicipalityContact,
            'domain' => 'municipality_info',
            'label' => 'تواصل مع البلدية',
            'handler' => 'municipality_contact',
        ],
    ];

    private const SERVICE_ACTION_MAP = [
        'requirements' => [
            'intent' => ChatbotIntent::ServiceRequirements,
            'domain' => 'electronic_services',
            'label' => 'المتطلبات',
            'handler' => 'service_requirements',
        ],
        'fees' => [
            'intent' => ChatbotIntent::ServiceFees,
            'domain' => 'electronic_services',
            'label' => 'الرسوم',
            'handler' => 'service_fees',
        ],
        'steps' => [
            'intent' => ChatbotIntent::ServiceApplicationSteps,
            'domain' => 'electronic_services',
            'label' => 'خطوات التقديم',
            'handler' => 'service_application_steps',
        ],
        'duration' => [
            'intent' => ChatbotIntent::ServiceDuration,
            'domain' => 'electronic_services',
            'label' => 'مدة الخدمة',
            'handler' => 'service_duration',
        ],
        'location' => [
            'intent' => ChatbotIntent::ServiceLocation,
            'domain' => 'electronic_services',
            'label' => 'مكان التقديم',
            'handler' => 'service_location',
        ],
        'application-link' => [
            'intent' => ChatbotIntent::ServiceOnlineLink,
            'domain' => 'electronic_services',
            'label' => 'رابط التقديم',
            'handler' => 'service_online_link',
        ],
    ];

    public function resolve(string $actionKey): ?array
    {
        return self::ACTION_MAP[$actionKey] ?? null;
    }

    public function resolveServiceAction(string $actionKey, int $serviceId): ?array
    {
        $resolved = self::SERVICE_ACTION_MAP[$actionKey] ?? null;

        if ($resolved === null) {
            return null;
        }

        $resolved['service_id'] = $serviceId;

        return $resolved;
    }

    public function has(string $actionKey): bool
    {
        return isset(self::ACTION_MAP[$actionKey]);
    }

    public function all(): array
    {
        return self::ACTION_MAP;
    }

    public function allServiceActions(): array
    {
        return self::SERVICE_ACTION_MAP;
    }
}
