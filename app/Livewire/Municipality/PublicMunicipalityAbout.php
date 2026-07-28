<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Models\Municipality;
use Livewire\Component;

final class PublicMunicipalityAbout extends Component
{
    public ?Municipality $municipality = null;
    public array $contacts = [];
    public array $socialPlatforms = [];
    public array $images = [];
    public string $municipalityName = 'بلدية إذنا';

    public function mount(): void
    {
        try {
            $municipality = Municipality::with(['contacts', 'socialPlatforms', 'media'])->first();

            if ($municipality) {
                $this->municipality = $municipality;
                $this->municipalityName = $municipality->name_ar ?? $this->municipalityName;

                $this->contacts = $municipality->contacts
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();

                $this->socialPlatforms = $municipality->socialPlatforms
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();

                $this->images = $municipality->media
                    ->where('is_active', true)
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();
            }
        } catch (\Throwable $e) {
            //
        }
    }

    public function render()
    {
        return view('livewire.municipality.public-municipality-about', [
            'municipality' => $this->municipality,
            'contacts' => $this->contacts,
            'socialPlatforms' => $this->socialPlatforms,
            'images' => $this->images,
        ])->layout('layouts.home', [
            'title' => 'عن ' . $this->municipalityName,
            'metaDescription' => $this->municipality?->short_description ?? 'تعرف على ' . $this->municipalityName,
        ]);
    }
}
