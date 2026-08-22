<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\ChatbotAnalytics\Events\WorkflowCancelledEvent;
use App\Domains\ChatbotAnalytics\Events\WorkflowCompletedEvent;
use App\Domains\CitizenWorkflows\Contracts\CitizenWorkflowRouterInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Exceptions\WorkflowIncompleteDataException;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use Illuminate\Support\Facades\Event;

class CitizenWorkflowEngine
{
    public function __construct(
        private WorkflowDraftRepositoryInterface $draftRepository,
        private CitizenWorkflowRouterInterface $router,
        private WorkflowValidator $validator,
        private ConfirmationFlow $confirmationFlow,
        private WorkflowExecutionDispatcher $executionDispatcher,
    ) {}

    public function start(string $sessionId, WorkflowType $type, ?int $userId = null): WorkflowStepResultData
    {
        $existingDraft = $this->draftRepository->findActiveBySession($sessionId);

        if ($existingDraft !== null) {
            if ($existingDraft->workflow_type === $type->value) {
                $this->draftRepository->update($existingDraft->id, ['current_step' => '_resume_decision']);

                $steps = $this->router->getSteps($type) ?? [];

                return new WorkflowStepResultData(
                    message: 'يوجد طلب قيد الإكمال. هل تريد المتابعة؟',
                    type: 'workflow_resume',
                    workflowType: $type->value,
                    workflowId: $existingDraft->session_id,
                    draftId: $existingDraft->id,
                    currentStep: '_resume_decision',
                    totalSteps: count($steps),
                    completedSteps: $this->router->getCompletedStepsCount($type, $existingDraft->answers ?? []),
                    stepNumber: count($steps),
                    progressPercent: count($steps) > 0 ? round((count($existingDraft->answers ?? []) / count($steps)) * 100, 1) : 0.0,
                    actions: [
                        ['key' => 'workflow:confirm', 'label' => 'متابعة', 'value' => 'confirm'],
                        ['key' => 'workflow:cancel', 'label' => 'إلغاء', 'value' => 'cancel'],
                    ],
                    currentStepLabel: 'استئناف الطلب',
                    nextConversationState: ConversationState::WorkflowCollectingData->value,
                );
            }

            // A different workflow type (e.g. contact request while a
            // complaint draft exists) starts cleanly: the older draft is
            // cancelled outright — no interruption confirmation, no lock.
            $this->dispatchCancelledEvent($existingDraft, 'switched_workflow_type');
            $this->draftRepository->cancel($existingDraft->id);
        }

        $steps = $this->router->getSteps($type) ?? [];
        $firstStep = $steps[0] ?? 'unknown';

        $this->draftRepository->create([
            'session_id' => $sessionId,
            'citizen_user_id' => $userId,
            'workflow_type' => $type->value,
            'current_step' => $firstStep,
            'answers' => [],
            'status' => 'collecting_data',
            'expires_at' => now()->addHours(2),
        ]);

        $question = $this->router->getInitialQuestion($type);

        return new WorkflowStepResultData(
            message: $question,
            type: 'workflow_question',
            workflowType: $type->value,
            draftId: null,
            currentStep: $firstStep,
            totalSteps: count($steps),
            completedSteps: 0,
            stepNumber: 1,
            progressPercent: 0.0,
            currentStepLabel: $this->router->getStepLabel($type, $firstStep),
            nextConversationState: ConversationState::WorkflowCollectingData->value,
        );
    }

    public function processInput(string $sessionId, string $input, ?int $userId = null): WorkflowStepResultData
    {
        $draft = $this->draftRepository->findActiveBySession($sessionId);
        if ($draft === null) {
            return new WorkflowStepResultData(
                message: 'لا يوجد طلب نشط. يمكنك بدء طلب جديد عن طريق كتابة "تقديم شكوى" أو "طلب اتصال".',
                type: 'workflow_not_found',
                nextConversationState: ConversationState::Normal->value,
            );
        }

        if ($draft->expires_at !== null && $draft->expires_at->isPast()) {
            $this->draftRepository->expire($draft->id);

            return new WorkflowStepResultData(
                message: 'انتهت صلاحية الطلب. يرجى بدء طلب جديد.',
                type: 'workflow_expired',
                workflowType: $draft->workflow_type,
                nextConversationState: ConversationState::Normal->value,
                switchIntent: 'main_menu',
                switchLabel: 'القائمة الرئيسية',
            );
        }

        $type = WorkflowType::from($draft->workflow_type);

        if ($draft->current_step === '_resume_decision') {
            return $this->handleResumeDecision($draft, $type, $input);
        }

        if ($draft->current_step === 'interrupt_confirm') {
            return $this->resolveInterruption($draft, $type, $input);
        }

        $globalCommand = $this->handleGlobalCommands($draft, $type, $input);
        if ($globalCommand !== null) {
            return $globalCommand;
        }

        if ($draft->status === 'confirming' || $draft->status === 'waiting_confirmation') {
            return $this->handleConfirmation($draft, $type, $input);
        }

        return $this->processStepInput($draft, $type, $input);
    }

