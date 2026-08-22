<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface;
use App\Domains\Chatbot\DTOs\DepartmentDetailsData;
use App\Domains\Chatbot\DTOs\DepartmentSummaryData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\JobDetailsData;
use App\Domains\Chatbot\DTOs\JobSummaryData;
use App\Domains\Chatbot\DTOs\WaterAreaData;
use App\Domains\Chatbot\DTOs\WaterScheduleData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Handlers\DepartmentContactHandler;
use App\Domains\Chatbot\Handlers\DepartmentSearchHandler;
use App\Domains\Chatbot\Handlers\DepartmentsListHandler;
use App\Domains\Chatbot\Handlers\JobDeadlineHandler;
use App\Domains\Chatbot\Handlers\JobDetailsHandler;
use App\Domains\Chatbot\Handlers\OpenJobsHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleNextHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleTodayHandler;

// ===================================================
// Department Handler Tests
// ===================================================

beforeEach(function (): void {
    $this->deptQuery = Mockery::mock(DepartmentQueryInterface::class);
});

it('departments list handler returns departments', function (): void {
    $this->deptQuery->shouldReceive('getPublishedDepartments')
        ->with(10)
        ->once()
        ->andReturn([
            new DepartmentSummaryData(id: 1, name: 'الدائرة الهندسية'),
            new DepartmentSummaryData(id: 2, name: 'قسم الصحة'),
        ]);

    $handler = new DepartmentsListHandler($this->deptQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'شو الأقسام', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('list');
    expect($response->message)->toContain('الدائرة الهندسية');
});

it('departments list handler returns empty state', function (): void {
    $this->deptQuery->shouldReceive('getPublishedDepartments')
        ->with(10)
        ->once()
        ->andReturn([]);

    $handler = new DepartmentsListHandler($this->deptQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'شو الأقسام', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('empty_state');
});

it('department search handler returns single result', function (): void {
    $this->deptQuery->shouldReceive('searchPublishedDepartments')
        ->with('هندسة', 5)
        ->once()
        ->andReturn([
            new DepartmentSummaryData(id: 1, name: 'الدائرة الهندسية', phone: '022345678'),
        ]);

    $handler = new DepartmentSearchHandler($this->deptQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'هندسة', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('text');
    expect($response->message)->toContain('الدائرة الهندسية');
});

it('department search handler returns clarification for multiple', function (): void {
    $this->deptQuery->shouldReceive('searchPublishedDepartments')
        ->once()
        ->andReturn([
            new DepartmentSummaryData(id: 1, name: 'الدائرة الهندسية'),
            new DepartmentSummaryData(id: 2, name: 'قسم الهندسة'),
        ]);

    $handler = new DepartmentSearchHandler($this->deptQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'هندسة', sessionId: 'test'),
        null,
    );

    expect($response->needsClarification)->toBeTrue();
});

it('department contact handler returns contact info', function (): void {
    $this->deptQuery->shouldReceive('searchPublishedDepartments')
        ->once()
        ->andReturn([new DepartmentSummaryData(id: 1, name: 'الدائرة الهندسية')]);
    $this->deptQuery->shouldReceive('getPublishedDepartmentById')
        ->with(1)
        ->once()
        ->andReturn(new DepartmentDetailsData(id: 1, name: 'الدائرة الهندسية', phone: '022345678', email: 'eng@idna-city.org'));

    $handler = new DepartmentContactHandler($this->deptQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'هندسة', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('contact');
    expect($response->message)->toContain('022345678');
});

it('all department handlers support correct intents', function (): void {
    $handler = new DepartmentsListHandler($this->deptQuery);
    expect($handler->supports(ChatbotIntent::DepartmentsList))->toBeTrue();
    expect($handler->supports(ChatbotIntent::ServiceFees))->toBeFalse();
});

// ===================================================
// Water Schedule Handler Tests
// ===================================================

beforeEach(function (): void {
    $this->waterQuery = Mockery::mock(WaterScheduleQueryInterface::class);
});

it('water schedule handler asks for area when not specified', function (): void {
    $this->waterQuery->shouldReceive('getPublishedAreas')
        ->once()
        ->andReturn([
            new WaterAreaData(id: 1, name: 'واد ريشة'),
            new WaterAreaData(id: 2, name: 'الحي الشرقي'),
        ]);

    $handler = new WaterScheduleHandler($this->waterQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'متى المي', sessionId: 'test'),
        null,
    );

    expect($response->needsClarification)->toBeTrue();
    expect($response->clarificationType)->toBe('water_area');
});

it('water schedule handler returns schedule for known area', function (): void {
    $this->waterQuery->shouldReceive('getPublishedAreas')
        ->once()
        ->andReturn([new WaterAreaData(id: 1, name: 'واد ريشة')]);
    $this->waterQuery->shouldReceive('getCurrentScheduleForArea')
        ->with(1)
        ->once()
        ->andReturn(new WaterScheduleData(
            id: 1, areaId: 1, areaName: 'واد ريشة',
            scheduleDate: '2026-07-29', startTime: '08:00', endTime: '14:00',
        ));

    $handler = new WaterScheduleHandler($this->waterQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'متى المي في واد ريشة', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('schedule');
    expect($response->message)->toContain('واد ريشة');
});

it('water schedule today handler returns schedules', function (): void {
    $this->waterQuery->shouldReceive('getTodaySchedules')
        ->once()
        ->andReturn([
            new WaterScheduleData(id: 1, areaId: 1, areaName: 'واد ريشة', scheduleDate: '2026-07-29', startTime: '08:00', endTime: '14:00'),
        ]);

    $handler = new WaterScheduleTodayHandler($this->waterQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'المياه اليوم', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('schedule');
});

it('water schedule next handler returns next schedule', function (): void {
    $this->waterQuery->shouldReceive('getPublishedAreas')
        ->once()
        ->andReturn([new WaterAreaData(id: 1, name: 'واد ريشة')]);
    $this->waterQuery->shouldReceive('getNextScheduleForArea')
        ->with(1)
        ->once()
        ->andReturn(new WaterScheduleData(
            id: 2, areaId: 1, areaName: 'واد ريشة',
            scheduleDate: '2026-07-30', startTime: '08:00', endTime: '14:00',
        ));

    $handler = new WaterScheduleNextHandler($this->waterQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'واد ريشة', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('schedule');
    expect($response->message)->toContain('2026-07-30');
});

// ===================================================
// Jobs Handler Tests
// ===================================================

beforeEach(function (): void {
    $this->jobsQuery = Mockery::mock(JobsQueryInterface::class);
});

it('open jobs handler returns open jobs', function (): void {
    $this->jobsQuery->shouldReceive('getOpenJobs')
        ->with(5)
        ->once()
        ->andReturn([
            new JobSummaryData(id: 1, title: 'مهندس مدني'),
            new JobSummaryData(id: 2, title: 'محاسب'),
        ]);

    $handler = new OpenJobsHandler($this->jobsQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'في وظائف مفتوحة', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('list');
    expect(collect($response->items)->pluck('title'))->toContain('مهندس مدني');
});

it('open jobs handler returns empty state', function (): void {
    $this->jobsQuery->shouldReceive('getOpenJobs')
        ->with(5)
        ->once()
        ->andReturn([]);

    $handler = new OpenJobsHandler($this->jobsQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'في وظائف مفتوحة', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('empty_state');
});

it('job details handler returns details', function (): void {
    $this->jobsQuery->shouldReceive('searchPublishedJobs')
        ->once()
        ->andReturn([new JobSummaryData(id: 1, title: 'مهندس مدني')]);
    $this->jobsQuery->shouldReceive('getPublishedJobById')
        ->with(1)
        ->once()
        ->andReturn(new JobDetailsData(id: 1, title: 'مهندس مدني', summary: 'مطلوب مهندس مدني', closingAt: '2026-08-15'));

    $handler = new JobDetailsHandler($this->jobsQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'مهندس مدني', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('text');
    expect($response->message)->toContain('مهندس مدني');
    expect($response->message)->toContain('2026-08-15');
});

it('job deadline handler returns deadline', function (): void {
    $this->jobsQuery->shouldReceive('searchPublishedJobs')
        ->once()
        ->andReturn([new JobSummaryData(id: 1, title: 'مهندس مدني')]);
    $this->jobsQuery->shouldReceive('getPublishedJobById')
        ->with(1)
        ->once()
        ->andReturn(new JobDetailsData(id: 1, title: 'مهندس مدني', closingAt: '2026-08-15'));

    $handler = new JobDeadlineHandler($this->jobsQuery);
    $response = $handler->handle(
        new IncomingChatMessageData(message: 'آخر موعد', sessionId: 'test'),
        null,
    );

    expect($response->type)->toBe('date');
    expect($response->message)->toContain('2026-08-15');
});

afterEach(function (): void {
    Mockery::close();
});
