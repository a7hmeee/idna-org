@php
    $isWorkflow = in_array($type ?? '', ['workflow_question', 'workflow_confirmation', 'workflow_success', 'workflow_cancelled', 'workflow_resumed', 'workflow_resume', 'workflow_validation_error', 'workflow_interrupt_confirmation', 'workflow_not_found', 'workflow_expired', 'workflow_failure', 'workflow_completed']);
    $workflow = $workflow ?? null;
    $workflowType = $workflow['type'] ?? $metadata['workflow_type'] ?? null;
    $currentStep = $workflow['current_step'] ?? $metadata['current_step'] ?? null;
    $currentStepLabel = $workflow['current_step_label'] ?? null;
    $totalSteps = $workflow['total_steps'] ?? $metadata['total_steps'] ?? null;
    $progressPercent = $workflow['progress_percent'] ?? $metadata['progress_percent'] ?? null;
    $trackingNumber = $workflow['tracking_number'] ?? $metadata['tracking_number'] ?? null;
    $feedbackEligible = $feedback_eligible ?? false;
@endphp

<div class="flex justify-start gap-2" dir="rtl">
    <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center shrink-0 mt-1">
        <i data-lucide="bot" class="w-4 h-4 text-primary"></i>
    </div>
            <div class="max-w-[85%] flex flex-col items-start gap-1">
                @if (($isWorkflow))
                    {{-- Workflow card takes full responsibility for rendering --}}
                    @include('components.chatbot.workflow-card', [
                        'message' => $content,
                        'type' => $type,
                        'workflowType' => $workflowType,
                        'currentStep' => $currentStep,
                        'currentStepLabel' => $currentStepLabel,
                        'totalSteps' => $totalSteps,
                        'progressPercent' => $progressPercent,
                        'trackingNumber' => $trackingNumber,
                        'actions' => $actions ?? [],
                        'feedbackEligible' => $feedbackEligible,
                        'messageId' => $messageId ?? null,
                    ])
                @elseif (($type ?? '') === 'empty_state')
                    <div class="bg-warning-light border border-warning/20 rounded-lg p-3">
                        <p class="text-xs text-warning">{{ e($content) }}</p>
                    </div>
                @else
                    <div class="bg-surface border border-border rounded-2xl rounded-br-sm px-4 py-2.5 text-sm leading-relaxed text-text shadow-sm">
                        <p class="whitespace-pre-line">{{ e($content) }}</p>

                {{-- Service Cards --}}
                @if (($type ?? '') === 'service_cards' && !empty($items))
                    <div class="mt-3 space-y-2">
                        @foreach ($items as $item)
                            @include('components.chatbot.service-card', ['item' => $item])
                        @endforeach
                    </div>
                @endif

                {{-- Steps --}}
                @if (($type ?? '') === 'steps' && !empty($items))
                    <ol class="list-decimal list-inside space-y-1 mt-2 text-text-secondary">
                        @foreach ($items as $step)
                            <li class="text-sm">{{ e(is_string($step) ? $step : ($step['label'] ?? $step['step'] ?? '')) }}</li>
                        @endforeach
                    </ol>
                @endif

                {{-- Requirements --}}
                @if (($type ?? '') === 'requirements' && !empty($items))
                    <ul class="list-disc list-inside space-y-1 mt-2 text-text-secondary">
                        @foreach ($items as $req)
                            <li class="text-sm">{{ e(is_string($req) ? $req : ($req['label'] ?? $req['requirement'] ?? '')) }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Fee items --}}
                @if (($type ?? '') === 'fee' && !empty($items))
                    <div class="mt-2 space-y-1 text-text-secondary">
                        @foreach ($items as $fee)
                            @if (is_string($fee))
                                <p class="text-sm">{{ e($fee) }}</p>
                            @else
                                <p class="text-sm">{{ e($fee['label'] ?? $fee['description'] ?? '') }}: {{ e($fee['amount'] ?? $fee['value'] ?? '') }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- List items --}}
                @if (($type ?? '') === 'list' && !empty($items))
                    <div class="mt-2 space-y-1.5">
                        @foreach ($items as $item)
                            <div class="bg-surface-hover border border-border rounded-lg p-2.5">
                                <p class="text-sm font-medium text-text">{{ e($item['name'] ?? $item['title'] ?? '') }}</p>
                                @if (!empty($item['description']))
                                    <p class="text-xs text-text-secondary mt-0.5">{{ e($item['description']) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Contact info --}}
                @if (($type ?? '') === 'contact' && !empty($items))
                    <div class="mt-2 space-y-1.5 text-text-secondary">
                        @foreach ($items as $contact)
                            <p class="text-sm"><span class="text-text-tertiary">{{ e($contact['label'] ?? '') }}:</span> {{ e($contact['value'] ?? '') }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Location --}}
                @if (($type ?? '') === 'location' && !empty($items))
                    <div class="mt-2 space-y-1.5 text-text-secondary">
                        @foreach ($items as $loc)
                            <p class="text-sm">{{ e($loc['address'] ?? $loc['value'] ?? '') }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Schedule --}}
                @if (($type ?? '') === 'schedule' && !empty($items))
                    <div class="mt-2 space-y-2">
                        @foreach ($items as $sched)
                            <div class="bg-surface-hover border border-border rounded-lg p-2.5">
                                @if (!empty($sched['area']))
                                    <p class="text-sm font-medium text-text">{{ e($sched['area']) }}</p>
                                @endif
                                @if (!empty($sched['date']))
                                    <p class="text-xs text-text-tertiary">التاريخ: {{ e($sched['date']) }}</p>
                                @endif
                                @if (!empty($sched['time']))
                                    <p class="text-xs text-text-tertiary">الوقت: {{ e($sched['time']) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Tracking result --}}
                @if (($type ?? '') === 'workflow_tracking' && !empty($items))
                    @include('components.chatbot.tracking-card', ['item' => $items])
                @endif

                {{-- Clarification options --}}
                @if (($needs_clarification ?? false) && !empty($actions))
                    <div class="flex flex-wrap gap-2 mt-3" dir="rtl">
                        @foreach ($actions as $i => $action)
                            @php
                                $actionValue = $action['value'] ?? ($i + 1);
                                $isKeyedAction = is_string($actionValue) && str_contains($actionValue, ':');
                            @endphp
                            @if ($isKeyedAction)
                                <button type="button"
                                        wire:click="quickAction('{{ e($actionValue) }}', '{{ e($action['label']) }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary-light text-primary text-xs rounded-xl hover:bg-primary hover:text-white border border-primary/20 hover:border-primary transition-all cursor-pointer border-none font-medium whitespace-nowrap">
                                    <span class="text-[10px] bg-primary/10 text-primary px-1.5 py-0.5 rounded-md font-bold">{{ $i + 1 }}</span>
                                    {{ e($action['label']) }}
                                </button>
                            @else
                                <button type="button"
                                        wire:click="selectClarificationOption({{ $actionValue }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary-light text-primary text-xs rounded-xl hover:bg-primary hover:text-white border border-primary/20 hover:border-primary transition-all cursor-pointer border-none font-medium whitespace-nowrap">
                                    <span class="text-[10px] bg-primary/10 text-primary px-1.5 py-0.5 rounded-md font-bold">{{ $i + 1 }}</span>
                                    {{ e($action['label']) }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Quick Actions (non-clarification) --}}
                @if (!($needs_clarification ?? false) && !empty($actions) && !$isWorkflow)
                    @include('components.chatbot.quick-actions', ['actions' => $actions])
                @endif

                {{-- Feedback prompt (once only) --}}
                @if ($feedbackEligible && ($messageId ?? null) !== null)
                    @include('components.chatbot.feedback', ['messageId' => $messageId])
                @endif
            </div>
        @endif

        @if (!empty($time))
            <span class="text-[10px] text-text-tertiary px-1">{{ $time }}</span>
        @endif
    </div>
</div>