    public function requestInterruption(string $sessionId, string $targetIntent, string $targetLabel): ?WorkflowStepResultData
    {
        $draft = $this->draftRepository->findActiveBySession($sessionId);
        if ($draft === null) {
            return null;
        }

        if ($this->router->getCompletedStepsCount(WorkflowType::from($draft->workflow_type), $draft->answers ?? []) > 0) {
            $this->draftRepository->update($draft->id, [
                'current_step' => 'interrupt_confirm',
                'metadata' => array_merge($draft->metadata ?? [], [
                    'interrupt_intent' => $targetIntent,
                    'interrupt_label' => $targetLabel,
                    'interrupt_from_step' => $draft->current_step,
                    'interrupt_from_status' => $draft->status,
                ]),
            ]);

            $workflowLabel = $this->router->getWorkflowLabel(WorkflowType::from($draft->workflow_type));

            return new WorkflowStepResultData(
                message: "لديك {$workflowLabel} غير مكتمل. هل تريد المتابعة أم الانتقال إلى {$targetLabel}؟",
                type: 'workflow_interrupt_confirmation',
                workflowType: $draft->workflow_type,
                draftId: $draft->id,
                actions: $this->confirmationFlow->getInterruptActions($targetLabel, $workflowLabel),
                currentStep: $draft->current_step,
                totalSteps: count($this->router->getSteps(WorkflowType::from($draft->workflow_type))),
                completedSteps: $this->router->getCompletedStepsCount(WorkflowType::from($draft->workflow_type), $draft->answers ?? []),
                stepNumber: null,
                progressPercent: count($this->router->getSteps(WorkflowType::from($draft->workflow_type))) > 0
                    ? round((count($draft->answers ?? []) / count($this->router->getSteps(WorkflowType::from($draft->workflow_type)))) * 100, 1)
                    : 0.0,
                switchIntent: null,
                nextConversationState: ConversationState::WorkflowInterrupting->value,
            );
        }

        $this->dispatchCancelledEvent($draft, 'empty_draft_direct_switch');
        $this->draftRepository->cancel($draft->id);

        return new WorkflowStepResultData(
            message: '',
            type: 'workflow_cancelled',
            cancelled: true,
            workflowType: $draft->workflow_type,
            draftId: $draft->id,
            switchIntent: $targetIntent,
            switchLabel: $targetLabel,
            nextConversationState: ConversationState::Normal->value,
        );
    }

    public function resolveInterruption(WorkflowDraft $draft, WorkflowType $type, string $input): WorkflowStepResultData
    {
        $interruptIntent = $draft->metadata['interrupt_intent'] ?? null;
        $interruptLabel = $draft->metadata['interrupt_label'] ?? '';
        $fromStep = $draft->metadata['interrupt_from_step'] ?? $draft->current_step;
        $fromStatus = $draft->metadata['interrupt_from_status'] ?? 'collecting_data';

        if ($this->confirmationFlow->isContinue($input)) {
            $this->draftRepository->update($draft->id, [
                'current_step' => $fromStep,
                'status' => $fromStatus,
                'metadata' => array_diff_key($draft->metadata ?? [], ['interrupt_intent' => true, 'interrupt_label' => true, 'interrupt_from_step' => true, 'interrupt_from_status' => true]),
            ]);

            $steps = $this->router->getSteps($type);
            $question = $this->router->getStepQuestion($type, $fromStep) ?? $this->router->getInitialQuestion($type);
            $completedSteps = $this->router->getCompletedStepsCount($type, $draft->answers ?? []);
            $stepNumber = $this->router->getStepNumber($type, $fromStep);
            $progressPercent = count($steps) > 0 ? round(($completedSteps / count($steps)) * 100, 1) : 0.0;

            return new WorkflowStepResultData(
                message: "تم استئناف الطلب.\n{$question}",
                type: 'workflow_question',
                workflowType: $type->value,
                draftId: $draft->id,
                currentStep: $fromStep,
                totalSteps: count($steps),
                completedSteps: $completedSteps,
                stepNumber: $stepNumber,
                progressPercent: $progressPercent,
                currentStepLabel: $this->router->getStepLabel($type, $fromStep),
                actions: $this->categoryActionsFor($type, $fromStep),
                nextConversationState: ConversationState::WorkflowCollectingData->value,
            );
        }

        if ($this->confirmationFlow->isSwitch($input) || $this->confirmationFlow->isConfirm($input)) {
            $this->dispatchCancelledEvent($draft, 'interruption_confirmed');
            $this->draftRepository->cancel($draft->id);

            return new WorkflowStepResultData(
                message: '',
                type: 'workflow_cancelled',
                cancelled: true,
                workflowType: $type->value,
                draftId: $draft->id,
                switchIntent: $interruptIntent,
                switchLabel: $interruptLabel,
                nextConversationState: ConversationState::Normal->value,
            );
        }

        $workflowLabel = $this->router->getWorkflowLabel($type);

        return new WorkflowStepResultData(
            message: "لديك {$workflowLabel} غير مكتمل. كيف تريد المتابعة؟",
            type: 'workflow_interrupt_confirmation',
            workflowType: $type->value,
            draftId: $draft->id,
            actions: $this->confirmationFlow->getInterruptActions($interruptLabel ?: 'هذا القسم', $workflowLabel),
            nextConversationState: ConversationState::WorkflowInterrupting->value,
        );
    }

