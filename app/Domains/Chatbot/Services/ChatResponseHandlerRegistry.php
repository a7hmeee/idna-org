<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Handlers\AnnouncementDetailsHandler;
use App\Domains\Chatbot\Handlers\AnnouncementSearchHandler;
use App\Domains\Chatbot\Handlers\CancelWorkflowHandler;
use App\Domains\Chatbot\Handlers\ContactRequestHandler;
use App\Domains\Chatbot\Handlers\CouncilDecisionByDateHandler;
use App\Domains\Chatbot\Handlers\CouncilDecisionDetailsHandler;
use App\Domains\Chatbot\Handlers\CouncilDecisionSearchHandler;
use App\Domains\Chatbot\Handlers\CouncilMemberDetailsHandler;
use App\Domains\Chatbot\Handlers\CouncilMemberSearchHandler;
use App\Domains\Chatbot\Handlers\CouncilMembersListHandler;
use App\Domains\Chatbot\Handlers\CreateComplaintHandler;
use App\Domains\Chatbot\Handlers\DepartmentContactHandler;
use App\Domains\Chatbot\Handlers\DepartmentDetailsHandler;
use App\Domains\Chatbot\Handlers\DepartmentSearchHandler;
use App\Domains\Chatbot\Handlers\DepartmentsListHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficeContactHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficeDetailsHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficeSearchHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficesListHandler;
use App\Domains\Chatbot\Handlers\FacilitiesListHandler;
use App\Domains\Chatbot\Handlers\FacilityDetailsHandler;
use App\Domains\Chatbot\Handlers\FacilityLocationHandler;
use App\Domains\Chatbot\Handlers\FacilitySearchHandler;
use App\Domains\Chatbot\Handlers\FacilityWorkingHoursHandler;
use App\Domains\Chatbot\Handlers\GreetingHandler;
use App\Domains\Chatbot\Handlers\JobDeadlineHandler;
use App\Domains\Chatbot\Handlers\JobDetailsHandler;
use App\Domains\Chatbot\Handlers\JobSearchHandler;
use App\Domains\Chatbot\Handlers\LatestAnnouncementsHandler;
use App\Domains\Chatbot\Handlers\LatestCouncilDecisionsHandler;
use App\Domains\Chatbot\Handlers\LatestJobsHandler;
use App\Domains\Chatbot\Handlers\LatestNewsHandler;
use App\Domains\Chatbot\Handlers\MunicipalityAboutHandler;
use App\Domains\Chatbot\Handlers\MunicipalityAddressHandler;
use App\Domains\Chatbot\Handlers\MunicipalityContactHandler;
use App\Domains\Chatbot\Handlers\MunicipalityEmailHandler;
use App\Domains\Chatbot\Handlers\MunicipalityMayorHandler;
use App\Domains\Chatbot\Handlers\MunicipalityPhoneHandler;
use App\Domains\Chatbot\Handlers\MunicipalityWorkingHoursHandler;
use App\Domains\Chatbot\Handlers\NewsDetailsHandler;
use App\Domains\Chatbot\Handlers\NewsSearchHandler;
use App\Domains\Chatbot\Handlers\OpenJobsHandler;
use App\Domains\Chatbot\Handlers\ResumeWorkflowHandler;
use App\Domains\Chatbot\Handlers\ServiceApplicationStepsHandler;
use App\Domains\Chatbot\Handlers\ServiceDurationHandler;
use App\Domains\Chatbot\Handlers\ServiceFeesHandler;
use App\Domains\Chatbot\Handlers\ServiceLocationHandler;
use App\Domains\Chatbot\Handlers\ServiceOnlineLinkHandler;
use App\Domains\Chatbot\Handlers\ServiceOverviewHandler;
use App\Domains\Chatbot\Handlers\ServiceRequirementsHandler;
use App\Domains\Chatbot\Handlers\ServiceSearchHandler;
use App\Domains\Chatbot\Handlers\ThanksHandler;
use App\Domains\Chatbot\Handlers\TrackWorkflowHandler;
use App\Domains\Chatbot\Handlers\UnknownHandler;
use App\Domains\Chatbot\Handlers\WaterAreaSearchHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleNextHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleTodayHandler;

