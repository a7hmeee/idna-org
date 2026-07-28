<?php

declare(strict_types=1);

namespace App\Livewire\News;

use App\Domains\News\Actions\CreateNewsAction;
use App\Domains\News\Actions\UpdateNewsAction;
use App\Domains\News\DTOs\NewsData;
use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;
use App\Domains\News\Models\NewsItem;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class NewsForm extends Component
{
    use WithFileUploads;

    public ?int $newsId = null;

    public string $titleAr = '';
    public string $titleEn = '';
    public string $category = '';
    public string $summary = '';
    public string $content = '';
    public $coverImage = null;
    public ?string $existingCoverImage = null;
    public string $author = '';
    public string $status = 'draft';
    public bool $isFeatured = false;
    public bool $isPublic = true;
    public string $publishAt = '';
    public string $metaTitle = '';
    public string $metaDescription = '';
    public string $metaKeywords = '';

    public function mount(?NewsItem $newsItem = null): void
    {
        if ($newsItem && $newsItem->exists) {
            $this->authorize('update', NewsItem::class);

            $this->newsId = $newsItem->id;
            $this->titleAr = $newsItem->title_ar;
            $this->titleEn = $newsItem->title_en ?? '';
            $this->category = $newsItem->category->value;
            $this->summary = $newsItem->summary ?? '';
            $this->content = $newsItem->content ?? '';
            $this->existingCoverImage = $newsItem->cover_image_path;
            $this->author = $newsItem->author ?? '';
            $this->status = $newsItem->status->value;
            $this->isFeatured = $newsItem->is_featured;
            $this->isPublic = $newsItem->is_public;
            $this->publishAt = $newsItem->publish_at?->format('Y-m-d') ?? now()->toDateString();
            $this->metaTitle = $newsItem->meta_title ?? '';
            $this->metaDescription = $newsItem->meta_description ?? '';
            $this->metaKeywords = $newsItem->meta_keywords ?? '';
        } else {
            $this->authorize('create', NewsItem::class);

            $this->category = NewsCategory::General->value;
            $this->status = NewsStatus::Draft->value;
            $this->publishAt = now()->toDateString();
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'titleAr' => ['required', 'string', 'max:255'],
            'titleEn' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', array_map(fn ($c) => $c->value, NewsCategory::cases()))],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'coverImage' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'author' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:' . implode(',', array_map(fn ($s) => $s->value, NewsStatus::cases()))],
            'isFeatured' => ['boolean'],
            'isPublic' => ['boolean'],
            'publishAt' => ['required', 'date'],
            'metaTitle' => ['nullable', 'string', 'max:255'],
            'metaDescription' => ['nullable', 'string', 'max:500'],
            'metaKeywords' => ['nullable', 'string', 'max:500'],
        ]);

        $coverImagePath = $this->existingCoverImage;

        if ($this->coverImage) {
            if ($this->existingCoverImage) {
                Storage::disk('public')->delete($this->existingCoverImage);
            }
            $coverImagePath = $this->coverImage->store('news/covers', 'public');
        }

        $dto = NewsData::fromRequest([
            'titleAr' => $this->titleAr,
            'titleEn' => $this->titleEn ?: null,
            'summary' => $this->summary,
            'content' => $this->content,
            'category' => $this->category,
            'status' => $this->status,
            'publishAt' => $this->publishAt,
            'coverImagePath' => $coverImagePath,
            'author' => $this->author ?: null,
            'isFeatured' => $this->isFeatured,
            'isPublic' => $this->isPublic,
            'metaTitle' => $this->metaTitle ?: null,
            'metaDescription' => $this->metaDescription ?: null,
            'metaKeywords' => $this->metaKeywords ?: null,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->newsId) {
            app(UpdateNewsAction::class)->execute($this->newsId, $dto);
            session()->flash('success', 'تم تحديث الخبر بنجاح.');
        } else {
            app(CreateNewsAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة الخبر بنجاح.');
        }

        $this->redirect(route('dashboard.news'), navigate: true);
    }

    public function render()
    {
        $categories = NewsCategory::cases();
        $statuses = NewsStatus::cases();

        return view('livewire.news.news-form', compact('categories', 'statuses'));
    }
}
