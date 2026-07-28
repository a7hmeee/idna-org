<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Repositories;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\ContactDTO;
use App\Domains\Municipality\DTOs\CustomFieldDTO;
use App\Domains\Municipality\DTOs\ExternalPlatformDTO;
use App\Domains\Municipality\DTOs\GeneralInfoDTO;
use App\Domains\Municipality\DTOs\SocialPlatformDTO;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\Municipality\Models\MunicipalityContact;
use App\Domains\Municipality\Models\MunicipalityCustomField;
use App\Domains\Municipality\Models\MunicipalityExternalPlatform;
use App\Domains\Municipality\Models\MunicipalitySocialPlatform;
use App\Domains\SharedKernel\Models\BusinessHour;
use App\Domains\SharedKernel\Models\EmergencyContact;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class EloquentMunicipalityRepository implements MunicipalityRepositoryInterface
{
    public function getProfile(): Municipality
    {
        return Municipality::firstOrCreate(
            [],
            ['name_ar' => 'البلدية', 'name_en' => 'Municipality'],
        );
    }

    public function getProfileWithCounts(): Municipality
    {
        return Municipality::withCount([
            'contacts',
            'socialPlatforms',
            'externalPlatforms',
            'customFields',
            'media',
            'businessHours',
            'emergencyContacts',
        ])->firstOrCreate(
            [],
            ['name_ar' => 'البلدية', 'name_en' => 'Municipality'],
        );
    }

    public function getMunicipalityId(): int
    {
        return Municipality::firstOrCreate(
            [],
            ['name_ar' => 'البلدية', 'name_en' => 'Municipality'],
        )->id;
    }

    public function updateGeneralInfo(GeneralInfoDTO $dto): Municipality
    {
        return DB::transaction(function () use ($dto): Municipality {
            $municipality = $this->getProfile();
            $municipality->update($dto->toArray());

            return $municipality;
        });
    }

    public function getContacts(): Collection
    {
        return MunicipalityContact::where('municipality_id', $this->getMunicipalityId())
            ->orderBy('display_order')
            ->get();
    }

    public function findContact(int $id): ?MunicipalityContact
    {
        return MunicipalityContact::find($id);
    }

    public function saveContact(ContactDTO $dto, ?int $id = null): MunicipalityContact
    {
        return DB::transaction(function () use ($dto, $id): MunicipalityContact {
            $data = $dto->toArray();

            if ($id) {
                $contact = MunicipalityContact::findOrFail($id);
                $contact->update($data);

                return $contact;
            }

            $data['municipality_id'] = $dto->municipalityId ?? $this->getMunicipalityId();

            return MunicipalityContact::create($data);
        });
    }

    public function deleteContact(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return MunicipalityContact::findOrFail($id)->delete();
        });
    }

    public function getSocialPlatforms(): Collection
    {
        return MunicipalitySocialPlatform::where('municipality_id', $this->getMunicipalityId())
            ->orderBy('display_order')
            ->get();
    }

    public function findSocialPlatform(int $id): ?MunicipalitySocialPlatform
    {
        return MunicipalitySocialPlatform::find($id);
    }

    public function saveSocialPlatform(SocialPlatformDTO $dto, ?int $id = null): MunicipalitySocialPlatform
    {
        return DB::transaction(function () use ($dto, $id): MunicipalitySocialPlatform {
            $data = $dto->toArray();

            if ($id) {
                $platform = MunicipalitySocialPlatform::findOrFail($id);
                $platform->update($data);

                return $platform;
            }

            $data['municipality_id'] = $dto->municipalityId ?? $this->getMunicipalityId();

            return MunicipalitySocialPlatform::create($data);
        });
    }

    public function deleteSocialPlatform(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return MunicipalitySocialPlatform::findOrFail($id)->delete();
        });
    }

    public function getExternalPlatforms(): Collection
    {
        return MunicipalityExternalPlatform::where('municipality_id', $this->getMunicipalityId())
            ->orderBy('display_order')
            ->get();
    }

    public function findExternalPlatform(int $id): ?MunicipalityExternalPlatform
    {
        return MunicipalityExternalPlatform::find($id);
    }

    public function saveExternalPlatform(ExternalPlatformDTO $dto, ?int $id = null): MunicipalityExternalPlatform
    {
        return DB::transaction(function () use ($dto, $id): MunicipalityExternalPlatform {
            $data = $dto->toArray();

            if ($id) {
                $platform = MunicipalityExternalPlatform::findOrFail($id);
                $platform->update($data);

                return $platform;
            }

            $data['municipality_id'] = $dto->municipalityId ?? $this->getMunicipalityId();

            return MunicipalityExternalPlatform::create($data);
        });
    }

    public function deleteExternalPlatform(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return MunicipalityExternalPlatform::findOrFail($id)->delete();
        });
    }

    public function getCustomFields(): Collection
    {
        return MunicipalityCustomField::where('municipality_id', $this->getMunicipalityId())
            ->orderBy('display_order')
            ->get();
    }

    public function findCustomField(int $id): ?MunicipalityCustomField
    {
        return MunicipalityCustomField::find($id);
    }

    public function saveCustomField(CustomFieldDTO $dto, ?int $id = null): MunicipalityCustomField
    {
        return DB::transaction(function () use ($dto, $id): MunicipalityCustomField {
            $data = $dto->toArray();

            if ($id) {
                $field = MunicipalityCustomField::findOrFail($id);
                $field->update($data);

                return $field;
            }

            $data['municipality_id'] = $dto->municipalityId ?? $this->getMunicipalityId();

            return MunicipalityCustomField::create($data);
        });
    }

    public function deleteCustomField(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return MunicipalityCustomField::findOrFail($id)->delete();
        });
    }

    public function findBusinessHour(int $id): ?BusinessHour
    {
        return BusinessHour::find($id);
    }

    public function findEmergencyContact(int $id): ?EmergencyContact
    {
        return EmergencyContact::find($id);
    }

    public function findMedia(int $id): ?Media
    {
        return Media::find($id);
    }
}
