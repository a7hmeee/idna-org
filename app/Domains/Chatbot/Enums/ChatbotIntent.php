<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Enums;

enum ChatbotIntent: string
{
    // Phase 2 - Foundation
    case Greeting = 'greeting';
    case Thanks = 'thanks';
    case ServiceSearch = 'service_search';
    case ServiceOverview = 'service_overview';
    case ServiceApplicationSteps = 'service_application_steps';
    case ServiceRequirements = 'service_requirements';
    case ServiceFees = 'service_fees';
    case ServiceDuration = 'service_duration';
    case ServiceLocation = 'service_location';
    case ServiceOnlineLink = 'service_online_link';
    case Unknown = 'unknown';
    case MunicipalityAssistantHome = 'municipality_assistant_home';

    // Phase 6 — Municipality Information
    case MunicipalityContact = 'municipality_contact';
    case MunicipalityPhone = 'municipality_phone';
    case MunicipalityEmail = 'municipality_email';
    case MunicipalityAddress = 'municipality_address';
    case MunicipalityWorkingHours = 'municipality_working_hours';
    case MunicipalityAbout = 'municipality_about';
    case MunicipalityMayor = 'municipality_mayor';

    // Phase 6 — Departments
    case DepartmentsList = 'departments_list';
    case DepartmentSearch = 'department_search';
    case DepartmentDetails = 'department_details';
    case DepartmentContact = 'department_contact';

    // Phase 6 — Water Schedule
    case WaterSchedule = 'water_schedule';
    case WaterScheduleToday = 'water_schedule_today';
    case WaterScheduleNext = 'water_schedule_next';
    case WaterAreaSearch = 'water_area_search';

    // Phase 6 — Jobs
    case JobsList = 'jobs_list';
    case JobsOpen = 'jobs_open';
    case LatestJobs = 'latest_jobs';
    case JobSearch = 'job_search';
    case JobDetails = 'job_details';
    case JobDeadline = 'job_deadline';

    // Phase 6 — News
    case LatestNews = 'latest_news';
    case NewsSearch = 'news_search';
    case NewsDetails = 'news_details';

    // Phase 6 — Announcements
    case LatestAnnouncements = 'latest_announcements';
    case AnnouncementSearch = 'announcement_search';
    case AnnouncementDetails = 'announcement_details';

    // Phase 6 — Council Decisions
    case LatestCouncilDecisions = 'latest_council_decisions';
    case CouncilDecisionSearch = 'council_decision_search';
    case CouncilDecisionDetails = 'council_decision_details';
    case CouncilDecisionByDate = 'council_decision_by_date';

    // Phase 6 — Facilities
    case FacilitiesList = 'facilities_list';
    case FacilitySearch = 'facility_search';
    case FacilityDetails = 'facility_details';
    case FacilityLocation = 'facility_location';
    case FacilityWorkingHours = 'facility_working_hours';

    // Phase 6 — Engineering Offices
    case EngineeringOfficesList = 'engineering_offices_list';
    case EngineeringOfficeSearch = 'engineering_office_search';
    case EngineeringOfficeDetails = 'engineering_office_details';
    case EngineeringOfficeContact = 'engineering_office_contact';

    // Phase 6 — Council Members
    case CouncilMembersList = 'council_members_list';
    case CouncilMemberSearch = 'council_member_search';
    case CouncilMemberDetails = 'council_member_details';

    // Phase 7 — Citizen Workflows
    case CreateComplaint = 'create_complaint';
    case ContactRequest = 'contact_request';
    case TrackWorkflow = 'track_workflow';
    case ResumeWorkflow = 'resume_workflow';
    case CancelWorkflow = 'cancel_workflow';

