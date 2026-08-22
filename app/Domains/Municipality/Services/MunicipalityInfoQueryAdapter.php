<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Services;

use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\MunicipalityContactData;
use App\Domains\Chatbot\DTOs\MunicipalityProfileData;
use App\Domains\Chatbot\DTOs\MunicipalityWorkingHoursData;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\SharedKernel\Enums\BusinessHourDay;
use Illuminate\Support\Facades\Cache;

final readonly class MunicipalityInfoQueryAdapter implements MunicipalityInfoQueryInterface
{
    private const CACHE_KEY = 'chatbot:municipality-info';

    private const CACHE_TTL = 86400;

    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function getPublicProfile(): ?MunicipalityProfileData
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): ?MunicipalityProfileData {
            $municipality = $this->repository->getProfile();

            if ($municipality === null) {
                return null;
            }

            return new MunicipalityProfileData(
                id: $municipality->id,
                nameAr: $municipality->name_ar,
                nameEn: $municipality->name_en,
                shortDescription: $municipality->short_description,
                fullDescription: $municipality->full_description,
                vision: $municipality->vision,
                mission: $municipality->mission,
                foundationDate: $municipality->foundation_date?->format('Y-m-d'),
                municipalityCode: $municipality->municipality_code,
                population: $municipality->population !== null ? (string) $municipality->population : null,
                area: $municipality->area !== null ? (string) $municipality->area : null,
                latitude: $municipality->latitude !== null ? (string) $municipality->latitude : null,
                longitude: $municipality->longitude !== null ? (string) $municipality->longitude : null,
            );
        });
    }

    public function getOfficialContacts(): array
    {
        $municipality = $this->repository->getProfile();

        if ($municipality === null) {
            return [];
        }

        return $municipality->contacts()
            ->where('is_active', true)
            ->get()
            ->map(fn ($contact) => new MunicipalityContactData(
                type: $contact->type,
                value: $contact->value,
                label: $contact->label,
                url: $contact->url,
                icon: $contact->icon,
                isActive: (bool) $contact->is_active,
            ))
            ->all();
    }

    public function getWorkingHours(): array
    {
        $municipality = $this->repository->getProfile();

        if ($municipality === null) {
            return [];
        }

        return $municipality->businessHours()
            ->get()
            ->map(fn ($hours) => new MunicipalityWorkingHoursData(
                day: $hours->day instanceof BusinessHourDay ? $hours->day->value : (string) $hours->day,
                openTime: $hours->open_time,
                closeTime: $hours->close_time,
                notes: $hours->notes,
                isClosed: (bool) ($hours->is_closed ?? false),
            ))
            ->all();
    }

    public function getAddress(): ?MunicipalityContactData
    {
        $municipality = $this->repository->getProfile();

        if ($municipality === null) {
            return null;
        }

        $address = $municipality->contacts()
            ->where('type', 'address')
            ->where('is_active', true)
            ->first();

        if ($address === null) {
            return null;
        }

        return new MunicipalityContactData(
            type: $address->type,
            value: $address->value,
            label: $address->label,
            url: $address->url,
            icon: $address->icon,
            isActive: true,
        );
    }

    public function getAboutSummary(): ?string
    {
        $profile = $this->getPublicProfile();

        if ($profile === null) {
            return null;
        }

        return $profile->shortDescription ?? $profile->fullDescription;
    }
}
