<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\MunicipalityDomainRouterInterface;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\DTOs\MunicipalityDomainRouteData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityDomainRouter implements MunicipalityDomainRouterInterface
{
    private const DOMAIN_HANDLER_MAP = [
        'municipality_info' => 'municipality_info',
        'departments' => 'departments',
        'water_schedule' => 'water_schedule',
        'jobs' => 'jobs',
        'news' => 'news',
        'announcements' => 'announcements',
        'council_decisions' => 'council_decisions',
        'facilities' => 'facilities',
        'engineering_offices' => 'engineering_offices',
        'council_members' => 'council_members',
    ];

    private const NON_SERVICE_INTENTS = [
        ChatbotIntent::MunicipalityContact,
        ChatbotIntent::MunicipalityPhone,
        ChatbotIntent::MunicipalityEmail,
        ChatbotIntent::MunicipalityAddress,
        ChatbotIntent::MunicipalityWorkingHours,
        ChatbotIntent::MunicipalityAbout,
        ChatbotIntent::DepartmentsList,
        ChatbotIntent::DepartmentSearch,
        ChatbotIntent::DepartmentDetails,
        ChatbotIntent::DepartmentContact,
        ChatbotIntent::WaterSchedule,
        ChatbotIntent::WaterScheduleToday,
        ChatbotIntent::WaterScheduleNext,
        ChatbotIntent::WaterAreaSearch,
        ChatbotIntent::JobsList,
        ChatbotIntent::JobsOpen,
        ChatbotIntent::LatestJobs,
        ChatbotIntent::JobSearch,
        ChatbotIntent::JobDetails,
        ChatbotIntent::JobDeadline,
        ChatbotIntent::LatestNews,
        ChatbotIntent::NewsSearch,
        ChatbotIntent::NewsDetails,
        ChatbotIntent::LatestAnnouncements,
        ChatbotIntent::AnnouncementSearch,
        ChatbotIntent::AnnouncementDetails,
        ChatbotIntent::LatestCouncilDecisions,
        ChatbotIntent::CouncilDecisionSearch,
        ChatbotIntent::CouncilDecisionDetails,
        ChatbotIntent::CouncilDecisionByDate,
        ChatbotIntent::FacilitiesList,
        ChatbotIntent::FacilitySearch,
        ChatbotIntent::FacilityDetails,
        ChatbotIntent::FacilityLocation,
        ChatbotIntent::FacilityWorkingHours,
        ChatbotIntent::EngineeringOfficesList,
        ChatbotIntent::EngineeringOfficeSearch,
        ChatbotIntent::EngineeringOfficeDetails,
        ChatbotIntent::EngineeringOfficeContact,
        ChatbotIntent::CouncilMembersList,
        ChatbotIntent::CouncilMemberSearch,
        ChatbotIntent::CouncilMemberDetails,
        ChatbotIntent::CreateComplaint,
        ChatbotIntent::ContactRequest,
        ChatbotIntent::TrackWorkflow,
        ChatbotIntent::ResumeWorkflow,
        ChatbotIntent::CancelWorkflow,
    ];

    private const INTENT_HANDLER_MAP = [
        'municipality_contact' => 'municipality_contact',
        'municipality_phone' => 'municipality_phone',
        'municipality_email' => 'municipality_email',
        'municipality_address' => 'municipality_address',
        'municipality_working_hours' => 'municipality_working_hours',
        'municipality_about' => 'municipality_about',
        'municipality_mayor' => 'municipality_mayor',
        'departments_list' => 'departments_list',
        'department_search' => 'department_search',
        'department_details' => 'department_details',
        'department_contact' => 'department_contact',
        'water_schedule' => 'water_schedule',
        'water_schedule_today' => 'water_schedule_today',
        'water_schedule_next' => 'water_schedule_next',
        'water_area_search' => 'water_area_search',
        'jobs_list' => 'jobs_list',
        'jobs_open' => 'jobs_open',
        'latest_jobs' => 'latest_jobs',
        'job_search' => 'job_search',
        'job_details' => 'job_details',
        'job_deadline' => 'job_deadline',
        'latest_news' => 'latest_news',
        'news_search' => 'news_search',
        'news_details' => 'news_details',
        'latest_announcements' => 'latest_announcements',
        'announcement_search' => 'announcement_search',
        'announcement_details' => 'announcement_details',
        'latest_council_decisions' => 'latest_council_decisions',
        'council_decision_search' => 'council_decision_search',
        'council_decision_details' => 'council_decision_details',
        'council_decision_by_date' => 'council_decision_by_date',
        'facilities_list' => 'facilities_list',
        'facility_search' => 'facility_search',
        'facility_details' => 'facility_details',
        'facility_location' => 'facility_location',
        'facility_working_hours' => 'facility_working_hours',
        'engineering_offices_list' => 'engineering_offices_list',
        'engineering_office_search' => 'engineering_office_search',
        'engineering_office_details' => 'engineering_office_details',
        'engineering_office_contact' => 'engineering_office_contact',
        'council_members_list' => 'council_members_list',
        'council_member_search' => 'council_member_search',
        'council_member_details' => 'council_member_details',
        'create_complaint' => 'create_complaint',
        'contact_request' => 'contact_request',
        'track_workflow' => 'track_workflow',
        'resume_workflow' => 'resume_workflow',
        'cancel_workflow' => 'cancel_workflow',
    ];

    public function route(
        ChatbotIntent $intent,
        string $message,
        ConversationStateData $context,
    ): MunicipalityDomainRouteData {
        $domain = $intent->domain();
        $intentValue = $intent->value;

        if ($domain === 'general') {
            return new MunicipalityDomainRouteData(
                domain: $domain,
                intent: $intent,
                handlerKey: $intentValue,
                source: 'intent',
            );
        }

        if ($domain === 'electronic_services') {
            // Use context hints but explicit intent always wins
            return new MunicipalityDomainRouteData(
                domain: $domain,
                intent: $intent,
                handlerKey: $intentValue,
                source: 'intent',
            );
        }

        // Phase 6 domains
        $handlerKey = self::INTENT_HANDLER_MAP[$intentValue] ?? $intentValue;

        $requiresEntity = in_array($intent, [
            ChatbotIntent::DepartmentSearch,
            ChatbotIntent::DepartmentDetails,
            ChatbotIntent::DepartmentContact,
            ChatbotIntent::WaterSchedule,
            ChatbotIntent::WaterAreaSearch,
            ChatbotIntent::JobSearch,
            ChatbotIntent::JobDetails,
            ChatbotIntent::NewsSearch,
            ChatbotIntent::NewsDetails,
            ChatbotIntent::AnnouncementSearch,
            ChatbotIntent::AnnouncementDetails,
            ChatbotIntent::CouncilDecisionSearch,
            ChatbotIntent::CouncilDecisionDetails,
            ChatbotIntent::FacilitySearch,
            ChatbotIntent::FacilityDetails,
            ChatbotIntent::FacilityLocation,
            ChatbotIntent::FacilityWorkingHours,
            ChatbotIntent::EngineeringOfficeSearch,
            ChatbotIntent::EngineeringOfficeDetails,
            ChatbotIntent::EngineeringOfficeContact,
            ChatbotIntent::CouncilMemberSearch,
            ChatbotIntent::CouncilMemberDetails,
        ], true);

        $entityType = match ($domain) {
            'departments' => 'department',
            'water_schedule' => 'water_area',
            'jobs' => 'job',
            'news' => 'news',
            'announcements' => 'announcement',
            'council_decisions' => 'council_decision',
            'facilities' => 'facility',
            'engineering_offices' => 'engineering_office',
            'council_members' => 'council_member',
            default => null,
        };

        return new MunicipalityDomainRouteData(
            domain: $domain,
            intent: $intent,
            handlerKey: $handlerKey,
            source: 'intent',
            requiresEntity: $requiresEntity,
            requiredEntityType: $requiresEntity ? $entityType : null,
            explanation: "Routed to {$domain} via intent {$intentValue}",
        );
    }

    public static function isNonServiceIntent(ChatbotIntent $intent): bool
    {
        return in_array($intent, self::NON_SERVICE_INTENTS, true);
    }

    public static function getDomainHandlerKey(string $domain): string
    {
        return self::DOMAIN_HANDLER_MAP[$domain] ?? 'unknown';
    }
}
