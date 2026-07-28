<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Models;

use App\Domains\SharedKernel\Models\BusinessHour;
use App\Domains\SharedKernel\Models\EmergencyContact;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property string|null $short_description
 * @property string|null $full_description
 * @property string|null $vision
 * @property string|null $mission
 * @property array|null $objectives
 * @property string|null $foundation_date
 * @property int|null $population
 * @property float|null $area
 * @property string|null $municipality_code
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Municipality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'short_description',
        'full_description',
        'vision',
        'mission',
        'objectives',
        'foundation_date',
        'population',
        'area',
        'municipality_code',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'foundation_date' => 'date',
            'population' => 'integer',
            'area' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(MunicipalityContact::class);
    }

    public function socialPlatforms(): HasMany
    {
        return $this->hasMany(MunicipalitySocialPlatform::class);
    }

    public function externalPlatforms(): HasMany
    {
        return $this->hasMany(MunicipalityExternalPlatform::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(MunicipalityCustomField::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function businessHours(): MorphMany
    {
        return $this->morphMany(BusinessHour::class, 'hourable');
    }

    public function emergencyContacts(): MorphMany
    {
        return $this->morphMany(EmergencyContact::class, 'contactable');
    }
}
