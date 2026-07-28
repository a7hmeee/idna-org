<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\SaveGeneralInfoAction;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\GeneralInfoDTO;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityGeneralInfo extends Component
{
    public string $nameAr = '';
    public string $nameEn = '';
    public ?string $shortDescription = null;
    public ?string $fullDescription = null;
    public ?string $vision = null;
    public ?string $mission = null;
    public string $objectives = '';
    public ?string $foundationDate = null;
    public ?int $population = null;
    public ?float $area = null;
    public ?string $municipalityCode = null;
    public ?float $latitude = null;
    public ?float $longitude = null;

    public function mount(): void
    {
        $this->authorize('update', Municipality::class);

        $municipality = app(MunicipalityRepositoryInterface::class)->getProfile();

        $this->nameAr = $municipality->name_ar;
        $this->nameEn = $municipality->name_en;
        $this->shortDescription = $municipality->short_description;
        $this->fullDescription = $municipality->full_description;
        $this->vision = $municipality->vision;
        $this->mission = $municipality->mission;
        $this->objectives = $municipality->objectives ? implode("\n", $municipality->objectives) : '';
        $this->foundationDate = $municipality->foundation_date?->format('Y-m-d');
        $this->population = $municipality->population;
        $this->area = $municipality->area !== null ? (float) $municipality->area : null;
        $this->municipalityCode = $municipality->municipality_code;
        $this->latitude = $municipality->latitude !== null ? (float) $municipality->latitude : null;
        $this->longitude = $municipality->longitude !== null ? (float) $municipality->longitude : null;
    }

    public function save(SaveGeneralInfoAction $action): void
    {
        $this->authorize('update', Municipality::class);

        $validated = $this->validate([
            'nameAr' => ['required', 'string', 'max:255'],
            'nameEn' => ['required', 'string', 'max:255'],
            'shortDescription' => ['nullable', 'string', 'max:500'],
            'fullDescription' => ['nullable', 'string'],
            'vision' => ['nullable', 'string', 'max:1000'],
            'mission' => ['nullable', 'string', 'max:1000'],
            'objectives' => ['nullable', 'string'],
            'foundationDate' => ['nullable', 'date'],
            'population' => ['nullable', 'integer', 'min:0'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'municipalityCode' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $validated['objectives'] = $this->parseObjectives($validated['objectives'] ?? '');

        $action->execute(GeneralInfoDTO::fromRequest($validated));

        session()->flash('success', 'تم حفظ المعلومات العامة بنجاح.');
    }

    private function parseObjectives(?string $input): ?array
    {
        if (empty($input)) {
            return null;
        }

        return array_values(array_filter(array_map('trim', explode("\n", $input))));
    }

    public function render()
    {
        return view('livewire.municipality.general-info');
    }
}
