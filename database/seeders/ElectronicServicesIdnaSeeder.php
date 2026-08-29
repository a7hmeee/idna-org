<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds electronic services from Idna Municipality portal (i.palexpand.ps).
 *
 * Data source: Official portal dump (portal_dump.zip) + specification document.
 * Categories, services, portal URLs, and form field metadata are from the source.
 * No fabricated data, descriptions, fees, or requirements are added.
 */
final class ElectronicServicesIdnaSeeder extends Seeder
{
    public function run(): void
    {
        ServiceCategory::query()->forceDelete();

        $categories = $this->getCategories();

        foreach ($categories as $catData) {
            $services = $catData['services'];
            unset($catData['services']);

            $category = ServiceCategory::create($catData);

            foreach ($services as $serviceData) {
                $serviceData['service_category_id'] = $category->id;
                $serviceData['status'] = 'active';
                $serviceData['is_public'] = true;
                $serviceData['is_featured'] = true;
                $serviceData['requires_login'] = true;
                $serviceData['sort_order'] = ($serviceData['sort_order'] ?? 0);

                ElectronicService::create($serviceData);
            }
        }

        $totalCategories = ServiceCategory::count();
        $totalServices = ElectronicService::count();

        $this->command->info("Seeded {$totalCategories} service categories and {$totalServices} electronic services.");
    }

