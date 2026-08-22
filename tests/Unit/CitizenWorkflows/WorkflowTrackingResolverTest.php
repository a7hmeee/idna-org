<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingData;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use App\Domains\CitizenWorkflows\Services\WorkflowTrackingResolver;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;

it('resolves by tracking number when complaint exists', function () {
    $draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $complaintRepo = Mockery::mock(ComplaintRepositoryInterface::class);
    $contactRepo = Mockery::mock(ContactRequestRepositoryInterface::class);

    $resolver = new WorkflowTrackingResolver($draftRepo, $complaintRepo, $contactRepo);

    $complaint = new Complaint;
    $complaint->tracking_number = 'CMP-001';
    $complaint->status = ComplaintStatus::Submitted;
    $complaint->subject = 'Test Complaint';

    $complaintRepo->expects('findByTrackingNumber')->with('CMP-001')->andReturn($complaint);

    $result = $resolver->resolveByTrackingNumber('CMP-001');

    expect($result)
        ->toBeInstanceOf(WorkflowTrackingData::class)
        ->exists->toBeTrue()
        ->trackingNumber->toBe('CMP-001')
        ->status->toBe('submitted')
        ->type->toBe('complaint');
});

it('resolves by tracking number when draft exists', function () {
    $draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $complaintRepo = Mockery::mock(ComplaintRepositoryInterface::class);
    $contactRepo = Mockery::mock(ContactRequestRepositoryInterface::class);

    $resolver = new WorkflowTrackingResolver($draftRepo, $complaintRepo, $contactRepo);

    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->tracking_number = 'TRK-001';
    $draft->status = 'completed';
    $draft->workflow_type = 'complaint';
    $draft->answers = [];

    $complaintRepo->expects('findByTrackingNumber')->with('TRK-001')->andReturnNull();
    $contactRepo->expects('findByTrackingNumber')->with('TRK-001')->andReturnNull();
    $draftRepo->expects('findByTracking')->with('TRK-001')->andReturn($draft);

    $result = $resolver->resolveByTrackingNumber('TRK-001');

    expect($result)
        ->toBeInstanceOf(WorkflowTrackingData::class)
        ->exists->toBeTrue()
        ->trackingNumber->toBe('TRK-001')
        ->status->toBe('completed')
        ->type->toBe('complaint');
});

it('returns null when tracking number not found', function () {
    $draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $complaintRepo = Mockery::mock(ComplaintRepositoryInterface::class);
    $contactRepo = Mockery::mock(ContactRequestRepositoryInterface::class);

    $resolver = new WorkflowTrackingResolver($draftRepo, $complaintRepo, $contactRepo);

    $complaintRepo->expects('findByTrackingNumber')->with('INVALID')->andReturnNull();
    $contactRepo->expects('findByTrackingNumber')->with('INVALID')->andReturnNull();
    $draftRepo->expects('findByTracking')->with('INVALID')->andReturnNull();

    $result = $resolver->resolveByTrackingNumber('INVALID');

    expect($result)->toBeNull();
});

it('resolves by session id when active draft exists', function () {
    $draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $complaintRepo = Mockery::mock(ComplaintRepositoryInterface::class);
    $contactRepo = Mockery::mock(ContactRequestRepositoryInterface::class);

    $resolver = new WorkflowTrackingResolver($draftRepo, $complaintRepo, $contactRepo);

    $draft = new WorkflowDraft;
    $draft->id = 2;
    $draft->tracking_number = 'TRK-002';
    $draft->status = 'collecting_data';
    $draft->workflow_type = 'contact_request';
    $draft->current_step = 'phone';
    $draft->answers = [];

    $draftRepo->expects('findActiveBySession')->with('session-1')->andReturn($draft);

    $result = $resolver->resolveBySessionId('session-1');

    expect($result)
        ->exists->toBeTrue()
        ->trackingNumber->toBe('TRK-002')
        ->status->toBe('collecting_data');
});
