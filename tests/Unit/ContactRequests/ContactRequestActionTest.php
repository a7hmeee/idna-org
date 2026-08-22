<?php

declare(strict_types=1);

use App\Domains\ContactRequests\Actions\SubmitContactRequestAction;
use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\DTOs\CreateContactRequestData;
use App\Domains\ContactRequests\Models\ContactRequest;

it('creates contact request via repository', function () {
    $repo = Mockery::mock(ContactRequestRepositoryInterface::class);
    $action = new SubmitContactRequestAction($repo);

    $data = new CreateContactRequestData(
        name: 'أحمد',
        phone: '0555000111',
        message: 'أريد معلومات عن الخدمات',
        email: 'ahmed@example.com',
        department: 'خدمة العملاء',
    );

    $contactRequest = new ContactRequest;
    $contactRequest->id = 1;
    $contactRequest->name = 'أحمد';
    $contactRequest->email = 'ahmed@example.com';
    $contactRequest->phone = '0555000111';
    $contactRequest->message = 'أريد معلومات عن الخدمات';

    $repo->expects('create')
        ->with(Mockery::on(fn (CreateContactRequestData $d) => $d->name === 'أحمد' && $d->email === 'ahmed@example.com'))
        ->andReturn($contactRequest);

    $result = $action->execute($data);

    expect($result)->toBeInstanceOf(ContactRequest::class);
    expect($result->name)->toBe('أحمد');
});

it('handles empty optional fields in contact request', function () {
    $repo = Mockery::mock(ContactRequestRepositoryInterface::class);
    $action = new SubmitContactRequestAction($repo);

    $data = new CreateContactRequestData(
        name: 'محمد',
        phone: '0555000222',
        message: 'استفسار',
        email: 'moh@example.com',
    );

    $contactRequest = new ContactRequest;
    $contactRequest->id = 2;
    $contactRequest->name = 'محمد';

    $repo->expects('create')->andReturn($contactRequest);

    $result = $action->execute($data);

    expect($result)->toBeInstanceOf(ContactRequest::class);
});