    private function getCategories(): array
    {
        return [
            // ─── Category 1: Electricity ────────────────────────────
            [
                'name' => 'طلبات وشكاوى الكهرباء',
                'description' => null,
                'icon' => 'zap',
                'status' => 'active',
                'is_public' => true,
                'sort_order' => 1,
                'services' => [
                    [
                        'name' => 'عطل كهرباء لمشترك',
                        'summary' => 'تقديم بلاغ عن عطل كهربائي لمشترك.',
                        'description' => 'تتيح هذه الخدمة للمواطن المُشترك لدى شركة الكهرباء تقديم بلاغ عن عطل في الكهرباء داخل منطقته. يُطلب من مقدم الطلب إدخال بيانات الهوية والجوال والموقع المحدد من خلال قائمة المناطق المتاحة، ثم وصف العطل بشكل موجز وإرفاق صورة أو مستندات داعمة إن وُجدت.',
                        'portal_url' => 'https://i.palexpand.ps/portal/elecMalfunction',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم الكامل ورقم الهوية (9 أرقام) ورقم الجوال (10 أرقام).'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة من القائمة المنسدلة ثم أدخل العنوان التفصيلي.'],
                            ['title' => 'وصف العطل', 'description' => 'اكتب وصفاً موجزاً لطبيعة العطل الذي تواجهه.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'قم بإرفاق صورة للعطل أو أي مستندات داعمة عند الحاجة.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات ثم اضغط على إرسال لتقديم البلاغ.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                            ['title' => 'المنطقة', 'description' => 'يجب اختيار المنطقة من القائمة', 'is_required' => true],
                            ['title' => 'وصف العطل', 'description' => 'وصف موجز لطبيعة العطل', 'is_required' => false],
                        ],
                        'documents' => [
                            ['name' => 'صورة العطل', 'description' => 'صورة توضيحية للعطل إن أمكن', 'is_required' => false],
                        ],
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'عطل كهرباء عام',
                        'summary' => 'تقديم بلاغ عن عطل كهربائي عام في منطقة ما.',
                        'description' => 'تتيح هذه الخدمة للمواطن الإبلاغ عن عطل كهربائي عام يؤثر على منطقة أو شارع بأكمله. يختلف هذا النوع عن عطل المشترك في أنه لا يتطلب اشتراكاً محدداً، ويكون العطل عاماً يؤثر على多人. يتم تحديد الموقع من خلال القائمة المنسدلة للمناطق.',
                        'portal_url' => 'https://i.palexpand.ps/portal/globalelecMalfunction',
                        'steps' => [
                            ['title' => 'وصف العطل', 'description' => 'اكتب وصفاً واضحاً للعطل العام الذي تلاحظه.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان التفصيلي.'],
                            ['title' => 'إدخال بيانات الاتصال', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال للتواصل معك.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق صوراً أو مستندات تدعم بلاغك.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'وصف العطل', 'description' => 'وصف واضح لطبيعة العطل العام', 'is_required' => true],
                            ['title' => 'المنطقة', 'description' => 'يجب تحديد المنطقة المتأثرة', 'is_required' => true],
                        ],
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'نقل عامود كهرباء/كابل',
                        'summary' => 'طلب نقل أو رفع عامود أو كابل كهرباء.',
                        'description' => 'تتيح هذه الخدمة للمواطن طلب نقل أو رفع أو تعديل عامود كهرباء أو كابل في منطقته. يشمل ذلك几种 من المهام مثل نقل العامود، نقل الكابل، رفع الكابل، إضافة عامود جديد، تغيير كابل الكهرباء، وزراعة أعمدة. يتم اختيار نوع المهمة من القائمة المنسدلة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/newTicket37',
                        'steps' => [
                            ['title' => 'اختيار نوع المهمة', 'description' => 'حدد نوع العمل المطلوب: نقل عامود، نقل كابل، رفع الكابل، إضافة عامود، تغيير كابل، زراعة أعمدة.'],
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان التفصيلي.'],
                            ['title' => 'وصف العمل المطلوب', 'description' => 'اكتب تفاصيل إضافية về العمل المطلوب.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق مخططات أو صوراً داعمة إن وُجدت.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'نوع المهمة', 'description' => 'يجب اختيار نوع العمل من القائمة', 'is_required' => true],
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                        ],
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'اصلاح / تركيب وحدات انارة تالفة',
                        'summary' => 'طلب اصلاح أو تركيب وحدات انارة في الشارع.',
                        'description' => 'تتيح هذه الخدمة للمواطن طلب اصلاح أو تركيب وحدات انارة تالفة في الشارع العام. يشمل ذلك تركيب وحدات انارة جديدة، اصلاح وحدات تالفة، تركيب كشافات، أو تركيب كشافات على الشارع العام. يتم اختيار نوع المهمة من القائمة المنسدلة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/newTicket27',
                        'steps' => [
                            ['title' => 'اختيار نوع المهمة', 'description' => 'حدد: تركيب وحدات انارة، اصلاح وحدات انارة، تركيب كشاف، تركيب كشافات على الشارع العام.'],
                            ['title' => 'وصف المشكلة', 'description' => 'اكتب وصفاً واضحاً للعطل أو العمل المطلوب.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان التفصيلي.'],
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق صوراً للوحدة التالفة أو الموقع.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'نوع المهمة', 'description' => 'يجب اختيار نوع العمل من القائمة', 'is_required' => true],
                            ['title' => 'وصف المشكلة', 'description' => 'وصف واضح للمشكلة', 'is_required' => false],
                        ],
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'عبث في عداد كهرباء',
                        'summary' => 'الإبلاغ عن عبث أو تلاعب في عداد كهرباء.',
                        'description' => null,
                        'portal_url' => 'https://i.palexpand.ps/portal/elecTheft',
                        'steps' => null,
                        'sort_order' => 5,
                    ],
                    [
                        'name' => 'تغيير عداد كهرباء',
                        'summary' => 'طلب تغيير أو استبدال عداد كهرباء.',
                        'description' => null,
                        'portal_url' => 'https://i.palexpand.ps/portal/elecMeterChangeRead',
                        'steps' => null,
                        'sort_order' => 6,
                    ],
                ],
            ],

            // ─── Category 2: Water ─────────────────────────────────
            [
                'name' => 'طلبات وشكاوى المياه',
                'description' => null,
                'icon' => 'droplet',
                'status' => 'active',
                'is_public' => true,
                'sort_order' => 2,
                'services' => [
                    [
                        'name' => 'عطل مياه لمشترك',
                        'summary' => 'تقديم بلاغ عن عطل في المياه لمشترك.',
                        'description' => 'تتيح هذه الخدمة للمواطن المُشترك لدى شركة المياه تقديم بلاغ عن عطل في شبكة المياه يخص مشتركه. يُطلب تحديد بيانات الهوية والجوال والموقع من خلال قائمة المناطق، ثم وصف العطل وإرفاق المستندات الداعمة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/waterMalfunction',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم الكامل ورقم الهوية (9 أرقام) ورقم الجوال (10 أرقام).'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة من القائمة المنسدلة ثم أدخل العنوان التفصيلي.'],
                            ['title' => 'وصف العطل', 'description' => 'اكتب وصفاً موجزاً لطبيعة عطل المياه.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'قم بإرفاق صورة أو مستندات داعمة عند الحاجة.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات ثم اضغط على إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                            ['title' => 'المنطقة', 'description' => 'يجب اختيار المنطقة من القائمة', 'is_required' => true],
                        ],
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'عطل مياه عام',
                        'summary' => 'تقديم بلاغ عن عطل مياه عام في منطقة ما.',
                        'description' => 'تتيح هذه الخدمة للمواطن الإبلاغ عن عطل عام في شبكة المياه يؤثر على منطقة بأكمله. لا يتطلب هذا النوع اشتراكاً محدداً، ويكون العطل عاماً ي ảnh على多人. يتم تحديد الموقع من خلال القائمة المنسدلة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/globalWaterMalfunction',
                        'steps' => [
                            ['title' => 'وصف العطل', 'description' => 'اكتب وصفاً واضحاً للعطل العام.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان التفصيلي.'],
                            ['title' => 'إدخال بيانات الاتصال', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق صوراً أو مستندات داعمة.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'وصف العطل', 'description' => 'وصف واضح لطبيعة العطل العام', 'is_required' => true],
                            ['title' => 'المنطقة', 'description' => 'يجب تحديد المنطقة المتأثرة', 'is_required' => true],
                        ],
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'طلب شبك عقار بالصرف الصحي',
                        'summary' => 'طلب ربط عقار بشبكة الصرف الصحي.',
                        'description' => null,
                        'portal_url' => 'https://i.palexpand.ps/portal/buildingSewage',
                        'steps' => null,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'عبث في عداد المياه',
                        'summary' => 'الإبلاغ عن عبث أو تلاعب في عداد مياه.',
                        'description' => null,
                        'portal_url' => 'https://i.palexpand.ps/portal/waterMeterTampering',
                        'steps' => null,
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'تلف عداد مياه',
                        'summary' => 'طلب إصلاح أو استبدال عداد مياه تالف.',
                        'description' => 'تتيح هذه الخدمة للمواطن المُشترك طلب إصلاح أو استبدال عداد مياه تالف. يتم إدخال بيانات الهوية والجوال والموقع، ثم إرفاق صورة للعداد التالف إن أمكن.',
                        'portal_url' => 'https://i.palexpand.ps/portal/waterMeterDamaged',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان التفصيلي.'],
                            ['title' => 'إرفاق صورة العداد', 'description' => 'قم بإرفاق صورة واضحة للعداد التالف.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                            ['title' => 'صورة العداد', 'description' => 'صورة واضحة للعداد التالف', 'is_required' => false],
                        ],
                        'documents' => [
                            ['name' => 'صورة العداد التالف', 'description' => 'صورة واضحة تظهر تلف العداد', 'is_required' => false],
                        ],
                        'sort_order' => 5,
                    ],
                ],
            ],

