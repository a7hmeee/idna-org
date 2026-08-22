<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\RuleIntentDetectorInterface;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final class RuleIntentDetector implements RuleIntentDetectorInterface
{
    private array $normalizedPatterns = [];

    public function __construct(
        private readonly ArabicTextNormalizer $normalizer,
    ) {}

    public function detect(string $normalizedMessage): ChatbotIntent
    {
        $message = trim($normalizedMessage);

        if ($message === '') {
            return ChatbotIntent::Unknown;
        }

        if ($this->normalizedPatterns === []) {
            $this->normalizedPatterns = [
                ChatbotIntent::Greeting->value => $this->normalizeAll([
                    'مرحبا', 'السلام عليكم', 'سلام', 'هلا', 'اهلين', 'صباح الخير', 'مساء الخير',
                    'هاي', 'هلو', 'يا هلا', 'يا هلا بك', 'صباحو', 'يسعد صباحك', 'يسعد مساك',
                ]),
                ChatbotIntent::MunicipalityAssistantHome->value => $this->normalizeAll([
                    'شو بتقدر تساعدني', 'شو عندك', 'كيف بتساعدني', 'بدي مساعدة', 'ساعدني', 'شو خدماتك',
                    'شو بقدر أسأل', 'بدي استفسر', 'عندي سؤال', 'مساعدة', 'قائمة الخيارات', 'شو بتعمل',
                ]),
                ChatbotIntent::Thanks->value => $this->normalizeAll([
                    'شكرا', 'يعطيك العافيه', 'يسلمو', 'مشكور', 'بارك الله فيك',
                ]),
                ChatbotIntent::ServiceApplicationSteps->value => $this->normalizeAll([
                    'كيف اقدم', 'كيف اقدم طلب', 'خطوات التقديم', 'شو الخطوات',
                    'من وين ابلش', 'كيف اسجل', 'طريقه التقديم',
                ]),
                ChatbotIntent::ServiceRequirements->value => $this->normalizeAll([
                    'المتطلبات', 'شو مطلوب', 'الاوراق المطلوبه', 'الوثائق', 'المستندات', 'شو اجيب معي',
                ]),
                ChatbotIntent::ServiceFees->value => $this->normalizeAll([
                    'الرسوم', 'رسوم', 'التكلفه', 'تكلفه', 'تكلفة', 'كم بتكلف', 'كم تكلفه', 'كم تكلفة',
                    'قديش الرسوم', 'قديش التكلفه', 'كم السعر', 'كم سعر', 'كم ادفع', 'قديش بدفع', 'شو رسوم',
                ]),
                ChatbotIntent::ServiceDuration->value => $this->normalizeAll([
                    'المده', 'كم بتاخذ', 'قديش بتطول', 'متى تخلص', 'ايام العمل',
                ]),
                ChatbotIntent::ServiceLocation->value => $this->normalizeAll([
                    'وين اقدم', 'مكان التقديم', 'اي قسم', 'وين اروح',
                ]),
                ChatbotIntent::ServiceOnlineLink->value => $this->normalizeAll([
                    'رابط التقديم', 'اقدم اونلاين', 'تقديم الكتروني', 'ابدا الخدمه', 'رابط الخدمه',
                ]),
                ChatbotIntent::ServiceOverview->value => $this->normalizeAll([
                    'شو هي', 'احكيلي عن', 'معلومات عن', 'تفاصيل الخدمه', 'شرح الخدمه',
                ]),
                ChatbotIntent::ServiceSearch->value => $this->normalizeAll([
                    'بدي خدمه', 'ابحث عن خدمه', 'خدمات البلديه', 'شو في خدمات', 'دورلي على',
                    'الخدمات', 'الخدمات البلديه', 'خدمات البلدية',
                ]),
                // Phase 6 — Municipality Information
                ChatbotIntent::MunicipalityPhone->value => $this->normalizeAll([
                    'رقم الهاتف', 'رقم البلديه', 'هاتف البلديه', 'تلفون البلديه', 'ارقام الهاتف',
                    'شو رقم', 'قديش رقم',
                ]),
                ChatbotIntent::MunicipalityEmail->value => $this->normalizeAll([
                    'البريد الالكتروني', 'ايميل البلديه', 'بريد البلديه', 'الايميل',
                ]),
                ChatbotIntent::MunicipalityAddress->value => $this->normalizeAll([
                    'عنوان البلديه', 'وين البلديه', 'مكان البلديه', 'موقع البلديه',
                ]),
                ChatbotIntent::MunicipalityWorkingHours->value => $this->normalizeAll([
                    'ساعات العمل', 'دوام البلديه', 'اوقات الدوام', 'متى الدوام', 'اوقات العمل',
                    'متى بفتح', 'متى بتفتح',
                ]),
                ChatbotIntent::MunicipalityAbout->value => $this->normalizeAll([
                    'عن البلديه', 'معلومات عن البلديه', 'تعرف على البلديه', 'نبذه عن البلديه',
                ]),
                ChatbotIntent::MunicipalityMayor->value => $this->normalizeAll([
                    'مين رئيس البلدية', 'من هو رئيس البلدية', 'رئيس البلدية', 'اسم رئيس البلدية',
                    'مين عمدة', 'عمدة البلدية', 'رئيس المجلس البلدي', 'مين رئيس المجلس',
                ]),
                ChatbotIntent::MunicipalityContact->value => $this->normalizeAll([
                    'تواصل معي', 'اتصل بي', 'تواصل مع البلدية', 'تواصل مع البلديه',
                    'اتصل بنا', 'معلومات الاتصال', 'بيانات التواصل', 'وسائل التواصل',
                    'طريقة التواصل', 'تواصل', 'اتصل', 'بلدي',
                ]),
                // Phase 6 — Departments
                ChatbotIntent::DepartmentsList->value => $this->normalizeAll([
                    'الاقسام', 'اقسام البلديه', 'شو الاقسام', 'الاقسام الموجوده', 'عرض الاقسام', 'اقسام',
                ]),
                ChatbotIntent::DepartmentSearch->value => $this->normalizeAll([
                    'قسم', 'دائره', 'دائرة', 'شعبه', 'شعبة',
                ]),
                // Phase 6 — Water Schedule
                ChatbotIntent::WaterSchedule->value => $this->normalizeAll([
                    'جدول المياه', 'مواعيد المياه', 'موعد المياه', 'المياه', 'المي', 'متى المي',
                    'مياه',
                ]),
                ChatbotIntent::WaterScheduleToday->value => $this->normalizeAll([
                    'المياه اليوم', 'المي اليوم', 'جدول اليوم', 'متى المي اليوم',
                ]),
                ChatbotIntent::WaterScheduleNext->value => $this->normalizeAll([
                    'الموعد القادم', 'بعدها', 'المي القادم', 'المياه الجايه',
                ]),
                ChatbotIntent::WaterAreaSearch->value => $this->normalizeAll([
                    'مناطق المياه', 'مناطق المي', 'منطقة', 'واد',
                ]),
                // Phase 6 — Jobs
                ChatbotIntent::JobsOpen->value => $this->normalizeAll([
                    'وظائف مفتوحه', 'وظائف شاغره', 'في وظائف', 'وظايف', 'فرص عمل',
                    'شو في وظائف', 'وظائف',
                ]),
                ChatbotIntent::LatestJobs->value => $this->normalizeAll([
                    'اخر الوظائف', 'جديد الوظائف', 'احدث الوظائف',
                ]),
                ChatbotIntent::JobDeadline->value => $this->normalizeAll([
                    'اخر موعد', 'تاريخ التقديم', 'موعد التقديم', 'موعد اغلاق',
                ]),
                // Phase 6 — News
                ChatbotIntent::LatestNews->value => $this->normalizeAll([
                    'اخر الاخبار', 'الاخبار', 'شو الاخبار', 'اخبار البلديه',
                    'شو الجديد', 'اخبار جديدة', 'اخبار جديده',
                ]),
                ChatbotIntent::NewsSearch->value => $this->normalizeAll([
                    'بحث في الاخبار', 'خبر عن',
                ]),
                // Phase 6 — Announcements
                ChatbotIntent::LatestAnnouncements->value => $this->normalizeAll([
                    'اخر الاعلانات', 'الاعلانات', 'اعلانات', 'تنبيهات',
                ]),
                // Phase 6 — Council Decisions
                ChatbotIntent::LatestCouncilDecisions->value => $this->normalizeAll([
                    'قرارات المجلس', 'اخر القرارات', 'قرارات', 'قرار المجلس',
                ]),
                ChatbotIntent::CouncilDecisionSearch->value => $this->normalizeAll([
                    'بحث في القرارات', 'قرار عن',
                ]),
                // Phase 6 — Facilities
                ChatbotIntent::FacilitiesList->value => $this->normalizeAll([
                    'المرافق', 'مرافق البلديه', 'مرافق عامه', 'المرافق العامه', 'منتزه', 'حديقه',
                    'حديقة', 'حدايق',
                ]),
                // Phase 6 — Engineering Offices
                ChatbotIntent::EngineeringOfficesList->value => $this->normalizeAll([
                    'مكاتب هندسيه', 'مهندسين', 'المكاتب الهندسيه', 'اعرض المكاتب', 'هندسي',
                ]),
                // Phase 6 — Council Members
                ChatbotIntent::CouncilMembersList->value => $this->normalizeAll([
                    'اعضاء المجلس', 'اعضاء', 'المجلس البلدي', 'اعضاء البلديه',
                    'مين أعضاء المجلس', 'أعضاء المجلس البلدي',
                ]),
                ChatbotIntent::CouncilMemberSearch->value => $this->normalizeAll([
                    'مين نائب الرئيس', 'نائب رئيس البلدية', 'نائب الرئيس',
                    'بحث عن عضو مجلس', 'عضو مجلس',
                ]),
                // Phase 7 — Citizen Workflows
                ChatbotIntent::CreateComplaint->value => $this->normalizeAll([
                    'بدي شكوى', 'شكوى', 'تقديم شكوى', 'اريد شكوى',
                ]),
                ChatbotIntent::ContactRequest->value => $this->normalizeAll([
                    'طلب اتصال', 'اتصال', 'تواصل معي', 'اتصل بي',
                ]),
                ChatbotIntent::TrackWorkflow->value => $this->normalizeAll([
                    'تتبع طلب', 'متابعة طلب', 'وين طلبي',
                ]),
                ChatbotIntent::ResumeWorkflow->value => $this->normalizeAll([
                    'كمل', 'استمر', 'تابع', 'استكمال', 'متابعه',
                ]),
                ChatbotIntent::CancelWorkflow->value => $this->normalizeAll([
                    'إلغاء', 'الغاء', 'الغاء طلب', 'إلغاء طلب',
                ]),
            ];
        }

        $order = [
            ChatbotIntent::Greeting,
            ChatbotIntent::MunicipalityAssistantHome,
            ChatbotIntent::Thanks,
            ChatbotIntent::ServiceApplicationSteps,
            ChatbotIntent::ServiceRequirements,
            ChatbotIntent::ServiceFees,
            ChatbotIntent::ServiceDuration,
            ChatbotIntent::ServiceLocation,
            ChatbotIntent::ServiceOnlineLink,
            ChatbotIntent::ServiceOverview,
            ChatbotIntent::ServiceSearch,
            // Phase 6 — ordered by specificity. Council member search is
            // checked before MunicipalityContact because the latter's single
            // "بلدي" token otherwise hijacks messages like
            // "أعضاء المجلس البلدي".
            ChatbotIntent::MunicipalityPhone,
            ChatbotIntent::MunicipalityEmail,
            ChatbotIntent::MunicipalityAddress,
            ChatbotIntent::MunicipalityWorkingHours,
            ChatbotIntent::MunicipalityAbout,
            ChatbotIntent::MunicipalityMayor,
            ChatbotIntent::CouncilMembersList,
            ChatbotIntent::CouncilMemberSearch,
            ChatbotIntent::DepartmentsList,
            ChatbotIntent::MunicipalityContact,
            ChatbotIntent::WaterScheduleToday,
            ChatbotIntent::WaterScheduleNext,
            ChatbotIntent::WaterSchedule,
            ChatbotIntent::WaterAreaSearch,
            ChatbotIntent::JobDeadline,
            ChatbotIntent::JobsOpen,
            ChatbotIntent::LatestJobs,
            ChatbotIntent::LatestNews,
            ChatbotIntent::NewsSearch,
            ChatbotIntent::LatestAnnouncements,
            ChatbotIntent::LatestCouncilDecisions,
            ChatbotIntent::CouncilDecisionSearch,
            ChatbotIntent::FacilitiesList,
            ChatbotIntent::EngineeringOfficesList,
            ChatbotIntent::CreateComplaint,
            ChatbotIntent::ContactRequest,
            ChatbotIntent::TrackWorkflow,
            ChatbotIntent::ResumeWorkflow,
            ChatbotIntent::CancelWorkflow,
        ];

        foreach ($order as $intent) {
            if (isset($this->normalizedPatterns[$intent->value]) && $this->matchesAny($message, $this->normalizedPatterns[$intent->value])) {
                return $intent;
            }
        }

        return ChatbotIntent::Unknown;
    }

    private function normalizeAll(array $patterns): array
    {
        return array_map(fn (string $p) => $this->normalizer->normalize($p), $patterns);
    }

    private function matchesAny(string $message, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (str_contains($message, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