    public function cancel(string $sessionId, ?int $userId = null): WorkflowStepResultData
    {
        $draft = $this->draftRepository->findActiveBySession($sessionId);
        if ($draft === null) {
            return new WorkflowStepResultData(
                message: 'لا يوجد طلب نشط لإلغائه.',
                type: 'workflow_cancelled',
            );
        }

        $this->dispatchCancelledEvent($draft, 'user_requested_cancel');
        $this->draftRepository->cancel($draft->id);

        return new WorkflowStepResultData(
            message: 'تم إلغاء الطلب.',
            type: 'workflow_cancelled',
            cancelled: true,
            workflowType: $draft->workflow_type,
            draftId: $draft->id,
            nextConversationState: ConversationState::Normal->value,
        );
    }

    public function resume(string $sessionId, ?int $userId = null): WorkflowStepResultData
    {
        $draft = $this->draftRepository->findResumableBySession($sessionId);
        if ($draft === null) {
            return new WorkflowStepResultData(
                message: 'لا يوجد طلب نشط لاستئنافه.',
                type: 'workflow_not_found',
                nextConversationState: ConversationState::Normal->value,
            );
        }

        if ($draft->status === 'completed' || $draft->status === 'cancelled' || $draft->status === 'expired') {
            return new WorkflowStepResultData(
                message: 'الطلب الذي تحاول استئنافه قد اكتمل أو ألغي أو انتهت صلاحيته.',
                type: 'workflow_not_found',
                workflowType: $draft->workflow_type,
                nextConversationState: ConversationState::Normal->value,
            );
        }

        $type = WorkflowType::from($draft->workflow_type);
        $steps = $this->router->getSteps($type);
        $answers = $draft->answers ?? [];

        $missingStep = null;
        foreach ($steps as $step) {
            if (! array_key_exists($step, $answers)) {
                $missingStep = $step;
                break;
            }
        }

        $completedSteps = $this->router->getCompletedStepsCount($type, $answers);

        if ($missingStep === null) {
            $this->draftRepository->update($draft->id, [
                'current_step' => 'confirm',
                'status' => 'confirming',
            ]);

            $confirmationMessage = $this->router->getConfirmationMessage($type, $answers);

            return new WorkflowStepResultData(
                message: "تم استئناف الطلب.\n{$confirmationMessage}",
                type: 'workflow_confirm',
                confirming: true,
                workflowType: $type->value,
                draftId: $draft->id,
                currentStep: 'confirm',
                totalSteps: count($steps),
                completedSteps: count($steps),
                stepNumber: count($steps),
                progressPercent: 100.0,
                currentStepLabel: 'تأكيد البيانات',
                actions: $this->confirmationFlow->getConfirmationActions(),
                nextConversationState: ConversationState::WorkflowConfirming->value,
            );
        }

        $this->draftRepository->update($draft->id, ['current_step' => $missingStep, 'status' => 'collecting_data']);
        $question = $this->router->getStepQuestion($type, $missingStep);

        return new WorkflowStepResultData(
            message: "تم استئناف الطلب.\n{$question}",
            type: 'workflow_resumed',
            workflowType: $type->value,
            draftId: $draft->id,
            currentStep: $missingStep,
            totalSteps: count($steps),
            completedSteps: $completedSteps,
            stepNumber: $this->router->getStepNumber($type, $missingStep),
            progressPercent: count($steps) > 0 ? round(($completedSteps / count($steps)) * 100, 1) : 0.0,
            currentStepLabel: $this->router->getStepLabel($type, $missingStep),
            actions: $this->categoryActionsFor($type, $missingStep),
            nextConversationState: ConversationState::WorkflowCollectingData->value,
        );
    }

