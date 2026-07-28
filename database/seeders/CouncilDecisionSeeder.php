<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Authentication\Models\User;
use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Database\Seeder;

final class CouncilDecisionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $decisions = [
            [
                'decision_number' => 'ق-2026-001',
                'title' => 'اعتماد الميزانية التقديرية للعام المالي 2026',
                'summary' => 'قرار المجلس البلدي رقم (1) لسنة 2026 بشأن اعتماد الميزانية التقديرية للعام المالي الجديد.',
                'content' => "قرر المجلس البلدي في جلسته رقم (5) المنعقدة بتاريخ 15/01/2026 ما يلي:\n\nأولاً: اعتماد الميزانية التقديرية للعام المالي 2026 بقيمة إجمالية قدرها 5,000,000 شيكل.\n\nثانياً: تخصيص 30% من الميزانية لمشاريع البنية التحتية.\n\nثالثاً: تخصيص 20% لتحسين الخدمات البلدية.",
                'type' => 'financial',
                'status' => 'published',
                'decision_date' => '2026-01-15',
                'session_number' => 'جلسة-5',
                'is_public' => true,
                'sort_order' => 1,
                'published_at' => '2026-01-16 10:00:00',
            ],
            [
                'decision_number' => 'ق-2026-002',
                'title' => 'تنظيم مواقف السيارات في وسط البلدة',
                'summary' => 'قرار بتنظيم عملية الوقوف والانتظار في شوارع وسط البلدة ومنع الانتظار المزدوج.',
                'content' => "قرر المجلس البلدي ما يلي:\n\nأولاً: منع الانتظار المزدوج للسيارات في الشوارع الرئيسية.\n\nثانياً: تخصيص مواقف قصيرة الأمد (15 دقيقة) أمام المحلات التجارية.\n\nثالثاً: تفعيل دوريات تنظيم الوقوف.",
                'type' => 'regulatory',
                'status' => 'published',
                'decision_date' => '2026-02-10',
                'session_number' => 'جلسة-8',
                'is_public' => true,
                'sort_order' => 2,
                'published_at' => '2026-02-11 09:30:00',
            ],
            [
                'decision_number' => 'ق-2026-003',
                'title' => 'إنشاء حديقة عامة في حي الفوار',
                'summary' => 'قرار بشأن إنشاء حديقة عامة في حي الفوار بمساحة 5 دونمات وتزويدها بالمرافق اللازمة.',
                'content' => "قرر المجلس البلدي الإعلان عن مناقصة لإنشاء حديقة عامة في حي الفوار وفق المواصفات التالية:\n\n- المساحة: 5 دونمات\n- ألعاب أطفال\n- ممرات مشاة\n- إنارة كاملة\n- نظام ري حديث",
                'type' => 'infrastructure',
                'status' => 'published',
                'decision_date' => '2026-03-05',
                'session_number' => 'جلسة-12',
                'is_public' => true,
                'sort_order' => 3,
                'published_at' => '2026-03-06 11:00:00',
            ],
            [
                'decision_number' => 'ق-2026-004',
                'title' => 'تعديل رسوم رخص البناء',
                'summary' => 'قرار بتعديل رسوم رخص البناء بما يتناسب مع التكاليف الحالية وتحفيز الاستثمار.',
                'content' => "قرر المجلس البلدي تعديل رسوم رخص البناء على النحو التالي:\n\n- تخفيض رسوم التراخيص للمساكن الخاصة بنسبة 15%\n- إعفاء المباني الزراعية من الرسوم\n- زيادة رسوم التراخيص التجارية والصناعية بنسبة 10%",
                'type' => 'administrative',
                'status' => 'draft',
                'decision_date' => '2026-04-20',
                'session_number' => 'جلسة-15',
                'is_public' => false,
                'sort_order' => 4,
            ],
            [
                'decision_number' => 'ق-2026-005',
                'title' => 'إطلاق خدمة الدفع الإلكتروني للفواتير البلدية',
                'summary' => 'قرار بالموافقة على إطلاق منصة إلكترونية لدفع الفواتير البلدية عبر الإنترنت.',
                'content' => "قرر المجلس البلدي اعتماد نظام الدفع الإلكتروني للفواتير البلدية من خلال:\n\n1. تطبيق جوال للهواتف الذكية\n2. موقع إلكتروني للدفع\n3. نقاط دفع في مراكز الخدمة\n\nعلى أن يتم التشغيل التجريبي خلال 3 أشهر.",
                'type' => 'service',
                'status' => 'published',
                'decision_date' => '2026-05-12',
                'session_number' => 'جلسة-20',
                'is_public' => true,
                'sort_order' => 5,
                'published_at' => '2026-05-13 08:00:00',
            ],
        ];

        foreach ($decisions as $decision) {
            CouncilDecision::firstOrCreate(
                ['decision_number' => $decision['decision_number']],
                array_merge($decision, [
                    'created_by' => $admin?->id,
                    'updated_by' => $admin?->id,
                ])
            );
        }

        $this->command?->info('✓ تم إضافة 5 قرارات تجريبية');
    }
}
