<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Enums;

enum ConversationState: string
{
    case Normal = 'normal';
    case WaitingForEntity = 'waiting_for_entity';
    case WaitingForClarification = 'waiting_for_clarification';
    case WaitingForSelection = 'waiting_for_selection';
    case WaitingForServiceCategory = 'waiting_for_service_category';
    case WaitingForServiceSelection = 'waiting_for_service_selection';
    case WaitingForServiceAction = 'waiting_for_service_action';
    case WaitingForTrackingNumber = 'waiting_for_tracking_number';
    case Completed = 'completed';
    case WorkflowCollectingData = 'workflow_collecting_data';
    case WorkflowConfirming = 'workflow_confirming';
    case WorkflowInterrupting = 'workflow_interrupting';

    public function label(): string
    {
        return match ($this) {
            self::Normal => 'عادي',
            self::WaitingForEntity => 'بانتظار الخدمة',
            self::WaitingForClarification => 'بانتظار التوضيح',
            self::WaitingForSelection => 'بانتظار الاختيار',
            self::WaitingForServiceCategory => 'بانتظار تصنيف الخدمة',
            self::WaitingForServiceSelection => 'بانتظار اختيار الخدمة',
            self::WaitingForServiceAction => 'بانتظار الإجراء',
            self::WaitingForTrackingNumber => 'بانتظار رقم المتابعة',
            self::Completed => 'مكتمل',
            self::WorkflowCollectingData => 'جمع بيانات الطلب',
            self::WorkflowConfirming => 'تأكيد الطلب',
            self::WorkflowInterrupting => 'مقاطعة الطلب',
        };
    }
}
