<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;

it('engineering offices public index view exists', function (): void {
    expect(View::exists('livewire.engineering-offices.public-engineering-offices-index'))->toBeTrue();
});

it('engineering office public show view exists', function (): void {
    expect(View::exists('livewire.engineering-offices.public-engineering-office-show'))->toBeTrue();
});

it('open data index view exists', function (): void {
    expect(View::exists('livewire.open-data.index'))->toBeTrue();
});

it('water schedule public view exists', function (): void {
    expect(View::exists('livewire.water-schedule.public-water-schedule'))->toBeTrue();
});

it('public page carousel view exists', function (): void {
    expect(View::exists('livewire.public-page-carousel'))->toBeTrue();
});