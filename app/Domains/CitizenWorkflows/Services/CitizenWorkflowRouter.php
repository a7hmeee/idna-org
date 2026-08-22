<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

use App\Domains\CitizenWorkflows\Contracts\CitizenWorkflowRouterInterface;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\ContactRequests\Actions\CreateContactRequestAction;
use Carbon\Carbon;

final class CitizenWorkflowRouter implements CitizenWorkflowRouterInterface
{
    public const STEP_DEFINITIONS = [
        'complaint' => [
            'steps' => ['citizen_name', 'phone', 'category', 'subject', 'description'],
            'action' => CreateComplaintAction::class,
        ],
        'contact_request' => [
            'steps' => ['name', 'phone', 'message'],
            'action' => CreateContactRequestAction::class,
        ],
    ];

    public const STEP_LABELS = [
        'complaint' => [
            'citizen_name' => 'الاسم',
            'phone' => 'رقم الهاتف',
            'category' => 'تصنيف الشكوى',
            'subject' => 'عنوان الشكوى',
            'description' => 'وصف الشكوى',
            'confirm' => 'تأكيد البيانات',
        ],
        'contact_request' => [
            'name' => 'الاسم',
            'phone' => 'رقم الهاتف',
            'message' => 'الرسالة',
            'confirm' => 'تأكيد البيانات',
        ],
    ];

    public const STEP_PUBLIC_LABELS = [
        'complaint' => [
            'citizen_name' => 'الاسم',
            'phone' => 'رقم الهاتف',
            'category' => 'تصنيف الشكوى',
            'subject' => 'عنوان الشكوى',
            'description' => 'وصف الشكوى',
            'confirm' => 'تأكيد البيانات',
        ],
        'contact_request' => [
            'name' => 'الاسم',
            'phone' => 'رقم الهاتف',
            'message' => 'الرسالة',
            'confirm' => 'تأكيد البيانات',
        ],
    ];

    public const CATEGORY_LABEL = 'تصنيف الشكوى';

    public const CATEGORY_OPTIONS = [
        ['label' => 'خدمات', 'value' => 'خدمات'],
        ['label' => 'بنية تحتية', 'value' => 'بنية تحتية'],
        ['label' => 'مياه', 'value' => 'مياه'],
        ['label' => 'كهرباء', 'value' => 'كهرباء'],
        ['label' => 'طرق', 'value' => 'طرق'],
        ['label' => 'صرف صحي', 'value' => 'صرف صحي'],
        ['label' => 'بيئة', 'value' => 'بيئة'],
        ['label' => 'ضوضاء', 'value' => 'ضوضاء'],
        ['label' => 'إداري', 'value' => 'إداري'],
        ['label' => 'أخرى', 'value' => 'أخرى'],
    ];

    private const INITIAL_QUESTIONS = [
        'complaint' => "أهلاً بك في خدمة تقديم الشكوى.\nللبدء، ما هو اسمك الكامل؟",
        'contact_request' => "أهلاً بك في خدمة طلب الاتصال.\nللبدء، ما هو اسمك؟",
    ];

    private const STEP_QUESTIONS = [
        'complaint' => [
            'citizen_name' => 'ما هو اسمك الكامل؟',
            'phone' => 'الرجاء إدخال رقم الهاتف؟',
            'category' => 'ما هو تصنيف الشكوى؟',
            'subject' => 'ما هو عنوان الشكوى؟',
            'description' => 'الرجاء وصف الشكوى بالتفصيل:',
        ],
        'contact_request' => [
            'name' => 'ما هو اسمك؟',
            'phone' => 'الرجاء إدخال رقم الهاتف؟',
            'message' => 'ما هي رسالتك؟',
        ],
    ];

    private const STEP_HINTS = [
        'complaint' => [
            'category' => 'يمكنك اختيار أحد التصنيفات التالية أو كتابتها:',
            'phone' => 'مثال: 0591234567',
        ],
        'contact_request' => [
            'phone' => 'مثال: 0591234567',
        ],
    ];

    private const WORKFLOW_LABELS = [
        'complaint' => 'تقديم شكوى',
        'contact_request' => 'طلب اتصال',
    ];

    public function getSteps(?WorkflowType $type): array
    {
        return self::STEP_DEFINITIONS[$type?->value]['steps'] ?? [];
    }

    public function getActionClass(WorkflowType $type): ?string
    {
        return self::STEP_DEFINITIONS[$type->value]['action'] ?? null;
    }

    public function getInitialQuestion(WorkflowType $type): string
    {
        return self::INITIAL_QUESTIONS[$type->value] ?? 'ما هو اسمك؟';
    }

    public function getStepQuestion(WorkflowType $type, string $step): ?string
    {
        return self::STEP_QUESTIONS[$type->value][$step] ?? 'الرجاء إدخال القيمة المطلوبة.';
    }

    public function getStepLabel(WorkflowType $type, string $step): string
    {
        return self::STEP_LABELS[$type->value][$step] ?? $step;
    }

    public function getStepHint(WorkflowType $type, string $step): ?string
    {
        return self::STEP_HINTS[$type->value][$step] ?? null;
    }

    public function getWorkflowLabel(WorkflowType $type): string
    {
        return self::WORKFLOW_LABELS[$type->value] ?? $type->value;
    }

    public function getCategoryOptions(): array
    {
        return self::CATEGORY_OPTIONS;
    }

    public function getStepActions(WorkflowType $type, string $step): array
    {
        if ($step === 'category') {
            return array_map(fn (array $option): array => [
                'key' => 'workflow:category:'.$option['value'],
                'label' => $option['label'],
                'value' => $option['value'],
            ], self::CATEGORY_OPTIONS);
        }

        return [];
    }

