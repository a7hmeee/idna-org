<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Department\Enums\DepartmentStatus;
use App\Domains\Department\Models\Department;
use App\Domains\RoleManagement\Support\PermissionSynchronizer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class DepartmentPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $registry = config('permissions');

        $departmentRegistry = array_filter($registry, fn (array $module): bool => $module['module'] === 'departments');

        $synchronizer = app(PermissionSynchronizer::class);
        $synchronizer->sync($departmentRegistry);

        $departmentPermissionNames = $synchronizer->getRegisteredNames($departmentRegistry);

        $superAdmin = Role::findOrCreate('Super Admin');
        $superAdmin->givePermissionTo($departmentPermissionNames);

        $admin = Role::findOrCreate('Admin');
        $admin->givePermissionTo($departmentPermissionNames);

        $departmentManager = Role::findOrCreate('Department Manager');
        $departmentManager->givePermissionTo([
            'departments.view',
            'departments.update',
        ]);

        $departments = [
            [
                'name' => 'قسم الهندسة والمشاريع',
                'slug' => 'قسم-الهندسة-والمشاريع',
                'short_description' => 'الإشراف على المشاريع البلدية والبنية التحتية',
                'description' => 'يقوم هذا القسم بالإشراف على تنفيذ المشاريع البلدية المختلفة، ومتابعة أعمال البنية التحتية، والإشراف على الطرق والشوارع، والمباني البلدية، وضمان جودة التنفيذ وفق المواصفات المعتمدة.',
                'icon' => 'building-2',
                'manager_name' => 'م. أحمد خليل',
                'manager_position' => 'مدير قسم الهندسة والمشاريع',
                'phone' => '022345678',
                'extension' => '101',
                'mobile' => '0599123456',
                'email' => 'engineering@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الثاني - مكتب 201',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'الريادة في تنفيذ المشاريع البلدية وفق أعلى معايير الجودة',
                'mission' => 'تطوير البنية التحتية وتحسين الخدمات المقدمة للمواطنين',
                'responsibilities' => "الإشراف على المشاريع البلدية\nمتابعة أعمال البنية التحتية\nالإشراف على صيانة الطرق والمباني\nإعداد الدراسات الهندسية\nمتابعة تراخيص البناء",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 1,
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'قسم الصحة والبيئة',
                'slug' => 'قسم-الصحة-والبيئة',
                'short_description' => 'الحفاظ على الصحة العامة والرقابة البيئية',
                'description' => 'مسؤول عن متابعة الشؤون الصحية والبيئية في المدينة، والإشراف على المسالخ والأسواق، ومكافحة الآفات، والرقابة على الأغذية والمحلات التجارية.',
                'icon' => 'stethoscope',
                'manager_name' => 'د. محمد عوض',
                'manager_position' => 'مدير قسم الصحة والبيئة',
                'phone' => '022345679',
                'extension' => '102',
                'mobile' => '0599123457',
                'email' => 'health@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الأول - مكتب 105',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'مدينة نظيفة وصحية تلبي تطلعات المواطنين',
                'mission' => 'الحفاظ على الصحة العامة والرقابة البيئية المستدامة',
                'responsibilities' => "الرقابة على الأغذية والمحلات\nمكافحة الآفات والحشرات\nالإشراف على المسالخ\nمتابعة النفايات الطبية\nالرقابة على الأسواق",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 2,
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'قسم الشؤون الإدارية والمالية',
                'slug' => 'قسم-الشؤون-الإدارية-والمالية',
                'short_description' => 'إدارة الموارد المالية والبشرية للبلدية',
                'description' => 'يتولى هذا القسم إدارة الشؤون المالية والإدارية، بما في ذلك إعداد الموازنة، والمشتريات، والموارد البشرية، والشؤون القانونية والإدارية.',
                'icon' => 'wallet',
                'manager_name' => 'أ. سامي النجار',
                'manager_position' => 'مدير الشؤون الإدارية والمالية',
                'phone' => '022345680',
                'extension' => '103',
                'mobile' => '0599123458',
                'email' => 'finance@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الثالث - مكتب 301',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'التميز في الإدارة المالية والإدارية لخدمة المواطنين',
                'mission' => 'تنظيم الموارد المالية والبشرية بكفاءة وشفافية',
                'responsibilities' => "إعداد الموازنة العامة\nإدارة المشتريات والعطاءات\nإدارة الموارد البشرية\nصرف الرواتب والمستحقات\nإدارة السجلات المالية",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 3,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'قسم العلاقات العامة والإعلام',
                'slug' => 'قسم-العلاقات-العامة-والإعلام',
                'short_description' => 'التواصل مع المواطنين والإعلام',
                'description' => 'يقوم بإدارة علاقات البلدية مع الجمهور والإعلام، وتنظيم الفعاليات والمناسبات، ونشر أخبار البلدية وإنجازاتها عبر وسائل التواصل المختلفة.',
                'icon' => 'megaphone',
                'manager_name' => 'أ. ليلى حسن',
                'manager_position' => 'مديرة قسم العلاقات العامة والإعلام',
                'phone' => '022345681',
                'extension' => '104',
                'mobile' => '0599123459',
                'email' => 'media@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الأرضي - مكتب 5',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'تواصل مؤسسي متميز مع المجتمع المحلي والإعلام',
                'mission' => 'تعزيز الصورة الذهنية للبلدية وبناء جسور التواصل',
                'responsibilities' => "إدارة حسابات التواصل الاجتماعي\nتنظيم المؤتمرات والفعاليات\nالرد على استفسارات المواطنين\nإعداد النشرات الإخبارية\nالتنسيق مع وسائل الإعلام",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 4,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'قسم النظافة والخدمات',
                'slug' => 'قسم-النظافة-والخدمات',
                'short_description' => 'الإشراف على أعمال النظافة وجمع النفايات',
                'description' => 'مسؤول عن جمع النفايات ونظافة الشوارع والأماكن العامة، وإدارة مكبات النفايات، والإشراف على عقود النظافة مع المقاولين.',
                'icon' => 'trash-2',
                'manager_name' => 'أ. خالد أبو عمر',
                'manager_position' => 'مدير قسم النظافة والخدمات',
                'phone' => '022345682',
                'extension' => '105',
                'mobile' => '0599123460',
                'email' => 'cleaning@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الأرضي - مكتب 3',
                'working_hours' => 'من الأحد إلى الخميس 7:00 ص - 2:00 م',
                'vision' => 'مدينة نظيفة وجميلة تسعد سكانها وزوارها',
                'mission' => 'تقديم خدمات نظافة متميزة وبجودة عالية',
                'responsibilities' => "جمع النفايات المنزلية\nنظافة الشوارع والأماكن العامة\nإدارة مكب النفايات\nالإشراف على مقاولي النظافة\nإدارة صناديق النفايات",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 5,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'قسم التخطيط والتطوير',
                'slug' => 'قسم-التخطيط-والتطوير',
                'short_description' => 'التخطيط الاستراتيجي وتطوير الأداء المؤسسي',
                'description' => 'يتولى إعداد الخطط الاستراتيجية للبلدية، ومتابعة تنفيذها، وتطوير الأداء المؤسسي، وإدارة مشاريع التحسين والتطوير الإداري.',
                'icon' => 'line-chart',
                'manager_name' => 'د. نادر عبد الرحمن',
                'manager_position' => 'مدير قسم التخطيط والتطوير',
                'phone' => '022345683',
                'extension' => '106',
                'mobile' => '0599123461',
                'email' => 'planning@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الثاني - مكتب 202',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'الريادة في التخطيط الحضري والتنمية المستدامة',
                'mission' => 'بناء مستقبل أفضل للمدينة من خلال التخطيط العلمي',
                'responsibilities' => "إعداد الخطط الاستراتيجية\nتطوير الأداء المؤسسي\nإدارة مشاريع التطوير\nإعداد التقارير الدورية\nمتابعة مؤشرات الأداء",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 6,
                'is_public' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'قسم الشؤون القانونية',
                'slug' => 'قسم-الشؤون-القانونية',
                'short_description' => 'تقديم الاستشارات القانونية وإدارة القضايا',
                'description' => 'يقدم الاستشارات القانونية للبلدية، ويتولى إدارة القضايا والعقود، وإعداد الأنظمة واللوائح البلدية، ومتابعة التعديات على أملاك البلدية.',
                'icon' => 'shield',
                'manager_name' => 'أ. عفاف سالم',
                'manager_position' => 'مديرة قسم الشؤون القانونية',
                'phone' => '022345684',
                'extension' => '107',
                'mobile' => '0599123462',
                'email' => 'legal@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الثالث - مكتب 305',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'التميز في العمل القانوني لحماية مصالح البلدية',
                'mission' => 'تقديم خدمات قانونية احترافية وفق أفضل الممارسات',
                'responsibilities' => "إدارة القضايا والدعاوى\nإعداد وصياغة العقود\nإعداد الأنظمة واللوائح\nالمتابعة القانونية للتعديات\nتقديم الاستشارات القانونية",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 7,
                'is_public' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'قسم تقنية المعلومات',
                'slug' => 'قسم-تقنية-المعلومات',
                'short_description' => 'إدارة البنية التحتية التقنية للبلدية',
                'description' => 'مسؤول عن إدارة أنظمة المعلومات والبنية التحتية التقنية، وصيانة الأجهزة والشبكات، وتطوير الخدمات الإلكترونية، وأمن المعلومات.',
                'icon' => 'laptop',
                'manager_name' => 'م. باسل جاد',
                'manager_position' => 'مدير قسم تقنية المعلومات',
                'phone' => '022345685',
                'extension' => '108',
                'mobile' => '0599123463',
                'email' => 'it@idhna.ps',
                'office_location' => 'المبنى الرئيسي - الطابق الأول - مكتب 108',
                'working_hours' => 'من الأحد إلى الخميس 8:00 ص - 3:00 م',
                'vision' => 'التحول الرقمي الكامل لخدمات البلدية',
                'mission' => 'تطوير البنية التحتية التقنية وتقديم خدمات إلكترونية متميزة',
                'responsibilities' => "إدارة الشبكة والخوادم\nصيانة الأجهزة\nتطوير الأنظمة والمواقع\nإدارة قواعد البيانات\nأمن المعلومات والنسخ الاحتياطي",
                'status' => DepartmentStatus::Active->value,
                'display_order' => 8,
                'is_public' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
