<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use Illuminate\Console\Command;

final class ChatbotWorkflowDiagnosticCommand extends Command
{
    protected $signature = 'chatbot:workflow-diagnostic
                            {session? : Session ID to inspect}
                            {--action= : Action to perform (status|cancel|resume|reset)}
                            {--all : Show all active workflows}';

    protected $description = 'Inspect and manage active chatbot workflows';

    public function handle(
        ConversationContextInterface $context,
        WorkflowDraftRepositoryInterface $draftRepository,
        WorkflowTrackingResolverInterface $tracker,
        CitizenWorkflowEngine $engine,
    ): int {
        $session = $this->argument('session');
        $action = $this->option('action');

        if ($session !== null) {
            $this->inspectSession($session, $context, $draftRepository, $tracker, $engine, $action);

            return self::SUCCESS;
        }

        if ($this->option('all')) {
            return $this->showAllActive($context, $draftRepository, $tracker);
        }

        $this->info('Usage: php artisan chatbot:workflow-diagnostic {session} [--action=status|cancel|resume|reset] [--all]');

        return self::SUCCESS;
    }

    private function inspectSession(
        string $session,
        ConversationContextInterface $context,
        WorkflowDraftRepositoryInterface $draftRepository,
        WorkflowTrackingResolverInterface $tracker,
        CitizenWorkflowEngine $engine,
        ?string $action,
    ): void {
        $this->info("Inspecting session: {$session}");
        $this->newLine();

        $state = $context->getState($session);

        $this->line('Context State:');
        $this->table(
            ['Key', 'Value'],
            [
                ['state', $state->state ?? 'null'],
                ['last_intent', $state->lastIntent ?? 'null'],
                ['current_domain', $state->currentDomain ?? 'null'],
                ['current_service_id', $state->currentServiceId ?? 'null'],
                ['current_service_name', $state->currentServiceName ?? 'null'],
                ['needs_clarification', $state->needsClarification ? 'true' : 'false'],
                ['expired', $state->expired ? 'true' : 'false'],
                ['workflow_draft_id', $state->workflowDraftId ?? 'null'],
                ['workflow_type', $state->workflowType ?? 'null'],
            ]
        );

        $this->newLine();

        $tracking = $tracker->resolveBySessionId($session);
        if ($tracking !== null && $tracking->exists) {
            $this->info('Active Workflow Draft:');
            $this->table(
                ['Key', 'Value'],
                [
                    ['tracking_number', $tracking->trackingNumber ?? 'null'],
                    ['status', $tracking->status ?? 'null'],
                    ['type', $tracking->type ?? 'null'],
                    ['current_step', (string) ($tracking->currentStep ?? 'null')],
                    ['total_steps', (string) ($tracking->totalSteps ?? 'null')],
                    ['created_at', $tracking->createdAt ?? 'null'],
                    ['updated_at', $tracking->updatedAt ?? 'null'],
                ]
            );
        } else {
            $this->warn('No active workflow draft found for this session.');
        }

        if ($action !== null) {
            $this->newLine();
            $this->info("Performing action: {$action}");

            match ($action) {
                'cancel' => $this->performCancel($session, $engine, $context),
                'resume' => $this->performResume($session, $engine),
                'reset' => $this->performReset($session, $context),
                default => $this->warn("Unknown action: {$action}"),
            };
        }
    }

    private function showAllActive(
        ConversationContextInterface $context,
        WorkflowDraftRepositoryInterface $draftRepository,
        WorkflowTrackingResolverInterface $tracker,
    ): int {
        $this->info('Active Workflow Sessions:');
        $this->newLine();

        $count = 0;
        foreach ($draftRepository->allActive() as $draft) {
            $count++;
            $this->line("Session: {$draft->session_id} | Type: {$draft->type} | Status: {$draft->status} | Tracking: {$draft->tracking_number}");
        }

        if ($count === 0) {
            $this->warn('No active workflows found.');
        } else {
            $this->info("Total active: {$count}");
        }

        return self::SUCCESS;
    }

    private function performCancel(string $session, CitizenWorkflowEngine $engine, ConversationContextInterface $context): void
    {
        $result = $engine->cancel($session, null);
        if ($result->cancelled) {
            $this->info('Workflow cancelled successfully.');
        } else {
            $this->warn($result->message);
        }
    }

    private function performResume(string $session, CitizenWorkflowEngine $engine): void
    {
        $result = $engine->resume($session, null);
        $this->info($result->message);
    }

    private function performReset(string $session, ConversationContextInterface $context): void
    {
        $context->reset($session);
        $this->info('Session context reset.');
    }
}
