<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceSearchTerm;
use Illuminate\Database\Seeder;

final class ChatbotSearchTermSeeder extends Seeder
{
    public function run(): void
    {
        $normalizer = app(ArabicTextNormalizer::class);

        $services = ElectronicService::where('is_public', true)
            ->where('status', 'active')
            ->get()
            ->keyBy('name');

        $termsByService = [];

        // ============================================================
        // رخص البناء — Building Permit
        // ============================================================

        $buildingPermit = $services->get('طلب رخصة بناء جديد');
        if ($buildingPermit) {
            $termsByService[$buildingPermit->id] = [
                // Aliases
                ['term' => 'رخصة بناء', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'تصريح بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
                ['term' => 'ترخيص بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 8],
                ['term' => 'رخصة مباني', 'type' => 'alias', 'weight' => 20, 'priority' => 7],
                // Keywords
                ['term' => 'بناء', 'type' => 'keyword', 'weight' => 20, 'priority' => 10],
                ['term' => 'بيت', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
                ['term' => 'دار', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
                ['term' => 'طابق', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'عمارة', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'إنشاء', 'type' => 'keyword', 'weight' => 12, 'priority' => 7],
                ['term' => 'إضافة', 'type' => 'keyword', 'weight' => 12, 'priority' => 7],
                ['term' => 'توسعة', 'type' => 'keyword', 'weight' => 12, 'priority' => 7],
                ['term' => 'منزل', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'سكن', 'type' => 'keyword', 'weight' => 12, 'priority' => 7],
                ['term' => 'تشطيب', 'type' => 'keyword', 'weight' => 10, 'priority' => 6],
                // Citizen expressions
                ['term' => 'بدي أبني بيت', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
                ['term' => 'بدي ابني بيت', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
                ['term' => 'بدي أرخص داري', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
                ['term' => 'بدي ارخص داري', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
                ['term' => 'بدي أضيف طابق', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
                ['term' => 'بدي اضيف طابق', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
                ['term' => 'بدي أعمل توسعة', 'type' => 'citizen_expression', 'weight' => 24, 'priority' => 9],
                ['term' => 'بدي اعمل توسعة', 'type' => 'citizen_expression', 'weight' => 24, 'priority' => 9],
                ['term' => 'بدي تصريح للبيت', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي أشتري أرض وأبني', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 8],
                ['term' => 'بدي اشري ارض وابني', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 8],
                ['term' => 'بدي أخذ رخصة لبيتي', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي اخذ رخصة لبيتي', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'كيف أبني على أرضي', 'type' => 'citizen_expression', 'weight' => 18, 'priority' => 7],
                ['term' => 'بدي أرخص أرضي', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 8],
            ];
        }

        // ============================================================
        // تجديد رخصة بناء — Building Permit Renewal
        // ============================================================

        $renewal = $services->get('طلب تجديد رخصة بناء');
        if ($renewal) {
            $termsByService[$renewal->id] = [
                ['term' => 'تجديد رخصة بناء', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'تجديد تصريح بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
                ['term' => 'تجديد رخصة', 'type' => 'alias', 'weight' => 20, 'priority' => 8],
                ['term' => 'تجديد', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'انتهت رخصتي', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي أجدد الرخصة', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي اجدد الرخصة', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'رخصة بناء قديمة', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 8],
            ];
        }

        // ============================================================
        // تعديل رخصة بناء — Building Permit Modification
        // ============================================================

        $modification = $services->get('طلب تعديل رخصة بناء');
        if ($modification) {
            $termsByService[$modification->id] = [
                ['term' => 'تعديل رخصة بناء', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'تغيير رخصة بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
                ['term' => 'تعديل', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'تغيير', 'type' => 'keyword', 'weight' => 12, 'priority' => 7],
                ['term' => 'تعديل المخططات', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي أغير بالرخصة', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي اغير بالرخصة', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
            ];
        }

        // ============================================================
        // شهادة إتمام بناء — Building Completion Certificate
        // ============================================================

        $completion = $services->get('طلب شهادة إتمام بناء');
        if ($completion) {
            $termsByService[$completion->id] = [
                ['term' => 'شهادة إتمام بناء', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'شهادة اتمام بناء', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'إتمام بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
                ['term' => 'اتمام بناء', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
                ['term' => 'شهادة إتمام', 'type' => 'alias', 'weight' => 20, 'priority' => 8],
                ['term' => 'شهادة اتمام', 'type' => 'alias', 'weight' => 20, 'priority' => 8],
                ['term' => 'إتمام', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'اتمام', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'خلصت بناء', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي شهادة إتمام', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي شهادة اتمام', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'خلصت بيتي', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 8],
                ['term' => 'بناءي انتهى', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 8],
            ];
        }

        // ============================================================
        // هدم مبنى — Building Demolition
        // ============================================================

        $demolition = $services->get('طلب هدم مبنى');
        if ($demolition) {
            $termsByService[$demolition->id] = [
                ['term' => 'تصريح هدم', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'هدم مبنى', 'type' => 'alias', 'weight' => 28, 'priority' => 9],
                ['term' => 'هدم', 'type' => 'keyword', 'weight' => 20, 'priority' => 10],
                ['term' => 'تصريح هدم', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
                ['term' => 'بدي أهدم', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
                ['term' => 'بدي اهدم', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
                ['term' => 'بدي أهدم داري', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
                ['term' => 'بدي اهدم داري', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
                ['term' => 'بدي أهدم بيتي', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
                ['term' => 'بدي اهدم بيتي', 'type' => 'citizen_expression', 'weight' => 26, 'priority' => 9],
            ];
        }

        // ============================================================
        // ترخيص محل تجاري — Commercial Shop Licence
        // ============================================================

        $shopLicence = $services->get('طلب ترخيص محل تجاري');
        if ($shopLicence) {
            $termsByService[$shopLicence->id] = [
                ['term' => 'ترخيص محل', 'type' => 'alias', 'weight' => 30, 'priority' => 10],
                ['term' => 'رخصة محل', 'type' => 'alias', 'weight' => 28, 'priority' => 9],
                ['term' => 'رخصة مهن', 'type' => 'alias', 'weight' => 25, 'priority' => 9],
                ['term' => 'تصريح محل', 'type' => 'alias', 'weight' => 25, 'priority' => 8],
                ['term' => 'محل', 'type' => 'keyword', 'weight' => 20, 'priority' => 10],
                ['term' => 'متجر', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
                ['term' => 'دكان', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'نشاط تجاري', 'type' => 'keyword', 'weight' => 18, 'priority' => 9],
                ['term' => 'تجاري', 'type' => 'keyword', 'weight' => 15, 'priority' => 8],
                ['term' => 'بدي أفتح محل', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
                ['term' => 'بدي افتح محل', 'type' => 'citizen_expression', 'weight' => 30, 'priority' => 10],
                ['term' => 'بدي أرخص المحل', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
                ['term' => 'بدي ارخص المحل', 'type' => 'citizen_expression', 'weight' => 28, 'priority' => 10],
                ['term' => 'بدي أفتح دكان', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي افتح دكان', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي أفتح متجر', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي افتح متجر', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي أفتح محل ملابس', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي افتح محل ملابس', 'type' => 'citizen_expression', 'weight' => 25, 'priority' => 9],
                ['term' => 'بدي أفتح سوبر ماركت', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي افتح سوبر ماركت', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي أفتح مطعم', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي افتح مطعم', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي أفتح صالون', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'بدي افتح صالون', 'type' => 'citizen_expression', 'weight' => 22, 'priority' => 8],
                ['term' => 'كيف أفتح محل', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 7],
                ['term' => 'كيف افتح محل', 'type' => 'citizen_expression', 'weight' => 20, 'priority' => 7],
            ];
        }

        // ============================================================
        // All other services: add minimal keywords from their names
        // ============================================================

        foreach ($services as $service) {
            if (isset($termsByService[$service->id])) {
                continue;
            }

            $nameTokens = array_filter(explode(' ', $service->name));
            $keywordTerms = [];
            $added = [];

            foreach ($nameTokens as $token) {
                $normalized = $normalizer->normalize($token);
                if (mb_strlen($normalized) >= 3 && ! isset($added[$normalized])) {
                    $keywordTerms[] = [
                        'term' => $token,
                        'type' => 'keyword',
                        'weight' => 10,
                        'priority' => 5,
                    ];
                    $added[$normalized] = true;
                }
            }

            if (! empty($keywordTerms)) {
                $termsByService[$service->id] = $keywordTerms;
            }
        }

        foreach ($termsByService as $serviceId => $terms) {
            foreach ($terms as $termData) {
                $normalized = $normalizer->normalize($termData['term']);

                // Skip if empty after normalization
                if ($normalized === '') {
                    continue;
                }

                ServiceSearchTerm::firstOrCreate(
                    [
                        'electronic_service_id' => $serviceId,
                        'term' => $termData['term'],
                        'type' => $termData['type'],
                    ],
                    [
                        'normalized_term' => $normalized,
                        'weight' => $termData['weight'],
                        'priority' => $termData['priority'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command?->info('Seeded '.count($termsByService).' services with search terms.');
    }
}
