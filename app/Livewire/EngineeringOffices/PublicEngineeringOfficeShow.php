<?php

namespace App\Livewire\EngineeringOffices;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.home')]
final class PublicEngineeringOfficeShow extends Component
{
    public string $officeSlug;

    public function mount(string $office): void
    {
        $this->officeSlug = $office;
    }

    public function render()
    {
        $repo = app(EngineeringOfficeRepositoryInterface::class);
        $office = $repo->findBySlug($this->officeSlug);

        if (! $office) {
            abort(404);
        }

        // Increment view count
        $repo->incrementViews($office->id);

        return view('livewire.engineering-offices.public-engineering-office-show', [
            'office' => $office,
        ])->layout('layouts.home', [
            'title' => $office->office_name,
            'metaDescription' => $office->summary ?? 'مكتب هندسي معتمد لدى بلدية إذنا.',
        ]);
    }
}
