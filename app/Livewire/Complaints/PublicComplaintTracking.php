<?php

declare(strict_types=1);

namespace App\Livewire\Complaints;

use App\Domains\Complaints\Models\Complaint;
use Livewire\Attributes\Url;
use Livewire\Component;

final class PublicComplaintTracking extends Component
{
    #[Url]
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
        return view('livewire.complaints.public-complaint-tracking')
            ->layout('layouts.home', [
                'title' => 'تتبع الشكوى | بلدية إذنا',
                'metaDescription' => 'تتبع حالة شكواك في بلدية إذنا',
            ]);
    }
}
