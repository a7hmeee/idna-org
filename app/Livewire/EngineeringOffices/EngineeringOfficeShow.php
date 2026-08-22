<?php

declare(strict_types=1);

namespace App\Livewire\EngineeringOffices;

use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class EngineeringOfficeShow extends Component
{
    public EngineeringOffice $office;

    public function mount(EngineeringOffice $office): void
    {
        $this->authorize('view', EngineeringOffice::class);
        $this->office = $office->load(['creator:id,name', 'updater:id,name']);
    }

    public function render()
    {
        return view('livewire.engineering-offices.engineering-office-show', [
            'canUpdate' => auth()->user()->can('update', EngineeringOffice::class),
            'canDelete' => auth()->user()->can('delete', EngineeringOffice::class),
            'canApprove' => auth()->user()->can('approve', EngineeringOffice::class),
            'canSuspend' => auth()->user()->can('suspend', EngineeringOffice::class),
        ]);
    }
}