    private function handleGlobalCommands(WorkflowDraft $draft, WorkflowType $type, string $input): ?WorkflowStepResultData
    {
        if ($this->confirmationFlow->isGlobalCancel($input)) {
            return $this->cancelWorkflow($draft, $type, $input);
        }

        if ($this->confirmationFlow->isHelp($input)) {
            return $this->handleHelp($draft, $type);
        }

        if ($this->confirmationFlow->isRestart($input) || $this->confirmationFlow->isHome($input) || $this->confirmationFlow->isExit($input) || $this->confirmationFlow->isChangeTopic($input)) {
            return $this->cancelWorkflowWithSwitch($draft, $type, 'main_menu', 'القائمة الرئيسية');
        }

        return null;
    }

    private function handleHelp(WorkflowDraft $draft, WorkflowType $type): WorkflowStepResultData
    {
        return new WorkflowStepResultData(
            message: "مساعدة: يمكنك إرسال 'إلغاء' في أي وقت لإلغاء هذا الطلب.\nيمكنك أيضًا إرسال 'القائمة الرئيسية' أو 'إعادة' للبدء من جديد.\nالخطوة الحالية: {$draft->current_step}",
            type: 'workflow_help',
            workflowType: $type->value,
            draftId: $draft->id,
            currentStep: $draft->current_step,
            totalSteps: count($this->router->getSteps($type)),
            completedSteps: $this->router->getCompletedStepsCount($type, $draft->answers ?? []),
            stepNumber: $this->router->getStepNumber($type, $draft->current_step) ?? count($this->router->getSteps($type)),
            progressPercent: count($this->router->getSteps($type)) > 0
                ? round((count($draft->answers ?? []) / count($this->router->getSteps($type)) * 100), 1)
                : 0.0,
            currentStepLabel: $this->router->getStepLabel($type, $draft->current_step) ?? 'خطوة حالية',
            nextConversationState: ConversationState::WorkflowCollectingData->value,
        );
    }

    private function handleResumeDecision(WorkflowDraft $draft, WorkflowType $type, string $input): WorkflowStepResultData
    {
        $workflowLabel = $this->router->getWorkflowLabel($type);

        if ($this->confirmationFlow->isGlobalCancel($input)) {
            return $this->cancelWorkflow($draft, $type, $input);
        }

        if ($this->confirmationFlow->isRestart($input) || $this->confirmationFlow->isHome($input) || $this->confirmationFlow->isExit($input) || $this->confirmationFlow->isChangeTopic($input)) {
            return $this->cancelWorkflowWithSwitch($draft, $type, 'main_menu', 'القائمة الرئيسية');
        }

        if ($this->confirmationFlow->isConfirm($input)) {
            return $this->resumeDraft($draft, $type);
        }

        return new WorkflowStepResultData(
            message: "يوجد طلب {$workflowLabel} قيد الإكمال. هل تريد المتابعة؟",
            type: 'workflow_resume',
            workflowType: $type->value,
            draftId: $draft->id,
            actions: [
                ['key' => 'workflow:confirm', 'label' => 'متابعة', 'value' => 'confirm'],
                ['key' => 'workflow:cancel', 'label' => 'إلغاء', 'value' => 'cancel'],
            ],
            currentStep: $draft->current_step,
            totalSteps: count($this->router->getSteps($type)),
            completedSteps: $this->router->getCompletedStepsCount($type, $draft->answers ?? []),
            stepNumber: $this->router->getStepNumber($type, $draft->current_step) ?? count($this->router->getSteps($type)),
            progressPercent: count($this->router->getSteps($type)) > 0
                ? round((count($draft->answers ?? []) / count($this->router->getSteps($type))) * 100, 1)
                : 0.0,
            currentStepLabel: 'استئناف الطلب',
            nextConversationState: ConversationState::WorkflowCollectingData->value,
        );
    }

