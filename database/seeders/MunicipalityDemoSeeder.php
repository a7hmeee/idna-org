<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\Municipality\Models\MunicipalityContact;
use App\Domains\Municipality\Models\MunicipalityCustomField;
use App\Domains\Municipality\Models\MunicipalityExternalPlatform;
use App\Domains\Municipality\Models\MunicipalitySocialPlatform;
use App\Domains\SharedKernel\Models\BusinessHour;
use App\Domains\SharedKernel\Models\EmergencyContact;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Seeder;

final class MunicipalityDemoSeeder extends Seeder
{
    private const DEMO_CODE = 'IDNA-001';

    public function run(): void
    {
        $municipality = Municipality::firstOrCreate(
            ['municipality_code' => self::DEMO_CODE],
            [
                'name_ar' => 'بلدية إذنا',
                'name_en' => 'Idna Municipality',
                'short_description' => 'بلدية إذنا هي الجهة الرسمية المعنية بتقديم الخدمات البلدية للمواطنين في مدينة إذنا.',
                'full_description' => 'بلدية إذنا هي الجهة الرسمية المعنية بتقديم الخدمات البلدية للمواطنين في مدينة إذنا. تأسست البلدية لخدمة المجتمع المحلي وتحسين جودة الحياة وتقديم أفضل الخدمات الإلكترونية للمواطنين.',
                'vision' => 'بلدية رائدة في تقديم الخدمات البلدية الإلكترونية وتحسين جودة حياة المواطن.',
                'mission' => 'تقديم خدمات بلدية متميزة وفق أعلى المعايير的专业ية والشفافية لخدمة مجتمع إذنا.',
                'objectives' => [
                    'تحسين جودة الخدمات البلدية',
                    'تعزيز المشاركة المجتمعية',
                    'تطوير البنية التحتية',
                    'حماية البيئة والنظافة العامة',
                ],
                'foundation_date' => '1994-01-01',
                'population' => 45000,
                'area' => 85.50,
                'latitude' => 31.6316,
                'longitude' => 35.0058,
            ]
        );

        $this->seedContacts($municipality);
        $this->seedSocialPlatforms($municipality);
        $this->seedExternalPlatforms($municipality);
        $this->seedCustomFields($municipality);
        $this->seedMedia($municipality);
        $this->seedBusinessHours($municipality);
        $this->seedEmergencyContacts($municipality);
    }

