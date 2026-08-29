<?php

declare(strict_types=1);

use App\Domains\Municipality\DTOs\CouncilMemberDTO;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use Illuminate\Validation\Rule;

it('toArray excludes slug when null so update does not overwrite existing slug', function (): void {
    $dto = CouncilMemberDTO::fromRequest([
        'full_name' => 'Ahmed Ali',
        'position' => 'deputy_mayor',
        'term_start' => '2024-01-01',
        'status' => 'active',
    ]);

    $array = $dto->toArray();

    expect($array)->not->toHaveKey('slug');
});

it('toArray includes slug when provided', function (): void {
    $dto = CouncilMemberDTO::fromRequest([
        'full_name' => 'Ahmed Ali',
        'slug' => 'ahmed-ali',
        'position' => 'deputy_mayor',
        'term_start' => '2024-01-01',
        'status' => 'active',
    ]);

    $array = $dto->toArray();

    expect($array)->toHaveKey('slug', 'ahmed-ali');
});

it('fromRequest sets slug to null when not provided', function (): void {
    $dto = CouncilMemberDTO::fromRequest([
        'full_name' => 'Ahmed Ali',
        'position' => 'council_member',
        'term_start' => '2024-01-01',
        'status' => 'active',
    ]);

    expect($dto->slug)->toBeNull();
});

it('position validation accepts all enum values', function (): void {
    $validator = validator(
        ['position' => 'deputy_mayor'],
        ['position' => ['required', Rule::in(CouncilMemberPosition::values())]]
    );

    expect($validator->passes())->toBeTrue();

    foreach (CouncilMemberPosition::values() as $value) {
        $v = validator(
            ['position' => $value],
            ['position' => ['required', Rule::in(CouncilMemberPosition::values())]]
        );
        expect($v->passes())->toBeTrue("Position '{$value}' should pass validation");
    }
});

it('toArray contains all expected fields', function (): void {
    $dto = CouncilMemberDTO::fromRequest([
        'full_name' => 'Test Member',
        'national_number' => '12345',
        'position' => 'council_member',
        'qualification' => 'BS',
        'profession' => 'Engineer',
        'bio' => 'Some bio',
        'phone' => '123456',
        'mobile' => '789012',
        'email' => 'test@example.com',
        'address' => 'Street 1',
        'facebook' => 'https://facebook.com/test',
        'twitter' => 'https://twitter.com/test',
        'linkedin' => 'https://linkedin.com/test',
        'term_start' => '2024-01-01',
        'term_end' => '2026-12-31',
        'years_of_experience' => 5,
        'committee' => 'Finance',
        'status' => 'active',
        'display_order' => 1,
        'is_public' => true,
        'is_featured' => false,
    ]);

    $array = $dto->toArray();

    expect($array)->toHaveKeys([
        'full_name', 'national_number', 'position', 'qualification',
        'profession', 'bio', 'phone', 'mobile', 'email', 'address',
        'facebook', 'twitter', 'linkedin', 'term_start', 'term_end',
        'years_of_experience', 'committee', 'status', 'display_order',
        'is_public', 'is_featured', 'created_by', 'updated_by',
    ]);
});