final readonly class ChatResponseHandlerRegistry
{
    private array $handlers;

    public function __construct(
        // Phase 2 — Foundation handlers
        GreetingHandler $greetingHandler,
        ThanksHandler $thanksHandler,
        ServiceSearchHandler $serviceSearchHandler,
        ServiceOverviewHandler $serviceOverviewHandler,
        ServiceApplicationStepsHandler $serviceApplicationStepsHandler,
        ServiceRequirementsHandler $serviceRequirementsHandler,
        ServiceFeesHandler $serviceFeesHandler,
        ServiceDurationHandler $serviceDurationHandler,
        ServiceLocationHandler $serviceLocationHandler,
        ServiceOnlineLinkHandler $serviceOnlineLinkHandler,
        UnknownHandler $unknownHandler,
        // Phase 6 — Municipality Information
        MunicipalityContactHandler $municipalityContactHandler,
        MunicipalityPhoneHandler $municipalityPhoneHandler,
        MunicipalityEmailHandler $municipalityEmailHandler,
        MunicipalityAddressHandler $municipalityAddressHandler,
        MunicipalityWorkingHoursHandler $municipalityWorkingHoursHandler,
        MunicipalityAboutHandler $municipalityAboutHandler,
        MunicipalityMayorHandler $municipalityMayorHandler,
        // Phase 6 — Departments
        DepartmentsListHandler $departmentsListHandler,
        DepartmentSearchHandler $departmentSearchHandler,
        DepartmentDetailsHandler $departmentDetailsHandler,
        DepartmentContactHandler $departmentContactHandler,
        // Phase 6 — Water Schedule
        WaterScheduleHandler $waterScheduleHandler,
        WaterScheduleTodayHandler $waterScheduleTodayHandler,
        WaterScheduleNextHandler $waterScheduleNextHandler,
        WaterAreaSearchHandler $waterAreaSearchHandler,
        // Phase 6 — Jobs
        OpenJobsHandler $openJobsHandler,
        LatestJobsHandler $latestJobsHandler,
        JobSearchHandler $jobSearchHandler,
        JobDetailsHandler $jobDetailsHandler,
        JobDeadlineHandler $jobDeadlineHandler,
        // Phase 6 — News
        LatestNewsHandler $latestNewsHandler,
        NewsSearchHandler $newsSearchHandler,
        NewsDetailsHandler $newsDetailsHandler,
        // Phase 6 — Announcements
        LatestAnnouncementsHandler $latestAnnouncementsHandler,
        AnnouncementSearchHandler $announcementSearchHandler,
        AnnouncementDetailsHandler $announcementDetailsHandler,
        // Phase 6 — Council Decisions
        LatestCouncilDecisionsHandler $latestCouncilDecisionsHandler,
        CouncilDecisionSearchHandler $councilDecisionSearchHandler,
        CouncilDecisionDetailsHandler $councilDecisionDetailsHandler,
        CouncilDecisionByDateHandler $councilDecisionByDateHandler,
        // Phase 6 — Facilities
        FacilitiesListHandler $facilitiesListHandler,
        FacilitySearchHandler $facilitySearchHandler,
        FacilityDetailsHandler $facilityDetailsHandler,
        FacilityLocationHandler $facilityLocationHandler,
        FacilityWorkingHoursHandler $facilityWorkingHoursHandler,
        // Phase 6 — Engineering Offices
        EngineeringOfficesListHandler $engineeringOfficesListHandler,
        EngineeringOfficeSearchHandler $engineeringOfficeSearchHandler,
        EngineeringOfficeDetailsHandler $engineeringOfficeDetailsHandler,
        EngineeringOfficeContactHandler $engineeringOfficeContactHandler,
        // Phase 6 — Council Members
        CouncilMembersListHandler $councilMembersListHandler,
        CouncilMemberSearchHandler $councilMemberSearchHandler,
        CouncilMemberDetailsHandler $councilMemberDetailsHandler,
        // Phase 7 — Citizen Workflows
        CreateComplaintHandler $createComplaintHandler,
        ContactRequestHandler $contactRequestHandler,
        TrackWorkflowHandler $trackWorkflowHandler,
        ResumeWorkflowHandler $resumeWorkflowHandler,
        CancelWorkflowHandler $cancelWorkflowHandler,
    ) {
        $this->handlers = [
            // Phase 2
            $greetingHandler,
            $thanksHandler,
            $serviceSearchHandler,
            $serviceOverviewHandler,
            $serviceApplicationStepsHandler,
            $serviceRequirementsHandler,
            $serviceFeesHandler,
            $serviceDurationHandler,
            $serviceLocationHandler,
            $serviceOnlineLinkHandler,
            // Phase 6 — Municipality Information
            $municipalityContactHandler,
            $municipalityPhoneHandler,
            $municipalityEmailHandler,
            $municipalityAddressHandler,
            $municipalityWorkingHoursHandler,
            $municipalityAboutHandler,
            $municipalityMayorHandler,
            // Phase 6 — Departments
            $departmentsListHandler,
            $departmentSearchHandler,
            $departmentDetailsHandler,
            $departmentContactHandler,
            // Phase 6 — Water Schedule
            $waterScheduleHandler,
            $waterScheduleTodayHandler,
            $waterScheduleNextHandler,
            $waterAreaSearchHandler,
            // Phase 6 — Jobs
            $openJobsHandler,
            $latestJobsHandler,
            $jobSearchHandler,
            $jobDetailsHandler,
            $jobDeadlineHandler,
            // Phase 6 — News
            $latestNewsHandler,
            $newsSearchHandler,
            $newsDetailsHandler,
            // Phase 6 — Announcements
            $latestAnnouncementsHandler,
            $announcementSearchHandler,
            $announcementDetailsHandler,
            // Phase 6 — Council Decisions
            $latestCouncilDecisionsHandler,
            $councilDecisionSearchHandler,
            $councilDecisionDetailsHandler,
            $councilDecisionByDateHandler,
            // Phase 6 — Facilities
            $facilitiesListHandler,
            $facilitySearchHandler,
            $facilityDetailsHandler,
            $facilityLocationHandler,
            $facilityWorkingHoursHandler,
            // Phase 6 — Engineering Offices
            $engineeringOfficesListHandler,
            $engineeringOfficeSearchHandler,
            $engineeringOfficeDetailsHandler,
            $engineeringOfficeContactHandler,
            // Phase 6 — Council Members
            $councilMembersListHandler,
            $councilMemberSearchHandler,
            $councilMemberDetailsHandler,
            // Phase 7 — Citizen Workflows
            $createComplaintHandler,
            $contactRequestHandler,
            $trackWorkflowHandler,
            $resumeWorkflowHandler,
            $cancelWorkflowHandler,
            // Unknown always last
            $unknownHandler,
        ];
    }

    public function resolve(ChatbotIntent $intent): ChatResponseHandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($intent)) {
                return $handler;
            }
        }

        return new UnknownHandler;
    }

    public function all(): array
    {
        return $this->handlers;
    }
}
