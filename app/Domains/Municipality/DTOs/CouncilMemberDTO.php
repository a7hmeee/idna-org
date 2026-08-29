<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class CouncilMemberDTO
{
    public function __construct(
        public ?int $id = null,
        public string $fullName = '',
        public ?string $slug = null,
        public ?string $nationalNumber = null,
        public string $position = 'council_member',
        public ?string $qualification = null,
        public ?string $profession = null,
        public ?string $bio = null,
        public ?string $photoPath = null,
        public ?string $phone = null,
        public ?string $mobile = null,
        public ?string $email = null,
        public ?string $address = null,
        public ?string $facebook = null,
        public ?string $twitter = null,
        public ?string $linkedin = null,
        public string $termStart = '',
        public ?string $termEnd = null,
        public ?int $yearsOfExperience = null,
        public ?string $committee = null,
        public string $status = 'active',
        public int $displayOrder = 0,
        public bool $isPublic = false,
        public bool $isFeatured = false,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            fullName: $validated['full_name'],
            slug: $validated['slug'] ?? null,
            nationalNumber: $validated['national_number'] ?? null,
            position: $validated['position'],
            qualification: $validated['qualification'] ?? null,
            profession: $validated['profession'] ?? null,
            bio: $validated['bio'] ?? null,
            photoPath: $validated['photo_path'] ?? null,
            phone: $validated['phone'] ?? null,
            mobile: $validated['mobile'] ?? null,
            email: $validated['email'] ?? null,
            address: $validated['address'] ?? null,
            facebook: $validated['facebook'] ?? null,
            twitter: $validated['twitter'] ?? null,
            linkedin: $validated['linkedin'] ?? null,
            termStart: $validated['term_start'],
            termEnd: $validated['term_end'] ?? null,
            yearsOfExperience: isset($validated['years_of_experience']) ? (int) $validated['years_of_experience'] : null,
            committee: $validated['committee'] ?? null,
            status: $validated['status'],
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isPublic: (bool) ($validated['is_public'] ?? false),
            isFeatured: (bool) ($validated['is_featured'] ?? false),
            createdBy: isset($validated['created_by']) ? (int) $validated['created_by'] : null,
            updatedBy: isset($validated['updated_by']) ? (int) $validated['updated_by'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            ...($this->slug !== null ? ['slug' => $this->slug] : []),
            'national_number' => $this->nationalNumber,
            'position' => $this->position,
            'qualification' => $this->qualification,
            'profession' => $this->profession,
            'bio' => $this->bio,
            'photo_path' => $this->photoPath,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'address' => $this->address,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
            'term_start' => $this->termStart,
            'term_end' => $this->termEnd,
            'years_of_experience' => $this->yearsOfExperience,
            'committee' => $this->committee,
            'status' => $this->status,
            'display_order' => $this->displayOrder,
            'is_public' => $this->isPublic,
            'is_featured' => $this->isFeatured,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
