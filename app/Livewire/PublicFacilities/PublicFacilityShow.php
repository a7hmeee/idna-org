<?php

declare(strict_types=1);

namespace App\Livewire\PublicFacilities;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\PublicFacilities\Actions\RecordFacilityViewAction;
use App\Domains\PublicFacilities\Models\Facility;
use Livewire\Component;

final class PublicFacilityShow extends Component
{
    public ?Facility $facility = null;

    public function mount(?Facility $facility = null): void
    {
        if ($facility && $facility->exists) {
            abort_if(! $facility->is_public || $facility->status->value !== 'published', 404);

            $this->facility = $facility;

            app(RecordFacilityViewAction::class)->execute($facility->id);
        }

        abort_unless($this->facility, 404);
    }

    public function render()
    {
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
        }

        return view('livewire.public-facilities.public-facility-show', [
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => ($this->facility->name ?? 'المرفق').' | '.$municipalityName,
            'metaDescription' => $this->facility->summary ?? 'تعرف على '.($this->facility->name ?? 'المرفق').' في '.$municipalityName,
        ]);
    }
}
