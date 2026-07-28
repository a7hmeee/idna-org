<?php

declare(strict_types=1);

use App\Domains\Authentication\Actions\LogoutAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Event::fake();

    $this->user = \App\Domains\Authentication\Models\User::factory()->create();
    Auth::login($this->user);
});

it('logs out authenticated user', function (): void {
    expect(Auth::check())->toBeTrue();

    $logoutAction = app(LogoutAction::class);
    $logoutAction->execute();

    expect(Auth::check())->toBeFalse();
});

it('dispatches logout event', function (): void {
    $logoutAction = app(LogoutAction::class);
    $logoutAction->execute();

    Event::assertDispatched(\App\Domains\Authentication\Events\UserLoggedOut::class);
});

it('invalidates session after logout', function (): void {
    $sessionId = session()->getId();

    $logoutAction = app(LogoutAction::class);
    $logoutAction->execute();

    expect(session()->getId())->not->toBe($sessionId);
});
