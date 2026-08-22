<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Announcements;

use App\Domains\Announcements\Actions\CreateAnnouncementAction;
use App\Domains\Announcements\Actions\UpdateAnnouncementAction;
use App\Domains\Announcements\DTOs\AnnouncementData;
use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementStatus;
use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Announcements\Models\Announcement;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class AnnouncementForm extends Component
{
    use WithFileUploads;

    public ?int $announcementId = null;

    public string $title = '';

    public string $type = 'general';

    public string $priority = 'normal';

    public string $status = 'draft';

    public string $summary = '';

    public string $content = '';

    public $image = null;

    public ?string $existingImage = null;

    public bool $isFeatured = false;

    public string $publishAt = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:'.implode(',', array_map(fn ($t) => $t->value, AnnouncementType::cases()))],
            'priority' => ['required', 'string', 'in:'.implode(',', array_map(fn ($p) => $p->value, AnnouncementPriority::cases()))],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'isFeatured' => ['boolean'],
            'publishAt' => ['required', 'date'],
        ];
    }

    protected $messages = [
        'title.required' => 'عنوان الإعلان مطلوب.',
        'summary.required' => 'الملخص مطلوب.',
        'content.required' => 'محتوى الإعلان مطلوب.',
        'publishAt.required' => 'تاريخ النشر مطلوب.',
        'image.max' => 'يجب ألا تتجاوز الصورة 5 ميجابايت.',
    ];

    public function mount(?Announcement $announcement = null): void
    {
        if ($announcement && $announcement->exists) {
            $this->authorize('update', Announcement::class);

            $this->announcementId = $announcement->id;
            $this->title = $announcement->title;
            $this->type = $announcement->type->value;
            $this->priority = $announcement->priority->value;
            $this->status = $announcement->status->value;
            $this->summary = $announcement->short_description;
            $this->content = $announcement->content;
            $this->existingImage = $announcement->desktop_image_path;
            $this->isFeatured = $announcement->is_featured;
            $this->publishAt = $announcement->published_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i');
        } else {
            $this->authorize('create', Announcement::class);

            $this->publishAt = now()->format('Y-m-d\TH:i');
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $imagePath = $this->existingImage;

        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('announcements', 'public');
        }

        $dto = AnnouncementData::fromRequest([
            'title' => $this->title,
            'type' => AnnouncementType::from($this->type),
            'priority' => AnnouncementPriority::from($this->priority),
            'status' => AnnouncementStatus::from($this->status),
            'summary' => $this->summary,
            'content' => $this->content,
            'imagePath' => $imagePath,
            'isFeatured' => $this->isFeatured,
            'publishAt' => $this->publishAt,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->announcementId) {
            app(UpdateAnnouncementAction::class)->execute($this->announcementId, $dto);
            session()->flash('success', 'تم تحديث الإعلان بنجاح.');
        } else {
            app(CreateAnnouncementAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة الإعلان بنجاح.');
        }

        $this->redirect(route('dashboard.announcements'), navigate: true);
    }

    public function removeImage(): void
    {
        if ($this->existingImage) {
            Storage::disk('public')->delete($this->existingImage);
        }
        $this->existingImage = null;
        $this->image = null;
    }

    public function render()
    {
        $types = AnnouncementType::cases();
        $priorities = AnnouncementPriority::cases();
        $statuses = AnnouncementStatus::cases();

        return view('livewire.admin.announcements.announcement-form', compact(
            'types', 'priorities', 'statuses'
        ));
    }
}
