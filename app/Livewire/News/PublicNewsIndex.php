<?php

declare(strict_types=1);

namespace App\Livewire\News;

use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Enums\NewsCategory;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicNewsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $filter = 'latest';

    protected $queryString = ['search', 'category', 'filter'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = '';
        $this->filter = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(NewsRepositoryInterface::class);

        $filter = $this->filter === 'featured' ? 'featured' : null;

        $news = $repo->getPublished(
            search: strlen($this->search) >= 2 ? $this->search : null,
            category: $this->category ?: null,
            filter: $filter,
        );
        $featured = $repo->getFeatured();

        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {}

        $categories = NewsCategory::cases();

        return view('livewire.news.public-news-index', [
            'news' => $news,
            'featured' => $featured,
            'municipalityName' => $municipalityName,
            'categories' => $categories,
        ])->layout('layouts.home', [
            'title' => 'الأخبار | ' . $municipalityName,
            'metaDescription' => 'تصفح جميع الأخبار والفعاليات الصادرة عن ' . $municipalityName . '، واطلع على آخر المستجدات.',
        ]);
    }
}
