<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Authentication\Models\User;
use App\Domains\PublicFacilities\Enums\FacilityStatus;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Database\Seeder;

final class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $createdBy = User::query()->value('id');
        $defaultAddress = 'إذنا، محافظة الخليل، فلسطين';

        $facilities = [
            ['name' => 'بلدية إذنا', 'slug' => 'idhna-municipality', 'category' => 'governmental', 'summary' => 'الجهة المحلية المسؤولة عن إدارة وتطوير الخدمات البلدية والبنية التحتية والمرافق العامة في بلدة إذنا، والعمل على تحسين جودة الخدمات المقدمة للسكان.', 'description' => 'الجهة المحلية المسؤولة عن إدارة وتطوير الخدمات البلدية والبنية التحتية والمرافق العامة في بلدة إذنا، والعمل على تحسين جودة الخدمات المقدمة للسكان.', 'address' => $defaultAddress, 'is_featured' => true],
            ['name' => 'مكتب بريد إذنا', 'slug' => 'idhna-post-office', 'category' => 'governmental', 'summary' => 'مرفق خدمي يقدم الخدمات البريدية لسكان بلدة إذنا والمناطق المحيطة.', 'description' => 'مرفق خدمي يقدم الخدمات البريدية لسكان بلدة إذنا والمناطق المحيطة.', 'address' => $defaultAddress],
            ['name' => 'عيادة إذنا الحكومية', 'slug' => 'idhna-government-clinic', 'category' => 'health', 'summary' => 'مرفق صحي حكومي يقدم خدمات الرعاية الصحية الأساسية لسكان بلدة إذنا والمناطق المحيطة.', 'description' => 'مرفق صحي حكومي يقدم خدمات الرعاية الصحية الأساسية لسكان بلدة إذنا والمناطق المحيطة.', 'address' => $defaultAddress],
            ['name' => 'الهلال الأحمر الفلسطيني – إذنا', 'slug' => 'palestine-red-crescent-idhna', 'category' => 'health', 'summary' => 'مرفق يقدم خدمات إنسانية وإغاثية وصحية للمجتمع المحلي.', 'description' => 'مرفق يقدم خدمات إنسانية وإغاثية وصحية للمجتمع المحلي.', 'address' => $defaultAddress],
            ['name' => 'الإغاثة الطبية الفلسطينية – إذنا', 'slug' => 'palestinian-medical-relief-idhna', 'category' => 'health', 'summary' => 'مؤسسة تقدم خدمات الرعاية الصحية والدعم الطبي والإغاثي للمجتمع المحلي.', 'description' => 'مؤسسة تقدم خدمات الرعاية الصحية والدعم الطبي والإغاثي للمجتمع المحلي.', 'address' => $defaultAddress],
            ['name' => 'مدرسة جمال علي طميزة للبنين', 'slug' => 'jamal-ali-tamiza-boys-school', 'category' => 'education', 'summary' => 'مدرسة تعليمية للبنين ضمن منظومة المدارس في بلدة إذنا.', 'description' => 'مدرسة تعليمية للبنين ضمن منظومة المدارس في بلدة إذنا.', 'address' => $defaultAddress],
            ['name' => 'مدرسة زكي خلاوي المختلطة', 'slug' => 'zaki-khalawi-mixed-school', 'category' => 'education', 'summary' => 'مدرسة تعليمية مختلطة تخدم الطلبة في بلدة إذنا.', 'description' => 'مدرسة تعليمية مختلطة تخدم الطلبة في بلدة إذنا.', 'address' => $defaultAddress],
            ['name' => 'مدرسة بير البلد للبنين', 'slug' => 'bir-al-balad-boys-school', 'category' => 'education', 'summary' => 'مدرسة تعليمية للبنين تخدم أبناء البلدة والمناطق المحيطة.', 'description' => 'مدرسة تعليمية للبنين تخدم أبناء البلدة والمناطق المحيطة.', 'address' => $defaultAddress],
            ['name' => 'مدرسة الأنصار الثانوية للبنات', 'slug' => 'al-ansar-secondary-girls-school', 'category' => 'education', 'summary' => 'مدرسة ثانوية للبنات ضمن المؤسسات التعليمية في بلدة إذنا.', 'description' => 'مدرسة ثانوية للبنات ضمن المؤسسات التعليمية في بلدة إذنا.', 'address' => $defaultAddress],
            ['name' => 'مدرسة ذكور إذنا الثانوية', 'slug' => 'idhna-secondary-boys-school', 'category' => 'education', 'summary' => 'مدرسة ثانوية للبنين تقدم التعليم الثانوي لأبناء بلدة إذنا.', 'description' => 'مدرسة ثانوية للبنين تقدم التعليم الثانوي لأبناء بلدة إذنا.', 'address' => $defaultAddress],
            ['name' => 'مدرسة ذكور إذنا الأساسية', 'slug' => 'idhna-basic-boys-school', 'category' => 'education', 'summary' => 'مدرسة أساسية للبنين تخدم الطلبة في بلدة إذنا.', 'description' => 'مدرسة أساسية للبنين تخدم الطلبة في بلدة إذنا.', 'address' => $defaultAddress],
            ['name' => 'مدرسة حفصة بنت عمر', 'slug' => 'hafsa-bint-omar-school', 'category' => 'education', 'summary' => 'مؤسسة تعليمية تخدم الطالبات والطلبة ضمن منظومة التعليم في البلدة.', 'description' => 'مؤسسة تعليمية تخدم الطالبات والطلبة ضمن منظومة التعليم في البلدة.', 'address' => $defaultAddress],
            ['name' => 'نادي شباب إذنا الرياضي', 'slug' => 'idhna-youth-sports-club', 'category' => 'sports', 'summary' => 'مؤسسة رياضية وشبابية توفر مساحة لممارسة الأنشطة الرياضية وتعزيز مشاركة الشباب في المجتمع المحلي.', 'description' => 'مؤسسة رياضية وشبابية توفر مساحة لممارسة الأنشطة الرياضية وتعزيز مشاركة الشباب في المجتمع المحلي.', 'address' => $defaultAddress],
            ['name' => 'نادي سجى لألعاب القوى والأثقال', 'slug' => 'saja-athletics-and-weightlifting-club', 'category' => 'sports', 'summary' => 'مرفق رياضي يهتم بألعاب القوى وتمارين القوة والأثقال والأنشطة الرياضية.', 'description' => 'مرفق رياضي يهتم بألعاب القوى وتمارين القوة والأثقال والأنشطة الرياضية.', 'address' => $defaultAddress],
            ['name' => 'ملتقى شباب إذنا', 'slug' => 'idhna-youth-forum', 'category' => 'youth', 'summary' => 'مساحة شبابية تهتم بالأنشطة والمبادرات المجتمعية وتنمية قدرات الشباب.', 'description' => 'مساحة شبابية تهتم بالأنشطة والمبادرات المجتمعية وتنمية قدرات الشباب.', 'address' => $defaultAddress],
            ['name' => 'جمعية إذنا الخيرية', 'slug' => 'idhna-charitable-society', 'category' => 'social', 'summary' => 'جمعية مجتمعية تهدف إلى تقديم الخدمات والمساعدات والمبادرات الاجتماعية لأهالي البلدة.', 'description' => 'جمعية مجتمعية تهدف إلى تقديم الخدمات والمساعدات والمبادرات الاجتماعية لأهالي البلدة.', 'address' => $defaultAddress],
            ['name' => 'جمعية إذنا الخيرية لرعاية الأيتام', 'slug' => 'idhna-orphans-care-charity', 'category' => 'charity', 'summary' => 'جمعية تهتم برعاية الأيتام وتقديم الدعم والخدمات الاجتماعية للفئات المحتاجة.', 'description' => 'جمعية تهتم برعاية الأيتام وتقديم الدعم والخدمات الاجتماعية للفئات المحتاجة.', 'address' => $defaultAddress],
            ['name' => 'جمعية تنمية الشباب', 'slug' => 'youth-development-society', 'category' => 'youth', 'summary' => 'مؤسسة تهتم بتنمية الشباب ودعم المبادرات والبرامج المجتمعية.', 'description' => 'مؤسسة تهتم بتنمية الشباب ودعم المبادرات والبرامج المجتمعية.', 'address' => $defaultAddress],
            ['name' => 'جمعية تنمية المرأة الريفية – النادي النسوي', 'slug' => 'rural-women-development-womens-club', 'category' => 'women', 'summary' => 'مؤسسة مجتمعية تهتم بتمكين المرأة الريفية وتنفيذ الأنشطة والبرامج التنموية.', 'description' => 'مؤسسة مجتمعية تهتم بتمكين المرأة الريفية وتنفيذ الأنشطة والبرامج التنموية.', 'address' => $defaultAddress],
            ['name' => 'جمعية الحسين للثقافة والفنون والتراث', 'slug' => 'al-hussein-culture-arts-heritage-society', 'category' => 'cultural', 'summary' => 'مؤسسة تهتم بالثقافة والفنون والمحافظة على التراث المحلي وتعزيز المشاركة الثقافية.', 'description' => 'مؤسسة تهتم بالثقافة والفنون والمحافظة على التراث المحلي وتعزيز المشاركة الثقافية.', 'address' => $defaultAddress],
            ['name' => 'منتزه إذنا', 'slug' => 'idhna-park', 'category' => 'entertainment', 'summary' => 'مساحة عامة وترفيهية يمكن أن يستفيد منها سكان البلدة للعائلات والأنشطة المجتمعية.', 'description' => 'مساحة عامة وترفيهية يمكن أن يستفيد منها سكان البلدة للعائلات والأنشطة المجتمعية.', 'address' => $defaultAddress],
            ['name' => 'المسجد العمري', 'slug' => 'al-omari-mosque', 'category' => 'religious', 'summary' => 'أحد المعالم الدينية والتراثية في بلدة إذنا.', 'description' => 'أحد المعالم الدينية والتراثية في بلدة إذنا.', 'address' => $defaultAddress],
            ['name' => 'مسجد سعد بن أبي وقاص', 'slug' => 'saad-ibn-abi-waqqas-mosque', 'category' => 'religious', 'summary' => 'مسجد يخدم سكان المنطقة ويشكل أحد المعالم الدينية في البلدة.', 'description' => 'مسجد يخدم سكان المنطقة ويشكل أحد المعالم الدينية في البلدة.', 'address' => $defaultAddress],
        ];

        foreach ($facilities as $order => $data) {
            $categoryId = FacilityCategory::query()
                ->where('slug', $data['category'])
                ->value('id');

            Facility::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'facility_category_id' => $categoryId,
                    'name' => $data['name'],
                    'summary' => $data['summary'],
                    'description' => $data['description'],
                    'cover_image_path' => null,
                    'gallery' => null,
                    'phone' => null,
                    'email' => null,
                    'address' => $data['address'],
                    'working_hours' => null,
                    'services' => null,
                    'features' => null,
                    'rules' => null,
                    'status' => FacilityStatus::Published,
                    'is_public' => true,
                    'is_featured' => $data['is_featured'] ?? false,
                    'display_order' => $order + 1,
                    'created_by' => $createdBy,
                    'updated_by' => $createdBy,
                ],
            );
        }
    }
}
