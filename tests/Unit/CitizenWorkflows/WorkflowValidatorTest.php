<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Services\WorkflowValidator;

beforeEach(function (): void {
    $this->validator = new WorkflowValidator;
});

it('validates citizen_name with valid input', function (): void {
    expect($this->validator->validate('citizen_name', 'أحمد محمد'))->toBeNull();
});

it('validates citizen_name with too short input', function (): void {
    expect($this->validator->validate('citizen_name', 'أ'))->toContain('حرفين');
});

it('validates citizen_name with too long input', function (): void {
    expect($this->validator->validate('citizen_name', str_repeat('أ', 101)))->toContain('طويل');
});

it('validates phone with valid input', function (): void {
    expect($this->validator->validate('phone', '0599123456'))->toBeNull();
});

it('validates phone with too short input', function (): void {
    expect($this->validator->validate('phone', '123'))->toContain('غير صحيح');
});

it('validates category with valid input', function (): void {
    expect($this->validator->validate('category', 'طرق'))->toBeNull();
});

it('validates subject with valid input', function (): void {
    expect($this->validator->validate('subject', 'مشكلة في الطريق الرئيسي'))->toBeNull();
});

it('validates subject with too short input', function (): void {
    expect($this->validator->validate('subject', 'أ'))->toContain('3 أحرف');
});

it('validates description with valid input', function (): void {
    expect($this->validator->validate('description', 'هذه مشكلة كبيرة في الطرق منذ شهر كامل'))->toBeNull();
});

it('validates description with too short input', function (): void {
    expect($this->validator->validate('description', 'قصير'))->toContain('10 أحرف');
});

it('validates name step with valid input', function (): void {
    expect($this->validator->validate('name', 'أحمد'))->toBeNull();
});

it('validates message step with valid input', function (): void {
    expect($this->validator->validate('message', 'أود الاستفسار عن خدمة معينة'))->toBeNull();
});

it('returns null for unknown step', function (): void {
    expect($this->validator->validate('unknown_step', 'value'))->toBeNull();
});