    private function seedContacts(Municipality $municipality): void
    {
        $contacts = [
            ['type' => 'phone', 'label' => 'الهاتف الرئيسي', 'value' => '+970-22-123456', 'icon' => 'phone', 'display_order' => 1],
            ['type' => 'phone', 'label' => 'خدمة العملاء', 'value' => '+970-22-123457', 'icon' => 'headset', 'display_order' => 2],
            ['type' => 'email', 'label' => 'الاستفسارات العامة', 'value' => 'info@idhna.ps', 'icon' => 'envelope', 'display_order' => 3],
            ['type' => 'email', 'label' => 'الدعم الفني', 'value' => 'support@idhna.ps', 'icon' => 'life-ring', 'display_order' => 4],
            ['type' => 'fax', 'label' => 'الفاكس', 'value' => '+970-22-123458', 'icon' => 'fax', 'display_order' => 5],
            ['type' => 'address', 'label' => 'المقر الرئيسي', 'value' => 'إذنا - شارع البلدية الرئيسي - مبنى البلدية', 'icon' => 'map-marker-alt', 'display_order' => 6],
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

    private function seedSocialPlatforms(Municipality $municipality): void
    {
        $platforms = [
            ['name' => 'فيسبوك', 'slug' => 'facebook', 'icon' => 'facebook', 'url' => 'https://facebook.com/idhna.municipality', 'color' => '#1877F2', 'display_order' => 1],
            ['name' => 'تويتر / X', 'slug' => 'x', 'icon' => 'x-twitter', 'url' => 'https://x.com/idhna_muni', 'color' => '#000000', 'display_order' => 2],
            ['name' => 'انستغرام', 'slug' => 'instagram', 'icon' => 'instagram', 'url' => 'https://instagram.com/idhna_municipality', 'color' => '#E4405F', 'display_order' => 3],
            ['name' => 'واتساب', 'slug' => 'whatsapp', 'icon' => 'whatsapp', 'url' => 'https://wa.me/97022123456', 'color' => '#25D366', 'display_order' => 4],
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

    private function seedExternalPlatforms(Municipality $municipality): void
    {
        $platforms = [
            ['name' => 'بوابة الخدمات الإلكترونية', 'description' => 'بوابة الخدمات الإلكترونية لبلدية إذنا', 'icon' => 'globe', 'url' => 'https://i.palexpand.ps/portal', 'category' => 'government', 'color' => '#2E7D32', 'open_in_new_tab' => true, 'is_featured' => true, 'display_order' => 1],
            ['name' => 'خرائط جوجل', 'description' => 'الموقع على خرائط جوجل', 'icon' => 'map', 'url' => 'https://maps.google.com/?q=31.6316,35.0058', 'category' => 'navigation', 'color' => '#34A853', 'open_in_new_tab' => true, 'is_featured' => false, 'display_order' => 2],
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

    private function seedCustomFields(Municipality $municipality): void
    {
        $fields = [
            ['key' => 'mayor_name', 'value' => 'أحمد محمد عودة', 'type' => 'text', 'display_order' => 1],
            ['key' => 'population_density', 'value' => '526', 'type' => 'number', 'display_order' => 2],
            ['key' => 'number_of_districts', 'value' => '12', 'type' => 'number', 'display_order' => 3],
            ['key' => 'established_as_municipality', 'value' => '1994', 'type' => 'text', 'display_order' => 4],
            ['key' => 'website_url', 'value' => 'https://idhna.ps', 'type' => 'url', 'display_order' => 5],
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

    private function seedMedia(Municipality $municipality): void
    {
        $media = [
            ['collection' => 'logo', 'title' => 'شعار البلدية', 'disk' => 'public', 'path' => 'idhna/logo.png', 'display_order' => 1],
            ['collection' => 'banner', 'title' => 'البانر الرئيسي', 'disk' => 'public', 'path' => 'idhna/banner.jpg', 'display_order' => 1],
            ['collection' => 'banner', 'title' => 'البانر الثانوي', 'disk' => 'public', 'path' => 'idhna/secondary-banner.jpg', 'display_order' => 2],
            ['collection' => 'images', 'title' => 'صورة البلدية', 'disk' => 'public', 'path' => 'municipality/media/images/4b26a37b-662f-4abb-abff-a57298d5a71a.jpeg', 'mime_type' => 'image/jpeg', 'display_order' => 1],
        ];

        foreach ($media as $data) {
            Media::updateOrCreate(
                [
                    'mediable_id' => $municipality->id,
                    'mediable_type' => Municipality::class,
                    'collection' => $data['collection'],
                    'title' => $data['title'],
                ],
                $data + ['mediable_id' => $municipality->id, 'mediable_type' => Municipality::class, 'is_active' => true, 'mime_type' => 'image/png', 'size' => 1024],
            );
        }
    }

    private function seedBusinessHours(Municipality $municipality): void
    {
        $hours = [
            ['day' => 'الأحد', 'opening_time' => '08:00', 'closing_time' => '16:00', 'is_closed' => false, 'display_order' => 1],
            ['day' => 'الإثنين', 'opening_time' => '08:00', 'closing_time' => '16:00', 'is_closed' => false, 'display_order' => 2],
            ['day' => 'الثلاثاء', 'opening_time' => '08:00', 'closing_time' => '16:00', 'is_closed' => false, 'display_order' => 3],
            ['day' => 'الأربعاء', 'opening_time' => '08:00', 'closing_time' => '16:00', 'is_closed' => false, 'display_order' => 4],
            ['day' => 'الخميس', 'opening_time' => '08:00', 'closing_time' => '14:00', 'is_closed' => false, 'display_order' => 5],
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
            ['name' => 'شرطة إذنا', 'department' => 'الأمن العام', 'phone' => '100', 'icon' => 'shield', 'display_order' => 1],
            ['name' => 'الدفاع المدني', 'department' => 'الإطفاء والإنقاذ', 'phone' => '101', 'icon' => 'fire-extinguisher', 'display_order' => 2],
            ['name' => 'الإسعاف الطبي', 'department' => 'الخدمات الصحية', 'phone' => '102', 'icon' => 'ambulance', 'display_order' => 3],
            ['name' => 'طوارئ البلدية', 'department' => 'عمليات البلدية', 'phone' => '106', 'icon' => 'building', 'display_order' => 4],
            ['name' => 'أمن الطرق', 'department' => 'المرور', 'phone' => '108', 'icon' => 'car', 'display_order' => 5],
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
}