    private function processStepInput(WorkflowDraft $draft, WorkflowType $type, string $input): WorkflowStepResultData
    {
        $currentStep = $draft->current_step ?? 'unknown';
        $steps = $this->router->getSteps($type) ?? [];

        if ($this->confirmationFlow->isCancel($input) || $this->confirmationFlow->isGlobalCancel($input)) {
            return $this->cancelWorkflow($draft, $type, $input);
        }

        if ($this->confirmationFlow->isHelp($input)) {
            return $this->handleHelp($draft, $type);
        }

        if ($this->confirmationFlow->isRestart($input) || $this->confirmationFlow->isHome($input) || $this->confirmationFlow->isExit($input) || $this->confirmationFlow->isChangeTopic($input)) {
            return $this->cancelWorkflowWithSwitch($draft, $type, 'main_menu', 'القائمة الرئيسية');
        }

        if ($this->confirmationFlow->isConfirm($input)) {
            $answers = $draft->answers ?? [];
            $completedCount = $this->router->getCompletedStepsCount($type, $answers);

            if ($completedCount >= 1) {
                $this->draftRepository->update($draft->id, [
                    'current_step' => 'confirm',
                    'status' => 'confirming',
                ]);

                $freshDraft = $this->draftRepository->findActiveBySession($draft->session_id ?? '0');

                return $this->handleConfirmation($freshDraft, $type, $input);
            }
        }

        $validationError = $this->validator->validate($currentStep, $input);
        if ($validationError !== null) {
            $question = $this->router->getStepQuestion($type, $currentStep) ?? '';

            return new WorkflowStepResultData(
                message: $validationError."\n".$question,
                type: 'workflow_validation_error',
                workflowType: $type->value,
                draftId: $draft->id,
                currentStep: $currentStep,
                totalSteps: count($steps),
                completedSteps: $this->router->getCompletedStepsCount($type, $draft->answers ?? []),
                stepNumber: $this->router->getStepNumber($type, $currentStep),
                progressPercent: count($steps) > 0 ? round((count($draft->answers ?? []) / count($steps)) * 100, 1) : 0.0,
                currentStepLabel: $this->router->getStepLabel($type, $currentStep),
                actions: $this->categoryActionsFor($type, $currentStep),
                nextConversationState: ConversationState::WorkflowCollectingData->value,
            );
        }

        $answers = $draft->answers ?? [];
        $answers[$currentStep] = $this->validator->normalize($currentStep, $input);
        $this->draftRepository->update($draft->id, ['answers' => $answers]);

        $currentIndex = array_search($currentStep, $steps, true);
        $nextIndex = $currentIndex !== false ? $currentIndex + 1 : -1;
        $completedSteps = $nextIndex;

        if ($nextIndex >= count($steps)) {
            $this->draftRepository->update($draft->id, [
                'current_step' => 'confirm',
                'status' => 'confirming',
            ]);

            $confirmationMessage = $this->router->getConfirmationMessage($type, $answers);

            return new WorkflowStepResultData(
                message: $confirmationMessage,
                type: 'workflow_confirm',
                confirming: true,
                workflowType: $type->value,
                draftId: $draft->id,
                currentStep: 'confirm',
                totalSteps: count($steps),
                completedSteps: count($steps),
                stepNumber: count($steps),
                progressPercent: 100.0,
                currentStepLabel: 'تأكيد البيانات',
                actions: $this->confirmationFlow->getConfirmationActions(),
                nextConversationState: ConversationState::WorkflowConfirming->value,
            );
        }

        $nextStep = $steps[$nextIndex];
        $this->draftRepository->update($draft->id, ['current_step' => $nextStep]);

        $question = $this->router->getStepQuestion($type, $nextStep);
        $progressPercent = count($steps) > 0 ? round(($completedSteps / count($steps)) * 100, 1) : 0.0;

        return new WorkflowStepResultData(
            message: $question,
            type: 'workflow_question',
            workflowType: $type->value,
            draftId: $draft->id,
            currentStep: $nextStep,
            totalSteps: count($steps),
            completedSteps: $completedSteps,
            stepNumber: $this->router->getStepNumber($type, $nextStep),
            progressPercent: $progressPercent,
            currentStepLabel: $this->router->getStepLabel($type, $nextStep),
            actions: $this->categoryActionsFor($type, $nextStep),
            nextConversationState: ConversationState::WorkflowCollectingData->value,
        );
    }

