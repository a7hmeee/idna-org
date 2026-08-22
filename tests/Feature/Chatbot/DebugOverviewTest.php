<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\DirectServiceResolverInterface;
use App\Domains\Chatbot\Contracts\EntityResolverInterface;
use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('debug overview resolution', function (): void {
    $cat = ServiceCategory::create([
        'name' => 'الخدمات الإلكترونية',
        'slug' => 'alkhdmat-alalktrony',
        'status' => 'active',
        'is_public' => true,
    ]);
    $svc = ElectronicService::create([
        'service_category_id' => $cat->id,
        'name' => 'إصدار رخصة بناء',
        'summary' => 'التقدم للحصول على رخصة بناء',
        'description' => 'خدمة إلكترونية لإصدار رخصة بناء',
        'status' => 'active',
        'is_public' => true,
        'steps' => ['تعبئة طلب التقديم', 'إرفاق المستندات', 'دفع الرسوم', 'المراجعة'],
        'requirements' => ['هوية', 'سند ملكية'],
        'fees' => [['item' => 'رسوم الترخيص', 'amount' => '500 شيكل']],
        'processing_time' => '15 يوم',
    ]);

    $normalizer = app(ArabicTextNormalizer::class);
    $resolved = app(DirectServiceResolverInterface::class)->resolve($normalizer->normalize('معلومات عن إصدار رخصة بناء'));
    fwrite(STDERR, 'RESOLVER='.($resolved === null ? 'NULL' : $resolved->name)."\n");

    $entity = app(EntityResolverInterface::class)->resolve($normalizer->normalize('معلومات عن إصدار رخصة بناء'));
    fwrite(STDERR, 'ENTITY='.($entity === null ? 'NULL' : $entity->name)."\n");
    $multiple = app(EntityResolverInterface::class)->resolveMultiple($normalizer->normalize('معلومات عن إصدار رخصة بناء'));
    fwrite(STDERR, 'MULTIPLE='.count($multiple)."\n");

    $predicted = app(HybridIntentPredictorInterface::class)->predict($normalizer->normalize('معلومات عن إصدار رخصة بناء'));
    fwrite(STDERR, 'INTENT='.$predicted->intent->value.' CONF='.var_export($predicted->confidence, true)."\n");

    $action = app(ProcessRuleBasedChatMessageAction::class);
    $response = $action->execute(new IncomingChatMessageData(message: 'معلومات عن إصدار رخصة بناء', sessionId: 'debug-overview'));
    fwrite(STDERR, 'TYPE='.$response->type."\n");
    fwrite(STDERR, 'MSG='.$response->message."\n");

    expect(true)->toBeTrue();
});
