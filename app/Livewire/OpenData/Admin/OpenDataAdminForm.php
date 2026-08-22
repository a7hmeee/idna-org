<?php

declare(strict_types=1);

namespace App\Livewire\OpenData\Admin;

use App\Domains\OpenData\Actions\CreateOpenDatasetAction;
use App\Domains\OpenData\Actions\DeleteOpenDatasetAction;
use App\Domains\OpenData\Actions\UpdateOpenDatasetAction;
use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Enums\OpenDataType;
use App\Domains\OpenData\Models\OpenDataset;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class OpenDataAdminForm extends Component
{
    use WithFileUploads;

    public ?OpenDataset $dataset = null;

    public string $title = '';

    public string $type = '';

    public string $category = '';

    public string $description = '';

    public $file = null;

    public string $external_url = '';

    public string $status = '';

    public bool $is_featured = false;

    public bool $removeFile = false;

    public bool $editing = false;

    public function mount(?OpenDataset $dataset = null): void
    {
        $this->dataset = $dataset;

        if ($dataset) {
            $this->editing = true;
            $this->authorize('update', OpenDataset::class);
            $this->title = $dataset->title;
            $this->type = $dataset->type->value;
            $this->category = $dataset->category ?? '';
            $this->description = $dataset->description ?? '';
            $this->external_url = $dataset->external_url ?? '';
            $this->status = $dataset->status->value;
            $this->is_featured = $dataset->is_featured;
        } else {
            $this->authorize('create', OpenDataset::class);
            $this->status = OpenDataStatus::Draft->value;
            $this->type = OpenDataType::Dataset->value;
        }
    }

    public function save(): void
    {
        if ($this->editing) {
            $this->authorize('update', OpenDataset::class);
        } else {
            $this->authorize('create', OpenDataset::class);
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:datasets,reports',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:102400',
            'external_url' => 'nullable|url|max:500',
            'status' => 'required|string|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);

        $data = [
            'title' => $this->title,
            'type' => $this->type,
            'category' => $this->category ?: null,
            'description' => $this->description ?: null,
            'external_url' => $this->external_url ?: null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'published_at' => $this->status === OpenDataStatus::Published->value ? now() : null,
            'created_by' => auth()->id(),
        ];

        if ($this->editing) {
            $file = $this->removeFile ? null : $this->file;
            app(UpdateOpenDatasetAction::class)->execute($this->dataset, $data, $file);
            session()->flash('success', 'تم تحديث مجموعة البيانات بنجاح');
        } else {
            app(CreateOpenDatasetAction::class)->execute($data, $this->file);
            session()->flash('success', 'تم إنشاء مجموعة البيانات بنجاح');

            $this->reset(['title', 'category', 'description', 'file', 'external_url']);
            $this->status = OpenDataStatus::Draft->value;
            $this->type = OpenDataType::Dataset->value;
            $this->is_featured = false;
        }
    }

    public function delete(): void
    {
        $this->authorize('delete', OpenDataset::class);

        app(DeleteOpenDatasetAction::class)->execute($this->dataset);

        session()->flash('success', 'تم حذف مجموعة البيانات بنجاح');

        $this->redirect(route('dashboard.open-data'), navigate: true);
    }

    public function render()
    {
        return view('livewire.open-data.admin.form');
    }
}