    private function handleConfirmation(WorkflowDraft $draft, WorkflowType $type, string $input): WorkflowStepResultData
    {
        if ($this->confirmationFlow->isCancel($input) || $this->confirmationFlow->isGlobalCancel($input)) {
            $this->dispatchCancelledEvent($draft, 'confirmation_rejected');
            $this->draftRepository->cancel($draft->id);

            return new WorkflowStepResultData(
                message: 'تم إلغاء الطلب.',
                type: 'workflow_cancelled',
                cancelled: true,
                workflowType: $type->value,
                draftId: $draft->id,
                nextConversationState: ConversationState::Normal->value,
            );
        }

        if (! $this->confirmationFlow->isConfirm($input)) {
            $confirmationMessage = $this->router->getConfirmationMessage($type, $draft->answers ?? []);

            return new WorkflowStepResultData(
                message: "الرجاء الرد بـ 'نعم' للتأكيد أو 'لا' للإلغاء.\n\n{$confirmationMessage}",
                type: 'workflow_confirm',
                confirming: true,
                workflowType: $type->value,
                draftId: $draft->id,
                currentStep: 'confirm',
                totalSteps: count($this->router->getSteps($type)),
                completedSteps: count($this->router->getSteps($type)),
                stepNumber: count($this->router->getSteps($type)),
                progressPercent: 100.0,
                currentStepLabel: 'تأكيد البيانات',
                actions: $this->confirmationFlow->getConfirmationActions(),
                nextConversationState: ConversationState::WorkflowConfirming->value,
            );
        }

        try {
            $freshDraft = $this->draftRepository->findActiveBySession($draft->session_id);

            if ($freshDraft === null || ($freshDraft->status !== 'confirming' && $freshDraft->status !== 'waiting_confirmation')) {
                return new WorkflowStepResultData(
                    message: 'تمت معالجة هذا الطلب مسبقاً.',
                    type: 'workflow_failure',
                    workflowType: $type->value,
                    nextConversationState: ConversationState::Normal->value,
                );
            }

            // Guard: never complete/persist a workflow with missing required
            // answers — even if a previous step was skipped by a misdetected
            // confirm. The user is sent back to the first incomplete step.
            $incompleteStep = $this->findIncompleteStep($type, $freshDraft->answers ?? []);

            if ($incompleteStep !== null) {
                $this->draftRepository->update($freshDraft->id, [
                    'current_step' => $incompleteStep,
                    'status' => 'collecting_data',
                ]);

                $question = $this->router->getStepQuestion($type, $incompleteStep) ?? '';
                $steps = $this->router->getSteps($type) ?? [];

                return new WorkflowStepResultData(
                    message: "بعض البيانات المطلوبة ناقصة. الرجاء إكمالها.\n{$question}",
                    type: 'workflow_validation_error',
                    workflowType: $type->value,
                    draftId: $freshDraft->id,
                    currentStep: $incompleteStep,
                    totalSteps: count($steps),
                    completedSteps: $this->router->getCompletedStepsCount($type, $freshDraft->answers ?? []),
                    stepNumber: $this->router->getStepNumber($type, $incompleteStep),
                    progressPercent: count($steps) > 0 ? round((count($freshDraft->answers ?? []) / count($steps)) * 100, 1) : 0.0,
                    currentStepLabel: $this->router->getStepLabel($type, $incompleteStep),
                    actions: $this->categoryActionsFor($type, $incompleteStep),
                    nextConversationState: ConversationState::WorkflowCollectingData->value,
                );
            }

            $this->draftRepository->complete($freshDraft->id);

            $answers = $freshDraft->answers ?? [];
            $answers['session_id'] = $freshDraft->session_id;
            $answers['user_id'] = $freshDraft->citizen_user_id;

            $result = $this->executionDispatcher->execute($type, $answers);
            $trackingNumber = null;
            $entityType = null;
            $entityId = null;

            if (is_object($result)) {
                $trackingNumber = $result->tracking_number ?? null;
                $entityId = isset($result->id) ? (int) $result->id : null;
                $entityType = $type->value === 'complaint' ? 'complaint' : 'contact_request';
            } elseif (is_array($result)) {
                $trackingNumber = $result['tracking_number'] ?? null;
                $entityId = isset($result['id']) ? (int) $result['id'] : null;
                $entityType = $type->value === 'complaint' ? 'complaint' : 'contact_request';
            }

            $this->draftRepository->update($freshDraft->id, [
                'final_entity_type' => $entityType,
                'final_entity_id' => $entityId,
                'tracking_number' => $trackingNumber,
                'completed_at' => now(),
            ]);

            $durationMs = $freshDraft->created_at ? (int) (now()->diffInSeconds($freshDraft->created_at) * 1000) : 0;
            try {
                $conversation = ChatbotConversation::where('session_id', $freshDraft->session_id)->first();
                $conversationId = $conversation ? $conversation->id : 0;
            } catch (\Throwable) {
                $conversationId = 0;
            }

            try {
                Event::dispatch(new WorkflowCompletedEvent(
                    conversationId: $conversationId,
                    workflowType: $type->value,
                    draftId: $freshDraft->id,
                    totalSteps: count($this->getSteps($type)),
                    durationMs: $durationMs,
                ));
            } catch (\Throwable) {
            }

            $successMessage = $this->router->getSuccessMessage($type, $answers, $result);

            $successDetails = $this->router->getSuccessDetails($type, $result);

            return new WorkflowStepResultData(
                message: $successMessage,
                type: 'workflow_completed',
                completed: true,
                trackingNumber: $trackingNumber,
                submissionDate: $successDetails['submission_date'] ?? null,
                statusLabel: $successDetails['status_label'] ?? null,
                workflowType: $type->value,
                draftId: $freshDraft->id,
                currentStep: 'confirm',
                totalSteps: count($this->router->getSteps($type)),
                completedSteps: count($this->router->getSteps($type)),
                stepNumber: count($this->router->getSteps($type)),
                progressPercent: 100.0,
                currentStepLabel: 'اكتمال',
                actions: $this->router->getSuccessActions($type, $trackingNumber),
                nextConversationState: ConversationState::Normal->value,
            );
        } catch (WorkflowIncompleteDataException $e) {
            // Final defense: the dispatcher refused to persist. Send the user
            // back to the first incomplete step instead of failing silently.
            $this->draftRepository->update($freshDraft->id, [
                'current_step' => $e->missingStep,
                'status' => 'collecting_data',
            ]);

            $question = $this->router->getStepQuestion($type, $e->missingStep) ?? '';
            $steps = $this->router->getSteps($type) ?? [];

            return new WorkflowStepResultData(
                message: "بعض البيانات المطلوبة ناقصة. الرجاء إكمالها.\n{$question}",
                type: 'workflow_validation_error',
                workflowType: $type->value,
                draftId: $freshDraft->id,
                currentStep: $e->missingStep,
                totalSteps: count($steps),
                completedSteps: $this->router->getCompletedStepsCount($type, $freshDraft->answers ?? []),
                stepNumber: $this->router->getStepNumber($type, $e->missingStep),
                progressPercent: count($steps) > 0 ? round((count($freshDraft->answers ?? []) / count($steps)) * 100, 1) : 0.0,
                currentStepLabel: $this->router->getStepLabel($type, $e->missingStep),
                actions: $this->categoryActionsFor($type, $e->missingStep),
                nextConversationState: ConversationState::WorkflowCollectingData->value,
            );
        } catch (\Throwable) {
            return new WorkflowStepResultData(
                message: 'تمت معالجة هذا الطلب مسبقاً.',
                type: 'workflow_failure',
                workflowType: $type->value,
                nextConversationState: ConversationState::Normal->value,
            );
        }
    }