            // ─── Category 3: Roads ─────────────────────────────────
            [
                'name' => 'طلبات وشكاوى الطرق',
                'description' => null,
                'icon' => 'road',
                'status' => 'active',
                'is_public' => true,
                'sort_order' => 3,
                'services' => [
                    [
                        'name' => 'شكوى طريق',
                        'summary' => 'تقديم شكوى عن حالة طريق أو شارع.',
                        'description' => 'تتيح هذه الخدمة للمواطن تقديم شكوى حول حالة طريق أو شارع عام. يمكن الإبلاغ عن تلف في الإسفلت، غياب 인ارة، مشاكل في التصريف، أو أي عيوب أخرى في البنية التحتية للطرق. يتم تحديد نوع الطلب وإدخال البيانات和个人ية ووصف المشكلة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/streetComplaint',
                        'steps' => [
                            ['title' => 'اختيار نوع الطلب', 'description' => 'حدد نوع الشكوى أو الطلب من القائمة.'],
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'وصف الشكوى', 'description' => 'اكتب وصفاً واضحاً لحالة الطريق أو المشكلة.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق صوراً للحالة إن أمكن.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                            ['title' => 'وصف الشكوى', 'description' => 'وصف واضح لحالة الطريق', 'is_required' => false],
                        ],
                        'sort_order' => 1,
                    ],
                ],
            ],

