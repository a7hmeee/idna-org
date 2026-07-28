<?php

declare(strict_types=1);

namespace App\Livewire\Complaints;

use App\Domains\Complaints\Models\Complaint;
use Livewire\Component;

final class PublicComplaintTracking extends Component
{
    public string $trackingNumber = '';
    public bool $searched = false;
    public ?Complaint $complaint = null;

    protected function rules(): array
    {
        return [
            'trackingNumber' => ['required', 'string', 'max:50'],
        ];
    }

    protected $messages = [
        'trackingNumber.required' => 'يرجى إدخال رقم التتبع.',
    ];

    public function track(): void
    {
        $this->validate();

        $this->complaint = Complaint::where('tracking_number', $this->trackingNumber)->first();
        $this->searched = true;
    }

    public function render()
    {
        return view('livewire.complaints.public-complaint-tracking');
    }
}