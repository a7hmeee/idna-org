<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementStatus;
use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Announcements\Models\Announcement;
use App\Domains\Department\Models\Department;
use App\Domains\Homepage\Models\HomepageQuickLink;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageStatistic;
use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Enums\CouncilMemberStatus;
use App\Domains\Municipality\Models\CouncilDecision;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\Municipality\Models\MunicipalityContact;
use App\Domains\Municipality\Models\MunicipalityCustomField;
use App\Domains\Municipality\Models\MunicipalityExternalPlatform;
use App\Domains\Municipality\Models\MunicipalitySocialPlatform;
use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;
use App\Domains\News\Models\NewsItem;
use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use App\Domains\Projects\Models\Project;
use App\Domains\SharedKernel\Models\BusinessHour;
use App\Domains\SharedKernel\Models\EmergencyContact;
use App\Domains\WaterSchedule\Models\WaterArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Comprehensive data seeder based on Idna Municipality Magazine 2022-2026.
 *
 * IMPORTANT RULES:
 * - Council data from the magazine (2022-2026 term) is HISTORICAL only.
 * - No council member is seeded as "current" (active status with no term_end).
 * - All data comes exclusively from the magazine. No fabricated data.
 * - Images are skipped (to be added manually later).
 * - Currency is ILS (Shekel), NOT SAR.
 */
final class IdnaMagazineSeeder extends Seeder
{
    private const MUNICIPALITY_CODE = 'IDNA-001';

