<?php

declare(strict_types=1);

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\Repositories\EloquentEngineeringOfficeRepository;
use App\Domains\OpenData\Contracts\OpenDataRepositoryInterface;
use App\Domains\OpenData\Repositories\EloquentOpenDataRepository;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\Repositories\EloquentWaterScheduleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('engineering office repository binding resolves', function (): void {
    $repo = app(EngineeringOfficeRepositoryInterface::class);

    expect($repo)->toBeInstanceOf(EloquentEngineeringOfficeRepository::class);
});

it('open data repository binding resolves', function (): void {
    $repo = app(OpenDataRepositoryInterface::class);

    expect($repo)->toBeInstanceOf(EloquentOpenDataRepository::class);
});

it('water schedule repository binding resolves', function (): void {
    $repo = app(WaterScheduleRepositoryInterface::class);

    expect($repo)->toBeInstanceOf(EloquentWaterScheduleRepository::class);
});

it('engineering office repository has all required methods', function (): void {
    $repo = app(EngineeringOfficeRepositoryInterface::class);

    expect(method_exists($repo, 'getPublicOffices'))->toBeTrue();
    expect(method_exists($repo, 'getFeaturedPublicOffices'))->toBeTrue();
    expect(method_exists($repo, 'findBySlug'))->toBeTrue();
    expect(method_exists($repo, 'incrementViews'))->toBeTrue();
});

it('water schedule repository has all required methods', function (): void {
    $repo = app(WaterScheduleRepositoryInterface::class);

    expect(method_exists($repo, 'getCurrentSchedule'))->toBeTrue();
    expect(method_exists($repo, 'getAreas'))->toBeTrue();
    expect(method_exists($repo, 'getCurrentMaintenance'))->toBeTrue();
});

it('open data repository has all required methods', function (): void {
    $repo = app(OpenDataRepositoryInterface::class);

    expect(method_exists($repo, 'getDatasets'))->toBeTrue();
    expect(method_exists($repo, 'getCategories'))->toBeTrue();
    expect(method_exists($repo, 'getFeaturedDatasets'))->toBeTrue();
});
