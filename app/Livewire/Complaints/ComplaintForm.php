<?php

declare(strict_types=1);

namespace App\Livewire\Complaints;

use App\Domains\Authentication\Models\User;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\Complaints\Actions\UpdateComplaintAction;
use App\Domains\Complaints\DTOs\ComplaintData;
use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\Department\Models\Department;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class ComplaintForm extends Component
{
    use WithFileUploads;

    public ?int $complaintId = null;

    public string $citizenName = '';

    public string $phone = '';

    public string $email = '';

    public string $category = '';

    public ?int $departmentId = null;

    public string $subject = '';

    public string $description = '';

    public string $location = '';

    public ?float $latitude = null;

    public ?float $longitude = null;

    public string $priority = 'medium';

    public string $status = 'submitted';

    public string $internalNotes = '';

    public string $publicResponse = '';

    public ?int $assignedTo = null;

    public $attachments = [];

    public array $existingAttachments = [];

    public bool $removeAttachments = false;

    protected function rules(): array
    {
        return [
            'citizenName' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'category' => ['required', 'string', 'in:'.implode(',', array_map(fn ($c) => $c->value, ComplaintCategory::cases()))],
            'departmentId' => ['nullable', 'integer', 'exists:departments,id'],
            'subject' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'priority' => ['required', 'string', 'in:'.implode(',', array_map(fn ($p) => $p->value, ComplaintPriority::cases()))],
            'status' => ['required', 'string', 'in:'.implode(',', array_map(fn ($s) => $s->value, ComplaintStatus::cases()))],
            'internalNotes' => ['nullable', 'string'],
            'publicResponse' => ['nullable', 'string'],
            'assignedTo' => ['nullable', 'integer', 'exists:users,id'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'],
        ];
    }

    protected $messages = [
        'citizenName.required' => 'اسم المواطن مطلوب.',
        'phone.required' => 'رقم الهاتف مطلوب.',
        'category.required' => 'تصنيف الشكوى مطلوب.',
        'subject.required' => 'عنوان الشكوى مطلوب.',
        'description.required' => 'وصف الشكوى مطلوب.',
        'attachments.*.max' => 'يجب ألا يتجاوز حجم المرفق 10 ميجابايت.',
    ];

    public function mount(?Complaint $complaint = null): void
    {
        if ($complaint && $complaint->exists) {
            $this->authorize('update', Complaint::class);

            $this->complaintId = $complaint->id;
            $this->citizenName = $complaint->citizen_name;
            $this->phone = $complaint->phone;
            $this->email = $complaint->email ?? '';
            $this->category = $complaint->category->value;
            $this->departmentId = $complaint->department_id;
            $this->subject = $complaint->subject;
            $this->description = $complaint->description;
            $this->location = $complaint->location ?? '';
            $this->latitude = $complaint->latitude;
            $this->longitude = $complaint->longitude;
            $this->priority = $complaint->priority->value;
            $this->status = $complaint->status->value;
            $this->internalNotes = $complaint->internal_notes ?? '';
            $this->publicResponse = $complaint->public_response ?? '';
            $this->assignedTo = $complaint->assigned_to;
            $this->existingAttachments = $complaint->attachments ?? [];
        } else {
            $this->authorize('create', Complaint::class);

            $this->priority = ComplaintPriority::Medium->value;
            $this->status = ComplaintStatus::Submitted->value;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $attachmentPaths = $this->existingAttachments;

        if ($this->removeAttachments) {
            foreach ($attachmentPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            $attachmentPaths = [];
        }

        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                $attachmentPaths[] = $file->store('complaints', 'public');
            }
        }

        $dto = ComplaintData::fromRequest([
            'citizenName' => $this->citizenName,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'category' => ComplaintCategory::from($this->category),
            'departmentId' => $this->departmentId,
            'subject' => $this->subject,
            'description' => $this->description,
            'location' => $this->location ?: null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'priority' => ComplaintPriority::from($this->priority),
            'status' => ComplaintStatus::from($this->status),
            'internalNotes' => $this->internalNotes ?: null,
            'publicResponse' => $this->publicResponse ?: null,
            'assignedTo' => $this->assignedTo,
            'attachments' => ! empty($attachmentPaths) ? $attachmentPaths : null,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->complaintId) {
            app(UpdateComplaintAction::class)->execute($this->complaintId, $dto);
            session()->flash('success', 'تم تحديث الشكوى بنجاح.');
        } else {
            app(CreateComplaintAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة الشكوى بنجاح.');
        }

        $this->redirect(route('dashboard.complaints'), navigate: true);
    }

    public function render()
    {
        $categories = ComplaintCategory::cases();
        $priorities = ComplaintPriority::cases();
        $statuses = ComplaintStatus::cases();
        $departments = Department::where('is_public', true)->orderBy('name')->get();
        $employees = User::where('status', 'active')->orderBy('name')->get();

        return view('livewire.complaints.complaint-form', compact(
            'categories', 'priorities', 'statuses', 'departments', 'employees'
        ));
    }
}