    /**
     * The first required step whose answer is missing or empty — or null
     * when every step has a value. Used as the guard before persistence.
     */
    private function findIncompleteStep(WorkflowType $type, array $answers): ?string
    {
        foreach ($this->router->getSteps($type) as $step) {
            $value = $answers[$step] ?? '';

            if (! is_string($value) || trim($value) === '') {
                return $step;
            }
        }

        return null;
    }

    private function cancelWorkflow(WorkflowDraft $draft, WorkflowType $type, string $reason): WorkflowStepResultData
    {
        $this->dispatchCancelledEvent($draft, $reason);
        $this->draftRepository->cancel($draft->id);

        return new WorkflowStepResultData(
            message: 'تم إلغاء الطلب.',
            type: 'workflow_cancelled',
            cancelled: true,
            workflowType: $type->value,
            draftId: $draft->id,
            nextConversationState: ConversationState::Normal->value,
        );
    }

    private function cancelWorkflowWithSwitch(WorkflowDraft $draft, WorkflowType $type, string $switchIntent, string $switchLabel): WorkflowStepResultData
    {
        $this->dispatchCancelledEvent($draft, 'global_command:'.$switchIntent);
        $this->draftRepository->cancel($draft->id);

        return new WorkflowStepResultData(
            message: 'تم إلغاء الطلب.',
            type: 'workflow_cancelled',
            cancelled: true,
            workflowType: $type->value,
            draftId: $draft->id,
            switchIntent: $switchIntent,
            switchLabel: $switchLabel,
            nextConversationState: ConversationState::Normal->value,
        );
    }

