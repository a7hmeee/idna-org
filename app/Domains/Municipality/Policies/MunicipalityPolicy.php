<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Policies;

use App\Domains\Authentication\Models\User;

final class MunicipalityPolicy
{
    public function view(User $user): bool
    {
        return $user->can('municipality.view');
    }

    public function update(User $user): bool
    {
        return $user->can('municipality.update');
    }

    public function manageContacts(User $user): bool
    {
        return $user->can('municipality.contacts.manage');
    }

    public function createContact(User $user): bool
    {
        return $user->can('municipality.contacts.create');
    }

    public function updateContact(User $user): bool
    {
        return $user->can('municipality.contacts.update');
    }

    public function deleteContact(User $user): bool
    {
        return $user->can('municipality.contacts.delete');
    }

    public function manageSocial(User $user): bool
    {
        return $user->can('municipality.social.manage');
    }

    public function createSocial(User $user): bool
    {
        return $user->can('municipality.social.create');
    }

    public function updateSocial(User $user): bool
    {
        return $user->can('municipality.social.update');
    }

    public function deleteSocial(User $user): bool
    {
        return $user->can('municipality.social.delete');
    }

    public function managePlatforms(User $user): bool
    {
        return $user->can('municipality.platforms.manage');
    }

    public function createPlatform(User $user): bool
    {
        return $user->can('municipality.platforms.create');
    }

    public function updatePlatform(User $user): bool
    {
        return $user->can('municipality.platforms.update');
    }

    public function deletePlatform(User $user): bool
    {
        return $user->can('municipality.platforms.delete');
    }

    public function manageCustomFields(User $user): bool
    {
        return $user->can('municipality.custom-fields.manage');
    }

    public function createCustomField(User $user): bool
    {
        return $user->can('municipality.custom-fields.create');
    }

    public function updateCustomField(User $user): bool
    {
        return $user->can('municipality.custom-fields.update');
    }

    public function deleteCustomField(User $user): bool
    {
        return $user->can('municipality.custom-fields.delete');
    }

    public function manageMedia(User $user): bool
    {
        return $user->can('municipality.media.manage');
    }

    public function createMedia(User $user): bool
    {
        return $user->can('municipality.media.create');
    }

    public function updateMedia(User $user): bool
    {
        return $user->can('municipality.media.update');
    }

    public function deleteMedia(User $user): bool
    {
        return $user->can('municipality.media.delete');
    }

    public function manageBusinessHours(User $user): bool
    {
        return $user->can('municipality.business-hours.manage');
    }

    public function updateBusinessHours(User $user): bool
    {
        return $user->can('municipality.business-hours.update');
    }

    public function manageEmergencyContacts(User $user): bool
    {
        return $user->can('municipality.emergency-contacts.manage');
    }

    public function createEmergencyContact(User $user): bool
    {
        return $user->can('municipality.emergency-contacts.create');
    }

    public function updateEmergencyContact(User $user): bool
    {
        return $user->can('municipality.emergency-contacts.update');
    }

    public function deleteEmergencyContact(User $user): bool
    {
        return $user->can('municipality.emergency-contacts.delete');
    }
}
