<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ConversationContextService;
use App\Domains\Chatbot\Services\HybridIntentPredictor;
use App\Domains\Chatbot\Services\MunicipalityDomainRouter;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ElectronicServicesSeeder;
use Database\Seeders\MunicipalityDemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WaterScheduleSeeder;

uses()->beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(WaterScheduleSeeder::class);
    $this->seed(MunicipalityDemoSeeder::class);
    $this->action = app(ProcessRuleBasedChatMessageAction::class);
    $this->context = app(ConversationContextService::class);
    $this->predictor = app(HybridIntentPredictor::class);
    $this->router = app(MunicipalityDomainRouter::class);
});

function diagTurn($action, string $sessionId, string $message): mixed
{
    return $action->execute(new IncomingChatMessageData(message: $message, sessionId: $sessionId));
}

it('DIAG-7b: بدي خدمة then الوظاء — mirror EXACTLY', function (): void {
    $sessionId = 'diag-7b-'.uniqid();

    $r1 = diagTurn($this->action, $sessionId, 'بدي خدمة');
    $s1 = $this->context->getState($sessionId);
    dump('AFTER turn1 بدي خدمة: state='.$s1->state->value.' pending='.($s1->pendingField ?? 'null').' domain='.($s1->currentDomain ?? 'null'));

    // Inspect what the pipeline PREDICTS for الوظاء using the SAME predictor instance the action uses
    $pred2 = $this->predictor->predict('الوظاء');
    dump('PREDICT الوظاء: intent='.$pred2->intent->value.' src='.$pred2->source.' conf='.$pred2->confidence.
         ' accepted='.($pred2->accepted ? '1' : '0').' reason='.($pred2->rejectionReason ?? 'null'));
    $route2 = $this->router->route($pred2->intent, 'الوظاء', $s1);
    dump('ROUTE الوظاء: domain='.$route2->domain.' handler='.$route2->handlerKey);

    $r2 = diagTurn($this->action, $sessionId, 'الوظاء');
    $s2 = $this->context->getState($sessionId);
    dump('AFTER turn2 الوظاء: state='.$s2->state->value.' pending='.($s2->pendingField ?? 'null').' domain='.($s2->currentDomain ?? 'null'));
    dump('R2 msg: '.$r2->message);
    dump('R2 type: '.$r2->type.' clarificationType:'.($r2->clarificationType ?? 'null'));

    expect(true)->toBeTrue();
});