    public function getStepNumber(WorkflowType $type, string $step): ?int
    {
        $steps = $this->getSteps($type);
        foreach ($steps as $index => $s) {
            if ($s === $step) {
                return $index + 1;
            }
        }

        return null;
    }

    public function getCompletedStepsCount(WorkflowType $type, array $answers): int
    {
        $steps = $this->getSteps($type);
        $count = 0;
        foreach ($steps as $step) {
            if (array_key_exists($step, $answers)) {
                $count++;
            }
        }

        return $count;
    }

    public function getConfirmationMessage(WorkflowType $type, array $data): string
    {
        $lines = match ($type->value) {
            'complaint' => [
                'الرجاء تأكيد بيانات الشكوى:',
                'الاسم: '.($data['citizen_name'] ?? $data['name'] ?? ''),
                'الهاتف: '.($data['phone'] ?? ''),
                'التصنيف: '.($this->resolveCategoryLabel($data['category'] ?? '', $type) ?? ''),
                'الموضوع: '.($data['subject'] ?? ''),
                'الوصف: '.($data['description'] ?? ''),
                '',
                'هل البيانات صحيحة؟',
            ],
            'contact_request' => [
                'الرجاء تأكيد بيانات طلب الاتصال:',
                'الاسم: '.($data['name'] ?? ''),
                'الهاتف: '.($data['phone'] ?? ''),
                'الرسالة: '.($data['message'] ?? ''),
                '',
                'هل البيانات صحيحة؟',
            ],
            default => ['الرجاء تأكيد البيانات.'],
        };

        return implode("\n", $lines);
    }

    public function getSuccessMessage(WorkflowType $type, array $data, mixed $result): string
    {
        $trackingNumber = $this->extractTrackingNumber($result);
        $submittedDate = $this->extractSubmittedDate($result);
        $statusLabel = $this->extractStatusLabel($result);

        $baseMessage = match ($type->value) {
            'complaint' => 'تم تقديم شكواك بنجاح.',
            'contact_request' => 'تم إرسال طلب الاتصال بنجاح.',
            default => 'تم إكمال العملية بنجاح.',
        };

        $lines = [$baseMessage];

        if ($trackingNumber !== null) {
            $lines[] = "رقم المتابعة: {$trackingNumber}";
        }

        if ($submittedDate !== null) {
            $lines[] = "تاريخ التقديم: {$submittedDate}";
        }

        if ($statusLabel !== null) {
            $lines[] = "الحالة الحالية: {$statusLabel}";
        }

        $lines[] = 'يمكنك استخدام رقم المتابعة لتتبع حالة الطلب لاحقاً.';

        return implode("\n", $lines);
    }

    public function getSuccessDetails(WorkflowType $type, mixed $result): array
    {
        $trackingNumber = $this->extractTrackingNumber($result);
        $submittedDate = $this->extractSubmittedDate($result);
        $statusLabel = $this->extractStatusLabel($result);

        return [
            'tracking_number' => $trackingNumber,
            'submission_date' => $submittedDate,
            'status_label' => $statusLabel,
        ];
    }

    public function getSuccessActions(WorkflowType $type, ?string $trackingNumber): array
    {
        $actions = [];

        if ($trackingNumber !== null) {
            $actions[] = [
                'key' => 'workflow:track',
                'label' => 'متابعة الطلب',
                'value' => 'تتبع طلب '.$trackingNumber,
            ];
        }

        return $actions;
    }

    public function getWorkflowStartMessage(WorkflowType $type): string
    {
        return $this->getInitialQuestion($type);
    }

    public function getWorkflowCancelLabel(WorkflowType $type): string
    {
        return match ($type->value) {
            'complaint' => 'شكوى',
            'contact_request' => 'طلب اتصال',
            default => 'طلب',
        };
    }

    public static function isWorkflowIntent(string $intentValue): bool
    {
        return in_array($intentValue, [
            'create_complaint',
            'contact_request',
            'track_workflow',
            'resume_workflow',
            'cancel_workflow',
        ], true);
    }

    private function resolveCategoryLabel(string $category, WorkflowType $type): ?string
    {
        foreach (self::CATEGORY_OPTIONS as $option) {
            if ($category === '' || $category === null) {
                return null;
            }

            if (mb_strtolower(trim($category)) === mb_strtolower(trim($option['value']))) {
                return $option['label'];
            }
        }

        return $category;
    }

    private function extractTrackingNumber(mixed $result): ?string
    {
        if (is_object($result) && property_exists($result, 'tracking_number') && $result->tracking_number !== null) {
            return $result->tracking_number;
        }

        if (is_array($result) && isset($result['tracking_number']) && $result['tracking_number'] !== null) {
            return (string) $result['tracking_number'];
        }

        return null;
    }

    private function extractSubmittedDate(mixed $result): ?string
    {
        if (is_object($result) && property_exists($result, 'submitted_at') && $result->submitted_at !== null) {
            return Carbon::parse($result->submitted_at)->format('Y-m-d');
        }

        if (is_array($result) && isset($result['submitted_at']) && $result['submitted_at'] !== null) {
            try {
                return Carbon::parse($result['submitted_at'])->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    private function extractStatusLabel(mixed $result): ?string
    {
        if (is_object($result) && property_exists($result, 'status') && $result->status !== null) {
            if (method_exists($result->status, 'label')) {
                return $result->status->label();
            }

            return (string) $result->status;
        }

        return null;
    }
}
