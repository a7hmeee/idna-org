<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Authentication\Models\User;
use App\Domains\Department\Models\Department;
use App\Domains\Jobs\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class JobSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $departments = Department::pluck('name', 'id')->toArray();

        $jobs = [
            [
                'title' => 'مهندس بلدي',
                'department_key' => 'الهندسة',
                'job_number' => 'ج-2026-001',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '5000-7000 شيكل',
                'vacancies' => 2,
                'summary' => 'مطلوب مهندس بلدي للعمل في قسم الهندسة للإشراف على المشاريع البلدية.',
                'description' => "المهندس البلدي مسؤول عن الإشراف على المشاريع البلدية والتأكد من مطابقتها للمواصفات.\n\nالمهام الرئيسية:\n- الإشراف على مشاريع البنية التحتية\n- إعداد التقارير الهندسية\n- متابعة المقاولين",
                'requirements' => ['بكالوريوس هندسة مدنية', 'خبرة 3 سنوات على الأقل', 'إجادة استخدام برامج الهندسة', 'رخصة مهنية سارية'],
                'responsibilities' => ['الإشراف على المشاريع البلدية', 'إعداد التقارير الهندسية', 'متابعة المقاولين', 'الإشراف على أعمال الصيانة'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية', 'شهادات الخبرة'],
                'application_method' => 'email',
                'application_email' => 'hr@idhna.ps',
            ],
            [
                'title' => 'محاسب',
                'department_key' => 'المالية',
                'job_number' => 'ج-2026-002',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '4000-5500 شيكل',
                'vacancies' => 1,
                'summary' => 'مطلوب محاسب للعمل في دائرة المالية لإدارة الحسابات البلدية.',
                'description' => "المحاسب مسؤول عن إدارة الحسابات البلدية والتقارير المالية.\n\nالمهام الرئيسية:\n- إدارة القيود المحاسبية\n- إعداد التقارير المالية الدورية\n- متابعة المقبوضات والمدفوعات",
                'requirements' => ['بكالوريوس محاسبة', 'خبرة سنتين على الأقل', 'إجادة استخدام البرامج المحاسبية'],
                'responsibilities' => ['إدارة القيود المحاسبية', 'إعداد التقارير المالية', 'متابعة المقبوضات والمدفوعات', 'التسوية المصرفية'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية'],
                'application_method' => 'email',
                'application_email' => 'hr@idhna.ps',
            ],
            [
                'title' => 'عامل نظافة',
                'job_number' => 'ج-2026-003',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '2000 شيكل',
                'vacancies' => 5,
                'summary' => 'مطلوب عمال نظافة للعمل في قسم الصحة والبيئة.',
                'description' => 'عمال النظافة مسؤولون عن الحفاظ على نظافة المدينة.',
                'requirements' => ['خبرة في مجال النظافة', 'قدرة على العمل الميداني'],
                'responsibilities' => ['كنس الشوارع', 'جمع النفايات', 'تنظيف المرافق العامة'],
                'required_documents' => ['صورة الهوية', 'شهادة خلو أطراف'],
                'application_method' => 'office',
            ],
            [
                'title' => 'سكرتير',
                'department_key' => 'الشؤون الإدارية',
                'job_number' => 'ج-2026-004',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '3000-4000 شيكل',
                'vacancies' => 1,
                'summary' => 'مطلوب سكرتير للعمل في مكتب رئيس البلدية.',
                'description' => 'السكرتير مسؤول عن تنظيم المواعيد وإدارة المراسلات.',
                'requirements' => ['دبلوم سكرتارية', 'إجادة استخدام الحاسوب', 'مهارات تواصل عالية'],
                'responsibilities' => ['تنظيم المواعيد', 'إدارة المراسلات', 'استقبال الزوار', 'تنظيم الملفات'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية'],
                'application_method' => 'email',
                'application_email' => 'hr@idhna.ps',
            ],
            [
                'title' => 'مهندس كهرباء',
                'department_key' => 'الهندسة',
                'job_number' => 'ج-2026-005',
                'employment_type' => 'contract',
                'location' => 'إذنا',
                'salary' => '6000-8000 شيكل',
                'vacancies' => 1,
                'summary' => 'مطلوب مهندس كهرباء للعمل على مشاريع الإنارة البلدية.',
                'description' => 'مهندس كهرباء مسؤول عن الإشراف على مشاريع الإنارة والكهرباء.',
                'requirements' => ['بكالوريوس هندسة كهرباء', 'خبرة 5 سنوات', 'رخصة مزاولة مهنة'],
                'responsibilities' => ['الإشراف على مشاريع الإنارة', 'صيانة الشبكات الكهربائية', 'إعداد الدراسات الفنية'],
                'benefits' => ['تأمين صحي', 'بدل نقل'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية', 'شهادات الخبرة'],
                'application_method' => 'external_link',
                'application_url' => 'https://idhna.ps/careers',
            ],
            [
                'title' => 'مراقب صحي',
                'department_key' => 'الصحة',
                'job_number' => 'ج-2026-006',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '3500-4500 شيكل',
                'vacancies' => 2,
                'summary' => 'مطلوب مراقب صحي للعمل في قسم الصحة والبيئة لمتابعة الامتثال الصحي.',
                'description' => "المراقب الصحي مسؤول عن متابعة الامتثال الصحي في المنشآت التجارية والغذائية.\n\nالمهام الرئيسية:\n- جولات تفتيشية على المحلات\n- متابعة التراخيص الصحية\n- إعداد تقارير المخالفات",
                'requirements' => ['دبلوم في الصحة العامة أو المجال الطبي', 'خبرة سنتين', 'رخصة قيادة'],
                'responsibilities' => ['جولات تفتيشية', 'متابعة التراخيص الصحية', 'إعداد تقارير المخالفات', 'التنسيق مع الجهات الرقابية'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية'],
                'application_method' => 'email',
                'application_email' => 'hr@idhna.ps',
            ],
            [
                'title' => 'سائق بلدية',
                'job_number' => 'ج-2026-007',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '2500 شيكل',
                'vacancies' => 3,
                'summary' => 'مطلوب سائقين للعمل في أسطول البلدية لنقل المعدات والعمال.',
                'description' => "السائق مسؤول عن نقل المعدات والعمال إلى مواقع العمل المختلفة.\n\nالمهام الرئيسية:\n- قيادة آليات البلدية\n- نقل العمال والمعدات\n- الصيانة الأولية للمركبات",
                'requirements' => ['رخصة قيادة مهنية سارية', 'خبرة سنتين على الأقل', 'القدرة على العمل الميداني'],
                'responsibilities' => ['قيادة آليات البلدية', 'نقل العمال والمعدات', 'الصيانة الأولية للمركبات', 'تسليم التقارير اليومية'],
                'required_documents' => ['صورة الهوية', 'رخصة قيادة', 'شهادة خلو أطراف'],
                'application_method' => 'office',
            ],
            [
                'title' => 'فني حاسوب',
                'department_key' => 'الشؤون الإدارية',
                'job_number' => 'ج-2026-008',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '4000-5000 شيكل',
                'vacancies' => 1,
                'summary' => 'مطلوب فني حاسوب لدعم البنية التحتية التقنية في البلدية.',
                'description' => "فني الحاسوب مسؤول عن صيانة أجهزة الحاسوب والشبكات في البلدية.\n\nالمهام الرئيسية:\n- صيانة أجهزة الحاسوب\n- إدارة الشبكات\n- دعم المستخدمين",
                'requirements' => ['دبلوم حاسوب أو تكنولوجيا معلومات', 'خبرة سنتين', 'معرفة في الشبكات'],
                'responsibilities' => ['صيانة أجهزة الحاسوب', 'إدارة الشبكات', 'دعم المستخدمين', 'إدارة قواعد البيانات'],
                'benefits' => ['تأمين صحي'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية'],
                'application_method' => 'email',
                'application_email' => 'hr@idhna.ps',
            ],
            [
                'title' => 'مساعد إداري',
                'department_key' => 'الشؤون الإدارية',
                'job_number' => 'ج-2026-009',
                'employment_type' => 'full_time',
                'location' => 'إذنا',
                'salary' => '3000-4000 شيكل',
                'vacancies' => 2,
                'summary' => 'مطلوب مساعد إداري للعمل في دائرة الشؤون الإدارية.',
                'description' => "المساعد الإداري مسؤول عن دعم العمليات الإدارية والتنظيمية في الدائرة.\n\nالمهام الرئيسية:\n- تنظيم الملفات والسجلات\n- إعداد المراسلات\n- دubMed الفرق الإدارية",
                'requirements' => ['دبلوم إدارة مكتبية', 'إجادة استخدام الحاسوب', 'مهارات تنظيمية'],
                'responsibilities' => ['تنظيم الملفات والسجلات', 'إعداد المراسلات', 'دعم الفرق الإدارية', 'إدارة المواعيد'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية'],
                'application_method' => 'email',
                'application_email' => 'hr@idhna.ps',
            ],
            [
                'title' => 'مهندس زراعي',
                'department_key' => 'الهندسة',
                'job_number' => 'ج-2026-010',
                'employment_type' => 'contract',
                'location' => 'إذنا',
                'salary' => '5000-6000 شيكل',
                'vacancies' => 1,
                'summary' => 'مطلوب مهندس زراعي للإشراف على المساحات الخضراء والحدائق العامة.',
                'description' => "المهندس الزراعي مسؤول عن تطوير وصيانة المساحات الخضراء والحدائق العامة في البلدية.\n\nالمهام الرئيسية:\n- الإشراف على الحدائق العامة\n- تطوير المساحات الخضراء\n- متابعة أعمال التشجير",
                'requirements' => ['بكالوريوس هندسة زراعية', 'خبرة 3 سنوات', 'معرفة في نظم الري'],
                'responsibilities' => ['الإشراف على الحدائق العامة', 'تطوير المساحات الخضراء', 'متابعة أعمال التشجير', 'الإشراف على فرق الصيانة الزراعية'],
                'benefits' => ['بدل مواصلات'],
                'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية', 'شهادات الخبرة'],
                'application_method' => 'external_link',
                'application_url' => 'https://idhna.ps/careers',
            ],
        ];

        $defaultDepartment = Department::first();

        foreach ($jobs as $jobData) {
            $departmentId = null;
            if (isset($jobData['department_key'])) {
                $dept = Department::where('name', 'like', '%'.$jobData['department_key'].'%')->first();
                $departmentId = $dept?->id ?? $defaultDepartment?->id;
            }

            $slug = Str::slug($jobData['title']);
            if ($jobData['job_number'] ?? null) {
                $slug .= '-'.Str::slug($jobData['job_number']);
            }

            Job::firstOrCreate(
                ['slug' => $slug],
                [
                    'department_id' => $departmentId,
                    'title' => $jobData['title'],
                    'slug' => $slug,
                    'job_number' => $jobData['job_number'] ?? null,
                    'employment_type' => $jobData['employment_type'],
                    'location' => $jobData['location'],
                    'salary' => $jobData['salary'] ?? null,
                    'vacancies' => $jobData['vacancies'],
                    'summary' => $jobData['summary'],
                    'description' => $jobData['description'],
                    'requirements' => $jobData['requirements'],
                    'responsibilities' => $jobData['responsibilities'],
                    'benefits' => $jobData['benefits'] ?? null,
                    'required_documents' => $jobData['required_documents'],
                    'application_method' => $jobData['application_method'],
                    'application_url' => $jobData['application_url'] ?? null,
                    'application_email' => $jobData['application_email'] ?? null,
                    'application_phone' => $jobData['application_phone'] ?? null,
                    'publish_at' => now()->subDay()->toDateString(),
                    'closing_at' => now()->addMonth()->toDateString(),
                    'status' => 'published',
                    'is_public' => true,
                    'is_featured' => in_array($jobData['title'], ['مهندس بلدي', 'مهندس كهرباء']),
                    'created_by' => $admin?->id,
                    'updated_by' => $admin?->id,
                ]
            );
        }

        $this->command?->info('✅ Jobs seeded successfully.');
    }
}
