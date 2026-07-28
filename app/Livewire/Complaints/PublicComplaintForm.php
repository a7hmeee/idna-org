<?php

declare(strict_types=1);

namespace App\Livewire\Complaints;

use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Models\Complaint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

final class PublicComplaintForm extends Component
{
    use WithFileUploads;

    public string $citizenName = '';
    public string $phone = '';
    public string $email = '';
    public string $category = '';
    public string $subject = '';
    public string $description = '';
    public string $location = '';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public $attachments = [];
    public bool $submitted = false;
    public string $trackingNumber = '';

    protected function rules(): array
    {
        return [
            'citizenName' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', array_map(fn($c) => $c->value, ComplaintCategory::cases()))],
            'subject' => ['required', 'string', 'min:10', 'max:500'],
            'description' => ['required', 'string', 'min:20'],
            'location' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    protected $messages = [
        'citizenName.required' => 'يرجى إدخال الاسم.',
        'phone.required' => 'يرجى إدخال رقم الهاتف.',
        'category.required' => 'يرجى اختيار تصنيف الشكوى.',
        'subject.required' => 'يرجى إدخال عنوان الشكوى.',
        'subject.min' => 'عنوان الشكوى يجب أن يكون 10 أحرف على الأقل.',
        'description.required' => 'يرجى إدخال وصف الشكوى.',
        'description.min' => 'وصف الشكوى يجب أن يكون 20 حرفاً على الأقل.',
        'attachments.max' => 'يمكن إرفاق 5 ملفات كحد أقصى.',
        'attachments.*.max' => 'الملف يجب أن لا يتجاوز 5 ميجابايت.',
    ];

    public function submit(): void
    {
        $data = $this->validate();

        $attachmentPaths = [];

        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                $attachmentPaths[] = $file->store('complaints/public', 'public');
            }
        }

        $trackingNumber = 'CMP-' . strtoupper(Str::random(10));

        Complaint::create([
            'tracking_number' => $trackingNumber,
            'citizen_name' => $this->citizenName,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'category' => ComplaintCategory::from($this->category),
            'subject' => $this->subject,
            'description' => $this->description,
            'location' => $this->location ?: null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            'priority' => ComplaintPriority::Medium,
            'status' => \App\Domains\Complaints\Enums\ComplaintStatus::Submitted,
            'submitted_at' => now(),
        ]);

        $this->trackingNumber = $trackingNumber;
        $this->submitted = true;

        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->citizenName = '';
        $this->phone = '';
        $this->email = '';
        $this->category = '';
        $this->subject = '';
        $this->description = '';
        $this->location = '';
        $this->latitude = null;
        $this->longitude = null;
        $this->attachments = [];
    }

    public function render()
    {
        $categories = ComplaintCategory::cases();

        return view('livewire.complaints.public-complaint-form', compact('categories'));
    }
}