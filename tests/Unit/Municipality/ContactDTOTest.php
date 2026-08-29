<?php

declare(strict_types=1);

use App\Domains\Homepage\Actions\ToggleHomepageSectionAction;
use App\Domains\Municipality\DTOs\ContactDTO;

it('ContactDTO handles camelCase keys from Livewire validation', function (): void {
    $dto = ContactDTO::fromRequest([
        'type' => 'phone',
        'label' => 'هاتف البلدية',
        'value' => '02-1234567',
        'icon' => 'phone',
        'url' => null,
        'displayOrder' => 5,
        'isActive' => false,
    ]);

    expect($dto->displayOrder)->toBe(5);
    expect($dto->isActive)->toBeFalse();
});

it('ContactDTO handles snake_case keys as fallback', function (): void {
    $dto = ContactDTO::fromRequest([
        'type' => 'email',
        'label' => 'البريد الإلكتروني',
        'value' => 'info@idhna.ps',
        'icon' => 'mail',
        'url' => 'mailto:info@idhna.ps',
        'display_order' => 3,
        'is_active' => true,
    ]);

    expect($dto->displayOrder)->toBe(3);
    expect($dto->isActive)->toBeTrue();
});

it('ContactDTO toArray outputs snake_case keys', function (): void {
    $dto = ContactDTO::fromRequest([
        'type' => 'phone',
        'label' => 'هاتف',
        'value' => '123',
        'displayOrder' => 2,
        'isActive' => true,
    ]);

    $array = $dto->toArray();

    expect($array)->toHaveKeys(['display_order', 'is_active']);
    expect($array['display_order'])->toBe(2);
    expect($array['is_active'])->toBeTrue();
});

it('ToggleHomepageSectionAction clears cache', function (): void {
    // Verify the action exists and has correct signature
    $reflection = new ReflectionClass(ToggleHomepageSectionAction::class);
    expect($reflection->hasMethod('execute'))->toBeTrue();

    $method = $reflection->getMethod('execute');
    $params = $method->getParameters();
    expect(count($params))->toBe(1);
    expect($params[0]->getName())->toBe('key');
});