            // ─── Category 4: Planning & Building ───────────────────
            [
                'name' => 'طلبات وشكاوى التنظيم والبناء',
                'description' => null,
                'icon' => 'blueprint',
                'status' => 'active',
                'is_public' => true,
                'sort_order' => 4,
                'services' => [
                    [
                        'name' => 'طلب استقامة',
                        'summary' => 'طلب استقامة أرض أو عقار على خط التنظيم.',
                        'description' => 'تتيح هذه الخدمة لمالكي الأراضي أو ممثليهم طلب استقامة أرض أو عقار على خط التنظيم المعتمد. يتم إدخال بيانات المالك والموقع من خلال رقم الحوض ورقم القطعة، مع إرفاق المخططات أو المستندات الداعمة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/straightening',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان ورقم الحوض ورقم القطعة.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق المخططات أو المستندات الداعمة للطلب.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الحوض', 'description' => 'رقم الحوض في المخطط الهيكلي', 'is_required' => true],
                            ['title' => 'رقم القطعة', 'description' => 'رقم القطعة الأرضية', 'is_required' => true],
                        ],
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'فتح ملف ترخيص',
                        'summary' => 'طلب فتح ملف ترخيص بناء جديد.',
                        'description' => 'تتيح هذه الخدمة لمالكي الأراضي أو ممثليهم طلب فتح ملف ترخيص بناء. يتم إدخال بيانات المالك والموقع من خلال رقم الحوض ورقم القطعة، مع إرفاق المستندات المطلوبة بشكل إلزامي.',
                        'portal_url' => 'https://i.palexpand.ps/portal/licenseFile',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان ورقم الحوض ورقم القطعة.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'قم بإرفاق المستندات المطلوبة بشكل إلزامي.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الحوض', 'description' => 'رقم الحوض في المخطط الهيكلي', 'is_required' => true],
                            ['title' => 'رقم القطعة', 'description' => 'رقم القطعة الأرضية', 'is_required' => true],
                            ['title' => 'المستندات المطلوبة', 'description' => 'يجب إرفاق المستندات المطلوبة', 'is_required' => true],
                        ],
                        'documents' => [
                            ['name' => 'المستندات المطلوبة', 'description' => 'يجب إرفاق جميع المستندات المطلوبة لفتح الملف', 'is_required' => true],
                        ],
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'ترخيص بناء',
                        'summary' => 'طلب الحصول على ترخيص بناء.',
                        'description' => 'تتيح هذه الخدمة لمالكي الأراضي أو ممثليهم طلب الحصول على ترخيص بناء. يتم إدخال بيانات المالك والموقع من خلال رقم الحوض ورقم القطعة، مع إرفاق المخططات الهندسية والمستندات المطلوبة بشكل إلزامي.',
                        'portal_url' => 'https://i.palexpand.ps/portal/buildingLicense',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان ورقم الحوض ورقم القطعة.'],
                            ['title' => 'إرفاق المخططات', 'description' => 'قم بإرفاق المخططات الهندسية والمستندات المطلوبة بشكل إلزامي.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الحوض', 'description' => 'رقم الحوض في المخطط الهيكلي', 'is_required' => true],
                            ['title' => 'رقم القطعة', 'description' => 'رقم القطعة الأرضية', 'is_required' => true],
                            ['title' => 'المخططات الهندسية', 'description' => 'يجب إرفاق المخططات الهندسية المعتمدة', 'is_required' => true],
                        ],
                        'documents' => [
                            ['name' => 'المخططات الهندسية', 'description' => 'المخططات الهندسية المعتمدة', 'is_required' => true],
                            ['name' => 'المستندات المطلوبة', 'description' => 'جميع المستندات المطلوبة لترخيص البناء', 'is_required' => true],
                        ],
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'مخطط موقع',
                        'summary' => 'طلب الحصول على مخطط موقع.',
                        'description' => 'تتيح هذه الخدمة لمالكي الأراضي أو ممثليهم طلب الحصول على مخطط موقع. يتم إدخال بيانات المالك مع تحديد نوع الملكية (مالك أو ممثل عنه). إذا كان المالك ممثلاً، يتم إدخال بيانات المالك الأصلي.',
                        'portal_url' => 'https://i.palexpand.ps/portal/sitePlan',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد نوع الملكية', 'description' => 'حدد ما إذا كنت المالك الأصلي أو ممثلاً عنه.'],
                            ['title' => 'بيانات المالك البديل', 'description' => 'إذا كنت ممثلاً، أدخل بيانات المالك الأصلي.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق المستندات الداعمة.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'نوع الملكية', 'description' => 'مالك أو ممثل عنه', 'is_required' => true],
                        ],
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'مصادقة مخططات هندسية',
                        'summary' => 'طلب مصادقة مخططات هندسية.',
                        'description' => 'تتيح هذه الخدمة لملكي الأراضي أو ممثليهم طلب مصادقة المخططات الهندسية. يتم إدخال بيانات المالك والموقع من خلال رقم الحوض ورقم القطعة، مع إرفاق المخططات المطلوب مصادقتها.',
                        'portal_url' => 'https://i.palexpand.ps/portal/engineeringValidation',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان ورقم الحوض ورقم القطعة.'],
                            ['title' => 'إرفاق المخططات', 'description' => 'أرفق المخططات الهندسية المطلوب مصادقتها.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الحوض', 'description' => 'رقم الحوض في المخطط الهيكلي', 'is_required' => true],
                            ['title' => 'رقم القطعة', 'description' => 'رقم القطعة الأرضية', 'is_required' => true],
                        ],
                        'sort_order' => 5,
                    ],
                    [
                        'name' => 'طلب استرداد تامين رخصة بناء',
                        'summary' => 'طلب استرداد مبلغ التامين المدفوع مع ترخيص البناء.',
                        'description' => 'تتيح هذه الخدمة لملكي الأراضي أو ممثليهم طلب استرداد مبلغ التامين المدفوع عند التقدم لترخيص البناء. يتم إدخال بيانات المالك والموقع ورقم الأصل ورقم الرخصة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/retrieveLic',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'تحديد الموقع', 'description' => 'اختر المنطقة وأدخل العنوان.'],
                            ['title' => 'إدخال بيانات الرخصة', 'description' => 'أدخل رقم الأصل ورقم الرخصة.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق المستندات الداعمة.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الأصل', 'description' => 'رقم أصل التامين', 'is_required' => true],
                            ['title' => 'رقم الرخصة', 'description' => 'رقم ترخيص البناء', 'is_required' => true],
                        ],
                        'sort_order' => 6,
                    ],
                    [
                        'name' => 'نقل ملكية رخصة بناء',
                        'summary' => 'طلب نقل ملكية ترخيص بناء من شخص لآخر.',
                        'description' => 'تتيح هذه الخدمة نقل ملكية ترخيص بناء من مالك إلى مالك جديد. يتم إدخال بيانات المالك الحالي والمالك الجديد مع جميع بياناتهم الشخصية ومواقع عقاراتهم.',
                        'portal_url' => 'https://i.palexpand.ps/portal/ownershipTransfer',
                        'steps' => [
                            ['title' => 'بيانات المالك الحالي', 'description' => 'أدخل الاسم ورقم الهوية والجوال والمنطقة والعنوان ورقم الحوض ورقم القطعة.'],
                            ['title' => 'بيانات المالك الجديد', 'description' => 'أدخل الاسم ورقم الهوية والجوال والمنطقة والعنوان ورقم الحوض ورقم القطعة للمالك الجديد.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق مستندات نقل الملكية.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'بيانات المالك الحالي', 'description' => 'جميع بياناته الشخصية وموقع عقاره', 'is_required' => true],
                            ['title' => 'بيانات المالك الجديد', 'description' => 'جميع بياناته الشخصية وموقع عقاره', 'is_required' => true],
                        ],
                        'sort_order' => 7,
                    ],
                    [
                        'name' => 'طلب الحصول على شهادة تصرف',
                        'summary' => 'طلب الحصول على شهادة تصرف отноساً للكهرباء والمياه.',
                        'description' => 'تتيح هذه الخدمة للمواطن طلب الحصول على شهادة تصرف تثبت عدم وجود التزامات مالية تجاه خدمات الكهرباء والمياه. يتم إدخال بيانات مقدم الطلب ونوعي الاشتراكات ورقم ترخيص البناء.',
                        'portal_url' => 'https://i.palexpand.ps/portal/ticket?ticket=MQ==',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم (مطلوب) ورقم الهوية (مطلوب) ورقم الهاتف.'],
                            ['title' => 'بيانات الاشتراكات', 'description' => 'حدد نوع اشتراك الكهرباء ونوع اشتراك المياه.'],
                            ['title' => 'رقم ترخيص البناء', 'description' => 'أدخل رقم ترخيص البناء إن وجد.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'اسم مقدم الطلب', 'description' => 'الاسم الكامل', 'is_required' => true],
                            ['title' => 'رقم الهوية', 'description' => 'رقم الهوية الوطنية', 'is_required' => true],
                            ['title' => 'نوع اشتراك الكهرباء', 'description' => 'منزلي، تجاري، صناعي، زراعي، مدارس، مساجد', 'is_required' => false],
                            ['title' => 'نوع اشتراك المياه', 'description' => 'دفع مسبق، صيني، منزلي', 'is_required' => false],
                        ],
                        'sort_order' => 8,
                    ],
                    [
                        'name' => 'طلب الحصول على شهادة تصرف عقاري',
                        'summary' => 'طلب شهادة تصرف تثبت أهلية العقار للبيع أو التصرف فيه.',
                        'description' => 'تتيح هذه الخدمة لمالكي العقارات طلب الحصول على شهادة تصرف عقارية تثبت أهلية العقار للبيع أو التصرف فيه. تشمل الشهادة بيانات المالك والعقار والمجاورين والاشتراكات والرخص.',
                        'portal_url' => 'https://i.palexpand.ps/portal/ticket?ticket=Mg==',
                        'steps' => [
                            ['title' => 'بيانات مقدم الطلب', 'description' => 'أدخل الاسم (مطلوب) ورقم الهوية (مطلوب) والعنوان (مطلوب) ورقم الهاتف (مطلوب).'],
                            ['title' => 'بيانات العقار', 'description' => 'أدخل الموقع (مطلوب) ورقم القطعة (مطلوب) ورقم الحوض (مطلوب).'],
                            ['title' => 'المجاورين', 'description' => 'أدخل أسماء المجاورين من الجهات الأربع إن أمكن.'],
                            ['title' => 'ملكية العقار', 'description' => 'حدد نوع الملكية (مالك/مستأجر) وكيف حصلت على الملكية.'],
                            ['title' => 'الاشتراكات والرخص', 'description' => 'أدخل بيانات اشتراكات الكهرباء والمياه وترخيص البناء.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'اسم مقدم الطلب', 'description' => 'الاسم الكامل', 'is_required' => true],
                            ['title' => 'رقم الهوية', 'description' => 'رقم الهوية الوطنية', 'is_required' => true],
                            ['title' => 'العنوان', 'description' => 'العنوان الكامل', 'is_required' => true],
                            ['title' => 'رقم الهاتف', 'description' => 'رقم التواصل', 'is_required' => true],
                            ['title' => 'الموقع', 'description' => 'موقع العقار', 'is_required' => true],
                            ['title' => 'رقم القطعة', 'description' => 'رقم القطعة الأرضية', 'is_required' => true],
                            ['title' => 'رقم الحوض', 'description' => 'رقم الحوض في المخطط', 'is_required' => true],
                            ['title' => 'ملكية العقار', 'description' => 'مالك أو مستأجر', 'is_required' => false],
                            ['title' => 'اشتراك الكهرباء', 'description' => 'هل توجد اشتراك كهرباء', 'is_required' => false],
                            ['title' => 'اشتراك المياه', 'description' => 'هل توجد اشتراك مياه', 'is_required' => false],
                            ['title' => 'رخصة البناء', 'description' => 'هل توجد رخصة بناء', 'is_required' => false],
                        ],
                        'sort_order' => 9,
                    ],
                ],
            ],

            // ─── Category 5: General Complaints ────────────────────
            [
                'name' => 'طلبات وشكاوى عامة',
                'description' => null,
                'icon' => 'alert-circle',
                'status' => 'active',
                'is_public' => true,
                'sort_order' => 5,
                'services' => [
                    [
                        'name' => 'شكوى عامة',
                        'summary' => 'تقديم شكوى عامة حول أي خدمة أو مشكلة بلدية.',
                        'description' => 'تتيح هذه الخدمة للمواطن تقديم شكوى عامة حول أي مشكلة تتعلق بالخدمات البلدية. يمكن الإبلاغ عن مشكلة في النظافة، البنية التحتية، الخدمات العامة، أو أي مخالفة بلدية أخرى. يتم إدخال البيانات个人ية ووصف المشكلة.',
                        'portal_url' => 'https://i.palexpand.ps/portal/publicComplaint',
                        'steps' => [
                            ['title' => 'إدخال بيانات مقدم الشكوى', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'وصف المشكلة', 'description' => 'اكتب وصفاً واضحاً للمشكلة أو الشكوى.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق صوراً أو مستندات تدعم الشكوى.'],
                            ['title' => 'تقديم الشكوى', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                            ['title' => 'وصف المشكلة', 'description' => 'وصف واضح للمشكلة', 'is_required' => false],
                        ],
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'طلب براءة ذمة داخلية',
                        'summary' => 'طلب الحصول على براءة ذمة داخلية.',
                        'description' => null,
                        'portal_url' => 'https://i.palexpand.ps/portal/innerQuittance',
                        'steps' => null,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'شكاوي النفايات',
                        'summary' => 'تقديم شكوى تتعلق بنفايات أو نظافة.',
                        'description' => null,
                        'portal_url' => 'https://i.palexpand.ps/portal/wasteComplaint',
                        'steps' => null,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'شكوى طريق',
                        'summary' => 'تقديم شكوى عن حالة طريق (طلبات متفرقة).',
                        'description' => 'تتيح هذه الخدمة للمواطن تقديم شكوى حول حالة طريق أو شارع من خلال قسم الطلبات المتفرقة. يمكن الإبلاغ عن تلف في الإسفلت، مشاكل في التصريف، أو أي عيوب أخرى.',
                        'portal_url' => 'https://i.palexpand.ps/portal/streetComplaint',
                        'steps' => [
                            ['title' => 'اختيار نوع الطلب', 'description' => 'حدد نوع الشكوى من القائمة.'],
                            ['title' => 'إدخال بيانات مقدم الطلب', 'description' => 'أدخل الاسم ورقم الهوية ورقم الجوال.'],
                            ['title' => 'وصف الشكوى', 'description' => 'اكتب وصفاً واضحاً لحالة الطريق.'],
                            ['title' => 'إرفاق المستندات', 'description' => 'أرفق صوراً للحالة إن أمكن.'],
                            ['title' => 'تقديم الطلب', 'description' => 'راجع البيانات واضغط إرسال.'],
                        ],
                        'requirements' => [
                            ['title' => 'رقم الهوية', 'description' => 'يجب أن يكون 9 أرقام', 'is_required' => true],
                            ['title' => 'رقم الجوال', 'description' => 'يجب أن يكون 10 أرقام', 'is_required' => true],
                        ],
                        'sort_order' => 4,
                    ],
                ],
            ],

            // ─── Category 6: Miscellaneous ─────────────────────────
            [
                'name' => 'خدمات متفرقة',
                'description' => null,
                'icon' => 'grid',
                'status' => 'active',
                'is_public' => true,
                'sort_order' => 6,
                'services' => [],
            ],
        ];
    }
}