    private function beginConfirmation(WorkflowDraft $draft, WorkflowType $type, array $answers, array $steps): WorkflowStepResultData
    {
        $this->draftRepository->update($draft->id, [
            'current_step' => 'confirm',
            'status' => 'confirming',
        ]);

        $confirmationMessage = $this->router->getConfirmationMessage($type, $answers);

        return new WorkflowStepResultData(
            message: $confirmationMessage,
            type: 'workflow_confirm',
            confirming: true,
            workflowType: $type->value,
            draftId: $draft->id,
            currentStep: 'confirm',
            totalSteps: count($steps),
            completedSteps: count($steps),
            stepNumber: count($steps),
            progressPercent: 100.0,
            currentStepLabel: 'تأكيد البيانات',
            actions: $this->confirmationFlow->getConfirmationActions(),
            nextConversationState: ConversationState::WorkflowConfirming->value,
        );
    }

    private function resumeDraft(WorkflowDraft $draft, WorkflowType $type): WorkflowStepResultData
    {
        $steps = $this->router->getSteps($type);
        $answers = $draft->answers ?? [];

        $missingStep = null;
        foreach ($steps as $step) {
            if (! array_key_exists($step, $answers)) {
                $missingStep = $step;
                break;
            }
        }

        $completedSteps = $this->router->getCompletedStepsCount($type, $answers);

        if ($missingStep === null) {
            $this->draftRepository->update($draft->id, [
                'current_step' => 'confirm',
                'status' => 'confirming',
            ]);

            $confirmationMessage = $this->router->getConfirmationMessage($type, $answers);

            return new WorkflowStepResultData(
                message: "تم استئناف الطلب.\n{$confirmationMessage}",
                type: 'workflow_confirm',
                confirming: true,
                workflowType: $type->value,
                draftId: $draft->id,
                currentStep: 'confirm',
                totalSteps: count($steps),
                completedSteps: count($steps),
                stepNumber: count($steps),
                progressPercent: 100.0,
                currentStepLabel: 'تأكيد البيانات',
                actions: $this->confirmationFlow->getConfirmationActions(),
                nextConversationState: ConversationState::WorkflowConfirming->value,
            );
        }

        $this->draftRepository->update($draft->id, ['current_step' => $missingStep, 'status' => 'collecting_data']);
        $question = $this->router->getStepQuestion($type, $missingStep);

        return new WorkflowStepResultData(
            message: "تم استئناف الطلب.\n{$question}",
            type: 'workflow_question',
            workflowType: $type->value,
            draftId: $draft->id,
            currentStep: $missingStep,
            totalSteps: count($steps),
            completedSteps: $completedSteps,
            stepNumber: $this->router->getStepNumber($type, $missingStep),
            progressPercent: count($steps) > 0 ? round(($completedSteps / count($steps)) * 100, 1) : 0.0,
            currentStepLabel: $this->router->getStepLabel($type, $missingStep),
            actions: $this->categoryActionsFor($type, $missingStep),
            nextConversationState: ConversationState::WorkflowCollectingData->value,
        );
    }

    private function categoryActionsFor(WorkflowType $type, string $step): array
    {
        return $step === 'category' ? $this->router->getStepActions($type, $step) : [];
    }

    private function getSteps(WorkflowType $type): array
    {
        return $this->router->getSteps($type) ?? [];
    }

    private function dispatchCancelledEvent(WorkflowDraft $draft, string $reason): void
    {
        try {
            $conversation = ChatbotConversation::where('session_id', $draft->session_id)->first();
            $conversationId = $conversation ? $conversation->id : 0;
        } catch (\Throwable) {
            $conversationId = 0;
        }

        $type = WorkflowType::from($draft->workflow_type);
        $steps = $this->getSteps($type);

        $currentStep = $draft->current_step;
        $currentIndex = array_search($currentStep, $steps, true);
        $currentStepNum = $currentIndex !== false ? $currentIndex + 1 : 0;

        try {
            Event::dispatch(new WorkflowCancelledEvent(
                conversationId: $conversationId,
                workflowType: $draft->workflow_type,
                draftId: $draft->id,
                currentStep: $currentStepNum,
                totalSteps: count($steps),
                cancellationReason: $reason,
            ));
        } catch (\Throwable) {
        }
    }
}
