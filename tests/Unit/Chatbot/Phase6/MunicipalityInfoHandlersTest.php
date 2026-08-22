<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\MunicipalityContactData;
use App\Domains\Chatbot\DTOs\MunicipalityProfileData;
use App\Domains\Chatbot\DTOs\MunicipalityWorkingHoursData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Handlers\MunicipalityAboutHandler;
use App\Domains\Chatbot\Handlers\MunicipalityAddressHandler;
use App\Domains\Chatbot\Handlers\MunicipalityContactHandler;
use App\Domains\Chatbot\Handlers\MunicipalityEmailHandler;
use App\Domains\Chatbot\Handlers\MunicipalityPhoneHandler;
use App\Domains\Chatbot\Handlers\MunicipalityWorkingHoursHandler;

beforeEach(function (): void {
    $this->infoQuery = Mockery::mock(MunicipalityInfoQueryInterface::class);
});

it('municipality phone handler returns phone', function (): void {
    $this->infoQuery->shouldReceive('getOfficialContacts')
        ->once()
        ->andReturn([
            new MunicipalityContactData(type: 'phone', value: '022345678'),
        ]);

    $handler = new MunicipalityPhoneHandler($this->infoQuery);
    $incoming = new IncomingChatMessageData(message: 'شو رقم البلدية', sessionId: 'test');

    $response = $handler->handle($incoming, null);

    expect($response->type)->toBe('contact');
    expect($response->message)->toContain('022345678');
});

it('municipality phone handler returns empty state when no phones', function (): void {
    $this->infoQuery->shouldReceive('getOfficialContacts')
        ->once()
        ->andReturn([]);

    $handler = new MunicipalityPhoneHandler($this->infoQuery);
    $incoming = new IncomingChatMessageData(message: 'شو رقم البلدية', sessionId: 'test');

    $response = $handler->handle($incoming, null);

    expect($response->type)->toBe('empty_state');
});

it('municipality email handler returns email', function (): void {
    $this->infoQuery->shouldReceive('getOfficialContacts')
        ->once()
        ->andReturn([
            new MunicipalityContactData(type: 'email', value: 'info@idna-city.org'),
        ]);

    $handler = new MunicipalityEmailHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'ايميل البلدية', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('contact');
    expect($response->message)->toContain('info@idna-city.org');
});

it('municipality address handler returns address', function (): void {
    $this->infoQuery->shouldReceive('getAddress')
        ->once()
        ->andReturn(
            new MunicipalityContactData(type: 'address', value: 'إذنا - الخليل'),
        );

    $handler = new MunicipalityAddressHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'وين البلدية', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('location');
    expect($response->message)->toContain('إذنا');
});

it('municipality address handler returns empty state when address missing', function (): void {
    $this->infoQuery->shouldReceive('getAddress')
        ->once()
        ->andReturnNull();

    $handler = new MunicipalityAddressHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'وين البلدية', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('empty_state');
});

it('municipality working hours handler returns hours', function (): void {
    $this->infoQuery->shouldReceive('getWorkingHours')
        ->once()
        ->andReturn([
            new MunicipalityWorkingHoursData(
                day: 'sunday',
                openTime: '08:00',
                closeTime: '14:00',
            ),
        ]);

    $handler = new MunicipalityWorkingHoursHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'متى الدوام', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('text');
    expect($response->message)->toContain('الأحد');
});

it('municipality about handler returns about info', function (): void {
    $this->infoQuery->shouldReceive('getPublicProfile')
        ->once()
        ->andReturn(
            new MunicipalityProfileData(
                id: 1,
                nameAr: 'بلدية إذنا',
                shortDescription: 'بلدية فلسطينية',
            )
        );

    $this->infoQuery->shouldReceive('getAboutSummary')
        ->once()
        ->andReturn('بلدية فلسطينية');

    $handler = new MunicipalityAboutHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'عن البلدية', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('text');
    expect($response->message)->toContain('بلدية إذنا');
});

it('municipality contact handler returns contacts', function (): void {
    $this->infoQuery->shouldReceive('getOfficialContacts')
        ->once()
        ->andReturn([
            new MunicipalityContactData(type: 'phone', value: '022345678', label: 'هاتف'),
            new MunicipalityContactData(type: 'email', value: 'info@idna-city.org', label: 'بريد'),
        ]);

    $handler = new MunicipalityContactHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'معلومات الاتصال', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('contact');
    expect($response->message)->toContain('معلومات الاتصال بالبلدية');
    expect(collect($response->items)->pluck('value'))->toContain('022345678');
    expect(collect($response->items)->pluck('value'))->toContain('info@idna-city.org');
});

it('municipality contact handler returns empty state when no contacts', function (): void {
    $this->infoQuery->shouldReceive('getOfficialContacts')
        ->once()
        ->andReturn([]);

    $handler = new MunicipalityContactHandler($this->infoQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'معلومات الاتصال', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('empty_state');
});

it('all municipality handlers support correct intents', function (): void {
    $handler = new MunicipalityPhoneHandler($this->infoQuery);
    expect($handler->supports(ChatbotIntent::MunicipalityPhone))->toBeTrue();
    expect($handler->supports(ChatbotIntent::MunicipalityEmail))->toBeFalse();
    expect($handler->supports(ChatbotIntent::Greeting))->toBeFalse();

    $handler2 = new MunicipalityEmailHandler($this->infoQuery);
    expect($handler2->supports(ChatbotIntent::MunicipalityEmail))->toBeTrue();
    expect($handler2->supports(ChatbotIntent::MunicipalityPhone))->toBeFalse();
});
