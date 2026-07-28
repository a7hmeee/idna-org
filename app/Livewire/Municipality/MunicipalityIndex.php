<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityIndex extends Component
{
    public function boot(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function render()
    {
        return view('livewire.municipality.index', [
            'municipality' => app(MunicipalityRepositoryInterface::class)->getProfileWithCounts(),
        ]);
    }
}
