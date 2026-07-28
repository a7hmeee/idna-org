<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Contracts;

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

interface MunicipalityRepositoryInterface
{
    public function getProfile(): Municipality;

    public function getProfileWithCounts(): Municipality;

    public function updateGeneralInfo(GeneralInfoDTO $dto): Municipality;

    public function getMunicipalityId(): int;

    public function getContacts(): Collection;

    public function findContact(int $id): ?MunicipalityContact;

    public function saveContact(ContactDTO $dto, ?int $id = null): MunicipalityContact;

    public function deleteContact(int $id): bool;

    public function getSocialPlatforms(): Collection;

    public function findSocialPlatform(int $id): ?MunicipalitySocialPlatform;

    public function saveSocialPlatform(SocialPlatformDTO $dto, ?int $id = null): MunicipalitySocialPlatform;

    public function deleteSocialPlatform(int $id): bool;

    public function getExternalPlatforms(): Collection;

    public function findExternalPlatform(int $id): ?MunicipalityExternalPlatform;

    public function saveExternalPlatform(ExternalPlatformDTO $dto, ?int $id = null): MunicipalityExternalPlatform;

    public function deleteExternalPlatform(int $id): bool;

    public function getCustomFields(): Collection;

    public function findCustomField(int $id): ?MunicipalityCustomField;

    public function saveCustomField(CustomFieldDTO $dto, ?int $id = null): MunicipalityCustomField;

    public function deleteCustomField(int $id): bool;

    public function findBusinessHour(int $id): ?BusinessHour;

    public function findEmergencyContact(int $id): ?EmergencyContact;

    public function findMedia(int $id): ?Media;
}