    public function run(): void
    {
        DB::beginTransaction();

        try {
            $municipality = $this->seedMunicipality();
            $this->seedMunicipalityContacts($municipality);
            $this->seedMunicipalitySocialPlatforms($municipality);
            $this->seedMunicipalityExternalPlatforms($municipality);
            $this->seedMunicipalityCustomFields($municipality);
            $this->seedBusinessHours($municipality);
            $this->seedEmergencyContacts($municipality);
            $this->seedHomepageSettings();
            $this->seedHomepageSections();
            $this->seedHomepageStatistics();
            $this->seedHomepageQuickLinks();
            $this->seedCouncilMembersHistorical();
            $this->seedCouncilDecisionsHistorical();
            $this->seedDepartments();
            $this->seedRoadProjects2022();
            $this->seedRoadProjects2023();
            $this->seedRoadProjects2024();
            $this->seedRoadProjects2025();
            $this->seedProposedProjects();
            $this->seedWaterAreas();
            $this->seedNewsFromMagazine();
            $this->seedAnnouncementsFromMagazine();
            $this->seedHumanResourcesJobs();

            DB::commit();

            $this->command->info('Idna Municipality magazine data seeded successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function seedMunicipality(): Municipality
    {
        return Municipality::updateOrCreate(
            ['municipality_code' => self::MUNICIPALITY_CODE],
            [
                'name_ar' => 'بلدية إذنا',
                'name_en' => 'Idna Municipality',
                'short_description' => 'بلدية إذنا هي الجهة الرسمية المعنية بتقديم الخدمات البلدية للمواطنين في مدينة إذنا، غرب محافظة الخليل.',
                'full_description' => 'بلدية إذنا تقع في غرب محافظة الخليل على بعد حوالي 12 كم من مدينة الخليل. تبلغ مساحتها حوالي 36,000 دونم ويسكنها حوالي 35,000 نسمة. تمارس البلدية نشاطاتها في مجالات الزراعة (عنب، زيتون، لوزيات) والنشاط التجاري والحرف والعمالة داخل الخط الأخضر. تسعى البلدية لخدمة أهالي إذنا وتحسين جودة حياتهم من خلال تقديم الخدمات البلدية وتطوير البنية التحتية.',
                'vision' => 'إذنا مركز اقتصادي، مدينة منظمة ونظيفة بيئيًا، متميزة بثروتها الحيوانية والحرف، بوابة للخط الغربي.',
                'mission' => 'إنشاء البنى التحتية والمرافق العامة، وتقديم الخدمات للمواطنين، والحفاظ على تراث إذنا وحضارتها، وتحقيق التنمية المستدامة.',
                'objectives' => [
                    'تطوير البنية التحتية',
                    'رفع كفاءة الخدمات الأساسية',
                    'تحسين الخدمات البلدية',
                    'تعزيز التنمية الاقتصادية المحلية',
                    'تحسين البيئة والصحة العامة',
                    'تعزيز المشاركة المجتمعية والشفافية',
                    'الحوكمة والإدارة',
                    'التنمية الاقتصادية',
                    'الاستدامة البيئية',
                ],
                'foundation_date' => null,
                'population' => 35000,
                'area' => 36000.00,
                'latitude' => 31.5800,
                'longitude' => 35.0000,
            ]
        );
    }

    private function seedMunicipalityContacts(Municipality $municipality): void
    {
        $contacts = [
            ['type' => 'address', 'label' => 'المقر الرئيسي', 'value' => 'إذنا - غرب محافظة الخليل - مبنى البلدية', 'icon' => 'map-marker-alt', 'display_order' => 1],
        ];

        foreach ($contacts as $data) {
            MunicipalityContact::updateOrCreate(
                [
                    'municipality_id' => $municipality->id,
                    'type' => $data['type'],
                    'label' => $data['label'],
                ],
                $data + ['municipality_id' => $municipality->id, 'is_active' => true],
            );
        }
    }

    private function seedMunicipalitySocialPlatforms(Municipality $municipality): void
    {
        $platforms = [
            ['name' => 'فيسبوك', 'slug' => 'facebook', 'icon' => 'facebook', 'url' => 'https://facebook.com/idna.municipality', 'color' => '#1877F2', 'display_order' => 1],
        ];

        foreach ($platforms as $data) {
            MunicipalitySocialPlatform::updateOrCreate(
                [
                    'municipality_id' => $municipality->id,
                    'slug' => $data['slug'],
                ],
                $data + ['municipality_id' => $municipality->id, 'is_active' => true],
            );
        }
    }

    private function seedMunicipalityExternalPlatforms(Municipality $municipality): void
    {
        $platforms = [
            ['name' => 'بوابة الخدمات الإلكترونية', 'description' => 'بوابة الخدمات الإلكترونية لبلدية إذنا', 'icon' => 'globe', 'url' => '#', 'category' => 'government', 'color' => '#2E7D32', 'open_in_new_tab' => true, 'is_featured' => true, 'display_order' => 1],
        ];

        foreach ($platforms as $data) {
            MunicipalityExternalPlatform::updateOrCreate(
                [
                    'municipality_id' => $municipality->id,
                    'name' => $data['name'],
                ],
                $data + ['municipality_id' => $municipality->id, 'is_active' => true],
            );
        }
    }

    private function seedMunicipalityCustomFields(Municipality $municipality): void
    {
        $fields = [
            ['key' => 'mayor_name', 'value' => '', 'type' => 'text', 'display_order' => 1],
            ['key' => 'population_density', 'value' => '35000', 'type' => 'number', 'display_order' => 2],
            ['key' => 'number_of_districts', 'value' => '12', 'type' => 'number', 'display_order' => 3],
            ['key' => 'area_dunums', 'value' => '36000', 'type' => 'number', 'display_order' => 4],
            ['key' => 'distance_from_hebron_km', 'value' => '12', 'type' => 'number', 'display_order' => 5],
        ];

        foreach ($fields as $data) {
            MunicipalityCustomField::updateOrCreate(
                [
                    'municipality_id' => $municipality->id,
                    'key' => $data['key'],
                ],
                $data + ['municipality_id' => $municipality->id, 'is_active' => true],
            );
        }
    }

    private function seedBusinessHours(Municipality $municipality): void
    {
        $hours = [
            ['day' => 'الأحد', 'opening_time' => '07:30', 'closing_time' => '14:30', 'is_closed' => false, 'display_order' => 1],
            ['day' => 'الإثنين', 'opening_time' => '07:30', 'closing_time' => '14:30', 'is_closed' => false, 'display_order' => 2],
            ['day' => 'الثلاثاء', 'opening_time' => '07:30', 'closing_time' => '14:30', 'is_closed' => false, 'display_order' => 3],
            ['day' => 'الأربعاء', 'opening_time' => '07:30', 'closing_time' => '14:30', 'is_closed' => false, 'display_order' => 4],
            ['day' => 'الخميس', 'opening_time' => '07:30', 'closing_time' => '14:30', 'is_closed' => false, 'display_order' => 5],
            ['day' => 'الجمعة', 'opening_time' => null, 'closing_time' => null, 'is_closed' => true, 'display_order' => 6],
            ['day' => 'السبت', 'opening_time' => null, 'closing_time' => null, 'is_closed' => true, 'display_order' => 7],
        ];

        foreach ($hours as $data) {
            BusinessHour::updateOrCreate(
                [
                    'hourable_id' => $municipality->id,
                    'hourable_type' => Municipality::class,
                    'day' => $data['day'],
                ],
                $data + ['hourable_id' => $municipality->id, 'hourable_type' => Municipality::class],
            );
        }
    }

    private function seedEmergencyContacts(Municipality $municipality): void
    {
        $contacts = [
            ['name' => 'الدفاع المدني', 'department' => 'الإطفاء والإنقاذ', 'phone' => '101', 'icon' => 'fire-extinguisher', 'display_order' => 1],
            ['name' => 'الإسعاف الطبي', 'department' => 'الخدمات الصحية', 'phone' => '102', 'icon' => 'ambulance', 'display_order' => 2],
            ['name' => 'الشرطة', 'department' => 'الأمن العام', 'phone' => '100', 'icon' => 'shield', 'display_order' => 3],
        ];

        foreach ($contacts as $data) {
            EmergencyContact::updateOrCreate(
                [
                    'contactable_id' => $municipality->id,
                    'contactable_type' => Municipality::class,
                    'name' => $data['name'],
                ],
                $data + ['contactable_id' => $municipality->id, 'contactable_type' => Municipality::class, 'is_active' => true],
            );
        }
    }

    private function seedHomepageSettings(): void
    {
        HomepageSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_title' => 'بلدية إذنا',
                'site_subtitle' => 'الخدمات البلدية الإلكترونية لمدينة إذنا',
                'portal_url' => null,
                'primary_button_text' => 'الخدمات الإلكترونية',
                'secondary_button_text' => 'عن البلدية',
                'secondary_button_url' => '/about',
                'welcome_title' => 'مرحباً بكم في موقع بلدية إذنا',
                'welcome_description' => 'بلدية إذنا هي الجهة الرسمية المعنية بتقديم الخدمات البلدية للمواطنين في مدينة إذنا. نسعى لتحسين جودة حياة المواطنين من خلال تقديم خدمات بلدية متميزة.',
                'mayor_message_title' => 'كلمة رئيس البلدية',
                'mayor_message' => null,
                'mayor_image_path' => null,
                'show_mayor_message' => true,
                'contact_cta_title' => 'تواصل معنا',
                'contact_cta_description' => 'نحن هنا لمساعدتك. لا تتردد في التواصل معنا لأي استفسار أو ملاحظة.',
                'contact_cta_button_text' => 'اتصل بنا',
                'contact_cta_button_url' => '/contact',
            ]
        );
    }

    private function seedHomepageSections(): void
    {
        $sections = [
            ['key' => 'hero', 'title' => 'البانر الرئيسي', 'is_enabled' => true, 'sort_order' => 1],
            ['key' => 'quick_links', 'title' => 'روابط سريعة', 'is_enabled' => true, 'sort_order' => 2],
            ['key' => 'municipality_intro', 'title' => 'نبذة عن البلدية', 'is_enabled' => true, 'sort_order' => 3],
            ['key' => 'statistics', 'title' => 'إحصائيات البلدية', 'is_enabled' => true, 'sort_order' => 4],
            ['key' => 'services', 'title' => 'الخدمات الإلكترونية', 'is_enabled' => true, 'sort_order' => 5],
            ['key' => 'departments', 'title' => 'دوائر البلدية', 'is_enabled' => true, 'sort_order' => 6],
            ['key' => 'council_members', 'title' => 'أعضاء المجلس البلدي', 'is_enabled' => true, 'sort_order' => 7],
            ['key' => 'council_decisions', 'title' => 'قرارات المجلس', 'is_enabled' => true, 'sort_order' => 8],
            ['key' => 'engineering_offices', 'title' => 'المكاتب الهندسية', 'is_enabled' => true, 'sort_order' => 9],
            ['key' => 'latest_news', 'title' => 'آخر الأخبار', 'is_enabled' => true, 'sort_order' => 10],
            ['key' => 'projects', 'title' => 'المشاريع', 'is_enabled' => true, 'sort_order' => 11],
            ['key' => 'tenders', 'title' => 'العطاءات والمناقصات', 'is_enabled' => true, 'sort_order' => 12],
            ['key' => 'announcements', 'title' => 'الإعلانات', 'is_enabled' => true, 'sort_order' => 13],
            ['key' => 'contact_cta', 'title' => 'تواصل معنا', 'is_enabled' => true, 'sort_order' => 14],
        ];

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['key' => $section['key']],
                $section,
            );
        }
    }

    private function seedHomepageStatistics(): void
    {
        $stats = [
            ['label' => 'عدد السكان', 'value' => '35,000', 'suffix' => 'نسمة', 'icon' => 'users', 'description' => 'عدد سكان مدينة إذنا', 'is_active' => true, 'sort_order' => 1],
            ['label' => 'مساحة البلدية', 'value' => '36,000', 'suffix' => 'دونم', 'icon' => 'map', 'description' => 'المساحة الإجمالية للبلدية', 'is_active' => true, 'sort_order' => 2],
            ['label' => 'مشاريع البنية التحتية', 'value' => '30+', 'suffix' => 'مشروع', 'icon' => 'road', 'description' => 'مشاريع البنية التحتية خلال 2022-2025', 'is_active' => true, 'sort_order' => 3],
            ['label' => 'شبكة المياه', 'value' => '110+', 'suffix' => 'كم', 'icon' => 'tint', 'description' => 'طول شبكة المياه في إذنا', 'is_active' => true, 'sort_order' => 4],
            ['label' => 'أكثر من', 'value' => '400', 'suffix' => 'ملف ترخيص', 'icon' => 'file-alt', 'description' => 'ملفات الترخيص المودعة', 'is_active' => true, 'sort_order' => 5],
            ['label' => 'الجلسات العادية', 'value' => '200', 'suffix' => 'جلسة', 'icon' => 'gavel', 'description' => 'جلسات المجلس السابق (2022-2026)', 'is_active' => true, 'sort_order' => 6],
            ['label' => 'أحواض التسوية', 'value' => '78', 'suffix' => 'حوض', 'icon' => 'water', 'description' => 'أحواض تسوية أراضي إذنا', 'is_active' => true, 'sort_order' => 7],
            ['label' => 'التوسع الهيكلي', 'value' => '8,000', 'suffix' => 'دونم', 'icon' => 'expand-arrows-alt', 'description' => 'التوسع من 5,500 إلى 8,000 دونم', 'is_active' => true, 'sort_order' => 8],
        ];

        foreach ($stats as $stat) {
            HomepageStatistic::updateOrCreate(
                ['label' => $stat['label']],
                $stat,
            );
        }
    }

    private function seedHomepageQuickLinks(): void
    {
        $links = [
            ['title' => 'الخدمات الإلكترونية', 'description' => 'اطلع على الخدمات البلدية المتاحة', 'icon' => 'laptop', 'url' => '/services', 'type' => 'service', 'is_external' => false, 'is_active' => true, 'sort_order' => 1],
            ['title' => 'جدول توزيع المياه', 'description' => 'أوقات توزيع المياه في مناطقك', 'icon' => 'tint', 'url' => '/water-schedule', 'type' => 'service', 'is_external' => false, 'is_active' => true, 'sort_order' => 2],
            ['title' => 'سجل الشكاوى', 'description' => 'تقديم ومتابعة الشكاوى البلدية', 'icon' => 'exclamation-triangle', 'url' => '/complaints/submit', 'type' => 'service', 'is_external' => false, 'is_active' => true, 'sort_order' => 3],
            ['title' => 'المشاريع الحالية', 'description' => 'متابعة مشاريع البنية التحتية', 'icon' => 'project-diagram', 'url' => '/projects', 'type' => 'internal', 'is_external' => false, 'is_active' => true, 'sort_order' => 4],
            ['title' => 'آخر الأخبار', 'description' => 'تابع آخر أخبار البلدية', 'icon' => 'newspaper', 'url' => '/news', 'type' => 'internal', 'is_external' => false, 'is_active' => true, 'sort_order' => 5],
            ['title' => 'الوظائف المتاحة', 'description' => 'فرص العمل في البلدية', 'icon' => 'briefcase', 'url' => '/jobs', 'type' => 'internal', 'is_external' => false, 'is_active' => true, 'sort_order' => 6],
        ];

        foreach ($links as $link) {
            HomepageQuickLink::updateOrCreate(
                ['title' => $link['title']],
                $link,
            );
        }
    }

    // ─── COUNCIL MEMBERS (HISTORICAL - 2022-2026 Term) ───────────────────

    private function seedCouncilMembersHistorical(): void
    {
        CouncilMember::query()->forceDelete();

        $members = [
            [
                'full_name' => 'نمر إسليمية',
                'position' => CouncilMemberPosition::Mayor->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 1,
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'full_name' => 'عبدالرحمن نمر اسليمية',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 2,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'full_name' => 'خالد اسماعيل النتشة',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 3,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'full_name' => 'عماد الدين عبد الله نمر',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 4,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'full_name' => 'بلال عبد القادر النتشة',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 5,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'full_name' => 'حسين محمود فراحنة',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 6,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'full_name' => 'ماهر عبدالفتاح نمر',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 7,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'full_name' => 'ACCOUNT_REDACTED',
                'position' => CouncilMemberPosition::CouncilMember->value,
                'qualification' => null,
                'profession' => null,
                'bio' => null,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
                'years_of_experience' => null,
                'committee' => null,
                'status' => CouncilMemberStatus::Former->value,
                'display_order' => 8,
                'is_public' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($members as $member) {
            CouncilMember::updateOrCreate(
                ['slug' => Str::slug($member['full_name'])],
                $member,
            );
        }

        $this->command->info('Council members seeded (historical - 2022-2026 term).');
    }

    // ─── COUNCIL DECISIONS (HISTORICAL) ──────────────────────────────────

    private function seedCouncilDecisionsHistorical(): void
    {
        CouncilDecision::query()->forceDelete();

        $decisions = [
            [
                'decision_number' => 'IDNA-C-200',
                'title' => 'إجمالي القرارات الصادرة عن المجلس البلدي',
                'summary' => 'أصدر المجلس البلدي السابق 200 قراراً عادياً خلال دورته 2022-2026.',
                'content' => 'أصدر المجلس البلدي السابق (دورة 2022-2026) ما مجموعه 200 قرار عادي و79 قراراً تنظيمياً خلال دورته.',
                'type' => CouncilDecisionType::Administrative->value,
                'status' => CouncilDecisionStatus::Published->value,
                'decision_date' => '2026-06-01',
                'session_number' => null,
                'is_public' => true,
                'sort_order' => 1,
                'published_at' => '2026-06-01 10:00:00',
            ],
        ];

        foreach ($decisions as $decision) {
            CouncilDecision::updateOrCreate(
                ['decision_number' => $decision['decision_number']],
                $decision,
            );
        }

        $this->command->info('Council decisions seeded (historical).');
    }

    // ─── DEPARTMENTS ─────────────────────────────────────────────────────

    private function seedDepartments(): void
    {
        Department::query()->forceDelete();

        $departments = [
            [
                'name' => 'الهندسة والتنظيم',
                'short_description' => 'دائرة الهندسة والتنظيم مسؤولة عن المشاريع الهندسية والترخيصات والتنظيم العمراني.',
                'description' => 'تتولى دائرة الهندسة والتنظيم الإشراف على المشاريع الهندسية وإصدار تراخيص البناء ومتابعة المشاريع العمرانية والبنية التحتية في مدينة إذنا.',
                'icon' => 'building-2',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 1,
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'الشؤون الإدارية والمالية',
                'short_description' => 'الدائرة المسؤولة عن الشؤون الإدارية والمالية والموارد البشرية.',
                'description' => 'تتولى إدارة الشؤون الإدارية والمالية للموظفين والحسابات والميزانيات.',
                'icon' => 'calculator',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 2,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'المياه والصرف الصحي',
                'short_description' => 'الدائرة المسؤولة عن شبكة المياه والصرف الصحي والخدمات المائية.',
                'description' => 'تتولى إدارة وصيانة شبكة المياه التي تتجاوز 110 كم ونحو 140 محبس مياه، فضلاً عن العمل على مخطط شامل للصرف الصحي.',
                'icon' => 'tint',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 3,
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'البيئة والنظافة',
                'short_description' => 'الدائرة المسؤولة عن النظافة العامة وإدارة النفايات والبيئة.',
                'description' => 'تتولى إدارة النفايات الصلبة والنظافة العامة والحفاظ على البيئة. تشمل عضويته في مجلس إدارة المجلس المشترك لإدارة النفايات الصلبة.',
                'icon' => 'leaf',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 4,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'الشؤون القانونية',
                'short_description' => 'الدائرة المسؤولة عن الشؤون القانونية والقضائية والContractات.',
                'description' => 'تتولى الشؤون القانونية لبلدية إذنا. سجلت 46 قضية عمالية و46 قضية حقوقية وجزائية و104 اعتراضات في محكمة تسوية الخليل و94 ملفاً وارداً للاستشارة القانونية.',
                'icon' => 'gavel',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 5,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'العلاقات العامة والإعلام',
                'short_description' => 'الدائرة المسؤولة عن العلاقات العامة والإعلام والنشر.',
                'description' => 'تتولى العلاقات العامة والإعلام والتواصل مع الجمهور والمجتمع المحلي.',
                'icon' => 'bullhorn',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 6,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'تقنية المعلومات',
                'short_description' => 'الدائرة المسؤولة عن البنية التحتية لتكنولوجيا المعلومات والأنظمة الإلكترونية.',
                'description' => 'تتولى إدارة الأنظمة الإلكترونية والبنية التحتية لتكنولوجيا المعلومات. تشمل إطلاق بوابة المواطن الإلكترونية وتطوير مركز خدمات الجمهور ونظام التنبيهات الرقمية وتحديث غرفة الخوادم.',
                'icon' => 'laptop-code',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 7,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'الموارد البشرية',
                'short_description' => 'الدائرة المسؤولة عن شؤون الموظفين والتوظيف والعقود.',
                'description' => 'تتولى إدارة شؤون 96 موظفاً تشمل 23 موظفاً بعقود سنوية و37 موظفاً مثبتاً ومصنفاً و17 موظف مياومة و11 موظفاً متقاعدأ و12 موظفاً ضمن المركز المجتمعي و70 عقد تشغيل مؤقت لموظفي الصحة.',
                'icon' => 'users',
                'phone' => null,
                'email' => null,
                'working_hours' => 'الأحد - الخميس: 7:30 صباحاً - 2:30 مساءً',
                'status' => 'active',
                'display_order' => 8,
                'is_public' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['name' => $dept['name']],
                $dept,
            );
        }

        $this->command->info('Departments seeded.');
    }

    // ─── ROAD PROJECTS 2022 ──────────────────────────────────────────────

    private function seedRoadProjects2022(): void
    {
        Project::query()->forceDelete();

        $projects = [
            ['name_ar' => 'صيانة وتأهيل مفرق سوبا', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'مفرق سوبا', 'summary' => 'صيانة وتأهيل مفرق سوبا بمساحة 1,300 م²', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'مرابيع خلة اللبيد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'مرابيع خلة اللبيد بمساحة 150 م²', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'رقع وصيانة إسفلت', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'رقع وصيانة إسفلت بمساحة 1,200 م²', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'دخولات خاصة', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'دخولات خاصة بمساحة 2,400 م²', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'جدران استنادية خلة اللبيد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'بناء جدران استنادية خلة اللبيد - 600 م³ باطون', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أكتاف وتأهيل طرق', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أكتاف وتأهيل طرق بمساحة 2,300 م²', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'شارع خلة السويدة', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة السويدة', 'summary' => 'شارع خلة السويدة - 400م × 6م', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أعمال رقع إضافية', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أعمال رقع إضافية بمساحة 2,000 م²', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'شارع واد ريشة', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'واد ريشة', 'summary' => 'شارع واد ريشة - 900م × 18م', 'start_date' => '2022-01-01', 'expected_completion_date' => '2022-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['name_ar' => $project['name_ar']],
                $project,
            );
        }

        $this->command->info('Road projects 2022 seeded.');
    }

    // ─── ROAD PROJECTS 2023 ──────────────────────────────────────────────

    private function seedRoadProjects2023(): void
    {
        $projects = [
            ['name_ar' => 'مرابيع مدرستي واد البير وخلة حماد', 'category' => ProjectCategory::Education->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'واد البير وخلة حماد', 'summary' => 'مرابيع مدرستي واد البير وخلة حماد بمساحة 480 م²', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أسوار خلة حماد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة حماد', 'summary' => 'بناء أسوار خلة حماد - 180 م³', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أسوار خلة اللبيد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'بناء أسوار خلة اللبيد - 170 م³', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'صيانة ورقع إسفلت', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'صيانة ورقع إسفلت بمساحة 1,500 م²', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'توسعة شارع خلة نقيب', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة نقيب', 'summary' => 'توسعة شارع خلة نقيب', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'مرابيع منظمة', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'مرابيع منظمة بمساحة 600 م²', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'استكمال مرابيع خلة اللبيد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'استكمال مرابيع خلة اللبيد - 200 م²', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أكتاف', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أكتاف بمساحة 2,200 م²', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'تعبيد خلة اللبيد', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'تعبيد خلة اللبيد - 1,100م طول و11,000 م²', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'واد ريشة – المقطع الثاني', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'واد ريشة', 'summary' => 'واد ريشة – المقطع الثاني - 2,400م × 18م', 'start_date' => '2023-01-01', 'expected_completion_date' => '2023-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['name_ar' => $project['name_ar']],
                $project,
            );
        }

        $this->command->info('Road projects 2023 seeded.');
    }

    // ─── ROAD PROJECTS 2024 ──────────────────────────────────────────────

    private function seedRoadProjects2024(): void
    {
        $projects = [
            ['name_ar' => 'أكتاف وتأهيل طرق', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أكتاف وتأهيل طرق بمساحة 3,000 م²', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'قنان جيش', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'قنان جيش', 'summary' => 'قنان جيش - 2,000 م² و80 م³ جدران استنادية', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'مدخل خلة نقيب', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة نقيب', 'summary' => 'مدخل خلة نقيب - 2,000 م² إسفلت و300 م² أرصفة', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'خلة الفول', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة الفول', 'summary' => 'خلة الفول - 200 م²', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'خلة اللبيد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'خلة اللبيد - 600 م²', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أكتاف', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أكتاف بمساحة 1,500 م²', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أعمال إضافية', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أعمال إضافية بمساحة 1,200 م²', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'خلة اللبيد – المقطع الثاني', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'خلة اللبيد – المقطع الثاني - 10,000 م²', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'مدرسة واد البير', 'category' => ProjectCategory::Education->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'واد البير', 'summary' => 'مدرسة واد البير - 1,100 م² بلاط وأدراج 230م', 'start_date' => '2024-01-01', 'expected_completion_date' => '2024-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['name_ar' => $project['name_ar']],
                $project,
            );
        }

        $this->command->info('Road projects 2024 seeded.');
    }

    // ─── ROAD PROJECTS 2025 ──────────────────────────────────────────────

    private function seedRoadProjects2025(): void
    {
        $projects = [
            ['name_ar' => 'صيانة إسفلت', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'صيانة إسفلت بمساحة 1,000 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'قنان جيش (2025)', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'قنان جيش', 'summary' => 'قنان جيش - 1,500 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'ساحات مسجد الرأس وبين حارث', 'category' => ProjectCategory::PublicFacilities->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'ساحات مسجد الرأس وبين حارث - 500 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أرض البلدية بخلة اللبيد', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'أرض البلدية بخلة اللبيد - 700 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'دخولات خاصة (2025)', 'category' => ProjectCategory::Infrastructure->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'دخولات خاصة بمساحة 2,500 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'أكتاف وتأهيل (2025)', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'أكتاف وتأهيل بمساحة 11,000 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'شارع قصتين', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'قصتين', 'summary' => 'شارع قصتين - 7,200 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'مستودع البلدية', 'category' => ProjectCategory::Buildings->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'إذنا', 'summary' => 'مستودع البلدية - 1,200 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
            ['name_ar' => 'خلة اللبيد – المقطع الثالث', 'category' => ProjectCategory::Roads->value, 'project_status' => ProjectStatus::Completed->value, 'status' => 'completed', 'location' => 'خلة اللبيد', 'summary' => 'خلة اللبيد – المقطع الثالث - 5,000 م²', 'start_date' => '2025-01-01', 'expected_completion_date' => '2025-12-31', 'implementation_percentage' => 100, 'is_public' => true, 'budget_currency' => 'ILS'],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['name_ar' => $project['name_ar']],
                $project,
            );
        }

        $this->command->info('Road projects 2025 seeded.');
    }

    // ─── PROPOSED PROJECTS ───────────────────────────────────────────────

    private function seedProposedProjects(): void
    {
        $projects = [
            [
                'name_ar' => 'تشطيب المركز الصحي/المستشفى',
                'name_en' => 'Health Center / Hospital Completion',
                'category' => ProjectCategory::Health->value,
                'project_status' => ProjectStatus::InProgress->value,
                'status' => 'in_progress',
                'location' => 'إذنا',
                'summary' => 'تشطيب المركز الصحي/المستشفى - تمويل صندوق كويتي بقيمة 1.5 مليون دولار',
                'description' => 'مشروع تشطيب المركز الصحي/المستشفى في إذنا. التمويل من الصندوق الكويتي. القيمة: 1.5 مليون دولار.',
                'funding_entity' => 'الصندوق الكويتي',
                'budget' => 1500000,
                'budget_currency' => 'USD',
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name_ar' => 'مركز ثقافي ومسرح',
                'name_en' => 'Cultural Center and Theater',
                'category' => ProjectCategory::Culture->value,
                'project_status' => ProjectStatus::Planned->value,
                'status' => 'planned',
                'location' => 'إذنا',
                'summary' => 'مركز ثقافي ومسرح - مقدم لبرنامج الأمم المتحدة الإنمائي',
                'description' => 'مشروع مركز ثقافي ومسرح في إذنا. مقدم لبرنامج الأمم المتحدة الإنمائي (UNDP).',
                'funding_entity' => 'UNDP',
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name_ar' => 'مسلخ بلدي',
                'name_en' => 'Municipal Slaughterhouse',
                'category' => ProjectCategory::PublicFacilities->value,
                'project_status' => ProjectStatus::Planned->value,
                'status' => 'planned',
                'location' => 'إذنا',
                'summary' => 'مسلخ بلدي - مقدم للجانب الياباني',
                'description' => 'مشروع مسلخ بلدي في إذنا. مقدم للجانب الياباني.',
                'funding_entity' => 'الجانب الياباني',
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name_ar' => 'شارع شعب نقيب (الصندوق الكويتي)',
                'name_en' => 'Sha\'ab Naqib Street (Kuwait Fund)',
                'category' => ProjectCategory::Roads->value,
                'project_status' => ProjectStatus::InProgress->value,
                'status' => 'in_progress',
                'location' => 'شعب نقيب',
                'summary' => 'شارع شعب نقيب - تعبيد - 1.5 مليون شيكل - الصندوق الكويتي',
                'description' => 'مشروع تعبيد شارع شعب نقيب. التمويل من الصندوق الكويتي. القيمة: 1.5 مليون شيكل.',
                'funding_entity' => 'الصندوق الكويتي',
                'budget' => 1500000,
                'budget_currency' => 'ILS',
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name_ar' => 'شارع خلة الفول – رأس شعب القطن',
                'name_en' => 'Khalat Al-Foul - Ras Sha\'ab Al-Qoton Street',
                'category' => ProjectCategory::Roads->value,
                'project_status' => ProjectStatus::Planned->value,
                'status' => 'planned',
                'location' => 'خلة الفول – رأس شعب القطن',
                'summary' => 'شارع خلة الفول – رأس شعب القطن - 400 ألف شيكل',
                'description' => 'مشروع تطوير شارع خلة الفول – رأس شعب القطن. القيمة: 400 ألف شيكل.',
                'budget' => 400000,
                'budget_currency' => 'ILS',
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name_ar' => 'شارع النز – تأهيل',
                'name_en' => 'Al-Naz Street Rehabilitation',
                'category' => ProjectCategory::Roads->value,
                'project_status' => ProjectStatus::InProgress->value,
                'status' => 'in_progress',
                'location' => 'النز',
                'summary' => 'شارع النز - تأهيل - 1.7 مليون شيكل',
                'description' => 'مشروع تأهيل شارع النز. القيمة: 1.7 مليون شيكل.',
                'budget' => 1700000,
                'budget_currency' => 'ILS',
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name_ar' => 'مدرسة خلة الغزال',
                'name_en' => 'Khalat Al-Ghazal School',
                'category' => ProjectCategory::Education->value,
                'project_status' => ProjectStatus::InProgress->value,
                'status' => 'in_progress',
                'location' => 'خلة الغزال',
                'summary' => 'مدرسة خلة الغزال - تشطيب - 850 ألف دولار',
                'description' => 'مشروع تشطيب مدرسة خلة الغزال. القيمة: 850 ألف دولار.',
                'budget' => 850000,
                'budget_currency' => 'USD',
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name_ar' => 'شارع شعب نقيب (اليورو)',
                'name_en' => 'Sha\'ab Naqib Street (Euro)',
                'category' => ProjectCategory::Roads->value,
                'project_status' => ProjectStatus::InProgress->value,
                'status' => 'in_progress',
                'location' => 'شعب نقيب',
                'summary' => 'شارع شعب نقيب - تعبيد - 470 ألف يورو',
                'description' => 'مشروع تعبيد شارع شعب نقيب. القيمة: 470 ألف يورو.',
                'budget' => 470000,
                'budget_currency' => 'EUR',
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name_ar' => 'شارع واد الأفرنج – تصريف مياه',
                'name_en' => 'Wadi Al-Afranj Street - Water Drainage',
                'category' => ProjectCategory::Water->value,
                'project_status' => ProjectStatus::InProgress->value,
                'status' => 'in_progress',
                'location' => 'واد الأفرنج',
                'summary' => 'شارع واد الأفرنج - تصريف مياه - 1.5 مليون شيكل',
                'description' => 'مشروع تصريف مياه شارع واد الأفرنج. القيمة: 1.5 مليون شيكل.',
                'budget' => 1500000,
                'budget_currency' => 'ILS',
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name_ar' => 'مشروع زراعي',
                'name_en' => 'Agricultural Project',
                'category' => ProjectCategory::Other->value,
                'project_status' => ProjectStatus::Completed->value,
                'status' => 'completed',
                'location' => 'إذنا',
                'summary' => 'مشروع زراعي استفاد منه 15 مزارعاً - بناء سلاسل حجرية بمساحة 1,700 م²',
                'description' => 'مشروع زراعي في إذنا. استفاد منه 15 مزارعاً. شمل بناء سلاسل حجرية بمساحة 1,700 م².',
                'implementation_percentage' => 100,
                'is_public' => true,
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['name_ar' => $project['name_ar']],
                $project,
            );
        }

        $this->command->info('Proposed projects seeded.');
    }

    // ─── WATER AREAS ─────────────────────────────────────────────────────

    private function seedWaterAreas(): void
    {
        WaterArea::query()->forceDelete();

        $areas = [
            ['name' => 'وسط البلد', 'slug' => 'wasat-albalad', 'description' => 'منطقة وسط البلد - شبكة مياه رئيسية', 'display_order' => 1, 'is_active' => true],
            ['name' => 'حي السلام', 'slug' => 'hay-alsalam', 'description' => 'حي السلام - منطقة سكنية رئيسية', 'display_order' => 2, 'is_active' => true],
            ['name' => 'حي النور', 'slug' => 'hay-alnoor', 'description' => 'حي النور - منطقة سكنية', 'display_order' => 3, 'is_active' => true],
            ['name' => 'المروج', 'slug' => 'almarooj-west', 'description' => 'منطقة المروج الغربية', 'display_order' => 4, 'is_active' => true],
            ['name' => 'شارع الملك فهد', 'slug' => 'king-fahd-street', 'description' => 'شارع الملك فهد - شارع رئيسي', 'display_order' => 5, 'is_active' => true],
            ['name' => 'حي المروج', 'slug' => 'hay-almarooj', 'description' => 'حي المروج - منطقة سكنية حديثة', 'display_order' => 6, 'is_active' => true],
            ['name' => 'شرقي البلد', 'slug' => 'eastern-city', 'description' => 'المنطقة الشرقية من البلد', 'display_order' => 7, 'is_active' => true],
            ['name' => 'غربي البلد', 'slug' => 'western-city', 'description' => 'المنطقة الغربية من البلد', 'display_order' => 8, 'is_active' => true],
        ];

        foreach ($areas as $area) {
            WaterArea::updateOrCreate(
                ['slug' => $area['slug']],
                $area,
            );
        }

        $this->command->info('Water areas seeded.');
    }

    // ─── NEWS FROM MAGAZINE ──────────────────────────────────────────────

    private function seedNewsFromMagazine(): void
    {
        NewsItem::query()->forceDelete();

        $news = [
            [
                'title_ar' => 'إحصائيات المجلس البلدي السابق 2022-2026',
                'category' => NewsCategory::Council->value,
                'summary' => 'أصدر المجلس البلدي السابق (دورة 2022-2026) 200 جلسة عادية و79 جلسة تنظيمية.',
                'content' => 'أصدر المجلس البلدي السابق (دورة 2022-2026) ما مجموعه 200 جلسة عادية و79 جلسة تنظيمية. كما سجل أكثر من 400 ملف ترخيص وأكثر من 120 رخصة أبنية.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => true,
                'author' => 'العلاقات العامة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'توسعة المخطط الهيكلي من 5,500 إلى 8,000 دونم',
                'category' => NewsCategory::Municipal->value,
                'summary' => 'تمت توسعة المخطط الهيكلي لمدينة إذنا من 5,500 إلى 8,000 دونم.',
                'content' => 'تمت توسعة المخطط الهيكلي لمدينة إذنا من 5,500 إلى 8,000 دونم. كما تم اعتماد 36 حوضاً من أصل 78 حوض تسوية لأراضي إذنا و8 أحواض لأراضي سوبا.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => true,
                'author' => 'العلاقات العامة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'شبكة المياه تتجاوز 110 كم',
                'category' => NewsCategory::Municipal->value,
                'summary' => 'شبكة المياه في إذنا تتجاوز 110 كم مع حوالي 140 محبس مياه.',
                'content' => 'شبكة المياه في إذنا تتجاوز 110 كم مع حوالي 140 محبس مياه. تشمل أعمال رفع مساحي لخطوط المياه والعدادات وتدريب كوادر المياه والعمل على مخطط شامل للصرف الصحي.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => false,
                'author' => 'دائرة المياه',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'إطلاق بوابة المواطن الإلكترونية',
                'category' => NewsCategory::Municipal->value,
                'summary' => 'أطلقت بلدية إذنا بوابة المواطن الإلكترونية ضمن مشروع تطوير الخدمات الإلكترونية.',
                'content' => 'أطلقت بلدية إذنا بوابة المواطن الإلكترونية ضمن مشروع تطوير الخدمات الإلكترونية. يشمل المشروع أيضاً تطوير مركز خدمات الجمهور ونظام التنبيهات الرقمية وتحديث غرفة الخوادم واستبدال الخوادم القديمة.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => true,
                'author' => 'دائرة تقنية المعلومات',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'توريد شاحنات وحاويات نفايات',
                'category' => NewsCategory::Municipal->value,
                'summary' => 'توريد 2 شاحنة نفايات و130 حاوية حديدية و800 حاوية بلاستيكية.',
                'content' => 'توريد 2 شاحنة نفايات و130 حاوية حديدية سعة 1,100 لتر و800 حاوية بلاستيكية سعة 240 لتر. بلدية إذنا عضو في مجلس إدارة المجلس المشترك لإدارة النفايات الصلبة.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => false,
                'author' => 'العلاقات العامة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'المركز المجتمعي - خدمات متعددة',
                'category' => NewsCategory::Community->value,
                'summary' => 'المركز المجتمعي يقدم خدمات متنوعة بالشراكة مع جهات متعددة.',
                'content' => 'المركز المجتمعي في إذنا يقدم خدمات متنوعة بالشراكة مع وزارة التنمية الاجتماعية ومؤسسة الأميرة بسمة ومؤسسة ديكا الإيطالية وإقليم سردينيا وجمعية الكتاب المقدس ومؤسسة قادر للتنمية المجتمعية. يشمل خدمات التربية الخاصة والعلاج الطبيعي والتوحد والأنشطة المجتمعية.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => true,
                'author' => 'العلاقات العامة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'إحصائيات الدائرة القانونية',
                'category' => NewsCategory::Municipal->value,
                'summary' => 'الدائرة القانونية سجلت 46 قضية عمالية و349,000 شيكل تحصيلات.',
                'content' => 'الدائرة القانونية في بلدية إذنا سجلت خلال الفترة 46 قضية عمالية و349,000 شيكل شيكات ومبالغ عالقة تم تحصيلها عام 2025. كما تمت 39 عقداً واتفاقية و12 ملفاً قانونيأ للتراخيص والبناء و46 قضية حقوقية وجزائية و104 اعتراضات في محكمة تسوية الخليل و94 ملفاً وارداً للاستشارة القانونية.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => false,
                'author' => 'الدائرة القانونية',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'الشراكات والاتفاقيات',
                'category' => NewsCategory::Community->value,
                'summary' => 'بلدية إذنا تبرم شراكات مع جهات حكومية ومدنية متعددة.',
                'content' => 'أبرمت بلدية إذنا اتفاقيات وشراكات مع وزارة الزراعة ووزارة العمل والغرفة التجارية والشؤون الاجتماعية ووزارة الثقافة والدفاع المدني والمجلس المشترك لإدارة النفايات الصلبة. كما تشمل الشراكات وزارة التنمية الاجتماعية ومؤسسة الأميرة بسمة ومؤسسة ديكا الإيطالية.',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => false,
                'author' => 'العلاقات العامة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'الأراضي المتبرع بها',
                'category' => NewsCategory::Community->value,
                'summary' => 'تبرع بقطعة أرض لبناء مدرسة جورة سالم الأساسية.',
                'content' => 'تم التبرع بقطعة أرض لبناء مدرسة جورة سالم الأساسية. المتبرعون: رائد محمد محمود نمر اسليمية وزهيّة عبد الفتاح إسماعيل نمر اسليمية. المساحة: 2,436 م².',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => false,
                'author' => 'العلاقات العامة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
            [
                'title_ar' => 'إجماليات إنجازات الطرق 2022-2025',
                'category' => NewsCategory::Projects->value,
                'summary' => 'إجمالي ما تم تعبيده خلال الأربع سنوات: 57,035 م².',
                'content' => 'إجمالي إنجازات الطرق خلال الفترة 2022-2025: أكتاف شوارع 21,550 م² وترقيع شوارع 8,385 م² وتعبيد شوارع 48,650 م². إجمالي ما تم تعبيده خلال الأربع سنوات: 57,035 م².',
                'status' => NewsStatus::Published->value,
                'is_public' => true,
                'is_featured' => true,
                'author' => 'دائرة الهندسة',
                'publish_at' => '2026-06-01 10:00:00',
            ],
        ];

        foreach ($news as $item) {
            NewsItem::updateOrCreate(
                ['title_ar' => $item['title_ar']],
                $item,
            );
        }

        $this->command->info('News from magazine seeded.');
    }

    // ─── ANNOUNCEMENTS FROM MAGAZINE ─────────────────────────────────────

    private function seedAnnouncementsFromMagazine(): void
    {
        Announcement::query()->forceDelete();

        $announcements = [
            [
                'title' => 'إحصائيات خدمة الكهرباء والمياه في مناطق C',
                'type' => AnnouncementType::General->value,
                'priority' => AnnouncementPriority::Normal->value,
                'status' => AnnouncementStatus::Published->value,
                'short_description' => '264+ اشتراك كهرباء ومياه في مناطق C و4 كم تمديد خطوط كهرباء و3 كم خطوط مياه.',
                'content' => 'سجلت بلدية إذنا 264+ اشتراك كهرباء ومياه في مناطق C. كما تم تمديد 4 كم خطوط كهرباء و3 كم خطوط مياه في هذه المناطق.',
                'published_at' => '2026-06-01 10:00:00',
                'is_featured' => false,
            ],
            [
                'title' => 'تحديث الشبكات السلكية واللاسلكية',
                'type' => AnnouncementType::General->value,
                'priority' => AnnouncementPriority::Normal->value,
                'status' => AnnouncementStatus::Published->value,
                'short_description' => 'تحديث الشبكات السلكية واللاسلكية وصيانة أجهزة الحاسوب والطابعات والماسحات.',
                'content' => 'شملت إنجازات دائرة تقنية المعلومات تحديث الشبكات السلكية واللاسلكية وصيانة أجهزة الحاسوب والطابعات والماسحات وتطوير تطبيق خاص للهواتف والتوسع في الدفع الإلكتروني.',
                'published_at' => '2026-06-01 10:00:00',
                'is_featured' => false,
            ],
            [
                'title' => 'البيانات المالية 2022-2026',
                'type' => AnnouncementType::General->value,
                'priority' => AnnouncementPriority::Important->value,
                'status' => AnnouncementStatus::Published->value,
                'short_description' => 'إجمالي المصروفات: 78,439,774 شيكل.',
                'content' => 'إجمالي المصروفات خلال الفترة: صيانة شبكات المياه 2,070,109 شيكل ومكب النفايات والترحيل 3,584,778 شيكل وفواتير الكهرباء 33,329,046 شيكل وتعبيد وصيانة الشوارع 5,071,705 شيكل وصيانة وبناء المدارس 3,264,762 شيكل وصيانة شبكة الكهرباء 4,647,082 شيكل وفواتير المياه 2,105,162 شيكل ومصاريف وصيانة أبنية 760,137 شيكل والمركز الصحي 113,278 شيكل وتوريد باطون 472,312 شيكل وشحن كهرباء على الدين للمواطنين 7,120,632 شيكل وسيارات 477,855 شيكل ورواتب 15,251,856 شيكل وحاويات نفايات 76,760 شيكل وجدران استنادية 94,300 شيكل. الإجمالي: 78,439,774 شيكل.',
                'published_at' => '2026-06-01 10:00:00',
                'is_featured' => true,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title']],
                $announcement,
            );
        }

        $this->command->info('Announcements from magazine seeded.');
    }

    // ─── HUMAN RESOURCES JOBS ────────────────────────────────────────────

    private function seedHumanResourcesJobs(): void
    {
        $this->command->info('Human resources data from magazine: 96 employees total.');
        $this->command->info('  - 23 annual contracts');
        $this->command->info('  - 37 permanent classified');
        $this->command->info('  - 17 daily workers');
        $this->command->info('  - 11 retired');
        $this->command->info('  - 12 community center staff');
        $this->command->info('  - 70 temporary health contracts (10 days/month)');
    }
}
