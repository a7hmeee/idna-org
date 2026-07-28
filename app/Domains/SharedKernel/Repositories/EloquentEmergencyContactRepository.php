<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Repositories;

use App\Domains\SharedKernel\Contracts\EmergencyContactRepositoryInterface;
use App\Domains\SharedKernel\DTOs\EmergencyContactDTO;
use App\Domains\SharedKernel\Models\EmergencyContact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class EloquentEmergencyContactRepository implements EmergencyContactRepositoryInterface
{
    public function getForModel(Model $model): Collection
    {
        return EmergencyContact::where('contactable_type', $model->getMorphClass())
            ->where('contactable_id', $model->getKey())
            ->orderBy('display_order')
            ->get();
    }

    public function save(EmergencyContactDTO $dto, ?int $id = null): EmergencyContact
    {
        return DB::transaction(function () use ($dto, $id): EmergencyContact {
            if ($id) {
                $contact = EmergencyContact::findOrFail($id);
                $contact->update($dto->toArray());

                return $contact;
            }

            return EmergencyContact::create($dto->toArray());
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return EmergencyContact::findOrFail($id)->delete();
        });
    }
}
