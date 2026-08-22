<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowRouter;

beforeEach(function (): void {
    $this->router = new CitizenWorkflowRouter;
});

it('returns steps for complaint workflow', function (): void {
    $steps = $this->router->getSteps(WorkflowType::Complaint);

    expect($steps)->toBe(['citizen_name', 'phone', 'category', 'subject', 'description']);
});

it('returns steps for contact request workflow', function (): void {
    $steps = $this->router->getSteps(WorkflowType::ContactRequest);

    expect($steps)->toBe(['name', 'phone', 'message']);
});

it('returns empty steps for unknown type', function (): void {
    expect($this->router->getSteps(WorkflowType::tryFrom('unknown')))->toBe([]);
});

it('returns initial question for complaint', function (): void {
    $question = $this->router->getInitialQuestion(WorkflowType::Complaint);

    expect($question)->toContain('شكوى');
    expect($question)->toContain('اسمك');
});

it('returns initial question for contact request', function (): void {
    $question = $this->router->getInitialQuestion(WorkflowType::ContactRequest);

    expect($question)->toContain('طلب الاتصال');
});

it('returns step question for complaint steps', function (): void {
    expect($this->router->getStepQuestion(WorkflowType::Complaint, 'citizen_name'))->toContain('اسمك');
    expect($this->router->getStepQuestion(WorkflowType::Complaint, 'phone'))->toContain('هاتف');
    expect($this->router->getStepQuestion(WorkflowType::Complaint, 'category'))->toContain('تصنيف');
    expect($this->router->getStepQuestion(WorkflowType::Complaint, 'subject'))->toContain('عنوان');
    expect($this->router->getStepQuestion(WorkflowType::Complaint, 'description'))->toContain('وصف');
});

it('returns default question for unknown step', function (): void {
    expect($this->router->getStepQuestion(WorkflowType::Complaint, 'unknown'))->toContain('القيمة المطلوبة');
});

it('builds complaint confirmation message', function (): void {
    $data = [
        'citizen_name' => 'أحمد محمد',
        'phone' => '0599123456',
        'category' => 'طرق',
        'subject' => 'مشكلة في الشارع',
        'description' => 'شرح مطول للمشكلة',
    ];

    $message = $this->router->getConfirmationMessage(WorkflowType::Complaint, $data);

    expect($message)->toContain('أحمد محمد');
    expect($message)->toContain('0599123456');
    expect($message)->toContain('طرق');
    expect($message)->toContain('مشكلة في الشارع');
    expect($message)->toContain('هل البيانات صحيحة؟');
});

it('builds contact request confirmation message', function (): void {
    $data = [
        'name' => 'أحمد',
        'phone' => '0599123456',
        'message' => 'أريد معلومات عن الخدمات',
    ];

    $message = $this->router->getConfirmationMessage(WorkflowType::ContactRequest, $data);

    expect($message)->toContain('أحمد');
    expect($message)->toContain('0599123456');
    expect($message)->toContain('أريد معلومات');
    expect($message)->toContain('هل البيانات صحيحة؟');
});

it('builds success message with tracking number from object', function (): void {
    $result = new stdClass;
    $result->tracking_number = 'CMP-ABC123';

    $message = $this->router->getSuccessMessage(WorkflowType::Complaint, [], $result);

    expect($message)->toContain('تم تقديم شكواك بنجاح');
    expect($message)->toContain('CMP-ABC123');
});

it('builds success message without tracking number', function (): void {
    $message = $this->router->getSuccessMessage(WorkflowType::ContactRequest, [], []);

    expect($message)->toContain('تم إرسال طلب الاتصال بنجاح');
});

it('detects workflow intents', function (): void {
    expect(CitizenWorkflowRouter::isWorkflowIntent('create_complaint'))->toBeTrue();
    expect(CitizenWorkflowRouter::isWorkflowIntent('contact_request'))->toBeTrue();
    expect(CitizenWorkflowRouter::isWorkflowIntent('track_workflow'))->toBeTrue();
    expect(CitizenWorkflowRouter::isWorkflowIntent('greeting'))->toBeFalse();
    expect(CitizenWorkflowRouter::isWorkflowIntent('unknown'))->toBeFalse();
});
