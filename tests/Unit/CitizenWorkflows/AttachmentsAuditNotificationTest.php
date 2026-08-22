<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;

it('carries attachment metadata in result', function () {
    $result = new WorkflowStepResultData(
        message: 'تم تقديم الطلب مع المرفقات.',
        type: 'workflow_success',
        completed: true,
        trackingNumber: 'TRK-ATT-001',
        workflowType: 'complaint',
        draftId: 1,
        metadata: [
            'attachments' => [
                ['id' => 10, 'filename' => 'doc.pdf', 'size' => 2048],
                ['id' => 11, 'filename' => 'image.jpg', 'size' => 512000],
            ],
            'attachment_count' => 2,
        ],
    );

    expect($result->metadata)->toHaveKey('attachments')
        ->and($result->metadata['attachments'])->toHaveCount(2)
        ->and($result->metadata['attachment_count'])->toBe(2)
        ->and($result->metadata['attachments'][0]['filename'])->toBe('doc.pdf');
});

it('carries no attachments when none uploaded', function () {
    $result = new WorkflowStepResultData(
        message: 'تم تقديم الطلب.',
        type: 'workflow_success',
        completed: true,
        workflowType: 'contact_request',
        metadata: ['attachment_count' => 0, 'attachments' => []],
    );

    expect($result->metadata['attachments'])->toBeEmpty()
        ->and($result->metadata['attachment_count'])->toBe(0);
});

it('carries audit trail in metadata', function () {
    $result = new WorkflowStepResultData(
        message: 'تم تقديم الطلب.',
        type: 'workflow_success',
        completed: true,
        workflowType: 'complaint',
        metadata: [
            'audit' => [
                'created_by' => 'citizen',
                'created_at' => '2025-01-01 10:00:00',
                'ip_address' => '192.168.1.1',
                'events' => [
                    ['action' => 'created', 'timestamp' => '2025-01-01 10:00:00'],
                    ['action' => 'submitted', 'timestamp' => '2025-01-01 10:05:00'],
                ],
            ],
        ],
    );

    expect($result->metadata['audit'])->toHaveKey('created_by', 'citizen')
        ->and($result->metadata['audit']['events'])->toHaveCount(2);
});

it('carries notification status in metadata', function () {
    $result = new WorkflowStepResultData(
        message: 'تم تقديم الطلب.',
        type: 'workflow_success',
        completed: true,
        workflowType: 'complaint',
        metadata: [
            'notifications' => [
                ['channel' => 'sms', 'status' => 'sent', 'recipient' => '0555000111'],
                ['channel' => 'email', 'status' => 'sent', 'recipient' => 'test@example.com'],
            ],
            'notification_summary' => 'تم إرسال إشعارات SMS والبريد الإلكتروني',
        ],
    );

    expect($result->metadata['notifications'])->toHaveCount(2)
        ->and($result->metadata['notification_summary'])->toContain('SMS');
});

it('carries notification failure info', function () {
    $result = new WorkflowStepResultData(
        message: 'تم تقديم الطلب.',
        type: 'workflow_success',
        completed: true,
        workflowType: 'complaint',
        metadata: [
            'notifications' => [
                ['channel' => 'sms', 'status' => 'failed', 'error' => 'Invalid phone number'],
            ],
            'notification_summary' => 'فشل إرسال إشعار SMS',
        ],
    );

    expect($result->metadata['notifications'][0]['status'])->toBe('failed')
        ->and($result->metadata['notification_summary'])->toContain('فشل');
});
