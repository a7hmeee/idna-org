<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Services\WorkflowExecutionDispatcher;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\ContactRequests\Actions\CreateContactRequestAction;
use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\Models\ContactRequest;

beforeEach(function (): void {
    $this->repository = Mockery::mock(ComplaintRepositoryInterface::class);
    $this->action = new CreateComplaintAction($this->repository);

    $this->contactRepository = Mockery::mock(ContactRequestRepositoryInterface::class);
    $this->contactAction = new CreateContactRequestAction($this->contactRepository);

    $this->dispatcher = new WorkflowExecutionDispatcher($this->action, $this->contactAction);
});

it('executes complaint workflow and returns complaint', function (): void {
    $data = [
        'name' => 'أحمد محمد',
        'phone' => '0599123456',
        'category' => 'طرق',
        'subject' => 'مشكلة في الشارع',
        'description' => 'شرح مطول للمشكلة في الطرق',
    ];

    $complaint = new Complaint;
    $complaint->tracking_number = 'CMP-TEST123';

    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn($complaint);

    $result = $this->dispatcher->execute(WorkflowType::Complaint, $data);

    expect($result)->toBeInstanceOf(Complaint::class);
    expect($result->tracking_number)->toBe('CMP-TEST123');
});

it('executes contact request workflow and returns ContactRequest instance', function (): void {
    $data = [
        'name' => 'أحمد',
        'phone' => '0599123456',
        'message' => 'أريد معلومات عن الخدمات',
    ];

    $contactRequest = new ContactRequest;
    $contactRequest->name = 'أحمد';
    $contactRequest->phone = '0599123456';
    $contactRequest->message = 'أريد معلومات عن الخدمات';
    $contactRequest->tracking_number = 'CR-TEST123';

    $this->contactRepository->shouldReceive('create')
        ->once()
        ->andReturn($contactRequest);

    $result = $this->dispatcher->execute(WorkflowType::ContactRequest, $data);

    expect($result)->toBeInstanceOf(ContactRequest::class);
    expect($result->name)->toBe('أحمد');
    expect($result->phone)->toBe('0599123456');
    expect($result->message)->toBe('أريد معلومات عن الخدمات');
});

it('resolves arabic category names', function (): void {
    $data = [
        'name' => 'test',
        'phone' => '0599',
        'category' => 'كهرباء',
        'subject' => 'مشكلة',
        'description' => 'شرح مفصل للمشكلة هنا',
    ];

    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn(new Complaint);

    $this->dispatcher->execute(WorkflowType::Complaint, $data);
});
