<?php

declare(strict_types=1);

use App\Domains\Authentication\Exceptions\AuthenticationException;
use App\Domains\Authentication\ValueObjects\IpAddress;

it('creates valid IP address', function (): void {
    $ip = IpAddress::fromString('192.168.1.1');

    expect($ip->value())->toBe('192.168.1.1');
});

it('creates valid IPv6 address', function (): void {
    $ip = IpAddress::fromString('::1');

    expect($ip->value())->toBe('::1');
});

it('detects private IP addresses', function (): void {
    $privateIp = IpAddress::fromString('192.168.1.1');
    $publicIp = IpAddress::fromString('8.8.8.8');

    expect($privateIp->isPrivate())->toBeTrue();
    expect($publicIp->isPrivate())->toBeFalse();
});

it('throws exception for invalid IP', function (): void {
    IpAddress::fromString('not-an-ip');
})->throws(AuthenticationException::class);

it('throws exception for empty IP', function (): void {
    IpAddress::fromString('');
})->throws(AuthenticationException::class);
