<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\MunicipalityContactData;
use App\Domains\Chatbot\DTOs\MunicipalityProfileData;

interface MunicipalityInfoQueryInterface
{
    public function getPublicProfile(): ?MunicipalityProfileData;

    public function getOfficialContacts(): array;

    public function getWorkingHours(): array;

    public function getAddress(): ?MunicipalityContactData;

    public function getAboutSummary(): ?string;
}