    public function label(): string
    {
        return match ($this) {
            self::Greeting => 'تحية',
            self::Thanks => 'شكر',
            self::ServiceSearch => 'بحث عن خدمة',
            self::ServiceOverview => 'نظرة عامة على الخدمة',
            self::ServiceApplicationSteps => 'خطوات التقديم',
            self::ServiceRequirements => 'المتطلبات',
            self::ServiceFees => 'الرسوم',
            self::ServiceDuration => 'المدة',
            self::ServiceLocation => 'الموقع',
            self::ServiceOnlineLink => 'رابط التقديم',
            self::Unknown => 'غير معروف',
            self::MunicipalityAssistantHome => 'المساعد الرئيسي للبلدية',
            self::MunicipalityContact => 'معلومات الاتصال',
            self::MunicipalityPhone => 'رقم الهاتف',
            self::MunicipalityEmail => 'البريد الإلكتروني',
            self::MunicipalityAddress => 'العنوان',
            self::MunicipalityWorkingHours => 'ساعات العمل',
            self::MunicipalityAbout => 'عن البلدية',
            self::MunicipalityMayor => 'رئيس البلدية',
            self::DepartmentsList => 'قائمة الأقسام',
            self::DepartmentSearch => 'بحث عن قسم',
            self::DepartmentDetails => 'تفاصيل قسم',
            self::DepartmentContact => 'جهة اتصال قسم',
            self::WaterSchedule => 'جدول المياه',
            self::WaterScheduleToday => 'جدول المياه اليوم',
            self::WaterScheduleNext => 'جدول المياه القادم',
            self::WaterAreaSearch => 'بحث عن منطقة مياه',
            self::JobsList => 'قائمة الوظائف',
            self::JobsOpen => 'وظائف مفتوحة',
            self::LatestJobs => 'آخر الوظائف',
            self::JobSearch => 'بحث عن وظيفة',
            self::JobDetails => 'تفاصيل وظيفة',
            self::JobDeadline => 'آخر موعد للتقديم',
            self::LatestNews => 'آخر الأخبار',
            self::NewsSearch => 'بحث في الأخبار',
            self::NewsDetails => 'تفاصيل خبر',
            self::LatestAnnouncements => 'آخر الإعلانات',
            self::AnnouncementSearch => 'بحث في الإعلانات',
            self::AnnouncementDetails => 'تفاصيل إعلان',
            self::LatestCouncilDecisions => 'آخر قرارات المجلس',
            self::CouncilDecisionSearch => 'بحث في القرارات',
            self::CouncilDecisionDetails => 'تفاصيل قرار',
            self::CouncilDecisionByDate => 'قرارات حسب التاريخ',
            self::FacilitiesList => 'قائمة المرافق',
            self::FacilitySearch => 'بحث عن مرفق',
            self::FacilityDetails => 'تفاصيل مرفق',
            self::FacilityLocation => 'موقع مرفق',
            self::FacilityWorkingHours => 'ساعات عمل مرفق',
            self::EngineeringOfficesList => 'قائمة المكاتب الهندسية',
            self::EngineeringOfficeSearch => 'بحث عن مكتب هندسي',
            self::EngineeringOfficeDetails => 'تفاصيل مكتب هندسي',
            self::EngineeringOfficeContact => 'جهة اتصال مكتب هندسي',
            self::CouncilMembersList => 'قائمة أعضاء المجلس',
            self::CouncilMemberSearch => 'بحث عن عضو مجلس',
            self::CouncilMemberDetails => 'تفاصيل عضو مجلس',
            self::CreateComplaint => 'تقديم شكوى',
            self::ContactRequest => 'طلب اتصال',
            self::TrackWorkflow => 'تتبع طلب',
            self::ResumeWorkflow => 'استئناف طلب',
            self::CancelWorkflow => 'إلغاء طلب',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    public function isServiceRelated(): bool
    {
        return match ($this) {
            self::Greeting, self::Thanks, self::Unknown, self::MunicipalityAssistantHome => false,
            self::CreateComplaint, self::ContactRequest, self::TrackWorkflow,
            self::ResumeWorkflow, self::CancelWorkflow => false,
            self::MunicipalityContact, self::MunicipalityPhone, self::MunicipalityEmail,
            self::MunicipalityAddress, self::MunicipalityWorkingHours, self::MunicipalityAbout,
            self::MunicipalityMayor => false,
            self::DepartmentsList, self::DepartmentSearch, self::DepartmentDetails,
            self::DepartmentContact => false,
            self::WaterSchedule, self::WaterScheduleToday, self::WaterScheduleNext,
            self::WaterAreaSearch => false,
            self::JobsList, self::JobsOpen, self::LatestJobs, self::JobSearch,
            self::JobDetails, self::JobDeadline => false,
            self::LatestNews, self::NewsSearch, self::NewsDetails => false,
            self::LatestAnnouncements, self::AnnouncementSearch, self::AnnouncementDetails => false,
            self::LatestCouncilDecisions, self::CouncilDecisionSearch, self::CouncilDecisionDetails,
            self::CouncilDecisionByDate => false,
            self::FacilitiesList, self::FacilitySearch, self::FacilityDetails,
            self::FacilityLocation, self::FacilityWorkingHours => false,
            self::EngineeringOfficesList, self::EngineeringOfficeSearch, self::EngineeringOfficeDetails,
            self::EngineeringOfficeContact => false,
            self::CouncilMembersList, self::CouncilMemberSearch, self::CouncilMemberDetails => false,
            default => true,
        };
    }

    public function domain(): string
    {
        return match ($this) {
            self::Greeting, self::Thanks, self::Unknown, self::MunicipalityAssistantHome => 'general',
            self::ServiceSearch, self::ServiceOverview, self::ServiceApplicationSteps,
            self::ServiceRequirements, self::ServiceFees, self::ServiceDuration,
            self::ServiceLocation, self::ServiceOnlineLink => 'electronic_services',
            self::MunicipalityContact, self::MunicipalityPhone, self::MunicipalityEmail,
            self::MunicipalityAddress, self::MunicipalityWorkingHours, self::MunicipalityAbout,
            self::MunicipalityMayor => 'municipality_info',
            self::DepartmentsList, self::DepartmentSearch, self::DepartmentDetails, self::DepartmentContact => 'departments',
            self::WaterSchedule, self::WaterScheduleToday, self::WaterScheduleNext, self::WaterAreaSearch => 'water_schedule',
            self::JobsList, self::JobsOpen, self::LatestJobs, self::JobSearch, self::JobDetails, self::JobDeadline => 'jobs',
            self::LatestNews, self::NewsSearch, self::NewsDetails => 'news',
            self::LatestAnnouncements, self::AnnouncementSearch, self::AnnouncementDetails => 'announcements',
            self::LatestCouncilDecisions, self::CouncilDecisionSearch, self::CouncilDecisionDetails, self::CouncilDecisionByDate => 'council_decisions',
            self::FacilitiesList, self::FacilitySearch, self::FacilityDetails, self::FacilityLocation, self::FacilityWorkingHours => 'facilities',
            self::EngineeringOfficesList, self::EngineeringOfficeSearch, self::EngineeringOfficeDetails, self::EngineeringOfficeContact => 'engineering_offices',
            self::CouncilMembersList, self::CouncilMemberSearch, self::CouncilMemberDetails => 'council_members',
            self::CreateComplaint, self::ContactRequest, self::TrackWorkflow,
            self::ResumeWorkflow, self::CancelWorkflow => 'citizen_workflow',
        };
    }
}
